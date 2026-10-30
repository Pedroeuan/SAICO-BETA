# Captura de tiempos operativos

Fecha: 8 de octubre de 2026.

## Implementado

Los checklists web y móvil permiten confirmar la fecha y hora real de salida o regreso. No se precarga una hora para evitar confirmaciones involuntarias. El móvil envía UTC con zona explícita; web indica la zona del servidor (America/Mexico_City). El backend normaliza antes de comparar y almacenar.

La nueva tabla `salidas_tiempos_operativos` conserva por salida y tipo:
- Hora declarada del evento y hora de registro del servidor, independientes.
- Usuario autenticado, canal, entorno y zona horaria.
- Contexto versión 1 observado al capturar el checklist: vehículo, creación de solicitud y salida programada.

El contexto NO es una instantánea tomada al solicitar una predicción. El entorno tampoco certifica que una operación sea real: las pruebas deberán realizarse en un ambiente separado. La diferencia entre las dos horas permite medir retrasos de captura, sin imponer un umbral arbitrario para excluirlos.

Se rechazan fechas futuras y anteriores a la creación de solicitud. El regreso no puede preceder una salida real ya registrada. La solicitud se bloquea dentro de la transacción para impedir dos checklists del mismo tipo por solicitudes concurrentes; la tabla además tiene una restricción única. Si el checklist falla, se revierte también el tiempo registrado.

Para compatibilidad, los clientes anteriores pueden omitir el campo; eso no genera una hora supuesta. Una entrada de una salida antigua puede registrar su regreso aunque falte la salida real, pero esa pareja incompleta no sirve para calcular una duración operativa.

## Base de datos

Se aplicó únicamente la migración `2026_10_08_000001_create_salidas_tiempos_operativos_table.php` en este entorno. Crea una tabla vacía, sin rellenar ni corregir registros históricos. No ejecuta otras migraciones pendientes.

Para otros entornos, desplegar primero la migración y backend y luego la aplicación:

```powershell
php artisan migrate --path=database/migrations/2026_10_08_000001_create_salidas_tiempos_operativos_table.php --force
```

## Alcance pendiente

Los KPIs existentes siguen utilizando las fechas históricas con sus advertencias de calidad. La siguiente integración debe extraer las nuevas parejas operativas, mostrar su cobertura por separado e incorporarlas a datasets versionados sin mezclarlas silenciosamente con los intervalos administrativos.

Faltan la instantánea al momento de solicitar una predicción, evaluación temporal de modelos, comparación contra una referencia, publicación versionada y monitoreo. Esta entrega habilita captura trazable; no activa un modelo ni certifica el sistema completo para producción.

## Verificación

- Backend: 13 pruebas y 79 aserciones, sobre SQLite en memoria; no usan la base operativa.
- Verifica conversión UTC, fechas inválidas, clientes anteriores, duplicados y reversión transaccional, además de permisos y KPIs existentes.
- No se crean salidas de demostración ni se finalizan operaciones del usuario para probar.

Resultados adicionales: el analizador de Flutter terminó sin incidencias. Pasaron las 25 pruebas generales de la aplicación y las 2 nuevas pruebas de selección explícita de fecha/hora (salida y regreso). El endpoint local responde 401 sin credenciales, confirmando que el backend está accesible y protegido.

## Instalación USB

APK debug compilado con backend real por USB (`127.0.0.1:8000`, sin datos simulados). El primer intento perdió conexión USB y se repitió. Android confirmó `lastUpdateTime=2026-10-08 20:57:18` para el paquete; el arranque de la actividad devolvió `Status: ok`. Se restableció `adb reverse tcp:8000 tcp:8000`. El segundo intento de ADB termino con `Success` y codigo de salida 0. No se realizó una captura operativa completa en el celular: requiere datos y evidencias reales del usuario. Esta compilación de desarrollo por USB no constituye distribución de producción.
