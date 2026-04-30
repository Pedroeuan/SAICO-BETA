from __future__ import annotations

import json
import logging
import os
import sys
from datetime import datetime
from pathlib import Path
from typing import Any

import mysql.connector
from dotenv import load_dotenv

from adaptadores import FacebookAdapter, LinkedInAdapter, TwitterAdapter


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
    "linkedin": LinkedInAdapter,
    "facebook": FacebookAdapter,
    "twitter": TwitterAdapter,
}


def cargar_env() -> dict[str, str]:
    """Carga variables desde el .env local del script Python."""
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
    """Abre una conexión a MySQL con timeout de 15 segundos."""
    return mysql.connector.connect(
        host=config.get("DB_HOST", "127.0.0.1"),
        port=int(config.get("DB_PORT", "3306")),
        user=config.get("DB_USERNAME", ""),
        password=config.get("DB_PASSWORD", ""),
        database=config.get("DB_DATABASE", ""),
        connection_timeout=15,
    )


def obtener_publicacion(conexion: mysql.connector.MySQLConnection, publicacion_id: int) -> dict[str, Any]:
    """Consulta la publicación y normaliza los campos JSON."""
    cursor = conexion.cursor(dictionary=True)
    cursor.execute(
        """
        SELECT id, titulo, slug, contenido, tipo, imagen, imagen_alt, video, url_destino,
               redes_objetivo, resultado_publicacion, publicado_en_redes, publicado_at, activo
        FROM publicaciones
        WHERE id = %s AND deleted_at IS NULL
        LIMIT 1
        """,
        (publicacion_id,),
    )
    publicacion = cursor.fetchone()
    cursor.close()

    if not publicacion:
        raise RuntimeError(f"No se encontro la publicacion con ID {publicacion_id}.")

    publicacion["redes_objetivo"] = json.loads(publicacion["redes_objetivo"] or "[]")
    publicacion["resultado_publicacion"] = json.loads(publicacion["resultado_publicacion"] or "{}")

    imagen = publicacion.get("imagen")
    if imagen:
        publicacion["imagen"] = str((BASE_DIR.parent / "storage" / "app" / "public" / imagen).resolve())

    return publicacion


def guardar_resultados(
    conexion: mysql.connector.MySQLConnection,
    publicacion_id: int,
    resultados: dict[str, Any],
) -> None:
    """Actualiza el registro con el resultado de publicación."""
    exito_global = any(bool(item.get("exito")) for item in resultados.values())
    cursor = conexion.cursor()
    cursor.execute(
        """
        UPDATE publicaciones
        SET resultado_publicacion = %s,
            publicado_en_redes = %s,
            publicado_at = %s,
            updated_at = %s
        WHERE id = %s
        """,
        (
            json.dumps(resultados, ensure_ascii=False),
            1 if exito_global else 0,
            datetime.now(),
            datetime.now(),
            publicacion_id,
        ),
    )
    conexion.commit()
    cursor.close()


def publicar_en_redes(publicacion: dict[str, Any], config: dict[str, str]) -> dict[str, Any]:
    """Orquesta la publicación red por red con tolerancia a fallos independientes."""
    resultados: dict[str, Any] = {}

    for red in publicacion.get("redes_objetivo", []):
        adaptador_cls = ADAPTADORES.get(red)
        if adaptador_cls is None:
            logger.error("No existe adaptador configurado para la red %s", red)
            resultados[red] = {
                "exito": False,
                "post_id": None,
                "red": red,
                "error": "adaptador no configurado",
            }
            continue

        try:
            adaptador = adaptador_cls(config, publicacion)
            if not adaptador.conectar():
                resultados[red] = {
                    "exito": False,
                    "post_id": None,
                    "red": red,
                    "error": "no fue posible autenticar con la API",
                }
                continue

            if publicacion.get("video"):
                resultado = adaptador.publicar_con_video()
            elif publicacion.get("imagen"):
                resultado = adaptador.publicar_con_imagen()
            else:
                resultado = adaptador.publicar_texto()

            resultados[red] = resultado
            logger.info("Resultado %s: %s", red, json.dumps(resultado, ensure_ascii=False))
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
    if len(sys.argv) < 2:
        raise ValueError("Debes indicar el ID de publicacion como argumento.")

    publicacion_id = validar_id_publicacion(sys.argv[1])
    config = cargar_env()
    conexion = obtener_conexion(config)

    try:
        publicacion = obtener_publicacion(conexion, publicacion_id)
        resultados = publicar_en_redes(publicacion, config)
        guardar_resultados(conexion, publicacion_id, resultados)
        print(json.dumps(resultados, ensure_ascii=False))
        return 0
    finally:
        conexion.close()


if __name__ == "__main__":
    try:
        raise SystemExit(main())
    except Exception as exc:
        logger.exception("Fallo general del script de publicaciones: %s", exc)
        print(
            json.dumps(
                {
                    "_general": {
                        "exito": False,
                        "post_id": None,
                        "red": "general",
                        "error": str(exc),
                    }
                },
                ensure_ascii=False,
            )
        )
        raise SystemExit(1)
