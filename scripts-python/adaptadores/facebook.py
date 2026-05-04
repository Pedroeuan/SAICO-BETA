from __future__ import annotations

import logging
import os
from pathlib import Path
from typing import Optional

import requests
from dotenv import load_dotenv

from .base import RedSocialBase


class FacebookAdapter(RedSocialBase):
    """Adaptador para publicar en la pagina de Facebook de la empresa."""

    API_VERSION = "v19.0"
    BASE_URL = f"https://graph.facebook.com/{API_VERSION}"
    TIMEOUT = 15

    def __init__(self) -> None:
        """Carga las credenciales de Facebook desde el archivo .env del script."""
        load_dotenv(Path(__file__).resolve().parents[1] / ".env")
        self.logger = logging.getLogger("publicaciones.facebook")
        self.page_token = str(os.getenv("FACEBOOK_PAGE_TOKEN", "")).strip()
        self.page_id = str(os.getenv("FACEBOOK_PAGE_ID", "")).strip()

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
                timeout=self.TIMEOUT,
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
                timeout=self.TIMEOUT,
            )
            response.raise_for_status()
            data = response.json()

            return {
                "exito": True,
                "post_id": str(data.get("id")) if data.get("id") else None,
                "red": self.nombre_red,
                "error": None,
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
                    timeout=self.TIMEOUT,
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
        except Exception as exc:
            self.logger.exception("Error al publicar imagen en Facebook: %s", exc)
            return {
                "exito": False,
                "post_id": None,
                "red": self.nombre_red,
                "error": str(exc),
            }
