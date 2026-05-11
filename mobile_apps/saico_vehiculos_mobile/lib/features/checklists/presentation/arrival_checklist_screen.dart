import 'dart:io';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../../core/layout/adaptive_page_scaffold.dart';
import '../../../core/network/network_error_formatter.dart';
import '../../../core/widgets/empty_state_widget.dart';
import '../application/checklist_controller.dart';
import '../data/checklist_models.dart';
import '../../vehicle_requests/data/vehicle_request_repository.dart';
import '../../vehicle_requests/application/vehicle_operations_controller.dart';
import 'checklist_form_shell.dart';

class ArrivalChecklistScreen extends ConsumerWidget {
  const ArrivalChecklistScreen({
    required this.exitId,
    super.key,
  });

  final String exitId;

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final detailState = ref.watch(exitDetailProvider(exitId));

    return AdaptivePageScaffold(
      title: 'Checklist de entrada',
      child: detailState.when(
        data: (detail) {
          final exit = detail.exit;
          if (exit.vehicle == null) {
            return const EmptyStateWidget(
              title: 'Salida no encontrada',
              message: 'No fue posible cargar la salida seleccionada.',
            );
          }

          return ChecklistFormShell(
            exitId: exitId,
            mode: ChecklistMode.arrival,
            title: 'Checklist de entrada #$exitId',
            vehicleLabel: exit.vehicle!.displayLabel,
            vehiclePhotoUrl: exit.vehicle!.photoUrl,
            driverName: exit.driverName,
            requestedByName: exit.requestedByName,
            departureAtLabel: exit.departureAtLabel,
            currentMileage: exit.vehicle!.currentMileage ?? 0,
            referenceMileage: exit.departureMileage,
            initialPayload: _buildInitialPayload(
              exitId: exitId,
              mileage: exit.vehicle!.currentMileage ?? 0,
            ),
            onSubmit: (payload) async {
              await ref.read(checklistControllerProvider.notifier).submitArrival(payload);

              if (!context.mounted) {
                return;
              }

              if (!ref.read(checklistControllerProvider).hasError) {
                ref.invalidate(vehicleOperationsControllerProvider);
                HapticFeedback.heavyImpact();
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Checklist de entrada guardado correctamente.')),
                );
                context.go('/home');
              } else {
                final error = ref.read(checklistControllerProvider).error;
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(content: Text(formatAppError(error ?? Exception('No fue posible guardar el checklist.')))),
                );
              }
            },
          );
        },
        error: (error, _) => Center(child: Text(formatAppError(error))),
        loading: () => const Center(child: CircularProgressIndicator()),
      ),
    );
  }

  ChecklistPayload _buildInitialPayload({
    required String exitId,
    required int mileage,
  }) {
    return ChecklistPayload(
      exitId: exitId,
      fuelLevel: '1/2',
      mileage: mileage,
      cleanedExterior: false,
      cleanedInterior: false,
      windshieldWasherFluid: 'suficiente',
      oilLevel: 'suficiente',
      coolantLevel: 'suficiente',
      brakeFluidLevel: 'suficiente',
      tireCondition: 'buen_estado',
      frontLeftTirePressure: 'normal',
      frontRightTirePressure: 'normal',
      rearLeftTirePressure: 'normal',
      rearRightTirePressure: 'normal',
      observations: '',
      photos: <File>[],
    );
  }
}
