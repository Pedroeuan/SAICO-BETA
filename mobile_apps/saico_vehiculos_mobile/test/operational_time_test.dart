import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:saico_vehiculos_mobile/features/checklists/presentation/checklist_form_shell.dart';

void main() {
  for (final mode in ChecklistMode.values) {
    testWidgets('Requires explicit operational time for $mode', (tester) async {
      await tester.pumpWidget(MaterialApp(
          home: Scaffold(
              body: ChecklistFormShell(
        exitId: '1',
        mode: mode,
        title: 'Checklist',
        vehicleLabel: 'Unidad',
        driverName: 'Conductor',
        requestedByName: 'Solicitante',
        currentMileage: 100,
        onSubmit: (_) async {},
      ))));
      final button = find.text(mode == ChecklistMode.arrival
          ? 'Hora real de regreso'
          : 'Hora real de salida');
      await tester.scrollUntilVisible(button, 250,
          scrollable: find.byType(Scrollable).first);
      await tester.pumpAndSettle();
      final form = tester.state<FormState>(find.byType(Form));
      expect(form.validate(), isFalse);
      await tester.pumpAndSettle();
      expect(find.text('Selecciona la fecha y hora real.'), findsOneWidget);
      await tester.tap(button);
      await tester.pumpAndSettle();
      await tester.tap(find.text('OK'));
      await tester.pumpAndSettle();
      await tester.tap(find.text('OK'));
      await tester.pumpAndSettle();
      expect(
          find.textContaining(
              mode == ChecklistMode.arrival ? 'Regreso:' : 'Salida:'),
          findsOneWidget);
      expect(tester.takeException(), isNull);
    });
  }
}
