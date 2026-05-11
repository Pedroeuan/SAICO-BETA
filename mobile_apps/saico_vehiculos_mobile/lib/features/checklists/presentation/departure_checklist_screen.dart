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

class DepartureChecklistScreen extends ConsumerWidget {
  const DepartureChecklistScreen({
    required this.exitId,
    super.key,
  });

  final String exitId;

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final detailState = ref.watch(exitDetailProvider(exitId));

    return AdaptivePageScaffold(
      title: 'Checklist de salida',
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
            mode: ChecklistMode.departure,
            title: 'Checklist de salida #$exitId',
            vehicleLabel: exit.vehicle!.displayLabel,
            vehiclePhotoUrl: exit.vehicle!.photoUrl,
            driverName: exit.driverName,
            requestedByName: exit.requestedByName,
            departureAtLabel: exit.departureAtLabel,
            currentMileage: exit.vehicle!.currentMileage ?? 0,
            initialPayload: _buildInitialPayload(
              exitId: exitId,
              mileage: exit.vehicle!.currentMileage ?? 0,
              defaults: detail.departureDefaults,
            ),
            licenseValid: _isDateValid(exit.driverLicenseExpiration),
            circulationCardValid: exit.vehicle!.isCirculationCardValidAt(DateTime.now()),
            insurancePolicyValid: exit.vehicle!.isInsuranceValidAt(DateTime.now()),
            onSubmit: (payload) async {
              await ref.read(checklistControllerProvider.notifier).submitDeparture(payload);

              if (!context.mounted) {
                return;
              }

              if (!ref.read(checklistControllerProvider).hasError) {
                ref.invalidate(vehicleOperationsControllerProvider);
                HapticFeedback.heavyImpact();
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Checklist de salida guardado correctamente.')),
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

  bool _isDateValid(DateTime? value) {
    if (value == null) {
      return false;
    }

    final today = DateTime.now();
    return !value.isBefore(DateTime(today.year, today.month, today.day));
  }

  ChecklistPayload _buildInitialPayload({
    required String exitId,
    required int mileage,
    required Map<String, dynamic> defaults,
  }) {
    return ChecklistPayload(
      exitId: exitId,
      fuelLevel: defaults['nivel_gasolina'] as String? ?? '1/2',
      mileage: _asInt(defaults['kilometraje']) ?? mileage,
      cleanedExterior: false,
      cleanedInterior: false,
      windshieldWasherFluid:
          defaults['liquido_limpiaparabrisas'] as String? ?? 'suficiente',
      oilLevel: defaults['aceite'] as String? ?? 'suficiente',
      coolantLevel: defaults['anticongelante'] as String? ?? 'suficiente',
      brakeFluidLevel: defaults['liquido_frenos'] as String? ?? 'suficiente',
      tireCondition: defaults['estado_llantas'] as String? ?? 'buen_estado',
      frontLeftTirePressure:
          defaults['llanta_delantera_izq_calibracion'] as String? ?? 'normal',
      frontRightTirePressure:
          defaults['llanta_delantera_der_calibracion'] as String? ?? 'normal',
      rearLeftTirePressure:
          defaults['llanta_trasera_izq_calibracion'] as String? ?? 'normal',
      rearRightTirePressure:
          defaults['llanta_trasera_der_calibracion'] as String? ?? 'normal',
      observations: '',
      photos: <File>[],
    );
  }

  int? _asInt(dynamic value) {
    if (value == null) {
      return null;
    }

    if (value is int) {
      return value;
    }

    if (value is num) {
      return value.toInt();
    }

    if (value is String) {
      final normalized = value.trim();
      if (normalized.isEmpty) {
        return null;
      }

      return int.tryParse(normalized) ?? double.tryParse(normalized)?.toInt();
    }

    return null;
  }
}
