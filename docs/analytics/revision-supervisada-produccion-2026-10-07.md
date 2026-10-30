# Revisión de datos para aprendizaje supervisado en producción

Fecha: 7 de octubre de 2026.

## Objetivo acordado

Preparar un sistema que pueda operar con datos reales en producción. No se generaron registros de demostración, se alteraron fechas operativas ni se entrenó un modelo con resultados ficticios. Los casos sintéticos de los tests sirven exclusivamente para comprobar el código y nunca se incorporan al dataset.

## Evidencia obtenida

Se revisaron las 17 salidas actuales, comparando fecha de solicitud, salida registrada, regreso y creación de ambos checklists. La extracción se realizó en una transacción de solo lectura.

- 13 solicitudes se crearon después de la salida registrada: 1 con diferencia de hasta 60 segundos, 6 entre 61 segundos y una hora, y 6 con diferencia mayor a una hora.
- Las 16 salidas finalizadas tienen checklists en orden cronológico.
- En las 16, el regreso registrado coincide con la captura del checklist de entrada con diferencia máxima de 60 segundos.
- El código web y móvil asigna `fecha_regreso = now()` al guardar/cerrar. Por tanto, esa columna acredita el momento del registro, no por sí sola el regreso físico.
- Hay 9 intervalos registrados mayores a 24 horas y 1 no positivo.
- Comparar la hora de los checklists no autoriza a reemplazar las fechas originales ni a afirmar que todas las salidas son reales.
- La zona configurada de la aplicación es America/Mexico_City; el reloj actual de la base es coherente con la extracción. Esto no demuestra qué zona se empleó en capturas antiguas. No se aplicó ninguna corrección horaria retrospectiva.

Las trece capturas posteriores no tienen una única magnitud de desfase. No hay evidencia suficiente para corregirlas todas con una constante ni para declararlas automáticamente inválidas o pruebas.

## Entregables técnicos

1. `scripts/analytics/review_temporal.php`: extracción temporal reproducible de solo lectura.
2. Carpeta privada `storage/app/analytics/reviews/20261007_205728_d411c4/`:
   - `fuente_temporal.json`: evidencia original seleccionada.
   - `revision_por_salida.csv`: expediente de revisión por identificador interno, sin nombres ni correos.
   - `manifest.json`: conteos, zona horaria y huellas de integridad.
3. `scripts/analytics/assess_supervised_readiness.py`: control de admisión de datos revisados. Verifica integridad del archivo fuente, correspondencia exacta de identificadores y que las columnas originales no se alteren en la revisión.
4. `scripts/analytics/test_supervised_readiness.py`: seis pruebas del control de admisión.

Los scripts de revisión producen archivos locales fuera del almacenamiento público; no modifican la base ni los KPIs existentes.

## Decisiones por registro

El CSV separa los datos originales de cinco campos de decisión:

| Campo | Valores permitidos |
|---|---|
| procedencia | pendiente, operacion, prueba |
| fecha_salida_representa | pendiente, salida_real, programada |
| fecha_regreso_representa | pendiente, regreso_real, cierre_tardio |
| decision_supervisado | pendiente, aprobar, excluir |
| evidencia_revision | Referencia al registro operativo o explicación verificable de la decisión; no incluir secretos ni datos personales innecesarios. |

La intención de usar el sistema en producción no confirma la procedencia de los registros históricos. Por eso las 17 filas permanecen pendientes. No es necesario recuperar todas: se pueden excluir las que carezcan de evidencia y construir historial válido con la operación futura.

El control requiere origen operativo confirmado, interpretación de fechas confirmada y evidencia. La aprobación manual no anula fechas invertidas, captura posterior a salida, falta de cierre ni regresos posteriores al corte.

Resultado actual: **0 registros admitidos tras revisión confirmada**. Los dos candidatos de la etapa 2 eran provisionales por estructura temporal; no eran registros certificados. Los candidatos admitidos en este nuevo proceso tampoco habilitan automáticamente predicciones: aún requieren comprobar entradas históricas, suficiencia de datos y evaluación del modelo.

## Contrato necesario para la captura futura

Antes de construir un predictor de tiempo real de recorrido, el flujo deberá distinguir estos eventos:

| Dato | Qué representa |
|---|---|
| Solicitud creada | Instante del servidor al registrar la solicitud. |
| Salida programada | Fecha planeada por el usuario. |
| Salida confirmada | Registro de inicio operativo; conservar cuándo se capturó y si hubo captura tardía. |
| Regreso confirmado | Registro del regreso operativo; distinguirlo del cierre administrativo. |
| Cierre del checklist | Instante de guardado, independiente de las fechas operativas. |
| Entradas de la predicción | Copia versionada de la información disponible cuando se solicita la estimación. |
| Origen | Separación verificable de operación y pruebas; usar un entorno de pruebas aparte. |

Estos campos/eventos son el siguiente desarrollo del flujo operativo; no se agregaron en esta entrega. No rellenar automáticamente las horas físicas históricas con `created_at`.

## Proceso posterior de producción

1. Capturar eventos operativos y entradas de predicción trazables.
2. Generar datasets versionados con estas reglas de admisión.
3. Evaluar suficiencia, diversidad y cobertura temporal; un conteo mínimo no garantiza fiabilidad.
4. Separar por tiempo respetando que la etiqueta de cada salida debe haberse conocido al entrenar. Una salida que termina después del corte no puede entrenar una predicción anterior a ese cierre.
5. Comparar una referencia histórica con los modelos mediante MAE/RMSE en datos no usados para ajustar.
6. Registrar versión, fecha y límites del modelo aprobado. Solo entonces exponer predicciones en la API.
7. Monitorizar errores y mantener la respuesta “predicción no disponible” cuando no exista un modelo validado aplicable.

La nueva evaluación es un control de datos previo a ese proceso, no un servicio de predicción ni una certificación automática de producción.

## Ejecutar

```powershell
php scripts/analytics/review_temporal.php
python scripts/analytics/assess_supervised_readiness.py storage/app/analytics/reviews/20261007_205728_d411c4
python -m unittest discover -s scripts/analytics -p test_supervised_readiness.py -v
```

Cada revisión/evaluación crea una carpeta nueva. Las huellas permiten identificar la fuente y la revisión utilizadas. La revisión original conserva todos los estados pendientes; no se sobreescribió con decisiones supuestas.
