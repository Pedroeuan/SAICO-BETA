# Manual de Usuario - Gestion de Vehiculos

## Objetivo

Este manual explica como usar el modulo de Gestion de Vehiculos:

- Registro y edicion de vehiculos
- Registro de salidas
- Registro de checklist de salida y entrada
- Consulta de panel
- Descarga de PDF y Excel

## Acceso

1. Inicia sesion en SAICO-BETA.
2. Ingresa al menu del modulo de Vehiculos.
3. Si tu rol no tiene permisos, no se mostraran ciertas opciones (por ejemplo administracion de vehiculos).

## 1) Gestion de Vehiculos

### Alta de vehiculo

1. Ir a `Vehiculos` -> `Nuevo Vehiculo`.
2. Capturar:
   - Placa
   - Marca
   - Modelo
   - Año
   - Estatus
   - Kilometraje
   - Documentacion (poliza/tarjeta y vencimientos)
3. Guardar.

### Edicion de vehiculo

1. En el listado, presiona `Editar`.
2. Actualiza los datos requeridos.
3. Guarda cambios.

### Baja de vehiculo

1. En el listado, presiona `Eliminar`.
2. Confirma la accion.

## 2) Salidas de Vehiculos

### Registrar salida

1. Ir a `Salidas de Vehiculos`.
2. Presionar `+ Nueva salida`.
3. Seleccionar vehiculo y chofer.
4. Guardar salida.

### Finalizar salida

1. Ubica la salida activa.
2. Presiona `Finalizar` (si aplica desde tu flujo de pantalla).

## 3) Checklist de Salida y Entrada

### Checklist de salida

1. En la salida registrada, presiona `Registrar` en checklist salida.
2. Captura:
   - Nivel de gasolina
   - Kilometraje
   - Limpieza exterior/interior
   - Observaciones
   - Herramientas
   - Evidencias fotograficas
3. Guardar.

### Checklist de entrada

1. Al finalizar el uso, presiona `Registrar` en checklist entrada.
2. Captura condiciones y evidencias.
3. Guardar.

### Ver checklist

- `Ver salida` muestra el checklist de salida.
- `Ver entrada` muestra el checklist de entrada.
- La vista esta organizada por pestañas (estado, herramientas, documentos, evidencias).

### Descargar PDF checklist

- Si la salida tiene checklist de salida y entrada, aparece boton `PDF`.
- El PDF unificado muestra resumen, documentos, herramientas y evidencias.

## 4) Panel Vehicular

En `Panel Vehicular` puedes:

- Consultar indicadores generales
- Ver graficas
- Exportar rendimiento:
  - PDF semana/mes/mes pasado/año
  - Excel semana/mes/mes pasado/año
- Filtrar por mes especifico (`YYYY-MM`) para exportaciones

## 5) Errores comunes

### No aparece una opcion

- Posible falta de permisos de rol.

### No se genera PDF de checklist

- Verifica que existan checklist de salida y entrada para esa salida.
- Verifica que la ruta de storage este enlazada (`php artisan storage:link`).

### No aparece logo en PDF

- Verifica existencia del archivo logo en `public/images/Logo_AICO_R.jpg`.

