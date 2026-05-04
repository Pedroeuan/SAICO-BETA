from __future__ import annotations

import logging
import os
from pathlib import Path
from typing import Optional

import tweepy
from dotenv import load_dotenv

from .base import RedSocialBase


class TwitterAdapter(RedSocialBase):
    """Adaptador para publicar en X/Twitter usando Tweepy v4."""

    TIMEOUT = 15

    def __init__(self) -> None:
        """Carga las credenciales de X/Twitter desde el .env del script."""
        load_dotenv(Path(__file__).resolve().parents[1] / ".env")
        self.logger = logging.getLogger("publicaciones.twitter")
        self.client = tweepy.Client(
            consumer_key=str(os.getenv("TWITTER_API_KEY", "")).strip(),
            consumer_secret=str(os.getenv("TWITTER_API_SECRET", "")).strip(),
            access_token=str(os.getenv("TWITTER_ACCESS_TOKEN", "")).strip(),
            access_token_secret=str(os.getenv("TWITTER_ACCESS_SECRET", "")).strip(),
        )
        auth = tweepy.OAuth1UserHandler(
            str(os.getenv("TWITTER_API_KEY", "")).strip(),
            str(os.getenv("TWITTER_API_SECRET", "")).strip(),
            str(os.getenv("TWITTER_ACCESS_TOKEN", "")).strip(),
            str(os.getenv("TWITTER_ACCESS_SECRET", "")).strip(),
        )
        self.api_v1 = tweepy.API(auth, timeout=self.TIMEOUT)

    @property
    def nombre_red(self) -> str:
        """Retorna el identificador interno de la red social."""
        return "twitter"

    def conectar(self) -> bool:
        """Verifica acceso al usuario autenticado."""
        try:
            self.api_v1.verify_credentials()
            return True
        except Exception as exc:
            self.logger.exception("No fue posible conectar con X/Twitter: %s", exc)
            return False

    def publicar_texto(self, titulo: str, contenido: str, url: Optional[str] = None) -> dict[str, Optional[str] | bool]:
        """Publica un mensaje de texto."""
        try:
            response = self.client.create_tweet(text=self._build_texto(titulo, contenido, url))
            tweet_id = getattr(response.data, "get", lambda _key, _default=None: None)("id")
            if tweet_id is None and isinstance(response.data, dict):
                tweet_id = response.data.get("id")
            return {
                "exito": True,
                "post_id": str(tweet_id) if tweet_id else None,
                "red": self.nombre_red,
                "error": None,
            }
        except Exception as exc:
            self.logger.exception("Error publicando texto en X/Twitter: %s", exc)
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
        """Publica un mensaje con imagen subida por API v1.1."""
        try:
            media = self.api_v1.media_upload(filename=str(Path(ruta_imagen)))
            response = self.client.create_tweet(
                text=self._build_texto(titulo, contenido, url),
                media_ids=[media.media_id_string],
            )
            tweet_id = getattr(response.data, "get", lambda _key, _default=None: None)("id")
            if tweet_id is None and isinstance(response.data, dict):
                tweet_id = response.data.get("id")
            return {
                "exito": True,
                "post_id": str(tweet_id) if tweet_id else None,
                "red": self.nombre_red,
                "error": None,
            }
        except Exception as exc:
            self.logger.exception("Error publicando imagen en X/Twitter: %s", exc)
            return {
                "exito": False,
                "post_id": None,
                "red": self.nombre_red,
                "error": str(exc),
            }

    def _build_texto(self, titulo: str, contenido: str, url: Optional[str] = None) -> str:
        """Genera el texto final y lo limita a 280 caracteres."""
        texto = " ".join([parte for parte in [titulo.strip(), contenido.strip(), (url or "").strip()] if parte]).strip()

        if len(texto) > 280:
            texto = texto[:277].rstrip() + "..."

        return texto
