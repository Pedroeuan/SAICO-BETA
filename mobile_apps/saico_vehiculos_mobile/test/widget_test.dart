import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:saico_vehiculos_mobile/core/layout/adaptive_page_scaffold.dart';
import 'package:saico_vehiculos_mobile/core/theme/app_theme.dart';
import 'package:saico_vehiculos_mobile/core/widgets/app_button.dart';
import 'package:saico_vehiculos_mobile/core/widgets/saico_brand_header.dart';
import 'package:saico_vehiculos_mobile/core/widgets/vehicle_card.dart';
import 'package:saico_vehiculos_mobile/core/widgets/status_badge.dart';
import 'package:saico_vehiculos_mobile/features/checklists/presentation/checklist_form_shell.dart';
import 'package:saico_vehiculos_mobile/features/vehicle_requests/domain/vehicle_exit_models.dart';

void main() {
  for (final size in [
    const Size(320, 640),
    const Size(393, 800),
    const Size(800, 393),
    const Size(1200, 800)
  ]) {
    for (final scale in [1.0, 1.6]) {
      Future<void> mount(WidgetTester tester, Widget child) async {
        await tester.binding.setSurfaceSize(size);
        addTearDown(() => tester.binding.setSurfaceSize(null));
        await tester.pumpWidget(MaterialApp(
          theme: AppTheme.light(),
          builder: (context, child) => MediaQuery(
              data: MediaQuery.of(context)
                  .copyWith(textScaler: TextScaler.linear(scale)),
              child: child!),
          home: AdaptivePageScaffold(title: 'SAICO', child: child),
        ));
        await tester.pumpAndSettle();
      }

      testWidgets('Cards and actions fit $size at $scale', (tester) async {
        await mount(
            tester,
            SingleChildScrollView(
                child: Column(children: [
              const SaicoBrandHeader(
                  title: 'Gestion movil de vehiculos',
                  subtitle:
                      'Solicitudes, checklist de salida y checklist de entrada con el mismo flujo operativo del sistema SAICO.',
                  height: 210),
              const SizedBox(height: 16),
              const VehicleCard(
                  vehicle: CatalogVehicle(
                      id: 1,
                      plate: 'ABC-123-XYZ',
                      brand: 'Volkswagen',
                      model: 'Transporter de pasajeros',
                      currentMileage: 123456),
                  trailing: StatusBadge(label: 'activo'),
                  subtitle: 'Hora de salida: 05/10/2026 21:30'),
              AppButton(
                  label: 'Registrar checklist de entrada',
                  icon: Icons.check,
                  onPressed: () {}),
            ])));
        expect(tester.takeException(), isNull);
        await tester.ensureVisible(find.text('Registrar checklist de entrada'));
        await tester.pumpAndSettle();
        expect(tester.takeException(), isNull);
      });
      testWidgets('Checklist fits and scrolls $size at $scale', (tester) async {
        await mount(
            tester,
            ChecklistFormShell(
                exitId: '1',
                mode: ChecklistMode.departure,
                title: 'Checklist de salida',
                vehicleLabel: 'Volkswagen Transporter ABC-123',
                driverName: 'Operador con nombre completo',
                requestedByName: 'Solicitante con nombre completo',
                currentMileage: 123456,
                onSubmit: (_) async {}));
        expect(tester.takeException(), isNull);
        await tester.scrollUntilVisible(find.text('Siguiente'), 250,
            scrollable: find.byType(Scrollable).first, maxScrolls: 30);
        await tester.pumpAndSettle();
        await tester.tap(find.text('Siguiente'));
        await tester.pumpAndSettle();
        expect(tester.takeException(), isNull);
        await tester.scrollUntilVisible(find.text('Siguiente'), 200,
            scrollable: find.byType(Scrollable).first, maxScrolls: 30);
        await tester.pumpAndSettle();
        await tester.tap(find.text('Siguiente'));
        await tester.pumpAndSettle();
        expect(find.text('Licencia de conducir'), findsOneWidget);
        expect(tester.takeException(), isNull);
      });
    }
  }
}
