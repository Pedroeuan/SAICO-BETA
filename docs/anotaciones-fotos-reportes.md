# Editor compartido de fotos en reportes

PINS 03_02 sirve como referencia para incorporar flechas y texto en otros formatos.
Se reutiliza `public/js/Reportes_Anotaciones_Imagenes_06_B_01.js`; no se duplica el editor.

## Integración en Crear y Editar

1. Agregar al formulario `data-anotaciones-fotos` y `data-modo="create"` o `data-modo="edit"`.
2. Conservar el script de carga/recorte del formato y Cropper.js. Después de ellos, cargar:

```blade
<script src="{{ asset('js/Reportes_Anotaciones_Imagenes_06_B_01.js') }}?v={{ filemtime(public_path('js/Reportes_Anotaciones_Imagenes_06_B_01.js')) }}"></script>
```

3. El modal existente debe tener los IDs `cropperModal`, `cropperImage`, `cropImageBtn`,
   `saveWithoutCropBtn`, `rotateLeftBtn` y `rotateRightBtn`, con `.img-container` y `.modal-footer`.
4. Cada campo de foto usa `.image-input`. Su grupo incluye `.image-preview` y un campo oculto
   `images_base64[indice]`. El script de recorte debe crear Cropper sobre `cropperImage`.
5. En Editar, cada foto guardada se coloca en `image-container-indice`, con la vista previa,
   su input de reemplazo y el campo Base64 del mismo índice. El editor agrega el botón
   «Marcar imagen guardada» y descarga la foto original para trabajar a resolución natural.

## Guardado en el controlador

El editor entrega un PNG aplanado en `images_base64[indice]`. El PDF del reporte consume
esa imagen guardada: no necesita dibujar las flechas por separado.

Si se reciben simultáneamente `replace_images[indice]` e `images_base64[indice]`, dar prioridad
al Base64 confirmado; de otro modo se guarda el original sin las marcas. En PINS 03_02:

```php
if ($request->hasFile("replace_images.$index") && empty($imagesBase64[$index])) {
    // Guardar el archivo de reemplazo sin edición.
} elseif (!empty($imagesBase64[$index])) {
    // Guardar el PNG editado.
} else {
    // Conservar la foto existente.
}
```

## Comprobación manual

- Crear: cargar una foto, elegir «Anotar imagen completa», trazar una flecha y guardar.
- Editar: abrir «Marcar imagen guardada», añadir una flecha o texto y guardar la imagen.
- Reemplazar: seleccionar otro archivo y anotarlo; comprobar que se guarda con las marcas.
- Guardar el reporte, volver a editarlo y generar su PDF: las marcas deben permanecer.
- Las anotaciones quedan integradas en los píxeles del PNG. Tras guardar y recargar, no se
  pueden deshacer individualmente las marcas anteriores; para retirarlas se necesita el original.
