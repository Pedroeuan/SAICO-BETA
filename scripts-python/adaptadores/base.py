from __future__ import annotations

from abc import ABC, abstractmethod
from typing import Optional


class RedSocialBase(ABC):
    """Contrato base para todos los adaptadores de redes sociales."""

    @abstractmethod
    def conectar(self) -> bool:
        """Valida credenciales y deja listo el adaptador."""

    @abstractmethod
    def publicar_texto(self, titulo: str, contenido: str, url: Optional[str] = None) -> dict[str, Optional[str] | bool]:
        """Publica solo texto y retorna un resultado estructurado."""

    @abstractmethod
    def publicar_con_imagen(
        self,
        titulo: str,
        contenido: str,
        ruta_imagen: str,
        url: Optional[str] = None,
    ) -> dict[str, Optional[str] | bool]:
        """Publica texto con imagen y retorna un resultado estructurado."""

    def publicar_con_video(self) -> dict[str, Optional[str] | bool]:
        """Implementacion futura para publicar video."""
        return {
            "exito": False,
            "post_id": None,
            "red": self.nombre_red,
            "error": "no implementado",
        }

    @property
    def nombre_red(self) -> str:
        """Nombre logico de la red basado en la clase."""
        nombre = self.__class__.__name__.replace("Adapter", "")
        return nombre.lower()
