import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../../core/config/app_config.dart';
import '../../../core/constants/api_endpoints.dart';
import '../../../core/network/api_client.dart';
import '../../checklists/data/checklist_models.dart';
import '../domain/vehicle_exit_models.dart';
import 'mock_mobile_store.dart';

final vehicleRequestRepositoryProvider = Provider<VehicleRequestRepository>((ref) {
  return VehicleRequestRepository(ref.watch(dioProvider));
});

final exitDetailProvider = FutureProvider.family<VehicleExitDetail, String>((ref, exitId) {
  return ref.watch(vehicleRequestRepositoryProvider).fetchExitDetail(exitId);
});

class VehicleRequestRepository {
  VehicleRequestRepository(this._dio);

  final Dio _dio;
  final MockMobileStore _mockStore = MockMobileStore.instance;

  Future<List<CatalogVehicle>> fetchAvailableVehicles() async {
    if (AppConfig.useMockData) {
      return _mockStore.getAvailableVehicles();
    }

    final response = await _dio.get<Map<String, dynamic>>(ApiEndpoints.availableVehicles);
    final data = (response.data?['data'] as List<dynamic>? ?? <dynamic>[]);

    return data
        .map((item) => CatalogVehicle.fromJson(item as Map<String, dynamic>))
        .toList(growable: false);
  }

  Future<List<CatalogUser>> fetchOperationalUsers() async {
    if (AppConfig.useMockData) {
      return _mockStore.getUsers();
    }

    final response = await _dio.get<Map<String, dynamic>>(ApiEndpoints.operationalUsers);
    final data = (response.data?['data'] as List<dynamic>? ?? <dynamic>[]);

    return data
        .map((item) => CatalogUser.fromJson(item as Map<String, dynamic>))
        .toList(growable: false);
  }

  Future<List<VehicleExitSummary>> fetchExits() async {
    if (AppConfig.useMockData) {
      return _mockStore.getExits();
    }

    final response = await _dio.get<Map<String, dynamic>>(ApiEndpoints.exits);
    final data = (response.data?['data'] as List<dynamic>? ?? <dynamic>[]);

    return data
        .map((item) => VehicleExitSummary.fromJson(item as Map<String, dynamic>))
        .toList(growable: false);
  }

  Future<void> createExit({
    required int vehicleId,
    required int driverId,
    required int requestedById,
    required DateTime departureAt,
    required String reason,
  }) async {
    if (AppConfig.useMockData) {
      _mockStore.createExit(
        vehicleId: vehicleId,
        driverId: driverId,
        requestedById: requestedById,
        departureAt: departureAt,
        reason: reason,
      );
      await Future<void>.delayed(const Duration(milliseconds: 650));
      return;
    }

    await _dio.post<void>(
      ApiEndpoints.exits,
      data: <String, Object?>{
        'vehiculo_id': vehicleId,
        'chofer_id': driverId,
        'solicitado_por': requestedById,
        'fecha_salida': departureAt.toIso8601String(),
        'motivo': reason,
      },
    );
  }
  
  VehicleExitSummary? getExitById(String exitId) {
    if (AppConfig.useMockData) {
      return _mockStore.getExitById(exitId);
    }

    return null;
  }

  ChecklistPayload? getLastChecklistSeedForVehicle(int vehicleId) {
    if (AppConfig.useMockData) {
      return _mockStore.getLastConditionForVehicle(vehicleId);
    }

    return null;
  }

  Future<VehicleExitDetail> fetchExitDetail(String exitId) async {
    if (AppConfig.useMockData) {
      final exit = _mockStore.getExitById(exitId);
      if (exit == null) {
        throw Exception('No fue posible cargar la salida seleccionada.');
      }

      return VehicleExitDetail(
        exit: exit,
        departureDefaults: const <String, dynamic>{},
      );
    }

    final response = await _dio.get<Map<String, dynamic>>(ApiEndpoints.exitDetail(exitId));
    final body = response.data ?? <String, dynamic>{};
    final data = body['data'] as Map<String, dynamic>? ?? <String, dynamic>{};
    final salida = data['salida'] as Map<String, dynamic>? ?? <String, dynamic>{};
    final defaults =
        data['defaults_ultima_entrada'] as Map<String, dynamic>? ?? <String, dynamic>{};

    return VehicleExitDetail(
      exit: VehicleExitSummary.fromJson(salida),
      departureDefaults: defaults,
    );
  }
}
