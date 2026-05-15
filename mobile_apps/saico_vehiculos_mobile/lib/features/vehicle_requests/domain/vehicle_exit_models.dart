class CatalogVehicle {
  const CatalogVehicle({
    required this.id,
    required this.plate,
    required this.brand,
    required this.model,
    this.year,
    this.photoUrl,
    this.statusLabel = 'disponible',
    this.currentMileage,
    this.documentationStatus = 'completa',
    this.insuranceExpiration,
    this.circulationCardExpiration,
  });

  final int id;
  final String plate;
  final String brand;
  final String model;
  final int? year;
  final String? photoUrl;
  final String statusLabel;
  final int? currentMileage;
  final String documentationStatus;
  final DateTime? insuranceExpiration;
  final DateTime? circulationCardExpiration;

  factory CatalogVehicle.fromJson(Map<String, dynamic> json) {
    return CatalogVehicle(
      id: _asInt(json['id']) ?? 0,
      plate: json['placa'] as String? ?? '',
      brand: json['marca'] as String? ?? '',
      model: json['modelo'] as String? ?? '',
      year: _asInt(json['anio']),
      photoUrl: json['foto_url'] as String?,
      statusLabel: json['estatus'] as String? ?? 'disponible',
      currentMileage: _asInt(json['kilometraje_actual']),
      documentationStatus: json['documentacion_estatus'] as String? ?? 'completa',
      insuranceExpiration: _parseDateTime(json['poliza_seguro_vencimiento']),
      circulationCardExpiration: _parseDateTime(json['tarjeta_circulacion_vencimiento']),
    );
  }

  String get displayLabel => '$plate - $brand $model';
  String get yearLabel => year?.toString() ?? '';
  bool get isDocumentationComplete => documentationStatus == 'completa';

  bool isInsuranceValidAt(DateTime at) {
    if (insuranceExpiration == null) {
      return false;
    }

    return !insuranceExpiration!.isBefore(DateTime(at.year, at.month, at.day));
  }

  bool isCirculationCardValidAt(DateTime at) {
    if (circulationCardExpiration == null) {
      return false;
    }

    return !circulationCardExpiration!.isBefore(DateTime(at.year, at.month, at.day));
  }

  CatalogVehicle copyWith({
    int? id,
    String? plate,
    String? brand,
    String? model,
    int? year,
    String? photoUrl,
    String? statusLabel,
    int? currentMileage,
    String? documentationStatus,
    DateTime? insuranceExpiration,
    DateTime? circulationCardExpiration,
  }) {
    return CatalogVehicle(
      id: id ?? this.id,
      plate: plate ?? this.plate,
      brand: brand ?? this.brand,
      model: model ?? this.model,
      year: year ?? this.year,
      photoUrl: photoUrl ?? this.photoUrl,
      statusLabel: statusLabel ?? this.statusLabel,
      currentMileage: currentMileage ?? this.currentMileage,
      documentationStatus: documentationStatus ?? this.documentationStatus,
      insuranceExpiration: insuranceExpiration ?? this.insuranceExpiration,
      circulationCardExpiration:
          circulationCardExpiration ?? this.circulationCardExpiration,
    );
  }
}

class CatalogUser {
  const CatalogUser({
    required this.id,
    required this.name,
    required this.role,
    this.licenseNumber,
    this.licenseExpiration,
  });

  final int id;
  final String name;
  final String role;
  final String? licenseNumber;
  final DateTime? licenseExpiration;

  factory CatalogUser.fromJson(Map<String, dynamic> json) {
    return CatalogUser(
      id: _asInt(json['id']) ?? 0,
      name: json['name'] as String? ?? '',
      role: json['rol'] as String? ?? '',
      licenseNumber: json['licencia_numero'] as String?,
      licenseExpiration: _parseDateTime(json['licencia_vencimiento']),
    );
  }

  bool get canRequestVehicle => role.trim().toLowerCase() != 'cliente';
  bool get hasRegisteredLicense => (licenseNumber ?? '').trim().isNotEmpty;

  bool isLicenseValidAt(DateTime at) {
    if (!hasRegisteredLicense || licenseExpiration == null) {
      return false;
    }

    return !licenseExpiration!.isBefore(DateTime(at.year, at.month, at.day));
  }
}

class VehicleExitSummary {
  const VehicleExitSummary({
    required this.id,
    required this.driverId,
    required this.requestedById,
    required this.folio,
    required this.status,
    required this.vehicleLabel,
    required this.driverName,
    required this.requestedByName,
    this.vehicle,
    this.departureAtLabel,
    this.reason,
    this.departureMileage,
    this.driverLicenseExpiration,
    this.departureChecklistCompleted = false,
    this.arrivalChecklistCompleted = false,
    this.departureChecklist,
    this.arrivalChecklist,
  });

  final int id;
  final int driverId;
  final int requestedById;
  final String folio;
  final String status;
  final String vehicleLabel;
  final String driverName;
  final String requestedByName;
  final CatalogVehicle? vehicle;
  final String? departureAtLabel;
  final String? reason;
  final int? departureMileage;
  final DateTime? driverLicenseExpiration;
  final bool departureChecklistCompleted;
  final bool arrivalChecklistCompleted;
  final VehicleChecklistSnapshot? departureChecklist;
  final VehicleChecklistSnapshot? arrivalChecklist;

  factory VehicleExitSummary.fromJson(Map<String, dynamic> json) {
    final vehicle = _asJsonMap(json['vehiculo']);
    final driver = _asJsonMap(json['chofer']);
    final requester = _asJsonMap(json['solicitante']);

    return VehicleExitSummary(
      id: _asInt(json['id']) ?? 0,
      driverId: _asInt(json['chofer_id']) ?? 0,
      requestedById: _asInt(json['solicitado_por']) ?? 0,
      folio: json['folio'] as String? ?? '',
      status: json['estatus'] as String? ?? '',
      vehicleLabel: '${vehicle['placa'] ?? ''} - ${vehicle['marca'] ?? ''}',
      driverName: driver['name'] as String? ?? 'Sin chofer',
      requestedByName: requester['name'] as String? ?? 'Sin solicitante',
      vehicle: vehicle.isEmpty ? null : CatalogVehicle.fromJson(vehicle),
      departureAtLabel: json['fecha_salida'] as String?,
      reason: json['motivo'] as String?,
      departureMileage: _asInt(json['kilometraje_salida']),
      driverLicenseExpiration: _parseDateTime(driver['licencia_vencimiento']),
      departureChecklistCompleted: json['checklist_salida_completo'] as bool? ?? false,
      arrivalChecklistCompleted: json['checklist_entrada_completo'] as bool? ?? false,
      departureChecklist: _parseChecklist(json['checklist_salida']),
      arrivalChecklist: _parseChecklist(json['checklist_entrada']),
    );
  }

  VehicleExitSummary copyWith({
    int? id,
    int? driverId,
    int? requestedById,
    String? folio,
    String? status,
    String? vehicleLabel,
    String? driverName,
    String? requestedByName,
    CatalogVehicle? vehicle,
    String? departureAtLabel,
    String? reason,
    int? departureMileage,
    DateTime? driverLicenseExpiration,
    bool? departureChecklistCompleted,
    bool? arrivalChecklistCompleted,
    VehicleChecklistSnapshot? departureChecklist,
    VehicleChecklistSnapshot? arrivalChecklist,
  }) {
    return VehicleExitSummary(
      id: id ?? this.id,
      driverId: driverId ?? this.driverId,
      requestedById: requestedById ?? this.requestedById,
      folio: folio ?? this.folio,
      status: status ?? this.status,
      vehicleLabel: vehicleLabel ?? this.vehicleLabel,
      driverName: driverName ?? this.driverName,
      requestedByName: requestedByName ?? this.requestedByName,
      vehicle: vehicle ?? this.vehicle,
      departureAtLabel: departureAtLabel ?? this.departureAtLabel,
      reason: reason ?? this.reason,
      departureMileage: departureMileage ?? this.departureMileage,
      driverLicenseExpiration: driverLicenseExpiration ?? this.driverLicenseExpiration,
      departureChecklistCompleted:
          departureChecklistCompleted ?? this.departureChecklistCompleted,
      arrivalChecklistCompleted:
          arrivalChecklistCompleted ?? this.arrivalChecklistCompleted,
      departureChecklist: departureChecklist ?? this.departureChecklist,
      arrivalChecklist: arrivalChecklist ?? this.arrivalChecklist,
    );
  }
}

class VehicleExitDetail {
  const VehicleExitDetail({
    required this.exit,
    this.departureDefaults = const <String, dynamic>{},
  });

  final VehicleExitSummary exit;
  final Map<String, dynamic> departureDefaults;
}

class VehicleChecklistSnapshot {
  const VehicleChecklistSnapshot({
    required this.id,
    required this.type,
    required this.condition,
    this.documents = const <ChecklistDocumentSnapshot>[],
    this.tools = const <ChecklistToolSnapshot>[],
    this.evidenceUrls = const <String>[],
  });

  final int id;
  final String type;
  final ChecklistConditionSnapshot condition;
  final List<ChecklistDocumentSnapshot> documents;
  final List<ChecklistToolSnapshot> tools;
  final List<String> evidenceUrls;
}

class ChecklistConditionSnapshot {
  const ChecklistConditionSnapshot({
    required this.fuelLevel,
    required this.mileage,
    required this.cleanedExterior,
    required this.cleanedInterior,
    required this.windshieldWasherFluid,
    required this.oilLevel,
    required this.coolantLevel,
    required this.brakeFluidLevel,
    required this.tireCondition,
    required this.frontLeftTirePressure,
    required this.frontRightTirePressure,
    required this.rearLeftTirePressure,
    required this.rearRightTirePressure,
    this.observations,
  });

  final String fuelLevel;
  final int mileage;
  final bool cleanedExterior;
  final bool cleanedInterior;
  final String windshieldWasherFluid;
  final String oilLevel;
  final String coolantLevel;
  final String brakeFluidLevel;
  final String tireCondition;
  final String frontLeftTirePressure;
  final String frontRightTirePressure;
  final String rearLeftTirePressure;
  final String rearRightTirePressure;
  final String? observations;
}

class ChecklistDocumentSnapshot {
  const ChecklistDocumentSnapshot({
    required this.document,
    required this.status,
  });

  final String document;
  final String status;

  bool get isValid => status.trim().toLowerCase() == 'ok';
}

class ChecklistToolSnapshot {
  const ChecklistToolSnapshot({
    required this.tool,
    required this.available,
  });

  final String tool;
  final bool available;
}

DateTime? _parseDateTime(dynamic value) {
  if (value is! String || value.trim().isEmpty) {
    return null;
  }

  return DateTime.tryParse(value);
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

bool _asBool(dynamic value) {
  if (value is bool) {
    return value;
  }

  if (value is num) {
    return value != 0;
  }

  if (value is String) {
    final normalized = value.trim().toLowerCase();
    return normalized == '1' || normalized == 'true' || normalized == 'si';
  }

  return false;
}

VehicleChecklistSnapshot? _parseChecklist(dynamic value) {
  final checklist = _asJsonMap(value);
  if (checklist.isEmpty) {
    return null;
  }

  final condition = _asJsonMap(checklist['condicion']);
  if (condition.isEmpty) {
    return null;
  }

  final documents = (checklist['documentos'] as List<dynamic>? ?? <dynamic>[])
      .whereType<Map<String, dynamic>>()
      .map(
        (item) => ChecklistDocumentSnapshot(
          document: item['documento'] as String? ?? '',
          status: item['estatus'] as String? ?? '',
        ),
      )
      .toList(growable: false);

  final tools = (checklist['herramientas'] as List<dynamic>? ?? <dynamic>[])
      .whereType<Map<String, dynamic>>()
      .map(
        (item) => ChecklistToolSnapshot(
          tool: item['herramienta'] as String? ?? '',
          available: _asBool(item['disponible']),
        ),
      )
      .toList(growable: false);

  final evidenceUrls = (checklist['evidencias'] as List<dynamic>? ?? <dynamic>[])
      .whereType<Map<String, dynamic>>()
      .map((item) => item['foto_url'] as String? ?? '')
      .where((item) => item.trim().isNotEmpty)
      .toList(growable: false);

  return VehicleChecklistSnapshot(
    id: _asInt(checklist['id']) ?? 0,
    type: checklist['tipo'] as String? ?? '',
    condition: ChecklistConditionSnapshot(
      fuelLevel: condition['nivel_gasolina'] as String? ?? '',
      mileage: _asInt(condition['kilometraje']) ?? 0,
      cleanedExterior: _asBool(condition['limpio_exterior']),
      cleanedInterior: _asBool(condition['limpio_interior']),
      windshieldWasherFluid: condition['liquido_limpiaparabrisas'] as String? ?? '',
      oilLevel: condition['aceite'] as String? ?? '',
      coolantLevel: condition['anticongelante'] as String? ?? '',
      brakeFluidLevel: condition['liquido_frenos'] as String? ?? '',
      tireCondition: condition['estado_llantas'] as String? ?? '',
      frontLeftTirePressure:
          condition['llanta_delantera_izq_calibracion'] as String? ?? '',
      frontRightTirePressure:
          condition['llanta_delantera_der_calibracion'] as String? ?? '',
      rearLeftTirePressure:
          condition['llanta_trasera_izq_calibracion'] as String? ?? '',
      rearRightTirePressure:
          condition['llanta_trasera_der_calibracion'] as String? ?? '',
      observations: condition['observaciones'] as String?,
    ),
    documents: documents,
    tools: tools,
    evidenceUrls: evidenceUrls,
  );
}

Map<String, dynamic> _asJsonMap(dynamic value) {
  if (value is Map<String, dynamic>) {
    return value;
  }

  return <String, dynamic>{};
}
