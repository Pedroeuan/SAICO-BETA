import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:saico_vehiculos_mobile/core/theme/app_theme.dart';
import 'package:saico_vehiculos_mobile/features/analytics/presentation/monthly_chart.dart';
import 'package:saico_vehiculos_mobile/features/auth/application/session_provider.dart';
import 'package:saico_vehiculos_mobile/features/auth/presentation/login_screen.dart';

class _Session extends SessionNotifier {
  @override
  SessionState build() => const SessionState();
}

void main() {
  testWidgets('Barras seleccionables conservan ausencias y meses de API',
      (tester) async {
    await tester.binding.setSurfaceSize(const Size(320, 640));
    addTearDown(() => tester.binding.setSurfaceSize(null));
    await tester.pumpWidget(MaterialApp(
        theme: AppTheme.light(),
        home: Scaffold(
            body: MediaQuery(
                data: const MediaQueryData(
                    textScaler: TextScaler.linear(2), disableAnimations: true),
                child: const SingleChildScrollView(
                    child: MonthlyChart(months: [
                  {'mes': '2026-06', 'salidas': 4, 'finalizadas': 2},
                  {'mes': '2026-08', 'salidas': 3, 'finalizadas': null},
                ]))))));
    await tester.pumpAndSettle();
    await tester.ensureVisible(find.text('2026-08'));
    await tester.tap(find.text('2026-08'));
    await tester.pumpAndSettle();
    expect(find.text('2026-08 · 3 salidas · Sin datos finalizadas'),
        findsOneWidget);
    expect(find.text('2026-07'), findsNothing);
    expect(tester.takeException(), isNull);
  });

  for (final size in [const Size(320, 640), const Size(640, 320)]) {
    testWidgets('Login con teclado, texto ampliado y movimiento reducido $size',
        (tester) async {
      await tester.binding.setSurfaceSize(size);
      addTearDown(() => tester.binding.setSurfaceSize(null));
      await tester.pumpWidget(ProviderScope(
          overrides: [sessionProvider.overrideWith(_Session.new)],
          child: MaterialApp(
              theme: AppTheme.light(),
              builder: (context, child) => MediaQuery(
                  data: MediaQuery.of(context).copyWith(
                      textScaler: const TextScaler.linear(2),
                      viewInsets: const EdgeInsets.only(bottom: 160),
                      disableAnimations: true),
                  child: child!),
              home: const LoginScreen())));
      await tester.pumpAndSettle();
      await tester.scrollUntilVisible(find.text('Ingresar'), 120,
          scrollable: find.byType(Scrollable).first);
      await tester.pumpAndSettle();
      expect(find.byTooltip('Mostrar contraseña'), findsOneWidget);
      expect(tester.takeException(), isNull);
    });
  }
}
