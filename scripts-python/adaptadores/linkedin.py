from __future__ import annotations

import logging
from pathlib import Path
from typing import Any, Optional

import requests

from .base import RedSocialBase


class LinkedInAdapter(RedSocialBase):
    """Adaptador para publicar contenido en LinkedIn usando UGC Posts API."""

    API_BASE = "https://api.linkedin.com/v2"
    TIMEOUT = 15

    def __init__(self, config: dict[str, Any], publicacion: dict[str, Any]) -> None:
        super().__init__(config, publicacion)
        self.logger = logging.getLogger("publicaciones.linkedin")
        self.access_token = str(config.get("LINKEDIN_ACCESS_TOKEN", "")).strip()
        self.person_urn = str(config.get("LINKEDIN_PERSON_URN", "")).strip()
        self.org_id = str(config.get("LINKEDIN_ORG_ID", "")).strip()
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
        return "linkedin"

    def conectar(self) -> bool:
        """Verifica credenciales mínimas y disponibilidad del perfil u organización."""
        if not self.access_token or not self.author_urn:
            self.logger.error("Faltan credenciales de LinkedIn.")
            return False

        try:
            response = self.session.get(
                f"{self.API_BASE}/me",
                timeout=self.TIMEOUT,
            )
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

    def publicar_texto(self) -> dict[str, Optional[str] | bool]:
        """Publica texto plano usando UGC Posts API."""
        try:
            payload = self._build_ugc_payload()
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

    def publicar_con_imagen(self) -> dict[str, Optional[str] | bool]:
        """Publica contenido con imagen en LinkedIn."""
        try:
            asset = self._subir_imagen()
            payload = self._build_ugc_payload(asset)
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

    def _subir_imagen(self) -> str:
        """Registra el asset en LinkedIn y sube el binario de la imagen."""
        imagen_path = Path(str(self.publicacion.get("imagen", "")))
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

    def _build_ugc_payload(self, asset: Optional[str] = None) -> dict[str, Any]:
        """Construye el payload de publicación UGC."""
        comentario = self._build_texto()
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
                        "text": str(self.publicacion.get("imagen_alt") or self.publicacion.get("titulo") or ""),
                    },
                    "media": asset,
                    "title": {
                        "text": str(self.publicacion.get("titulo", ""))[:200],
                    },
                }
            ]

        return payload

    def _build_texto(self) -> str:
        """Arma el texto final con título, contenido y URL opcional."""
        titulo = str(self.publicacion.get("titulo", "")).strip()
        contenido = str(self.publicacion.get("contenido", "")).strip()
        url = str(self.publicacion.get("url_destino") or "").strip()
        texto = f"{titulo}\n\n{contenido}"
        if url:
            texto = f"{texto}\n\n{url}"

        if len(texto) > 3000:
            self.logger.warning("Texto de LinkedIn excedia 3000 caracteres. Se recorto antes de publicar.")
            texto = texto[:2997] + "..."

        return texto
