import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:go_router/go_router.dart';
import 'package:saico_vehiculos_mobile/core/theme/app_theme.dart';
import 'package:saico_vehiculos_mobile/features/auth/application/session_provider.dart';
import 'package:saico_vehiculos_mobile/features/vehicle_requests/application/vehicle_operations_controller.dart';
import 'package:saico_vehiculos_mobile/features/vehicle_requests/domain/vehicle_exit_models.dart';
import 'package:saico_vehiculos_mobile/features/vehicle_requests/presentation/vehicle_operations_home_screen.dart';

class _Session extends SessionNotifier {
  @override
  SessionState build() => const SessionState();
}

class _Operations extends VehicleOperationsController {
  _Operations(this.seed);
  final VehicleOperationsState seed;
  @override
  Future<VehicleOperationsState> build() async => seed;
}

void main() {
  for (final active in [false, true]) {
    for (final scale in [1.0, 1.6]) {
      testWidgets('Home tabs separate content, active=$active scale=$scale',
          (tester) async {
        await tester.binding.setSurfaceSize(const Size(320, 700));
        addTearDown(() => tester.binding.setSurfaceSize(null));
        final seed = VehicleOperationsState(
            exits: active
                ? const [
                    VehicleExitSummary(
                      id: 1,
                      driverId: 1,
                      requestedById: 1,
                      folio: 'SAL-001',
                      status: 'activo',
                      vehicleLabel: 'ABC-123',
                      driverName: 'Operador',
                      requestedByName: 'Solicitante',
                    )
                  ]
                : []);
        final router = GoRouter(routes: [
          GoRoute(
              path: '/',
              builder: (_, __) => const VehicleOperationsHomeScreen()),
          GoRoute(
              path: '/request',
              builder: (_, __) =>
                  const Scaffold(body: Text('Formulario de solicitud'))),
        ]);
        addTearDown(router.dispose);
        await tester.pumpWidget(ProviderScope(
            overrides: [
              sessionProvider.overrideWith(_Session.new),
              vehicleOperationsControllerProvider
                  .overrideWith(() => _Operations(seed)),
            ],
            child: MaterialApp.router(
              routerConfig: router,
              theme: AppTheme.light(),
              builder: (context, child) => MediaQuery(
                  data: MediaQuery.of(context)
                      .copyWith(textScaler: TextScaler.linear(scale)),
                  child: child!),
            )));
        await tester.pumpAndSettle();
        expect(find.text('SAICO'), findsOneWidget);
        expect(find.textContaining('Administra las salidas'), findsNothing);
        expect(find.textContaining('Modulo gestion'), findsNothing);
        expect(tester.takeException(), isNull);
        if (active) {
          await tester.ensureVisible(find.text('Ir a salida y entrada'));
          await tester.pumpAndSettle();
          await tester.tap(find.text('Ir a salida y entrada'));
        } else {
          await tester.tap(find.text('Salida y\nentrada'));
        }
        await tester.pumpAndSettle();
        expect(
            find.text(
                active ? 'Registrar salida' : 'No tienes una salida activa.'),
            findsOneWidget);
        if (active) {
          final button = tester.widget<ElevatedButton>(find.ancestor(
              of: find.text('Registrar entrada'),
              matching: find.byType(ElevatedButton)));
          expect(button.onPressed, isNull);
        }
        expect(tester.takeException(), isNull);
        await tester.tap(find.text('Historial'));
        await tester.pumpAndSettle();
        expect(
            find.text(active
                ? 'SAL-001 · Operador'
                : 'Aún no hay salidas registradas.'),
            findsOneWidget);
        expect(tester.takeException(), isNull);
        await tester.tap(find.text('Solicitar'));
        await tester.pumpAndSettle();
        if (!active) {
          await tester.ensureVisible(find.text('Nueva solicitud'));
          await tester.pumpAndSettle();
          await tester.tap(find.text('Nueva solicitud'));
          await tester.pumpAndSettle();
          expect(find.text('Formulario de solicitud'), findsOneWidget);
        }
      });
    }
  }
}
