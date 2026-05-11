import 'dart:io';

class ChecklistDocumentStatus {
  const ChecklistDocumentStatus({
    required this.label,
    required this.isValid,
  });

  final String label;
  final bool isValid;
}

class ChecklistToolOption {
  const ChecklistToolOption({
    required this.keyName,
    required this.label,
    this.isAvailable = false,
  });

  final String keyName;
  final String label;
  final bool isAvailable;

  ChecklistToolOption copyWith({
    bool? isAvailable,
  }) {
    return ChecklistToolOption(
      keyName: keyName,
      label: label,
      isAvailable: isAvailable ?? this.isAvailable,
    );
  }
}

class ChecklistPayload {
  const ChecklistPayload({
    required this.exitId,
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
    required this.observations,
    required this.photos,
    this.tools = const <ChecklistToolOption>[],
  });

  final String exitId;
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
  final String observations;
  final List<File> photos;
  final List<ChecklistToolOption> tools;
}
