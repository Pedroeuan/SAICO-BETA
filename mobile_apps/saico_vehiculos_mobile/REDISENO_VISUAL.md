# Rediseño visual SAICO

## Identidad y sistema de diseño

Se revisaron `config/adminlte.php`, `LOGO-2.png`, `Logo_AICO.png` y los
recursos de `assets/branding`. El símbolo oficial se encuentra en
`public/vendor/adminlte/dist/img/Logos/Logo_AICO.png`.

El generador identifica como colores dominantes **#013976** (azul) y
**#C5161D** (rojo). Se usan en `app_colors.dart`; los tonos de fondo y los
colores semánticos se adaptan para contraste. Tipografía, espaciado,
radios, tema de controles y transiciones se centralizan en `lib/core/theme`.

## Pantallas

- **Acceso:** símbolo, título breve, formulario compacto, iconos de campos,
  acción con icono y descripciones accesibles para mostrar/ocultar contraseña.
- **Inicio:** saludo con nombre, Solicitar / Salida y entrada / Historial,
  acceso visible a Analítica y pestañas ajustadas al tamaño del texto.
- **Solicitudes:** se eliminan párrafos redundantes; se conservan las
  advertencias de documentación, licencia, permisos y las validaciones.
- **Checklists:** encabezado compacto con información bajo demanda,
  fotografía del vehículo más compacta, eliminación de títulos duplicados,
  controles de evidencia accesibles y acciones con icono y texto.
- **Detalles e historial:** tarjetas del mismo tema, títulos cortos,
  metadatos legibles y distribución adaptable.
- **Analítica:** resumen adaptable, coberturas con escala 0–100 y consulta
  al tocar, barras mensuales agrupadas seleccionables, leyendas, escala
  numérica, filtros en hoja desplazable y definiciones bajo demanda.
  Se mantienen separados tiempo real declarado, intervalo administrativo,
  cobertura de horas reales y cobertura de checklists.

Los gráficos usan widgets de Flutter: `Material`, `InkWell`, `Semantics`,
`AnimatedContainer` y `LinearProgressIndicator`. Esto permite selección,
accesibilidad y adaptación al texto sin incorporar dependencias ni cambiar
la compatibilidad del proyecto. Solo se grafican los conteos y coberturas
de la API; no se rellenan meses, ni se estiman valores. Los valores ausentes
se presentan como «Sin datos».

Las transiciones de página, pestañas y barras respetan
`MediaQuery.disableAnimations`. Los formularios y estados vacíos son
desplazables para facilitar su uso con teclado y en horizontal.

## Icono Android

Se recorta el símbolo del recurso oficial, sin letras ni registro de marca
pequeño. Se generan nuevos recursos `ic_saico` para las cinco densidades y
un icono adaptativo con fondo blanco. El símbolo ocupa el 60 % del lienzo
adaptativo de 108 dp, dentro de su zona segura central. Los recursos
`ic_launcher` y los logos originales se conservan.

Generación reproducible desde la raíz de esta aplicación:

```powershell
flutter test tool/generate_saico_icons.dart
```

## Verificación y APK de revisión

- Análisis estático: `flutter analyze`.
- Suite: `flutter test` (31 pruebas, incluidas selección de barras, ausencia
  de meses/valores, filtros, validación de hora real y adaptación visual).
- Tamaños probados para componentes/checklists: 320×640, 393×800,
  800×393 y 1200×800; texto normal y 1.6×.
- Acceso probado con teclado simulado, texto 2× y movimiento reducido en
  320×640 y 640×320; gráfico probado en 320 px con texto 2×.
- Revisión del PNG de instalación generado y compilación Android debug.

Comando de compilación, con la configuración de conexión existente:

```powershell
flutter build apk --debug --no-pub --dart-define=API_BASE_URL=http://192.168.0.16:8000/api/mobile/v1 --dart-define=USE_MOCK_DATA=false
```

APK: `build/app/outputs/flutter-apk/app-debug.apk`.

Pendiente: revisión en un teléfono físico, máscaras de distintos launchers,
TalkBack y flujo integral contra Laravel (autenticación, cámara, subida de
evidencias y consultas con datos del servidor). Las pruebas de widgets
usan fixtures; no equivalen a esas verificaciones de dispositivo y red.

Esta intervención se limita a la presentación de Flutter y los recursos de
marca/instalación. Había cambios previos en backend, rutas, repositorios y
configuración; se han conservado.
