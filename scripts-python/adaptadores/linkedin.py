from __future__ import annotations

import logging
import os
from pathlib import Path
from typing import Any, Optional

import requests
from dotenv import load_dotenv

from .base import RedSocialBase


class LinkedInAdapter(RedSocialBase):
    """Adaptador para publicar contenido en LinkedIn usando UGC Posts API."""

    API_BASE = "https://api.linkedin.com/v2"
    TIMEOUT = 15

    def __init__(self) -> None:
        """Carga las credenciales de LinkedIn desde el .env del script."""
        load_dotenv(Path(__file__).resolve().parents[1] / ".env")
        self.logger = logging.getLogger("publicaciones.linkedin")
        self.access_token = str(os.getenv("LINKEDIN_ACCESS_TOKEN", "")).strip()
        self.person_urn = str(os.getenv("LINKEDIN_PERSON_URN", "")).strip()
        self.org_id = str(os.getenv("LINKEDIN_ORG_ID", "")).strip()
        self.author_urn = f"urn:li:organization:{self.org_id}" if self.org_id else self.person_urn
        self.session = requests.Session()
        self.session.headers.update(
            {
                "Authorization": f"Bearer {self.access_token}",
                "X-Restli-Protocol-Version": "2.0.0",
            }
        )

    @property
    def nombre_red(self) -> str:
        """Retorna el identificador interno de la red social."""
        return "linkedin"

    def conectar(self) -> bool:
        """Verifica credenciales minimas y disponibilidad del perfil u organizacion."""
        if not self.access_token or not self.author_urn:
            self.logger.error("Faltan credenciales de LinkedIn.")
            return False

        try:
            response = self.session.get(f"{self.API_BASE}/me", timeout=self.TIMEOUT)
            if response.ok:
                return True

            if self.org_id:
                org_response = self.session.get(
                    f"{self.API_BASE}/organizations/{self.org_id}",
                    timeout=self.TIMEOUT,
                )
                return org_response.ok

            self.logger.error("LinkedIn devolvio error de conexion: %s", response.text)
            return False
        except requests.RequestException as exc:
            self.logger.exception("No fue posible conectar con LinkedIn: %s", exc)
            return False

    def publicar_texto(self, titulo: str, contenido: str, url: Optional[str] = None) -> dict[str, Optional[str] | bool]:
        """Publica texto plano usando la API de UGC Posts."""
        try:
            payload = self._build_ugc_payload(titulo, contenido, url)
            response = self.session.post(
                f"{self.API_BASE}/ugcPosts",
                json=payload,
                timeout=self.TIMEOUT,
            )
            response.raise_for_status()

            post_id = response.headers.get("x-restli-id") or response.json().get("id")
            return {
                "exito": True,
                "post_id": str(post_id) if post_id else None,
                "red": self.nombre_red,
                "error": None,
            }
        except requests.RequestException as exc:
            self.logger.exception("Error publicando texto en LinkedIn: %s", exc)
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
        """Publica contenido con imagen en LinkedIn."""
        try:
            asset = self._subir_imagen(ruta_imagen)
            payload = self._build_ugc_payload(titulo, contenido, url, asset)
            response = self.session.post(
                f"{self.API_BASE}/ugcPosts",
                json=payload,
                timeout=self.TIMEOUT,
            )
            response.raise_for_status()

            post_id = response.headers.get("x-restli-id") or response.json().get("id")
            return {
                "exito": True,
                "post_id": str(post_id) if post_id else None,
                "red": self.nombre_red,
                "error": None,
            }
        except requests.RequestException as exc:
            self.logger.exception("Error publicando imagen en LinkedIn: %s", exc)
            return {
                "exito": False,
                "post_id": None,
                "red": self.nombre_red,
                "error": str(exc),
            }

    def _subir_imagen(self, ruta_imagen: str) -> str:
        """Registra el asset en LinkedIn y sube el binario de la imagen."""
        imagen_path = Path(ruta_imagen)
        registro_payload = {
            "registerUploadRequest": {
                "recipes": ["urn:li:digitalmediaRecipe:feedshare-image"],
                "owner": self.author_urn,
                "serviceRelationships": [
                    {
                        "relationshipType": "OWNER",
                        "identifier": "urn:li:userGeneratedContent",
                    }
                ],
            }
        }
        response = self.session.post(
            f"{self.API_BASE}/assets?action=registerUpload",
            json=registro_payload,
            timeout=self.TIMEOUT,
        )
        response.raise_for_status()
        value = response.json()["value"]
        upload_url = value["uploadMechanism"]["com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest"]["uploadUrl"]
        asset = value["asset"]

        with imagen_path.open("rb") as image_file:
            upload_response = requests.put(
                upload_url,
                data=image_file,
                headers={"Authorization": f"Bearer {self.access_token}"},
                timeout=self.TIMEOUT,
            )
            upload_response.raise_for_status()

        return asset

    def _build_ugc_payload(
        self,
        titulo: str,
        contenido: str,
        url: Optional[str] = None,
        asset: Optional[str] = None,
    ) -> dict[str, Any]:
        """Construye el payload de publicacion UGC."""
        comentario = self._build_texto(titulo, contenido, url)
        payload: dict[str, Any] = {
            "author": self.author_urn,
            "lifecycleState": "PUBLISHED",
            "specificContent": {
                "com.linkedin.ugc.ShareContent": {
                    "shareCommentary": {
                        "text": comentario,
                    },
                    "shareMediaCategory": "NONE",
                }
            },
            "visibility": {
                "com.linkedin.ugc.MemberNetworkVisibility": "PUBLIC",
            },
        }

        if asset:
            payload["specificContent"]["com.linkedin.ugc.ShareContent"]["shareMediaCategory"] = "IMAGE"
            payload["specificContent"]["com.linkedin.ugc.ShareContent"]["media"] = [
                {
                    "status": "READY",
                    "description": {
                        "text": titulo[:200],
                    },
                    "media": asset,
                    "title": {
                        "text": titulo[:200],
                    },
                }
            ]

        return payload

    def _build_texto(self, titulo: str, contenido: str, url: Optional[str] = None) -> str:
        """Arma el texto final con titulo, contenido y URL opcional."""
        texto = f"{titulo.strip()}\n\n{contenido.strip()}"
        if url and url.strip():
            texto = f"{texto}\n\n{url.strip()}"

        if len(texto) > 3000:
            self.logger.warning("Texto de LinkedIn excedia 3000 caracteres. Se recorto antes de publicar.")
            texto = texto[:2997] + "..."

        return texto
