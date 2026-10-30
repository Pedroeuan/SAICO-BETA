# Etapa 1 ? Diagn?stico de datos para Anal?tica SAICO

Fecha: 6 de octubre de 2026. Fuente: base configurada en la conexi?n de Laravel de este proyecto.

## Resultado

La base permite iniciar indicadores descriptivos de salidas y checklists. A?n no permite justificar modelos predictivos confiables: hay 17 salidas, de las cuales 16 est?n finalizadas y solo 15 tienen una duraci?n cronol?gicamente positiva. Adem?s, varias duraciones requieren revisi?n operativa.

Esta auditor?a no limpia, corrige ni modifica registros. No se entrenaron modelos ni se implementaron indicadores en la aplicaci?n durante esta etapa.

## Inventario

| Tabla | Registros | Uso previsto |
|---|---:|---|
| vehiculos | 9 | Cat?logo y segmentaci?n por veh?culo. |
| salidas_vehiculos | 17 | Fechas, estado, duraci?n y relaciones de cada salida. |
| salidas_checklists | 33 | 17 de salida y 16 de entrada. |
| checklist_condiciones | 33 | Kilometraje, combustible y condiciones registradas. |
| cargas_combustible | 1 | Registro de carga; insuficiente para estimar rendimiento entre cargas. |
| mantenimientos | 2 | Registro descriptivo; insuficiente para predicci?n de mantenimiento. |
| salidas_eventos_flujo | No existe | No se puede usar esta tabla para reconstruir eventos operativos. |

Las columnas existentes est?n documentadas en el JSON adjunto. Que exista un modelo o una migraci?n en el repositorio no demuestra que la tabla exista ni que tenga datos; aqu? se verific? la base real.

## Cobertura temporal y representatividad

- Fechas de salida: del 13 de marzo al 25 de mayo de 2026.
- Por mes de salida: marzo 7, abril 8 y mayo 2.
- ?ltimo regreso registrado: 5 de octubre de 2026. No confundir este dato con nuevas solicitudes en octubre.
- 5 veh?culos tienen salidas y 4 no tienen historial.
- Distribuci?n: un veh?culo tiene 9 salidas; uno tiene 3; dos tienen 2; uno tiene 1.
- 16 salidas est?n finalizadas y 1 contin?a activa; esta ?ltima tiene m?s de 30 d?as de antig?edad.
- No se verific? si cada registro corresponde a operaci?n real o a pruebas. Es necesario identificar esa procedencia antes de entrenar.

## Integridad y calidad

### Relaciones y checklists

- No se encontraron salidas hu?rfanas de veh?culo, chofer o solicitante.
- No se encontraron checklists hu?rfanos de salida ni condiciones hu?rfanas de checklist.
- No se encontraron cargas o mantenimientos hu?rfanos de veh?culo.
- No se encontraron grupos duplicados de placa, de checklist por salida/tipo, ni de condici?n por checklist.
- No se encontraron veh?culos con m?ltiples salidas activas.
- Las 16 salidas finalizadas tienen los dos checklists: cobertura estructural de 100%.
- La salida activa tiene checklist de salida. Su ausencia de regreso y duraci?n es esperable; no debe tratarse autom?ticamente como dato defectuoso.

### Fechas y duraci?n

- Una salida tiene regreso anterior a la salida y duraci?n almacenada negativa. Excluirla de m?tricas de duraci?n y del objetivo de entrenamiento hasta aclararla; no convertir la duraci?n a valor absoluto.
- Las 15 duraciones positivas van de 22.85 a 191,803.30 minutos (aproximadamente 133.20 d?as).
- Promedio de estas 15: 17,780.34 minutos (aproximadamente 12.35 d?as).
- Mediana: 1,449.02 minutos (aproximadamente 24.15 horas).
- Hay 9 salidas finalizadas de m?s de 24 horas, de las cuales 3 superan 7 d?as y 2 superan 30 d?as.
- Los intervalos anteriores est?n anidados; no deben sumarse.
- No se encontraron diferencias mayores a un minuto entre duraci?n almacenada y c?lculo desde fechas en los registros comparables.
- Una duraci?n larga no se declara incorrecta solo por superar un umbral. Puede reflejar una salida larga o un cierre tard?o: requiere revisi?n del proceso.
- El flujo guarda fecha_salida desde la solicitud y fecha_regreso al finalizar. Antes de denominar este valor ?duraci?n real del recorrido?, hay que confirmar que esas marcas representan el movimiento f?sico del veh?culo. Actualmente es m?s preciso llamarlo ?intervalo registrado entre salida y cierre?.

### Kilometraje

- Las 16 salidas finalizadas tienen un par ?nico de kilometrajes de salida y entrada.
- Ning?n par disminuye ni permanece igual; el m?ximo de un recorrido es 44 km.
- La suma de diferencias es 151 km. Este resultado valida aritm?tica y completitud, no la veracidad f?sica de la captura.
- El indicador de kilometraje puede conservar esos 16 pares y mostrar la advertencia de fechas por separado. Un dataset que exija simult?neamente cronolog?a v?lida y kilometraje dispone de 15 candidatos, antes de revisar duraciones largas y procedencia.

### Condiciones

- No hay valores NULL en los campos principales comprobados: kilometraje, gasolina, aceite, anticongelante, l?quido de frenos y estado de llantas.
- Sin embargo, l?quido de frenos tiene 12 cadenas vac?as en los 33 checklists; 6 corresponden a entradas (37.5% de las 16 entradas).
- Por tanto, checklist existente no equivale a checklist con todos los campos completos.
- En entradas, l?quido de frenos: 9 suficientes, 1 escaso y 6 sin dato.
- Estado de llantas en entradas: 7 buen estado, 7 regular y 2 malo.
- Aceite en entradas: 15 suficientes y 1 escaso. Anticongelante: 14 suficientes, 1 escaso y 1 sin l?quido.
- Estos son conteos por campo: una misma salida puede presentar varias condiciones. No sumarlos para calcular ?salidas con incidencia?.
- No existe todav?a una etiqueta de aprendizaje validada que indique un resultado futuro de revisi?n. Su definici?n, cobertura y balance deben establecerse antes de entrenar un clasificador.

## Viabilidad por indicador y modelo

| Resultado | Dictamen | Condiciones |
|---|---|---|
| Salidas finalizadas | Viable: 16 en el hist?rico auditado | Definir el filtro temporal: fecha de salida o fecha de cierre. |
| Salidas activas | Viable: 1 | Advertir antig?edad; no cerrar autom?ticamente. |
| Kil?metros registrados | Viable: 151 km en 16 pares | Aplicar validaci?n por par y mostrar denominador. |
| Cobertura de ambos checklists | Viable: 16/16 = 100% | Etiquetar como cobertura, no como calidad completa de captura. |
| Duraci?n promedio | Condicionado | Separar 1 duraci?n negativa y revisar intervalos largos antes de usarlo como KPI operativo. |
| Condiciones de revisi?n | Condicionado | Definir criterio por salida y tratamiento de campos vac?os; no inventar ?sin incidencia?. |
| Rendimiento de combustible | No disponible | Solo existe una carga; no hay intervalo entre cargas para estimarlo. Los niveles del checklist no son litros consumidos. |
| Predicci?n de duraci?n | Solo exploraci?n acad?mica por ahora | 15 candidatos iniciales, historial concentrado y duraci?n sem?nticamente ambigua. No reportar fiabilidad operacional. |
| Predicci?n de condiciones | No preparada | Pocos ejemplos, campos incompletos y etiqueta sin definir. |
| Agrupamiento de uso | Exploraci?n descriptiva | Solo 5 veh?culos con historial; no interpretar grupos como patrones estables. |
| Predicci?n de mantenimiento | No preparada | Dos registros no sostienen una evaluaci?n defendible. |

No se establece un n?mero universal de registros suficiente para ML. Se necesita ampliar diversidad, continuidad temporal y cobertura de resultados; despu?s evaluar estabilidad y generalizaci?n.

## Reglas propuestas para la etapa 2

1. Conservar registros originales. Construir una copia de trabajo con una fila por salida y relaciones expl?citas.
2. Excluir del objetivo de duraci?n las salidas activas, fechas faltantes o invertidas y duplicados ambiguos. Guardar motivos de exclusi?n.
3. Marcar duraciones superiores a 24 horas para revisi?n, sin eliminarlas autom?ticamente.
4. Tratar cadenas vac?as como faltantes; no convertirlas a ?suficiente?, ?normal? ni cero.
5. Definir si la predicci?n se realiza al solicitar o despu?s del checklist de salida. No utilizar condiciones de entrada, kilometraje final, fecha de regreso o duraci?n como entradas para predecir ese mismo resultado.
6. Separar posteriormente entrenamiento y evaluaci?n por tiempo; con el volumen actual cualquier evaluaci?n ser? exploratoria.
7. Utilizar identificadores internos y atributos necesarios; no exportar nombres, correos, placas, documentos ni texto libre sin una necesidad definida.
8. Documentar los denominadores y exclusiones de cada KPI. No aplicar un filtro inicial de ?mes actual? como si cero salidas implicara ausencia de historial.

## Evidencias y reproducci?n

- `auditoria-bd-2026-10-06.json`: captura agregada, inventario de columnas, conteos y controles.
- `../../scripts/analytics/audit_database.php`: auditor?a CLI reproducible con consultas agregadas y transacci?n MySQL/MariaDB de solo lectura, finalizada con rollback.
- Ejecutar desde la ra?z: `php scripts/analytics/audit_database.php`.
- El script utiliza la conexi?n configurada por Laravel y no imprime credenciales ni registros identificables. Requiere las tablas auditadas; informa la ausencia de la tabla opcional de eventos.
- Verificaciones realizadas: sintaxis PHP v?lida, ejecuci?n completa de consultas, archivo JSON v?lido y conciliaci?n de conteos (17=16+1; 33=17+16; cobertura finalizada 16/16).

## Cierre de la etapa

Etapa 1 completada: inventario, cobertura, calidad, relaciones, viabilidad y reglas de preparaci?n documentadas. La siguiente etapa es preparar el dataset conservando las anomal?as como marcas de calidad y resolver la interpretaci?n de duraci?n; no entrenar todav?a un modelo para presentarlo como confiable.
