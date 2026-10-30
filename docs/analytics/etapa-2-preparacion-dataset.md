# Etapa 2 — Preparación del dataset de SAICO

Fecha: 6 de octubre de 2026. Versión de reglas: 1.0.0.

## Entrega realizada

Se construyó una copia de trabajo de las salidas, con una fila por salida, normalización de campos faltantes y marcas de calidad. La base de datos original no se modificó. La extracción se hizo mediante una transacción MySQL/MariaDB de solo lectura y consultas a columnas explícitamente permitidas.

El dataset no se publica en la aplicación ni se utiliza todavía para entrenar. Su propósito es preparar la etapa de indicadores y documentar qué puede entrar en los futuros experimentos.

## Archivos

Carpeta local de esta ejecución:

`storage/app/analytics/datasets/20261006_230128_c4b9b9/`

| Archivo | Contenido |
|---|---|
| `fuente_minima.json` | Copia de las columnas necesarias de 17 salidas, 33 checklists y 33 condiciones antes de normalizar. No es un respaldo completo de la base. |
| `salidas_preparadas.csv` | 17 filas, una por salida, valores derivados y banderas de calidad. |
| `candidatos_duracion.csv` | 2 candidatos provisionales para explorar duración al solicitar; incluye entradas permitidas, objetivo y metadatos de revisión. No es un conjunto validado para entrenar. |
| `incidencias_calidad.csv` | Una fila por salida y alerta; una salida puede tener varias alertas. |
| `diccionario.json` | Definición de cada columna, función y representación de faltantes. |
| `manifest.json` | Versión, fecha, zona horaria, conteos, limitaciones y huellas SHA-256 de los archivos. |

La carpeta está fuera del almacenamiento público y está ignorada por Git mediante `storage/app/.gitignore`. Se excluyeron nombres, correos, placas, documentos, observaciones y motivos en texto libre. Los identificadores internos se conservan para trazabilidad: estos datos son seudonimizados, no anónimos.

Un resumen sin filas individuales está disponible en `resumen-dataset-2026-10-06.json`.

## Resultado del corte

| Control | Resultado |
|---|---:|
| Salidas conservadas | 17 |
| Salidas finalizadas | 16 |
| Intervalos de duración positivos con fechas válidas | 15 |
| Pares de kilometraje utilizables para KPI | 16 |
| Kilómetros registrados válidos | 151 |
| Duraciones superiores a 24 horas marcadas | 9 |
| Duración no positiva excluida del KPI de duración | 1 |
| Solicitudes creadas después de su fecha de salida registrada | 13 |
| Candidatos exploratorios tras el control temporal de creación | 2 |
| Entradas con líquido de frenos faltante | 6 |
| Salidas con líquido de frenos faltante | 6 |

Los conteos de alertas se superponen; no sumarlos como si fueran salidas distintas.

### Nuevo hallazgo temporal

Trece filas tienen `created_at > fecha_salida`. Esto puede deberse a captura retrospectiva, migración de datos u otra práctica operativa; no se atribuye una causa sin verificarla.

Para una predicción realizada al solicitar, se adoptó una regla conservadora: no incluir estas filas entre los candidatos hasta aclarar la cronología. Se mantienen en el dataset general y siguen siendo utilizables para KPIs cuando cumplen sus propios controles. Esta regla no afirma que los 13 registros sean falsos ni exige corregirlos automáticamente.

Los dos candidatos restantes no bastan para presentar entrenamiento, validación y prueba confiables. Tampoco están certificados: todos los registros mantienen `procedencia_verificada=0`, y cualquier duración larga conserva su alerta.

## Diccionario de las columnas principales

| Campo | Significado y uso |
|---|---|
| `salida_id` | Identificador interno para relacionar alertas; nunca entrada del modelo. |
| `vehiculo_id` | Identificador categórico del vehículo; no tratarlo como cantidad numérica. |
| `estatus` | Estado observado al extraer; no usarlo para predecir el resultado de esa salida. |
| `fecha_solicitud` | `created_at` de la solicitud, para controles y futura partición temporal. |
| `fecha_salida`, `fecha_regreso` | Fechas registradas, interpretadas en la zona de la aplicación. La segunda es información posterior a la solicitud. |
| `salida_hora` | Hora programada, 0 a 23. |
| `salida_dia_semana` | Lunes=1 a domingo=7. |
| `salida_mes` | Mes programado, 1 a 12. |
| `duracion_guardada_minutos` | Valor almacenado, solo para reconciliación. |
| `duracion_calculada_minutos` | Diferencia con signo entre regreso y salida, en minutos. Objetivo exploratorio. |
| `checklists_salida`, `checklists_entrada` | Cantidad de checklists por tipo; permite detectar duplicados. |
| `condiciones_salida`, `condiciones_entrada` | Cantidad de condiciones del checklist único; 0 si no se pudo escoger un checklist sin ambigüedad. |
| `km_salida`, `km_entrada` | Lecturas no negativas de odómetro; NULL si faltan, son inválidas o ambiguas. |
| `km_recorridos` | Diferencia con signo entre ambos odómetros. Debe consultarse junto con su elegibilidad. |
| `salida_*`, `entrada_*` de condiciones | Categorías normalizadas del checklist correspondiente. No usar condiciones de entrada para predecir el mismo regreso. |
| `elegible_kpi_duracion` | 1 si está finalizada, existe el vehículo y las fechas dan un intervalo positivo no futuro. |
| `elegible_kpi_km` | 1 si está finalizada, existe el vehículo y hay un par único de odómetros no negativos que no disminuyen. |
| `ambos_checklists_unicos` | Cobertura de formularios; no equivale a captura completa de todos sus campos. |
| `candidato_duracion_exploratoria` | Elegible en duración y creación de la solicitud no posterior a la salida. No implica validación operativa. |
| `requiere_revision_duracion` | Intervalo superior a 1,440 minutos. Alerta, no eliminación automática. |
| `procedencia_verificada` | 0 hasta verificar si la fila es operativa, de prueba o migrada. |
| `motivos_exclusion_*` | Códigos separados por `\|`, específicos del indicador o modelo. |
| `alertas_calidad` | Unión de alertas y causas de exclusión por fila. |

El diccionario JSON incluye cada columna individual. Una celda CSV vacía significa NULL, no cero. Un 0 en una bandera significa falso. Las fechas usan `America/Mexico_City` en este corte; la zona real se registra en el manifiesto.

## Reglas aplicadas

1. No eliminar filas del dataset general. Mantener anomalías, valores calculados y motivos explícitos.
2. Convertir espacios y cadenas vacías a NULL. No imputar condiciones faltantes como normales.
3. Aceptar únicamente las categorías definidas en el formulario; una categoría desconocida se marca y su valor original permanece en la copia mínima.
4. No escoger arbitrariamente entre checklists o condiciones duplicados. Bloquear el cálculo dependiente de esa relación.
5. No convertir duraciones negativas a absolutas. Excluirlas solo de los usos incompatibles.
6. Marcar intervalos largos para revisión. Conservarlos en los candidatos si cumplen los controles restantes; no prometer que sean duración física de viaje.
7. Una salida activa no necesita todavía regreso. Su falta de checklist de entrada indica indisponibilidad para el cálculo de kilometraje final, no necesariamente un error de operación.
8. Calcular cada KPI sobre su propia población elegible. No usar automáticamente una única tabla filtrada para todos los indicadores.
9. No entrenar, imputar, escalar ni dividir en conjuntos todavía. En una etapa posterior cualquier ajuste de preprocesamiento debe aprenderse solo del entrenamiento.

## Prevención de uso de información futura

Escenario elegido: estimar el intervalo de salida a cierre al registrar la solicitud.

Lista permitida inicial de entradas: `vehiculo_id`, `salida_hora`, `salida_dia_semana`, `salida_mes`. La identidad del vehículo debe codificarse como categoría y contemplar vehículos no observados en entrenamiento.

Objetivo: `duracion_calculada_minutos`.

El identificador de salida, fechas de trazabilidad y banderas son metadatos; no entrenar usando todas las columnas del CSV automáticamente. Quedan fuera de las entradas el regreso, estado final, duración guardada, kilometraje de regreso, condiciones de entrada y los datos actuales mutables del vehículo. No se ha verificado históricamente si las fechas programadas fueron editadas después: esta limitación debe resolverse al validar el experimento.

## Ejecución y controles

Desde la raíz del repositorio:

```powershell
php scripts/analytics/test_dataset.php
php scripts/analytics/prepare_dataset.php
```

Cada extracción crea una carpeta nueva; no sobreescribe cortes anteriores. El manifiesto se escribe al final: una carpeta sin manifiesto no debe considerarse una entrega completa. Una nueva ejecución puede diferir si cambió la base o la fecha de referencia.

Controles realizados:

- Sintaxis PHP de los scripts.
- 19 comprobaciones de reglas con casos sintéticos: fechas imposibles, duración negativa/cero/futura, captura retrospectiva, faltantes, categorías desconocidas, duplicados, kilometraje decreciente y relaciones ausentes.
- 17 filas y 17 identificadores únicos en la exportación real.
- Conciliación de 15 intervalos válidos, 16 pares y 151 km con la etapa 1.
- Seis valores de líquido de frenos de entrada conservados como faltantes.
- Verificación de las huellas SHA-256 de los cinco archivos de datos/diccionario.

## Estado y siguiente entrega

La preparación técnica de la etapa 2 está completada. Queda pendiente la revisión operativa de cronología, procedencia e intervalos largos antes de declarar un dataset apto para predicción.

La etapa 3 puede avanzar con los indicadores descriptivos, incluyendo denominadores y avisos de calidad. No se realizaron cambios en Flutter ni se instaló una nueva APK en esta etapa.
