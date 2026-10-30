# Integración móvil — Analítica

Fecha: 7 de octubre de 2026.

## Cambios

- Acceso con icono y etiqueta **Analítica** en la barra superior del inicio de SAICO.
- Ruta Flutter `/analytics`, independiente de las tres pestañas operativas existentes.
- Pestañas **Resumen**, **Tendencia** y **Calidad** para distribuir la información.
- Resumen: salidas totales, finalizadas y activas, kilómetros, intervalo promedio en horas y cobertura de checklists.
- Denominadores y exclusiones junto a kilómetros, duración y cobertura. La conversión de minutos a horas es solo presentación.
- Advertencia visible cuando el promedio incluye intervalos mayores a 24 horas. La cobertura no se presenta como garantía de calidad de todos los campos.
- Gráfica de barras por mes, con conteos exactos accesibles en texto; no se inventan meses sin registros.
- Filtros por rango de fechas y vehículo, limpieza de filtros y actualización manual.
- Estados de carga, error con reintento y selección sin datos. Los valores NULL se muestran como **Sin datos**.

## Backend

Se consume `/api/mobile/v1/analitica/kpis` con el cliente autenticado existente. Los parámetros son `desde`, `hasta` y `vehiculo_id`; los filtros no recalculan indicadores en el teléfono.

Se agregó `/api/mobile/v1/analitica/vehiculos` para obtener vehículos con historial visible al usuario. No se utiliza el catálogo de vehículos disponibles, porque excluiría unidades ocupadas o no disponibles que sí tienen registros históricos. La nueva ruta requiere Sanctum y aplica el mismo alcance por rol/participación. Responde con `id` y `label` (placa, marca, modelo), sin datos de personas.

Las solicitudes se cancelan al abandonar la vista o cambiar filtros. Se utiliza `Accept: application/json` y el token de sesión existente. No hay sustitución por datos simulados en ejecución.

## Validación

- Backend: 7 pruebas y 59 aserciones en SQLite en memoria, incluyendo autenticación y visibilidad del catálogo histórico.
- Flutter: 5 pruebas de Analítica (pestañas, letra ampliada, alertas, selección y limpieza de vehículo, error/reintento, estado vacío y parámetros enviados al servidor).
- Las 20 pruebas existentes de inicio y diseño también pasaron durante la ejecución general; se corrigió y volvió a comprobar el único desbordamiento encontrado en las nuevas pestañas con letra ampliada.
- Análisis Flutter sin incidencias antes del ajuste final de altura; cambio posterior cubierto por las pruebas de diseño.
- Las dos rutas de Analítica aparecen registradas en Laravel.
- Conectividad USB verificada desde el Samsung: la ruta protegida responde HTTP 401 sin token, como corresponde.

## Uso

Abrir **SAICO → Analítica**. Por defecto se consulta todo el histórico que el usuario puede ver. **Filtros** permite elegir fechas de salida y vehículo; **Limpiar filtros** restaura el histórico completo. El icono de actualización vuelve a consultar los datos.

La compilación de prueba usa `http://127.0.0.1:8000/api/mobile/v1` a través de `adb reverse tcp:8000 tcp:8000`. Requiere mantener USB y servidor encendidos; puede ser necesario restablecer el enlace al reconectar el dispositivo.

## Alcance

Esta fase conecta los KPIs descriptivos al celular. No incorpora predicciones ni agrupamientos: la revisión de datos de las fases anteriores no permite todavía presentar un modelo predictivo como confiable.

## Instalación en el dispositivo

APK de depuración generado e instalado correctamente en el Samsung SM-A235M por USB. Se restableció `adb reverse` después de una reconexión y Android confirmó el arranque de la aplicación. La comprobación visual llegó a la pantalla de inicio de sesión; la revisión de los KPIs en el dispositivo con una cuenta real queda pendiente de que el usuario inicie sesión. No se solicitaron ni utilizaron sus credenciales.
