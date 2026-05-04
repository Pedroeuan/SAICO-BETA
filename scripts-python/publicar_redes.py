from __future__ import annotations

import json
import importlib
import logging
import os
import sys
from pathlib import Path
from typing import Any

import mysql.connector
from dotenv import load_dotenv


BASE_DIR = Path(__file__).resolve().parent
LOG_DIR = BASE_DIR / "logs"
LOG_DIR.mkdir(parents=True, exist_ok=True)
LOG_FILE = LOG_DIR / "publicaciones.log"

logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s %(levelname)s %(name)s %(message)s",
    handlers=[
        logging.FileHandler(LOG_FILE, encoding="utf-8"),
        logging.StreamHandler(sys.stderr),
    ],
)

logger = logging.getLogger("publicaciones")

ADAPTADORES = {
    "facebook": ("adaptadores.facebook", "FacebookAdapter"),
    "linkedin": ("adaptadores.linkedin", "LinkedInAdapter"),
    "twitter": ("adaptadores.twitter", "TwitterAdapter"),
}


def cargar_env() -> dict[str, str]:
    """Carga variables desde el archivo .env del directorio scripts-python."""
    load_dotenv(BASE_DIR / ".env")
    return dict(os.environ)


def validar_id_publicacion(valor: str) -> int:
    """Valida que el argumento recibido sea un entero positivo."""
    try:
        publicacion_id = int(valor)
    except (TypeError, ValueError) as exc:
        raise ValueError("El ID de publicacion debe ser un entero.") from exc

    if publicacion_id <= 0:
        raise ValueError("El ID de publicacion debe ser mayor a cero.")

    return publicacion_id


def obtener_conexion(config: dict[str, str]) -> mysql.connector.MySQLConnection:
    """Abre una conexion a MySQL usando las variables del .env del script."""
    return mysql.connector.connect(
        host=config.get("DB_HOST", "127.0.0.1"),
        user=config.get("DB_USER", ""),
        password=config.get("DB_PASS", ""),
        database=config.get("DB_NAME", ""),
        connection_timeout=15,
    )


def normalizar_json(valor: Any, predeterminado: Any) -> Any:
    """Convierte valores JSON provenientes de MySQL a estructuras nativas."""
    if valor in (None, ""):
        return predeterminado
    if isinstance(valor, (list, dict)):
        return valor
    if isinstance(valor, (bytes, bytearray)):
        valor = valor.decode("utf-8")

    try:
        return json.loads(valor)
    except (TypeError, json.JSONDecodeError):
        return predeterminado


def obtener_publicacion(conexion: mysql.connector.MySQLConnection, publicacion_id: int) -> dict[str, Any]:
    """Obtiene la publicacion desde MySQL y normaliza sus campos relevantes."""
    cursor = conexion.cursor(dictionary=True)
    cursor.execute("SELECT * FROM publicaciones WHERE id = %s", (publicacion_id,))
    publicacion = cursor.fetchone()
    cursor.close()

    if not publicacion:
        raise RuntimeError(f"No se encontro la publicacion con ID {publicacion_id}.")

    publicacion["redes_objetivo"] = normalizar_json(publicacion.get("redes_objetivo"), [])
    publicacion["resultado_publicacion"] = normalizar_json(publicacion.get("resultado_publicacion"), {})

    imagen = str(publicacion.get("imagen") or "").strip()
    if imagen:
        publicacion["imagen"] = str((BASE_DIR.parent / "storage" / "app" / "public" / imagen).resolve())

    return publicacion


def credenciales_configuradas(red: str) -> bool:
    """Verifica si la red social tiene todas las credenciales requeridas."""
    variables = {
        "facebook": ["FACEBOOK_PAGE_TOKEN", "FACEBOOK_PAGE_ID"],
        "linkedin": ["LINKEDIN_ACCESS_TOKEN", "LINKEDIN_ORG_ID"],
        "twitter": ["TWITTER_API_KEY", "TWITTER_API_SECRET", "TWITTER_ACCESS_TOKEN", "TWITTER_ACCESS_SECRET"],
    }
    return all(os.getenv(variable) for variable in variables.get(red, []))


def obtener_adaptador_cls(red: str) -> type[Any] | None:
    """Importa el adaptador solo cuando realmente se necesita esa red."""
    modulo_y_clase = ADAPTADORES.get(red)
    if modulo_y_clase is None:
        return None

    modulo_nombre, clase_nombre = modulo_y_clase

    try:
        modulo = importlib.import_module(modulo_nombre)
        return getattr(modulo, clase_nombre)
    except Exception as exc:
        logger.exception("No fue posible cargar el adaptador de %s: %s", red, exc)
        return None


def guardar_resultados(
    conexion: mysql.connector.MySQLConnection,
    publicacion_id: int,
    resultados: dict[str, Any],
) -> None:
    """Actualiza la publicacion con el detalle final del intento de autopublicacion."""
    cursor = conexion.cursor()
    cursor.execute(
        """
        UPDATE publicaciones
        SET publicado_en_redes = 1,
            publicado_at = NOW(),
            resultado_publicacion = %s
        WHERE id = %s
        """,
        (json.dumps(resultados, ensure_ascii=False), publicacion_id),
    )
    conexion.commit()
    cursor.close()


def publicar_en_redes(publicacion: dict[str, Any]) -> dict[str, Any]:
    """Orquesta la publicacion red por red sin detenerse si alguna falla."""
    resultados: dict[str, Any] = {}
    titulo = str(publicacion.get("titulo") or "").strip()
    contenido = str(publicacion.get("contenido") or "").strip()
    url = str(publicacion.get("url_destino") or "").strip() or None
    ruta_imagen = str(publicacion.get("imagen") or "").strip()
    redes_objetivo = publicacion.get("redes_objetivo", [])

    if not isinstance(redes_objetivo, list):
        logger.warning("El campo redes_objetivo no tiene formato de lista para la publicacion %s.", publicacion.get("id"))
        redes_objetivo = []

    for red in redes_objetivo:
        red = str(red).strip().lower()
        adaptador_cls = obtener_adaptador_cls(red)

        if adaptador_cls is None:
            logger.warning("No existe adaptador configurado para la red %s.", red)
            resultados[red] = {
                "exito": False,
                "post_id": None,
                "red": red,
                "error": "adaptador no configurado",
            }
            continue

        if not credenciales_configuradas(red):
            logger.warning("Se omite la red %s porque no tiene credenciales configuradas.", red)
            resultados[red] = {
                "exito": False,
                "post_id": None,
                "red": red,
                "error": "credenciales no configuradas",
            }
            continue

        try:
            adaptador = adaptador_cls()
            if not adaptador.conectar():
                logger.warning("No fue posible conectar con la API de %s.", red)
                resultados[red] = {
                    "exito": False,
                    "post_id": None,
                    "red": red,
                    "error": "no fue posible autenticar con la API",
                }
                continue

            if ruta_imagen:
                resultado = adaptador.publicar_con_imagen(titulo, contenido, ruta_imagen, url)
            else:
                resultado = adaptador.publicar_texto(titulo, contenido, url)

            resultados[red] = resultado
            logger.info("Resultado de publicacion en %s: %s", red, json.dumps(resultado, ensure_ascii=False))
        except Exception as exc:
            logger.exception("Fallo independiente al publicar en %s: %s", red, exc)
            resultados[red] = {
                "exito": False,
                "post_id": None,
                "red": red,
                "error": str(exc),
            }

    return resultados


def main() -> int:
    """Punto de entrada del script orquestador."""
    try:
        if len(sys.argv) < 2:
            raise ValueError("Debes indicar el ID de publicacion como argumento.")

        publicacion_id = validar_id_publicacion(sys.argv[1])
        config = cargar_env()
        conexion = obtener_conexion(config)

        try:
            publicacion = obtener_publicacion(conexion, publicacion_id)
            resultados = publicar_en_redes(publicacion)
            guardar_resultados(conexion, publicacion_id, resultados)
        finally:
            conexion.close()

        print(json.dumps(resultados, ensure_ascii=False))
        return 0
    except Exception as exc:
        logger.exception("Fallo general del script de publicaciones: %s", exc)
        resultado_error = {
            "_general": {
                "exito": False,
                "post_id": None,
                "red": "general",
                "error": str(exc),
            }
        }
        print(json.dumps(resultado_error, ensure_ascii=False))
        return 0


if __name__ == "__main__":
    raise SystemExit(main())
