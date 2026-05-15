from __future__ import annotations

import logging
import os
from pathlib import Path
from typing import Optional

import requests

from .base import RedSocialBase


class FacebookAdapter(RedSocialBase):
    """Adaptador para publicar en la pagina de Facebook de la empresa."""

    API_VERSION = "v19.0"
    BASE_URL = f"https://graph.facebook.com/{API_VERSION}"

    def __init__(self) -> None:
        """Carga las credenciales de Facebook desde el archivo .env del script."""
        self.cargar_entorno()
        self.logger = logging.getLogger("publicaciones.facebook")
        self.page_token = str(os.getenv("FACEBOOK_PAGE_TOKEN", "")).strip()
        self.page_id = str(os.getenv("FACEBOOK_PAGE_ID", "")).strip()
        self.timeout = (
            int(os.getenv("FACEBOOK_CONNECT_TIMEOUT", "15")),
            int(os.getenv("FACEBOOK_READ_TIMEOUT", "45")),
        )

    @property
    def nombre_red(self) -> str:
        """Retorna el identificador interno de la red social."""
        return "facebook"

    def conectar(self) -> bool:
        """Valida el acceso a la pagina consultando Graph API."""
        if not self.page_token or not self.page_id:
            self.logger.error("Faltan credenciales de Facebook.")
            return False

        try:
            response = requests.get(
                f"{self.BASE_URL}/{self.page_id}",
                params={"access_token": self.page_token},
                timeout=self.timeout,
            )
            if response.status_code == 200:
                return True

            self.logger.error("No fue posible validar la pagina de Facebook: %s", response.text)
            return False
        except Exception as exc:
            self.logger.exception("Error al conectar con Facebook: %s", exc)
            return False

    def publicar_texto(self, titulo: str, contenido: str, url: Optional[str] = None) -> dict[str, Optional[str] | bool]:
        """Publica una entrada de texto en el feed de la pagina."""
        try:
            payload: dict[str, str] = {
                "message": f"{titulo}\n\n{contenido}".strip(),
                "access_token": self.page_token,
            }
            if url:
                payload["link"] = url

            response = requests.post(
                f"{self.BASE_URL}/{self.page_id}/feed",
                data=payload,
                timeout=self.timeout,
            )
            response.raise_for_status()
            data = response.json()

            return {
                "exito": True,
                "post_id": str(data.get("id")) if data.get("id") else None,
                "red": self.nombre_red,
                "error": None,
            }
        except requests.RequestException as exc:
            detalle = self._extraer_error_api(exc.response)
            self.logger.exception("Error al publicar texto en Facebook: %s", detalle)
            return {
                "exito": False,
                "post_id": None,
                "red": self.nombre_red,
                "error": detalle,
            }
        except Exception as exc:
            self.logger.exception("Error al publicar texto en Facebook: %s", exc)
            return {
                "exito": False,
                "post_id": None,
                "red": self.nombre_red,
                "error": str(exc),
            }

    def publicar_con_imagen(
        self,
        titulo: str,
        contenido: str,
        ruta_imagen: str,
        url: Optional[str] = None,
    ) -> dict[str, Optional[str] | bool]:
        """Publica una imagen con descripcion; si la imagen falla, publica solo texto."""
        try:
            imagen_path = Path(ruta_imagen)
            if not imagen_path.is_file():
                self.logger.warning("La imagen no existe en %s. Se usara publicacion de texto.", ruta_imagen)
                return self.publicar_texto(titulo, contenido, url)

            with open(imagen_path, "rb") as archivo_imagen:
                response = requests.post(
                    f"{self.BASE_URL}/{self.page_id}/photos",
                    data={
                        "caption": f"{titulo}\n\n{contenido}".strip(),
                        "access_token": self.page_token,
                    },
                    files={"source": archivo_imagen},
                    timeout=self.timeout,
                )
            response.raise_for_status()
            data = response.json()

            return {
                "exito": True,
                "post_id": str(data.get("post_id") or data.get("id")) if (data.get("post_id") or data.get("id")) else None,
                "red": self.nombre_red,
                "error": None,
            }
        except OSError as exc:
            self.logger.warning("No fue posible abrir la imagen %s. Se usara publicacion de texto. Error: %s", ruta_imagen, exc)
            return self.publicar_texto(titulo, contenido, url)
        except requests.RequestException as exc:
            detalle = self._extraer_error_api(exc.response)
            self.logger.exception("Error al publicar imagen en Facebook: %s", detalle)
            return {
                "exito": False,
                "post_id": None,
                "red": self.nombre_red,
                "error": detalle,
            }
        except Exception as exc:
            self.logger.exception("Error al publicar imagen en Facebook: %s", exc)
            return {
                "exito": False,
                "post_id": None,
                "red": self.nombre_red,
                "error": str(exc),
            }

    def _extraer_error_api(self, response: Optional[requests.Response]) -> str:
        if response is None:
            return "Facebook no devolvio respuesta HTTP."

        try:
            data = response.json()
        except ValueError:
            return f"HTTP {response.status_code}: {response.text.strip()}"

        error = data.get("error") if isinstance(data, dict) else None
        if isinstance(error, dict):
            mensaje = str(error.get("message") or f"HTTP {response.status_code}").strip()
            tipo = str(error.get("type") or "").strip()
            codigo = str(error.get("code") or "").strip()
            subcodigo = str(error.get("error_subcode") or "").strip()
            partes = [
                parte for parte in [
                    mensaje,
                    f"type={tipo}" if tipo else "",
                    f"code={codigo}" if codigo else "",
                    f"subcode={subcodigo}" if subcodigo else "",
                ] if parte
            ]
            return " | ".join(partes)

        return f"HTTP {response.status_code}: {response.text.strip()}"
