from __future__ import annotations

import logging
from pathlib import Path
from typing import Any, Optional

import requests

from .base import RedSocialBase


class FacebookAdapter(RedSocialBase):
    """Adaptador para publicar en páginas de empresa vía Graph API."""

    API_VERSION = "v18.0"
    BASE_URL = f"https://graph.facebook.com/{API_VERSION}"
    TIMEOUT = 15

    def __init__(self, config: dict[str, Any], publicacion: dict[str, Any]) -> None:
        super().__init__(config, publicacion)
        self.logger = logging.getLogger("publicaciones.facebook")
        self.page_id = str(config.get("FACEBOOK_PAGE_ID", "")).strip()
        self.access_token = str(config.get("FACEBOOK_PAGE_ACCESS_TOKEN", "")).strip()

    @property
    def nombre_red(self) -> str:
        return "facebook"

    def conectar(self) -> bool:
        """Verifica credenciales y acceso a la página de Facebook."""
        if not self.page_id or not self.access_token:
            self.logger.error("Faltan credenciales de Facebook.")
            return False

        try:
            response = requests.get(
                f"{self.BASE_URL}/{self.page_id}",
                params={"access_token": self.access_token},
                timeout=self.TIMEOUT,
            )
            if not response.ok:
                self.logger.error("Facebook devolvio error de conexion: %s", response.text)
            return response.ok
        except requests.RequestException as exc:
            self.logger.exception("No fue posible conectar con Facebook: %s", exc)
            return False

    def publicar_texto(self) -> dict[str, Optional[str] | bool]:
        """Publica texto en el feed de la página."""
        try:
            payload = {
                "message": self._build_texto(),
                "access_token": self.access_token,
            }
            if self.publicacion.get("url_destino"):
                payload["link"] = str(self.publicacion["url_destino"])

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
        except requests.RequestException as exc:
            self.logger.exception("Error publicando texto en Facebook: %s", exc)
            return {
                "exito": False,
                "post_id": None,
                "red": self.nombre_red,
                "error": str(exc),
            }

    def publicar_con_imagen(self) -> dict[str, Optional[str] | bool]:
        """Publica una imagen en la página con caption."""
        try:
            imagen_path = Path(str(self.publicacion.get("imagen", "")))
            with imagen_path.open("rb") as image_file:
                response = requests.post(
                    f"{self.BASE_URL}/{self.page_id}/photos",
                    data={
                        "caption": self._build_texto(),
                        "access_token": self.access_token,
                    },
                    files={"source": image_file},
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
        except requests.RequestException as exc:
            self.logger.exception("Error publicando imagen en Facebook: %s", exc)
            return {
                "exito": False,
                "post_id": None,
                "red": self.nombre_red,
                "error": str(exc),
            }

    def _build_texto(self) -> str:
        """Genera el texto final de la publicación."""
        titulo = str(self.publicacion.get("titulo", "")).strip()
        contenido = str(self.publicacion.get("contenido", "")).strip()
        url = str(self.publicacion.get("url_destino") or "").strip()
        partes = [titulo, contenido]
        if url:
            partes.append(url)
        return "\n\n".join([parte for parte in partes if parte])
