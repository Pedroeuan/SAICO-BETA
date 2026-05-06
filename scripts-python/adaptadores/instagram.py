from __future__ import annotations

import logging
import os
import time
from pathlib import Path
from typing import Optional
from urllib.parse import quote, urlparse

import requests

from .base import RedSocialBase


class InstagramAdapter(RedSocialBase):
    """Adaptador para publicar imagenes en Instagram Business mediante Graph API."""

    API_VERSION = "v19.0"
    BASE_URL = f"https://graph.facebook.com/{API_VERSION}"
    TIMEOUT = 20
    ESTADOS_LISTOS = {"FINISHED", "PUBLISHED"}
    ESTADOS_ERROR = {"ERROR", "EXPIRED"}

    def __init__(self) -> None:
        self.cargar_entorno()
        self.logger = logging.getLogger("publicaciones.instagram")
        self.access_token = str(
            os.getenv("INSTAGRAM_ACCESS_TOKEN", "") or os.getenv("FACEBOOK_PAGE_TOKEN", "")
        ).strip()
        self.ig_user_id = str(os.getenv("INSTAGRAM_IG_USER_ID", "")).strip()
        self.app_url = str(os.getenv("APP_URL", "")).strip().rstrip("/")

    @property
    def nombre_red(self) -> str:
        return "instagram"

    def conectar(self) -> bool:
        if not self.access_token or not self.ig_user_id or not self.app_url:
            self.logger.error("Faltan credenciales o APP_URL para Instagram.")
            return False

        try:
            response = requests.get(
                f"{self.BASE_URL}/{self.ig_user_id}",
                params={
                    "fields": "id,username",
                    "access_token": self.access_token,
                },
                timeout=self.TIMEOUT,
            )
            if response.ok:
                return True

            self.logger.error("No fue posible validar la cuenta de Instagram: %s", response.text)
            return False
        except requests.RequestException as exc:
            self.logger.exception("Error al conectar con Instagram: %s", exc)
            return False

    def publicar_texto(self, titulo: str, contenido: str, url: Optional[str] = None) -> dict[str, Optional[str] | bool]:
        return {
            "exito": False,
            "post_id": None,
            "red": self.nombre_red,
            "error": "Instagram requiere una imagen publica para publicar.",
        }

    def publicar_con_imagen(
        self,
        titulo: str,
        contenido: str,
        ruta_imagen: str,
        url: Optional[str] = None,
    ) -> dict[str, Optional[str] | bool]:
        try:
            image_url = self._resolver_url_publica(ruta_imagen)
            if not image_url:
                return {
                    "exito": False,
                    "post_id": None,
                    "red": self.nombre_red,
                    "error": "No fue posible construir una URL publica de la imagen para Instagram.",
                }

            caption = self._build_caption(titulo, contenido, url)
            contenedor_id = self._crear_contenedor(image_url, caption)
            post_id = self._publicar_contenedor(contenedor_id)

            return {
                "exito": True,
                "post_id": post_id,
                "red": self.nombre_red,
                "error": None,
            }
        except Exception as exc:
            self.logger.exception("Error al publicar imagen en Instagram: %s", exc)
            return {
                "exito": False,
                "post_id": None,
                "red": self.nombre_red,
                "error": str(exc),
            }

    def _crear_contenedor(self, image_url: str, caption: str) -> str:
        response = requests.post(
            f"{self.BASE_URL}/{self.ig_user_id}/media",
            data={
                "image_url": image_url,
                "caption": caption,
                "access_token": self.access_token,
            },
            timeout=self.TIMEOUT,
        )
        if not response.ok:
            detalle = self._extraer_error_api(response)
            raise RuntimeError(f"Instagram rechazo el contenedor de medios: {detalle}")
        creation_id = response.json().get("id")
        if not creation_id:
            raise RuntimeError("Instagram no devolvio el creation id del contenedor.")

        self._esperar_contenedor_listo(str(creation_id))
        return str(creation_id)

    def _publicar_contenedor(self, creation_id: str) -> str:
        response = requests.post(
            f"{self.BASE_URL}/{self.ig_user_id}/media_publish",
            data={
                "creation_id": creation_id,
                "access_token": self.access_token,
            },
            timeout=self.TIMEOUT,
        )
        response.raise_for_status()
        post_id = response.json().get("id")
        if not post_id:
            raise RuntimeError("Instagram no devolvio el id de la publicacion.")

        return str(post_id)

    def _esperar_contenedor_listo(self, creation_id: str) -> None:
        for _ in range(12):
            response = requests.get(
                f"{self.BASE_URL}/{creation_id}",
                params={
                    "fields": "status_code",
                    "access_token": self.access_token,
                },
                timeout=self.TIMEOUT,
            )
            response.raise_for_status()
            status = str(response.json().get("status_code", "")).upper()

            if status in self.ESTADOS_LISTOS:
                return

            if status in self.ESTADOS_ERROR:
                raise RuntimeError(f"Instagram devolvio un estado invalido para el contenedor: {status}")

            time.sleep(2)

        raise RuntimeError("Instagram no termino de procesar la imagen dentro del tiempo esperado.")

    def _resolver_url_publica(self, ruta_imagen: str) -> Optional[str]:
        imagen_path = Path(ruta_imagen)
        if not imagen_path.is_file():
            self.logger.warning("La imagen local no existe para Instagram: %s", ruta_imagen)
            return None

        if not self._app_url_es_publica():
            self.logger.warning(
                "APP_URL no es publica para Instagram: %s. Debe ser accesible desde internet.",
                self.app_url,
            )
            return None

        base_storage = (Path(__file__).resolve().parents[2] / "storage" / "app" / "public").resolve()
        try:
            relativa = imagen_path.resolve().relative_to(base_storage)
        except ValueError:
            self.logger.warning("La imagen no esta dentro del disco publico esperado: %s", ruta_imagen)
            return None

        relativa_url = "/".join(quote(segment) for segment in relativa.parts)
        return f"{self.app_url}/storage/{relativa_url}"

    def _app_url_es_publica(self) -> bool:
        if not self.app_url:
            return False

        host = (urlparse(self.app_url).hostname or "").lower()
        if host in {"127.0.0.1", "localhost", "::1", ""}:
            return False

        prefijos_privados = ("10.", "192.168.", "172.16.", "172.17.", "172.18.", "172.19.", "172.20.", "172.21.", "172.22.", "172.23.", "172.24.", "172.25.", "172.26.", "172.27.", "172.28.", "172.29.", "172.30.", "172.31.")
        return not host.startswith(prefijos_privados)

    def _extraer_error_api(self, response: requests.Response) -> str:
        try:
            data = response.json()
        except ValueError:
            return f"HTTP {response.status_code}: {response.text.strip()}"

        error = data.get("error") if isinstance(data, dict) else None
        if isinstance(error, dict):
            mensaje = str(error.get("message") or f"HTTP {response.status_code}").strip()
            tipo = str(error.get("type") or "").strip()
            codigo = str(error.get("code") or "").strip()
            partes = [parte for parte in [mensaje, f"type={tipo}" if tipo else "", f"code={codigo}" if codigo else ""] if parte]
            return " | ".join(partes)

        return f"HTTP {response.status_code}: {response.text.strip()}"

    def _build_caption(self, titulo: str, contenido: str, url: Optional[str] = None) -> str:
        caption = f"{titulo.strip()}\n\n{contenido.strip()}".strip()
        if url and url.strip():
            caption = f"{caption}\n\n{url.strip()}"

        if len(caption) > 2200:
            self.logger.warning("Caption de Instagram excedia 2200 caracteres. Se recorto antes de publicar.")
            caption = caption[:2197].rstrip() + "..."

        return caption
