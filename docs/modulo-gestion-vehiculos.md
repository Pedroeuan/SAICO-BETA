# Guia Tecnica - Modulo Gestion de Vehiculos

## 1. Arquitectura del modulo

El modulo sigue el flujo estandar de Laravel:

1. Ruta (`routes/web.php`)
2. Controlador (`app/Http/Controllers/Vehiculos/*`)
3. Modelo (`app/Models/Vehiculos/*`)
4. Vista Blade (`resources/views/vehiculos/*`, `resources/views/salidas/*`)
5. Exportaciones PDF/Excel

## 2. Controladores involucrados

- `VehiculoController`
  - CRUD de vehiculos
- `PanelController`
  - KPIs y graficas del panel
- `SalidaVehiculoController`
  - Registro y finalizacion de salidas
- `SalidaChecklistController`
  - Checklist salida/entrada, vista detalle, PDF checklist
- `RendimientoExportController`
  - Exportacion de rendimiento PDF/Excel

## 3. Modelos

- `App\Models\Vehiculos\Vehiculo`
- `App\Models\Vehiculos\SalidaVehiculo`
- `App\Models\Vehiculos\Checklist\SalidaChecklist`
- Relaciones de apoyo con `User`

## 4. Rutas del modulo

### Vehiculos
- `/vehiculos`
- `/vehiculos/index`
- `/vehiculos/create`
- `/vehiculos/store`
- `/vehiculos/edit/{id}`
- `/vehiculos/update/{id}`
- `/vehiculos/delete/{id}`

### Panel
- `/salidas-vehiculos/panel`
- `/salidas-vehiculos/rendimiento/pdf/{periodo}`
- `/salidas-vehiculos/rendimiento/excel/{periodo}`

### Salidas
- `/salidas-vehiculos`
- `/salidas-vehiculos/index`
- `/salidas-vehiculos/create`
- `/salidas-vehiculos/store`
- `/salidas-vehiculos/finalizar/{id}`

### Checklist
- `/salidas-vehiculos/checklist-salida/{salida}` (GET|POST)
- `/salidas-vehiculos/checklist-entrada/{salida}` (GET|POST)
- `/salidas-vehiculos/checklist/{salida}/{tipo}` (GET)
- `/salidas-vehiculos/checklist-pdf/{salida}` (GET)

## 5. Vistas principales

- `resources/views/vehiculos/index.blade.php`
- `resources/views/vehiculos/create.blade.php`
- `resources/views/vehiculos/edit.blade.php`
- `resources/views/vehiculos/panel/index.blade.php`
- `resources/views/vehiculos/reportes/rendimiento_pdf.blade.php`
- `resources/views/salidas/index.blade.php`
- `resources/views/salidas/create.blade.php`
- `resources/views/salidas/checklist/salida.blade.php`
- `resources/views/salidas/checklist/entrada.blade.php`
- `resources/views/salidas/checklist/show.blade.php`
- `resources/views/salidas/checklist/pdf_unificado.blade.php`

## 6. Exportaciones

### Rendimiento PDF
- Controller: `RendimientoExportController@pdf`
- Vista: `vehiculos.reportes.rendimiento_pdf`
- Soporta periodos: `semana`, `mes`, `mes_pasado`, `anio`
- Soporta filtro opcional por mes: `?mes=YYYY-MM`

### Rendimiento Excel
- Controller: `RendimientoExportController@excel`
- Export class: `VehiculosRendimientoExport`
- Incluye columnas generales de vehiculo y metricas

### Checklist PDF
- Controller: `SalidaChecklistController@pdf`
- Vista: `salidas.checklist.pdf_unificado`
- Formato carta portrait

## 7. Permisos y seguridad

Gate de administracion del modulo:
- `vehiculos-admin-access`

Definicion:
- `app/Providers/AuthServiceProvider.php`

## 8. Convenciones de comentarios recomendadas

Para mantener consistencia:

- En rutas:
  - Comentario de seccion (`/*VEHICULOS*/`)
  - Comentario por ruta (`/*Ruta de Vista de ...*/`)
- En controladores:
  - Comentar solo logica de negocio no obvia (validaciones clave, restricciones)
- En vistas:
  - Comentar bloques UI grandes (tabs, cards, tablas de acciones)

## 9. Como rastrear una funcionalidad

Ejemplo: boton PDF checklist en listado de salidas

1. Vista:
   - `resources/views/salidas/index.blade.php`
   - `route('salidas.checklist.pdf', $salida->id)`
2. Ruta:
   - `routes/web.php`
   - `name('salidas.checklist.pdf')`
3. Controller:
   - `SalidaChecklistController@pdf`
4. Vista PDF:
   - `resources/views/salidas/checklist/pdf_unificado.blade.php`

