# Etapa 3 — KPIs disponibles en Laravel

Fecha: 6 de octubre de 2026.

## Entrega

Endpoint de lectura para la futura vista Analítica de la aplicación:

`GET /api/mobile/v1/analitica/kpis`

Requiere la sesión móvil de Sanctum (`Authorization: Bearer <token>`). Devuelve `success`, `message` y `data`, igual que las demás rutas móviles. No crea ni modifica registros.

## Permisos

Se reutiliza `MobileVehiculoService::listVisibleSalidas`:

- Los roles administrativos reconocidos por el servicio consultan todas las salidas.
- Los demás usuarios consultan únicamente salidas donde son chofer o solicitante.
- El filtro por vehículo nunca amplía ese alcance.
- Un vehículo inexistente o sin salidas visibles devuelve un resultado vacío. No se confirma su existencia a través de esta ruta.
- La respuesta se envía con `Cache-Control: private, no-store`.

## Filtros

| Parámetro | Formato | Comportamiento |
|---|---|---|
| `desde` | `YYYY-MM-DD` | Desde las 00:00:00 de esa fecha. |
| `hasta` | `YYYY-MM-DD` | Incluye todo ese día, mediante límite exclusivo al día siguiente. |
| `vehiculo_id` | Entero positivo | Identificador interno, siempre limitado por permisos. |

Los tres son opcionales. Sin fechas se utiliza todo el histórico visible. Se permiten límites individuales. Una fecha inválida o un rango invertido devuelve 422; sin autenticación se devuelve 401.

Los filtros temporales se aplican a `fecha_salida`, no a fecha de creación ni de regreso. La zona horaria utilizada es la configurada por Laravel y se informa en la respuesta. Ejemplo de ruta:

`/api/mobile/v1/analitica/kpis?desde=2026-03-01&hasta=2026-05-31&vehiculo_id=1`

## Contrato de datos

`data` incluye:

- `generado_en`, `zona_horaria`, `version_reglas`: trazabilidad.
- `alcance`: todas las salidas o solo las propias por participación.
- `filtros`: valores efectivos, campo temporal y semántica inclusiva.
- `sin_datos`: no hay salidas visibles que cumplan los filtros.
- `indicadores`: valores y poblaciones de cálculo.
- `serie_mensual`: meses con registros, ordenados, con `mes`, `salidas` y `finalizadas`. No se inventan meses intermedios ni se rellenan automáticamente con cero.
- `calidad`: conteos por alerta, sin identificadores de personas ni de salidas. Las alertas se superponen.

| Indicador | Fórmula / interpretación |
|---|---|
| `salidas_total` | Todas las salidas dentro del alcance y filtros. |
| `salidas_finalizadas` | Estado `finalizado`. |
| `salidas_activas` | Estado `activo` dentro de la población filtrada; no significa automáticamente actividad del día de hoy. |
| `kilometros_registrados` | Suma de diferencias no negativas entre odómetros de finalizadas con vehículo existente y par único válido. |
| `duracion_promedio` | Promedio de intervalos positivos entre salida y regreso, no futuros, en finalizadas con vehículo existente. |
| `cobertura_checklists` | Finalizadas con un checklist único de cada tipo / total de finalizadas × 100. |

Los contadores son cero si no hay registros. Kilometraje, duración y porcentaje son `null` cuando no existe una población calculable. Cero kilómetros con pares válidos sí es un resultado distinto de ausencia de datos.

Kilometraje y duración incluyen `registros_validos` y `registros_excluidos`; las exclusiones se cuentan sobre las finalizadas de la selección, no sobre las activas. La cobertura devuelve `numerador` y `denominador`.

## Calidad y presentación futura

- Los intervalos negativos se excluyen de duración sin transformarlos a positivos.
- Los intervalos superiores a 24 horas permanecen en el cálculo, pero generan `requiere_revision=true` y un conteo. La futura interfaz debe mostrar este aviso junto al promedio.
- La duración describe salida registrada a cierre; no está verificada como tiempo físico de recorrido.
- La cobertura de checklists no equivale a campos completos: las seis entradas sin líquido de frenos siguen apareciendo como alerta.
- La ausencia de entrada en una salida activa no se presenta como defecto de datos.
- Duplicados o relaciones ambiguas bloquean el cálculo dependiente en vez de multiplicar kilómetros.
- La captura retrospectiva se informa en calidad, pero no invalida automáticamente KPIs retrospectivos. Las restricciones para candidatos predictivos continúan separadas.

## Reglas compartidas y ejecución

La clase de preparación se trasladó a `app/Services/Analytics/SalidasDataset.php`. Los scripts de la etapa 2 conservan compatibilidad mediante `scripts/analytics/SalidasDataset.php`, que carga la misma clase. Así, dataset y API comparten las validaciones.

El servicio lee salidas por lotes de 500 y consulta los checklists y condiciones de cada lote sin cargar usuarios ni documentos completos. Las lecturas se agrupan en una transacción; la consistencia de instantánea depende del aislamiento de la conexión de base de datos (MySQL/MariaDB normalmente utiliza REPEATABLE READ).

## Validación

- 6 pruebas de integración y 52 aserciones aprobadas.
- La suite configura una conexión SQLite `:memory:` dedicada antes de crear tablas: no ejecuta migraciones, limpieza ni fixtures sobre la base operativa.
- Casos: acceso sin sesión, administrador y operador, participación como solicitante, filtro de vehículo ajeno, resultados vacíos, rango inclusivo, filtros inválidos, intervalos negativos/largos, campos faltantes y duplicados.
- Las 19 comprobaciones de preparación de la etapa 2 continúan pasando.
- Ruta registrada y sintaxis PHP verificadas.
- Cotejo del servicio contra datos reales dentro de una transacción de solo lectura, usando un contexto administrativo en memoria, sin crear cuentas ni tokens. La autenticación HTTP se verifica en la suite aislada.

Resultado global del cotejo real:

| Medida | Resultado |
|---|---:|
| Salidas totales | 17 |
| Finalizadas / activas | 16 / 1 |
| Kilómetros, en 16 pares válidos | 151 |
| Promedio de duración, en 15 intervalos positivos | 17,780.34 minutos |
| Excluidos del promedio | 1 |
| Intervalos del promedio mayores a 24 horas | 9 |
| Cobertura de ambos checklists | 16/16 = 100% |

El promedio requiere revisión y no debe presentarse como una duración operativa confiable. Evidencia agregada: `kpis-verificacion-2026-10-06.json`.

Comandos de comprobación:

```powershell
php vendor/phpunit/phpunit/phpunit tests/Unit/MobileAnalyticsTest.php
php scripts/analytics/test_dataset.php
php artisan route:list --path=api/mobile/v1/analitica
```

## Estado

Backend de indicadores implementado y validado. No se entrenó un modelo ni se agregaron pantallas Flutter en esta etapa. La conexión visual de Analítica podrá consumir este contrato sin volver a calcular indicadores en el teléfono.
