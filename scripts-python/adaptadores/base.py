from __future__ import annotations

from abc import ABC, abstractmethod
from typing import Any, Optional


class RedSocialBase(ABC):
    """Contrato base para todos los adaptadores de redes sociales."""

    def __init__(self, config: dict[str, Any], publicacion: dict[str, Any]) -> None:
        self.config = config
        self.publicacion = publicacion

    @abstractmethod
    def conectar(self) -> bool:
        """Valida credenciales y deja listo el adaptador."""

    @abstractmethod
    def publicar_texto(self) -> dict[str, Optional[str] | bool]:
        """Publica solo texto y retorna un resultado estructurado."""

    @abstractmethod
    def publicar_con_imagen(self) -> dict[str, Optional[str] | bool]:
        """Publica texto con imagen y retorna un resultado estructurado."""

    def publicar_con_video(self) -> dict[str, Optional[str] | bool]:
        """Implementación futura para publicar video."""
        return {
            "exito": False,
            "post_id": None,
            "red": self.nombre_red,
            "error": "no implementado",
        }

    @property
    def nombre_red(self) -> str:
        """Nombre lógico de la red basado en la clase."""
        nombre = self.__class__.__name__.replace("Adapter", "")
        return nombre.lower()
