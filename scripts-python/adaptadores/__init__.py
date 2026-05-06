"""Paquete de adaptadores de redes sociales.

Las clases se importan de forma diferida desde el orquestador para evitar
que una dependencia rota en una red afecte a las demas.
"""

__all__ = [
    "RedSocialBase",
    "FacebookAdapter",
    "InstagramAdapter",
    "LinkedInAdapter",
]
