# KPIs y dataset de tiempos operativos

Fecha: 8 de octubre de 2026.

## Resultado

La API y la pantalla Analítica agregan dos indicadores:

- **Tiempo real declarado:** promedio entre salida y regreso operativos válidos, mostrado en horas. Las horas las declara el usuario; no son mediciones GPS.
- **Cobertura de horas reales:** salidas finalizadas con duración operativa válida / todas las finalizadas del alcance seleccionado.

Se conserva el intervalo administrativo anterior como indicador independiente. Una pareja ausente no se sustituye con fechas del checklist ni aporta cero al promedio. Sin parejas válidas, el promedio es null / «Sin datos»; con finalizadas pero ninguna pareja válida, la cobertura es 0 %. Sin finalizadas, la cobertura es null.

Los filtros siguen usando la fecha de salida programada y el vehículo. Se respeta el mismo alcance de acceso por conductor, solicitante o administrador. Las nuevas consultas se limitan a los identificadores previamente autorizados y no multiplican filas con joins.

## Reglas compartidas

`app/Services/Analytics/OperationalDataset.php`, versión 1.0.0, se usa tanto en API como en exportación:

- Un evento de cada tipo y un checklist de cada tipo, sin duplicados.
- Operación finalizada y vehículo existente.
- Fechas y zonas horarias válidas; diferencias calculadas en instantes absolutos.
- Evento posterior o igual a creación de solicitud, registrado después o al mismo instante del evento, sin captura futura al corte.
- Capturas en orden; regreso estrictamente posterior a salida.
- Cierre administrativo válido, no futuro ni anterior a la captura de regreso.
- Duraciones mayores de 24 horas se incluyen, se cuentan y se advierten para revisión.

La etiqueta se considera disponible como mínimo desde el cierre administrativo. Al entrenar debe respetarse además cuándo se incorporó la fuente al dataset, evitando usar información posterior al corte. Los retrasos de captura se conservan como auditoría.

## Dataset reproducible

`php scripts/analytics/prepare_dataset.php` sigue consultando la BD mediante transacción de solo lectura. Agrega a cada exportación:

- `fuente_minima.json`: eventos operativos seleccionados, incluidos canal, entorno y zona; no incluye nombres, usuarios de captura ni evidencias fotográficas.
- `duraciones_operativas.json`: una fila por salida, duración válida o null, exclusiones, retrasos y diccionario. Las filas mantienen `aprobado_entrenamiento=0`.
- `manifest.json`: versión operativa, conteos, objetivo separado y huellas SHA-256 de los archivos.

No mezcla estas etiquetas con `candidatos_duracion.csv`, que conserva los candidatos históricos exploratorios. No define todavía entradas operativas aprobadas para entrenamiento; el regreso y la duración son resultados posteriores y no deben utilizarse como entradas.

Exportación final de esta entrega: `storage/app/analytics/datasets/20261008_210438_0664af`. Tiene 17 salidas, 16 finalizadas y 0 eventos operativos nuevos. Se verificaron las huellas de los 6 archivos y las 17 filas sin inventar etiquetas. Las extracciones previas se conservan.

## Verificación

- 12 pruebas de API y reglas de dataset: 102 aserciones, incluida separación de promedios, permisos, filtros, duplicados, nulos, cronología y zonas horarias.
- Las 6 pruebas de captura operativa también pasaron durante esta entrega, antes de incorporar la regla de extracción.
- 6 pruebas Flutter de analítica: indicadores separados, filtros, estados vacíos, errores, pantallas estrechas y texto ampliado.
- Se eliminó un import sin uso detectado por el analizador en el catálogo de vehículos.

## Próxima fase

La captura alimenta estos KPIs automáticamente. Para regenerar el dataset se ejecuta el comando indicado; no se configuró una tarea programada. Falta crear instantáneas de entradas al solicitar una predicción, verificar procedencia y suficiencia de operaciones reales, evaluar modelos con separación temporal y publicarlos con monitoreo. Esta entrega no habilita predicciones ni convierte los datos históricos en operaciones verificadas.

## Cierre de verificación e instalación

El analizador Flutter terminó sin incidencias. La exportación reprodujo exactamente las 17 filas al recalcularlas desde la fuente conservada. El APK debug se compiló con backend real por USB; ADB confirmó `Success` en la instalación y `Status: ok` al abrirlo. Se restableció el puerto 8000 por USB; la API respondió 401 sin autenticación. No se ingresaron credenciales ni se generaron operaciones reales para probar la pantalla del dispositivo.
