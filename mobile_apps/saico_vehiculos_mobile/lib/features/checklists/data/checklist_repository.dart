import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:dio/dio.dart';

import '../../../core/config/app_config.dart';
import '../../../core/constants/api_endpoints.dart';
import '../../../core/network/api_client.dart';
import '../../vehicle_requests/data/mock_mobile_store.dart';
import 'checklist_models.dart';

final checklistRepositoryProvider = Provider<ChecklistRepository>((ref) {
  return ChecklistRepository(ref.watch(dioProvider));
});

class ChecklistRepository {
  const ChecklistRepository(this._dio);

  final Dio _dio;

  Future<void> submitDepartureChecklist(ChecklistPayload payload) async {
    if (AppConfig.useMockData) {
      MockMobileStore.instance.completeDepartureChecklist(
        payload: payload,
      );
      await Future<void>.delayed(const Duration(milliseconds: 900));
      return;
    }

    await _dio.post<void>(
      ApiEndpoints.departureChecklist(payload.exitId),
      data: _buildChecklistFormData(payload, includeTools: true),
    );
  }

  Future<void> submitArrivalChecklist(ChecklistPayload payload) async {
    if (AppConfig.useMockData) {
      MockMobileStore.instance.completeArrivalChecklist(
        payload: payload,
      );
      await Future<void>.delayed(const Duration(milliseconds: 900));
      return;
    }

    await _dio.post<void>(
      ApiEndpoints.arrivalChecklist(payload.exitId),
      data: _buildChecklistFormData(payload, includeTools: false),
    );
  }

  FormData _buildChecklistFormData(
    ChecklistPayload payload, {
    required bool includeTools,
  }) {
    final formData = FormData.fromMap(<String, dynamic>{
      'nivel_gasolina': payload.fuelLevel,
      'kilometraje': payload.mileage,
      'limpio_exterior': payload.cleanedExterior ? '1' : '0',
      'limpio_interior': payload.cleanedInterior ? '1' : '0',
      'observaciones': payload.observations,
      'liquido_limpiaparabrisas': payload.windshieldWasherFluid,
      'aceite': payload.oilLevel,
      'anticongelante': payload.coolantLevel,
      'liquido_frenos': payload.brakeFluidLevel,
      'estado_llantas': payload.tireCondition,
      'llanta_delantera_izq_calibracion': payload.frontLeftTirePressure,
      'llanta_delantera_der_calibracion': payload.frontRightTirePressure,
      'llanta_trasera_izq_calibracion': payload.rearLeftTirePressure,
      'llanta_trasera_der_calibracion': payload.rearRightTirePressure,
    });

    if (includeTools) {
      for (final tool in payload.tools) {
        formData.fields.add(
          MapEntry(
            'herramientas[${tool.keyName}]',
            tool.isAvailable ? '1' : '0',
          ),
        );
      }
    }

    for (final photo in payload.photos) {
      formData.files.add(
        MapEntry(
          'evidencias[]',
          MultipartFile.fromFileSync(photo.path),
        ),
      );
    }

    return formData;
  }
}
