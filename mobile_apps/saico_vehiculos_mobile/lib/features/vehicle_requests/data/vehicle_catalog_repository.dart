import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../../core/config/app_config.dart';
import '../domain/vehicle_exit_models.dart';
import 'vehicle_request_repository.dart';

final vehicleCatalogRepositoryProvider = Provider<VehicleCatalogRepository>((ref) {
  return VehicleCatalogRepository(ref.watch(vehicleRequestRepositoryProvider));
});

class VehicleCatalogRepository {
  const VehicleCatalogRepository(this._requestRepository);

  final VehicleRequestRepository _requestRepository;

  Future<List<CatalogVehicle>> fetchAvailableVehicles() async {
    return _requestRepository.fetchAvailableVehicles();
  }

  Future<List<CatalogUser>> fetchOperationalUsers() async {
    return _requestRepository.fetchOperationalUsers();
  }
}
