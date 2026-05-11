import 'dart:io';

import '../../checklists/data/checklist_models.dart';
import '../domain/vehicle_exit_models.dart';

class MockMobileStore {
  MockMobileStore._()
      : _vehicles = <CatalogVehicle>[
          CatalogVehicle(
            id: 1,
            plate: 'YH-241-A',
            brand: 'Nissan',
            model: 'NP300',
            year: 2022,
            statusLabel: 'ocupado',
            currentMileage: 128440,
            documentationStatus: 'completa',
            insuranceExpiration: DateTime(2026, 12, 10),
            circulationCardExpiration: DateTime(2026, 11, 30),
          ),
          CatalogVehicle(
            id: 2,
            plate: 'LA-880-Z',
            brand: 'Toyota',
            model: 'Hilux',
            year: 2023,
            statusLabel: 'disponible',
            currentMileage: 98320,
            documentationStatus: 'completa',
            insuranceExpiration: DateTime(2026, 10, 18),
            circulationCardExpiration: DateTime(2026, 9, 24),
          ),
          CatalogVehicle(
            id: 3,
            plate: 'RT-552-P',
            brand: 'Ford',
            model: 'Ranger',
            year: 2021,
            statusLabel: 'disponible',
            currentMileage: 154210,
            documentationStatus: 'vencida',
            insuranceExpiration: DateTime(2026, 3, 20),
            circulationCardExpiration: DateTime(2026, 4, 2),
          ),
        ],
        _users = <CatalogUser>[
          CatalogUser(
            id: 1,
            name: 'Francisco Cruz',
            role: 'Administrador',
            licenseNumber: 'LIC-778812',
            licenseExpiration: DateTime(2026, 12, 31),
          ),
          CatalogUser(
            id: 2,
            name: 'Carlos Mendoza',
            role: 'Chofer',
            licenseNumber: 'LIC-441820',
            licenseExpiration: DateTime(2026, 8, 15),
          ),
          CatalogUser(
            id: 3,
            name: 'Miguel Ortega',
            role: 'Tecnico',
            licenseNumber: 'LIC-330199',
            licenseExpiration: DateTime(2026, 7, 5),
          ),
          const CatalogUser(
            id: 4,
            name: 'Cliente Externo',
            role: 'Cliente',
          ),
        ],
        _exits = <VehicleExitSummary>[
          VehicleExitSummary(
            id: 18,
            driverId: 2,
            requestedById: 1,
            folio: 'SV-20260508-001',
            status: 'activo',
            vehicleLabel: 'YH-241-A - Nissan NP300',
            driverName: 'Carlos Mendoza',
            requestedByName: 'Francisco Cruz',
            departureAtLabel: '2026-05-08 08:30',
            reason: 'Visita tecnica a planta',
            vehicle: CatalogVehicle(
              id: 1,
              plate: 'YH-241-A',
              brand: 'Nissan',
              model: 'NP300',
              year: 2022,
              statusLabel: 'ocupado',
              currentMileage: 128440,
              documentationStatus: 'completa',
              insuranceExpiration: DateTime(2026, 12, 10),
              circulationCardExpiration: DateTime(2026, 11, 30),
            ),
            departureMileage: 128440,
            driverLicenseExpiration: DateTime(2026, 8, 15),
            departureChecklistCompleted: false,
            arrivalChecklistCompleted: false,
          ),
          VehicleExitSummary(
            id: 12,
            driverId: 3,
            requestedById: 1,
            folio: 'SV-20260507-014',
            status: 'finalizado',
            vehicleLabel: 'LA-880-Z - Toyota Hilux',
            driverName: 'Miguel Ortega',
            requestedByName: 'Francisco Cruz',
            departureAtLabel: '2026-05-07 16:10',
            reason: 'Entrega de herramientas',
            vehicle: CatalogVehicle(
              id: 2,
              plate: 'LA-880-Z',
              brand: 'Toyota',
              model: 'Hilux',
              year: 2023,
              statusLabel: 'disponible',
              currentMileage: 98320,
              documentationStatus: 'completa',
              insuranceExpiration: DateTime(2026, 10, 18),
              circulationCardExpiration: DateTime(2026, 9, 24),
            ),
            departureMileage: 98010,
            driverLicenseExpiration: DateTime(2026, 7, 5),
            departureChecklistCompleted: true,
            arrivalChecklistCompleted: true,
          ),
        ] {
    _lastConditionByVehicleId = <int, ChecklistPayload>{
      1: _seedPayload(exitId: '18', mileage: 128440),
      2: _seedPayload(exitId: '12', mileage: 98320),
      3: _seedPayload(exitId: '0', mileage: 154210),
    };
  }

  static final MockMobileStore instance = MockMobileStore._();

  List<CatalogVehicle> _vehicles;
  final List<CatalogUser> _users;
  List<VehicleExitSummary> _exits;
  late Map<int, ChecklistPayload> _lastConditionByVehicleId;

  List<CatalogVehicle> getAvailableVehicles() {
    return _vehicles
        .where(
          (vehicle) => vehicle.statusLabel == 'disponible' && vehicle.isDocumentationComplete,
        )
        .toList(growable: false);
  }

  List<CatalogUser> getUsers() {
    return List<CatalogUser>.from(_users);
  }

  List<VehicleExitSummary> getExits() {
    return List<VehicleExitSummary>.from(_exits);
  }

  VehicleExitSummary? getExitById(String exitId) {
    for (final exit in _exits) {
      if (exit.id.toString() == exitId) {
        return exit;
      }
    }

    return null;
  }

  ChecklistPayload? getLastConditionForVehicle(int vehicleId) {
    return _lastConditionByVehicleId[vehicleId];
  }

  void createExit({
    required int vehicleId,
    required int driverId,
    required int requestedById,
    required DateTime departureAt,
    required String reason,
  }) {
    final vehicleIndex = _vehicles.indexWhere((vehicle) => vehicle.id == vehicleId);
    if (vehicleIndex == -1) {
      throw StateError('El vehiculo seleccionado no existe.');
    }

    final vehicle = _vehicles[vehicleIndex];
    final driver = _users.firstWhere((user) => user.id == driverId);
    final requester = _users.firstWhere((user) => user.id == requestedById);

    if (vehicle.statusLabel != 'disponible') {
      throw StateError('El vehiculo no esta disponible.');
    }

    if (!vehicle.isDocumentationComplete) {
      throw StateError('El vehiculo no tiene documentacion completa.');
    }

    if (_exits.any((item) => item.driverId == driverId && item.status == 'activo')) {
      throw StateError('El chofer ya tiene un vehiculo asignado.');
    }

    if (!driver.hasRegisteredLicense) {
      throw StateError('El chofer no tiene licencia registrada.');
    }

    if (!driver.isLicenseValidAt(departureAt)) {
      throw StateError('La licencia del chofer esta vencida.');
    }

    final occupiedVehicle = vehicle.copyWith(statusLabel: 'ocupado');
    _vehicles = List<CatalogVehicle>.from(_vehicles)..[vehicleIndex] = occupiedVehicle;

    final nextId = (_exits.map((item) => item.id).fold<int>(
              0,
              (previous, item) => item > previous ? item : previous,
            )) +
        1;
    final folio = 'SV-20260509-${nextId.toString().padLeft(3, '0')}';

    final newExit = VehicleExitSummary(
      id: nextId,
      driverId: driver.id,
      requestedById: requester.id,
      folio: folio,
      status: 'activo',
      vehicleLabel: occupiedVehicle.displayLabel,
      driverName: driver.name,
      requestedByName: requester.name,
      departureAtLabel: departureAt.toString(),
      reason: reason,
      vehicle: occupiedVehicle,
      departureMileage: occupiedVehicle.currentMileage,
      driverLicenseExpiration: driver.licenseExpiration,
      departureChecklistCompleted: false,
      arrivalChecklistCompleted: false,
    );

    _exits = <VehicleExitSummary>[newExit, ..._exits];
  }

  void completeDepartureChecklist({
    required ChecklistPayload payload,
  }) {
    final index = _exits.indexWhere((exit) => exit.id.toString() == payload.exitId);
    if (index == -1) {
      return;
    }

    final exit = _exits[index];
    final updatedVehicle = exit.vehicle?.copyWith(
      currentMileage: payload.mileage,
      statusLabel: 'ocupado',
    );

    if (updatedVehicle != null) {
      _syncVehicle(updatedVehicle);
      _lastConditionByVehicleId[updatedVehicle.id] = payload;
    }

    _exits = List<VehicleExitSummary>.from(_exits)
      ..[index] = exit.copyWith(
        departureChecklistCompleted: true,
        departureMileage: payload.mileage,
        vehicle: updatedVehicle,
      );
  }

  void completeArrivalChecklist({
    required ChecklistPayload payload,
  }) {
    final index = _exits.indexWhere((exit) => exit.id.toString() == payload.exitId);
    if (index == -1) {
      return;
    }

    final exit = _exits[index];
    final updatedVehicle = exit.vehicle?.copyWith(
      currentMileage: payload.mileage,
      statusLabel: 'disponible',
    );

    if (updatedVehicle != null) {
      _syncVehicle(updatedVehicle);
      _lastConditionByVehicleId[updatedVehicle.id] = payload;
    }

    _exits = List<VehicleExitSummary>.from(_exits)
      ..[index] = exit.copyWith(
        status: 'finalizado',
        arrivalChecklistCompleted: true,
        vehicle: updatedVehicle,
      );
  }

  void _syncVehicle(CatalogVehicle vehicle) {
    final vehicleIndex = _vehicles.indexWhere((item) => item.id == vehicle.id);
    if (vehicleIndex == -1) {
      return;
    }

    _vehicles = List<CatalogVehicle>.from(_vehicles)..[vehicleIndex] = vehicle;
  }

  ChecklistPayload _seedPayload({
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
