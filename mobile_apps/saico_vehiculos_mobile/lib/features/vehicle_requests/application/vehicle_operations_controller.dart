import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../data/vehicle_catalog_repository.dart';
import '../data/vehicle_request_repository.dart';
import '../domain/vehicle_exit_models.dart';

final vehicleOperationsControllerProvider =
    AsyncNotifierProvider<VehicleOperationsController, VehicleOperationsState>(
  VehicleOperationsController.new,
);

class VehicleOperationsState {
  const VehicleOperationsState({
    this.exits = const <VehicleExitSummary>[],
    this.vehicles = const <CatalogVehicle>[],
    this.users = const <CatalogUser>[],
  });

  final List<VehicleExitSummary> exits;
  final List<CatalogVehicle> vehicles;
  final List<CatalogUser> users;

  VehicleExitSummary? get activeExit {
    for (final item in exits) {
      if (item.status.toLowerCase() == 'activo') {
        return item;
      }
    }

    return null;
  }
}

class VehicleOperationsController extends AsyncNotifier<VehicleOperationsState> {
  @override
  Future<VehicleOperationsState> build() async {
    return _loadState();
  }

  Future<void> refreshData() async {
    state = const AsyncLoading();
    state = await AsyncValue.guard(_loadState);
  }

  Future<void> createExit({
    required int vehicleId,
    required int driverId,
    required int requestedById,
    required DateTime departureAt,
    required String reason,
  }) async {
    HapticFeedback.selectionClick();
    await ref.read(vehicleRequestRepositoryProvider).createExit(
          vehicleId: vehicleId,
          driverId: driverId,
          requestedById: requestedById,
          departureAt: departureAt,
          reason: reason,
        );

    await refreshData();
  }

  Future<VehicleOperationsState> _loadState() async {
    final requestRepository = ref.read(vehicleRequestRepositoryProvider);
    final catalogRepository = ref.read(vehicleCatalogRepositoryProvider);

    final results = await Future.wait<dynamic>(<Future<dynamic>>[
      requestRepository.fetchExits(),
      catalogRepository.fetchAvailableVehicles(),
      catalogRepository.fetchOperationalUsers(),
    ]);

    return VehicleOperationsState(
      exits: results[0] as List<VehicleExitSummary>,
      vehicles: results[1] as List<CatalogVehicle>,
      users: results[2] as List<CatalogUser>,
    );
  }
}
