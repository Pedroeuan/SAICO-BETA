import 'dart:io';
import 'dart:ui' as ui;
import 'package:flutter_test/flutter_test.dart';

/// Generador reproducible: conserva los PNG originales y crea ic_saico.
/// Ejecutar: flutter test tool/generate_saico_icons.dart
void main() {
  test('Generar recursos Android desde el símbolo oficial', () async {
    final source =
        File('../../public/vendor/adminlte/dist/img/Logos/Logo_AICO.png');
    final codec = await ui.instantiateImageCodec(await source.readAsBytes());
    final image = (await codec.getNextFrame()).image;
    final pixels = await image.toByteData(format: ui.ImageByteFormat.rawRgba);
    final palette = <String, int>{};
    for (var i = 0; i < pixels!.lengthInBytes; i += 4) {
      if (pixels.getUint8(i + 3) < 250) continue;
      final rgb = List.generate(3, (channel) => pixels.getUint8(i + channel));
      if (rgb.every((value) => value > 240)) continue;
      final hex =
          rgb.map((value) => value.toRadixString(16).padLeft(2, '0')).join();
      palette.update(hex, (count) => count + 1, ifAbsent: () => 1);
    }
    final colors = palette.entries.toList()
      ..sort((a, b) => b.value.compareTo(a.value));
    // ignore: avoid_print
    print(
        'Paleta del símbolo oficial: ${colors.take(3).map((e) => '#${e.key}').join(', ')}');
    const root = 'android/app/src/main/res';
    Future<void> render(String path, int size, {bool adaptive = false}) async {
      final recorder = ui.PictureRecorder();
      final canvas = ui.Canvas(recorder);
      if (!adaptive) {
        canvas.drawColor(const ui.Color(0xFFFFFFFF), ui.BlendMode.src);
      }
      // Todo el símbolo queda dentro de la zona segura central de 66/108 dp.
      final width = size * (adaptive ? 0.60 : 0.78);
      final height = width * 990 / 1200;
      canvas.drawImageRect(
          image,
          const ui.Rect.fromLTWH(235, 45, 1200, 990),
          ui.Rect.fromLTWH(
              (size - width) / 2, (size - height) / 2, width, height),
          ui.Paint()..filterQuality = ui.FilterQuality.high);
      final picture = recorder.endRecording();
      final result = await picture.toImage(size, size);
      final bytes = await result.toByteData(format: ui.ImageByteFormat.png);
      final output = File(path);
      await output.parent.create(recursive: true);
      await output.writeAsBytes(bytes!.buffer.asUint8List());
      result.dispose();
      picture.dispose();
    }

    for (final entry in {
      'mdpi': 48,
      'hdpi': 72,
      'xhdpi': 96,
      'xxhdpi': 144,
      'xxxhdpi': 192
    }.entries) {
      await render('$root/mipmap-${entry.key}/ic_saico.png', entry.value);
    }
    await render('$root/drawable-nodpi/saico_foreground.png', 432,
        adaptive: true);
    await render('assets/branding/saico_symbol.png', 192);
    image.dispose();
    codec.dispose();
  });
}
