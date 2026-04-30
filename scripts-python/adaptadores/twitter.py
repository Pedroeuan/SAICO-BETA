from __future__ import annotations

import logging
from pathlib import Path
from typing import Any, Optional

import tweepy

from .base import RedSocialBase


class TwitterAdapter(RedSocialBase):
    """Adaptador para publicar en X/Twitter usando Tweepy v4."""

    TIMEOUT = 15

    def __init__(self, config: dict[str, Any], publicacion: dict[str, Any]) -> None:
        super().__init__(config, publicacion)
        self.logger = logging.getLogger("publicaciones.twitter")
        self.client = tweepy.Client(
            consumer_key=str(config.get("TWITTER_API_KEY", "")).strip(),
            consumer_secret=str(config.get("TWITTER_API_SECRET", "")).strip(),
            access_token=str(config.get("TWITTER_ACCESS_TOKEN", "")).strip(),
            access_token_secret=str(config.get("TWITTER_ACCESS_TOKEN_SECRET", "")).strip(),
        )
        auth = tweepy.OAuth1UserHandler(
            str(config.get("TWITTER_API_KEY", "")).strip(),
            str(config.get("TWITTER_API_SECRET", "")).strip(),
            str(config.get("TWITTER_ACCESS_TOKEN", "")).strip(),
            str(config.get("TWITTER_ACCESS_TOKEN_SECRET", "")).strip(),
        )
        self.api_v1 = tweepy.API(auth, timeout=self.TIMEOUT)

    @property
    def nombre_red(self) -> str:
        return "twitter"

    def conectar(self) -> bool:
        """Verifica acceso al usuario autenticado."""
        try:
            self.api_v1.verify_credentials()
            return True
        except Exception as exc:
            self.logger.exception("No fue posible conectar con X/Twitter: %s", exc)
            return False

    def publicar_texto(self) -> dict[str, Optional[str] | bool]:
        """Publica un tweet de texto."""
        try:
            response = self.client.create_tweet(text=self._build_texto())
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

    def publicar_con_imagen(self) -> dict[str, Optional[str] | bool]:
        """Publica un tweet con imagen subida por API v1.1."""
        try:
            imagen_path = Path(str(self.publicacion.get("imagen", "")))
            media = self.api_v1.media_upload(filename=str(imagen_path))
            response = self.client.create_tweet(
                text=self._build_texto(),
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

    def _build_texto(self) -> str:
        """Genera el tweet y lo limita a 280 caracteres."""
        titulo = str(self.publicacion.get("titulo", "")).strip()
        contenido = str(self.publicacion.get("contenido", "")).strip()
        url = str(self.publicacion.get("url_destino") or "").strip()
        texto = " ".join([parte for parte in [titulo, contenido, url] if parte]).strip()

        if len(texto) > 280:
            texto = texto[:277].rstrip() + "..."

        return texto
