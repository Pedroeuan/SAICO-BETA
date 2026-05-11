import 'dart:io';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

import '../../../core/theme/app_colors.dart';
import '../../../core/widgets/app_button.dart';
import '../../../core/widgets/app_card.dart';
import '../../../core/widgets/photo_evidence_picker.dart';
import '../../../core/widgets/saico_brand_header.dart';
import '../data/checklist_models.dart';

enum ChecklistMode { departure, arrival }

class ChecklistFormShell extends StatefulWidget {
  const ChecklistFormShell({
    required this.exitId,
    required this.mode,
    required this.title,
    required this.vehicleLabel,
    required this.driverName,
    required this.requestedByName,
    required this.currentMileage,
    required this.onSubmit,
    this.vehiclePhotoUrl,
    this.departureAtLabel,
    this.referenceMileage,
    this.initialPayload,
    this.licenseValid = false,
    this.circulationCardValid = false,
    this.insurancePolicyValid = false,
    super.key,
  });

  final String exitId;
  final ChecklistMode mode;
  final String title;
  final String vehicleLabel;
  final String driverName;
  final String requestedByName;
  final String? departureAtLabel;
  final int currentMileage;
  final String? vehiclePhotoUrl;
  final int? referenceMileage;
  final ChecklistPayload? initialPayload;
  final bool licenseValid;
  final bool circulationCardValid;
  final bool insurancePolicyValid;
  final Future<void> Function(ChecklistPayload payload) onSubmit;

  @override
  State<ChecklistFormShell> createState() => _ChecklistFormShellState();
}

class _ChecklistFormShellState extends State<ChecklistFormShell> {
  static const List<String> _fuelLevels = <String>['Lleno', '3/4', '1/2', '1/4', 'Vacio'];
  static const List<String> _fluidLevels = <String>['suficiente', 'escaso', 'no_hay'];
  static const List<String> _tireConditions = <String>['buen_estado', 'regular', 'malo'];
  static const List<String> _tirePressures = <String>['baja', 'normal', 'alta'];

  final GlobalKey<FormState> _formKey = GlobalKey<FormState>();
  final TextEditingController _mileageController = TextEditingController();
  final TextEditingController _observationsController = TextEditingController();

  late String _fuelLevel;
  late String _windshieldWasherFluid;
  late String _oilLevel;
  late String _coolantLevel;
  late String _brakeFluidLevel;
  late String _tireCondition;
  late String _frontLeftTirePressure;
  late String _frontRightTirePressure;
  late String _rearLeftTirePressure;
  late String _rearRightTirePressure;
  late bool _cleanedExterior;
  late bool _cleanedInterior;
  late List<ChecklistToolOption> _tools;
  List<File> _photos = <File>[];
  int _activeTab = 0;
  bool _isSubmitting = false;

  bool get _isArrival => widget.mode == ChecklistMode.arrival;

  @override
  void initState() {
    super.initState();

    final seed = widget.initialPayload;
    _fuelLevel = seed?.fuelLevel ?? '1/2';
    _windshieldWasherFluid = seed?.windshieldWasherFluid ?? 'suficiente';
    _oilLevel = seed?.oilLevel ?? 'suficiente';
    _coolantLevel = seed?.coolantLevel ?? 'suficiente';
    _brakeFluidLevel = seed?.brakeFluidLevel ?? 'suficiente';
    _tireCondition = seed?.tireCondition ?? 'buen_estado';
    _frontLeftTirePressure = seed?.frontLeftTirePressure ?? 'normal';
    _frontRightTirePressure = seed?.frontRightTirePressure ?? 'normal';
    _rearLeftTirePressure = seed?.rearLeftTirePressure ?? 'normal';
    _rearRightTirePressure = seed?.rearRightTirePressure ?? 'normal';
    _cleanedExterior = seed?.cleanedExterior ?? false;
    _cleanedInterior = seed?.cleanedInterior ?? false;
    _mileageController.text = (seed?.mileage ?? widget.currentMileage).toString();
    _observationsController.text = seed?.observations ?? '';
    _tools = (seed?.tools.isNotEmpty ?? false) ? seed!.tools : _defaultTools();
  }

  @override
  void dispose() {
    _mileageController.dispose();
    _observationsController.dispose();
    super.dispose();
  }

  List<ChecklistToolOption> _defaultTools() {
    return const <ChecklistToolOption>[
      ChecklistToolOption(keyName: 'llantas', label: 'Llantas'),
      ChecklistToolOption(keyName: 'extintor', label: 'Extintor'),
      ChecklistToolOption(keyName: 'cables_corriente', label: 'Cables para corriente'),
      ChecklistToolOption(keyName: 'gato_hidraulico', label: 'Gato hidraulico'),
      ChecklistToolOption(keyName: 'llave_cruz', label: 'Llave de cruz'),
      ChecklistToolOption(keyName: 'llanta_refaccion', label: 'Llanta de refaccion'),
    ];
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) {
      return;
    }

    final mileage = int.tryParse(_mileageController.text.trim());
    if (mileage == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Captura un kilometraje valido.')),
      );
      return;
    }

    if (_isArrival && widget.referenceMileage != null && mileage <= widget.referenceMileage!) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            'El kilometraje final debe ser mayor a ${widget.referenceMileage}.',
          ),
        ),
      );
      return;
    }

    if (_photos.length != 3) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Debes adjuntar exactamente 3 evidencias fotograficas.'),
        ),
      );
      return;
    }

    setState(() => _isSubmitting = true);
    HapticFeedback.mediumImpact();

    try {
      await widget.onSubmit(
        ChecklistPayload(
          exitId: widget.exitId,
          fuelLevel: _fuelLevel,
          mileage: mileage,
          cleanedExterior: _cleanedExterior,
          cleanedInterior: _cleanedInterior,
          windshieldWasherFluid: _windshieldWasherFluid,
          oilLevel: _oilLevel,
          coolantLevel: _coolantLevel,
          brakeFluidLevel: _brakeFluidLevel,
          tireCondition: _tireCondition,
          frontLeftTirePressure: _frontLeftTirePressure,
          frontRightTirePressure: _frontRightTirePressure,
          rearLeftTirePressure: _rearLeftTirePressure,
          rearRightTirePressure: _rearRightTirePressure,
          observations: _observationsController.text.trim(),
          photos: _photos,
          tools: _isArrival ? const <ChecklistToolOption>[] : _tools,
        ),
      );
    } finally {
      if (mounted) {
        setState(() => _isSubmitting = false);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Form(
      key: _formKey,
      child: LayoutBuilder(
        builder: (context, constraints) {
          final isWide = constraints.maxWidth >= 980;
          final hero = SaicoBrandHeader(
            compact: true,
            height: 220,
            title: widget.title,
            subtitle: _isArrival
                ? 'Confirma condiciones de regreso, kilometraje final y evidencia fotografica.'
                : 'Captura la salida con el mismo orden operativo del sistema SAICO.',
          );
          final vehiclePanel = _VehiclePanel(
            vehicleLabel: widget.vehicleLabel,
            driverName: widget.driverName,
            requestedByName: widget.requestedByName,
            departureAtLabel: widget.departureAtLabel,
            vehiclePhotoUrl: widget.vehiclePhotoUrl,
          );
          final content = _isArrival ? _buildArrivalContent(context) : _buildDepartureContent(context);

          if (!isWide) {
            return ListView(
              children: <Widget>[
                hero,
                const SizedBox(height: 16),
                vehiclePanel,
                const SizedBox(height: 16),
                content,
                const SizedBox(height: 24),
              ],
            );
          }

          return Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: <Widget>[
              Expanded(
                flex: 5,
                child: Column(
                  children: <Widget>[
                    hero,
                    const SizedBox(height: 16),
                    vehiclePanel,
                  ],
                ),
              ),
              const SizedBox(width: 16),
              Expanded(flex: 7, child: content),
            ],
          );
        },
      ),
    );
  }

  Widget _buildDepartureContent(BuildContext context) {
    return AppCard(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
          Text(widget.title, style: Theme.of(context).textTheme.titleLarge),
          const SizedBox(height: 16),
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            child: Row(
              children: <Widget>[
                _TabChip(
                  label: 'Datos generales',
                  selected: _activeTab == 0,
                  onTap: () => setState(() => _activeTab = 0),
                ),
                const SizedBox(width: 10),
                _TabChip(
                  label: 'Herramientas',
                  selected: _activeTab == 1,
                  onTap: () => setState(() => _activeTab = 1),
                ),
                const SizedBox(width: 10),
                _TabChip(
                  label: 'Documentos',
                  selected: _activeTab == 2,
                  onTap: () => setState(() => _activeTab = 2),
                ),
                const SizedBox(width: 10),
                _TabChip(
                  label: 'Evidencias',
                  selected: _activeTab == 3,
                  onTap: () => setState(() => _activeTab = 3),
                ),
              ],
            ),
          ),
          const SizedBox(height: 20),
          if (_activeTab == 0) _buildGeneralSection(context),
          if (_activeTab == 1) _buildToolsSection(context),
          if (_activeTab == 2) _buildDocumentsSection(context),
          if (_activeTab == 3) _buildEvidenceSection(context),
        ],
      ),
    );
  }

  Widget _buildArrivalContent(BuildContext context) {
    return AppCard(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
          Text(widget.title, style: Theme.of(context).textTheme.titleLarge),
          const SizedBox(height: 16),
          _buildGeneralSection(context),
          const SizedBox(height: 16),
          _buildEvidenceSection(context),
        ],
      ),
    );
  }

  Widget _buildGeneralSection(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: <Widget>[
        _SelectField(
          label: _isArrival ? 'Nivel de gasolina al regresar' : 'Nivel de gasolina',
          value: _fuelLevel,
          items: _fuelLevels,
          onChanged: (value) => setState(() => _fuelLevel = value!),
        ),
        const SizedBox(height: 16),
        TextFormField(
          controller: _mileageController,
          keyboardType: TextInputType.number,
          decoration: InputDecoration(
            labelText: _isArrival ? 'Kilometraje final' : 'Kilometraje',
            helperText: widget.referenceMileage != null
                ? 'Referencia de salida: ${widget.referenceMileage} km'
                : 'Kilometraje actual registrado: ${widget.currentMileage} km',
          ),
          validator: (value) {
            if (value == null || value.trim().isEmpty) {
              return 'Ingresa el kilometraje.';
            }
            return null;
          },
        ),
        const SizedBox(height: 16),
        Container(
          decoration: BoxDecoration(
            color: AppColors.primarySoft,
            borderRadius: BorderRadius.circular(18),
            border: Border.all(color: AppColors.line),
          ),
          padding: const EdgeInsets.all(14),
          child: const Text(
            'Captura de llantas por tanteo: registra si la calibracion se percibe como Baja, Normal o Alta en cada llanta.',
          ),
        ),
        const SizedBox(height: 16),
        _ResponsiveGrid(
          children: <Widget>[
            _SelectField(
              label: 'Liquido limpia parabrisas',
              value: _windshieldWasherFluid,
              items: _fluidLevels,
              onChanged: (value) => setState(() => _windshieldWasherFluid = value!),
            ),
            _SelectField(
              label: 'Aceite',
              value: _oilLevel,
              items: _fluidLevels,
              onChanged: (value) => setState(() => _oilLevel = value!),
            ),
            _SelectField(
              label: 'Anticongelante',
              value: _coolantLevel,
              items: _fluidLevels,
              onChanged: (value) => setState(() => _coolantLevel = value!),
            ),
            _SelectField(
              label: 'Liquido de frenos',
              value: _brakeFluidLevel,
              items: _fluidLevels,
              onChanged: (value) => setState(() => _brakeFluidLevel = value!),
            ),
          ],
        ),
        const SizedBox(height: 16),
        _SelectField(
          label: 'Estado general de llantas',
          value: _tireCondition,
          items: _tireConditions,
          onChanged: (value) => setState(() => _tireCondition = value!),
        ),
        const SizedBox(height: 16),
        _ResponsiveGrid(
          children: <Widget>[
            _SelectField(
              label: 'Delantera izquierda',
              value: _frontLeftTirePressure,
              items: _tirePressures,
              onChanged: (value) => setState(() => _frontLeftTirePressure = value!),
            ),
            _SelectField(
              label: 'Delantera derecha',
              value: _frontRightTirePressure,
              items: _tirePressures,
              onChanged: (value) => setState(() => _frontRightTirePressure = value!),
            ),
            _SelectField(
              label: 'Trasera izquierda',
              value: _rearLeftTirePressure,
              items: _tirePressures,
              onChanged: (value) => setState(() => _rearLeftTirePressure = value!),
            ),
            _SelectField(
              label: 'Trasera derecha',
              value: _rearRightTirePressure,
              items: _tirePressures,
              onChanged: (value) => setState(() => _rearRightTirePressure = value!),
            ),
          ],
        ),
        const SizedBox(height: 12),
        Card(
          elevation: 0,
          color: Colors.white,
          child: Column(
            children: <Widget>[
              SwitchListTile.adaptive(
                value: _cleanedExterior,
                contentPadding: EdgeInsets.zero,
                title: const Text('Limpio exterior'),
                onChanged: (value) => setState(() => _cleanedExterior = value),
              ),
              const Divider(height: 1),
              SwitchListTile.adaptive(
                value: _cleanedInterior,
                contentPadding: EdgeInsets.zero,
                title: const Text('Limpio interior'),
                onChanged: (value) => setState(() => _cleanedInterior = value),
              ),
            ],
          ),
        ),
        const SizedBox(height: 12),
        TextFormField(
          controller: _observationsController,
          minLines: 3,
          maxLines: 4,
          decoration: const InputDecoration(labelText: 'Observaciones'),
          validator: (value) {
            final text = value?.trim() ?? '';
            if (text.length > 500) {
              return 'Las observaciones no pueden superar 500 caracteres.';
            }
            return null;
          },
        ),
        if (!_isArrival) ...<Widget>[
          const SizedBox(height: 20),
          Align(
            alignment: Alignment.centerRight,
            child: AppButton(
              label: 'Siguiente',
              icon: Icons.arrow_forward,
              onPressed: () => setState(() => _activeTab = 1),
            ),
          ),
        ],
      ],
    );
  }

  Widget _buildToolsSection(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: <Widget>[
        _ResponsiveGrid(
          children: _tools.asMap().entries.map((entry) {
            final option = entry.value;
            return AppCard(
              backgroundColor: const Color(0xFFFDFEFF),
              child: CheckboxListTile(
                value: option.isAvailable,
                contentPadding: EdgeInsets.zero,
                title: Text(option.label),
                controlAffinity: ListTileControlAffinity.leading,
                onChanged: (value) {
                  setState(() {
                    _tools = List<ChecklistToolOption>.from(_tools)
                      ..[entry.key] = option.copyWith(isAvailable: value ?? false);
                  });
                },
              ),
            );
          }).toList(growable: false),
        ),
        const SizedBox(height: 20),
        Align(
          alignment: Alignment.centerRight,
          child: AppButton(
            label: 'Siguiente',
            icon: Icons.arrow_forward,
            onPressed: () => setState(() => _activeTab = 2),
          ),
        ),
      ],
    );
  }

  Widget _buildDocumentsSection(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: <Widget>[
        _DocumentTile(
          label: 'Licencia de conducir',
          isValid: widget.licenseValid,
        ),
        const SizedBox(height: 12),
        _DocumentTile(
          label: 'Tarjeta de circulacion',
          isValid: widget.circulationCardValid,
        ),
        const SizedBox(height: 12),
        _DocumentTile(
          label: 'Poliza de seguro',
          isValid: widget.insurancePolicyValid,
        ),
        const SizedBox(height: 20),
        Align(
          alignment: Alignment.centerRight,
          child: AppButton(
            label: 'Siguiente',
            icon: Icons.arrow_forward,
            onPressed: () => setState(() => _activeTab = 3),
          ),
        ),
      ],
    );
  }

  Widget _buildEvidenceSection(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: <Widget>[
        PhotoEvidencePicker(
          photos: _photos,
          minimumPhotos: 3,
          maximumPhotos: 3,
          helperText: 'Evidencia fotografica (exactamente 3 imagenes)',
          onPhotosChanged: (files) {
            setState(() => _photos = files);
          },
        ),
        const SizedBox(height: 20),
        Wrap(
          spacing: 12,
          runSpacing: 12,
          alignment: WrapAlignment.end,
          children: <Widget>[
            if (!_isArrival)
              AppButton(
                label: 'Regresar',
                isSecondary: true,
                onPressed: () => setState(() => _activeTab = 2),
              ),
            AppButton(
              label: _isSubmitting
                  ? 'Guardando checklist...'
                  : _isArrival
                      ? 'Finalizar salida'
                      : 'Guardar checklist',
              isLoading: _isSubmitting,
              onPressed: _submit,
            ),
          ],
        ),
      ],
    );
  }
}

class _VehiclePanel extends StatelessWidget {
  const _VehiclePanel({
    required this.vehicleLabel,
    required this.driverName,
    required this.requestedByName,
    required this.vehiclePhotoUrl,
    this.departureAtLabel,
  });

  final String vehicleLabel;
  final String driverName;
  final String requestedByName;
  final String? departureAtLabel;
  final String? vehiclePhotoUrl;

  @override
  Widget build(BuildContext context) {
    return AppCard(
      child: Column(
        children: <Widget>[
          Container(
            width: double.infinity,
            height: 260,
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(24),
              gradient: const LinearGradient(
                colors: <Color>[AppColors.primarySoft, Color(0xFFFCEFF1)],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
            ),
            child: ClipRRect(
              borderRadius: BorderRadius.circular(24),
              child: _VehicleIllustration(photoUrl: vehiclePhotoUrl),
            ),
          ),
          const SizedBox(height: 16),
          Text(
            vehicleLabel,
            textAlign: TextAlign.center,
            style: Theme.of(context).textTheme.titleLarge,
          ),
          const SizedBox(height: 12),
          _VehicleInfoRow(label: 'Chofer', value: driverName),
          _VehicleInfoRow(label: 'Solicitado por', value: requestedByName),
          if ((departureAtLabel ?? '').isNotEmpty)
            _VehicleInfoRow(label: 'Fecha salida', value: departureAtLabel!),
        ],
      ),
    );
  }
}

class _VehicleInfoRow extends StatelessWidget {
  const _VehicleInfoRow({
    required this.label,
    required this.value,
  });

  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Row(
        children: <Widget>[
          SizedBox(width: 110, child: Text(label)),
          Expanded(
            child: Text(
              value,
              style: Theme.of(context).textTheme.titleSmall,
            ),
          ),
        ],
      ),
    );
  }
}

class _TabChip extends StatelessWidget {
  const _TabChip({
    required this.label,
    required this.selected,
    required this.onTap,
  });

  final String label;
  final bool selected;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(999),
      child: Ink(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
        decoration: BoxDecoration(
          color: selected ? AppColors.primary : Colors.white,
          borderRadius: BorderRadius.circular(999),
          border: Border.all(
            color: selected ? AppColors.primary : AppColors.line,
          ),
        ),
        child: Text(
          label,
          style: TextStyle(color: selected ? Colors.white : AppColors.ink),
        ),
      ),
    );
  }
}

class _SelectField extends StatelessWidget {
  const _SelectField({
    required this.label,
    required this.value,
    required this.items,
    required this.onChanged,
  });

  final String label;
  final String value;
  final List<String> items;
  final ValueChanged<String?> onChanged;

  @override
  Widget build(BuildContext context) {
    return DropdownButtonFormField<String>(
      value: value,
      decoration: InputDecoration(labelText: label),
      items: items
          .map(
            (item) => DropdownMenuItem<String>(
              value: item,
              child: Text(item.replaceAll('_', ' ')),
            ),
          )
          .toList(growable: false),
      onChanged: onChanged,
    );
  }
}

class _ResponsiveGrid extends StatelessWidget {
  const _ResponsiveGrid({
    required this.children,
  });

  final List<Widget> children;

  @override
  Widget build(BuildContext context) {
    return LayoutBuilder(
      builder: (context, constraints) {
        final columns = constraints.maxWidth >= 720 ? 2 : 1;
        final itemWidth = columns == 1 ? constraints.maxWidth : (constraints.maxWidth - 16) / 2;

        return Wrap(
          spacing: 16,
          runSpacing: 16,
          children: children
              .map((child) => SizedBox(width: itemWidth, child: child))
              .toList(growable: false),
        );
      },
    );
  }
}

class _DocumentTile extends StatelessWidget {
  const _DocumentTile({
    required this.label,
    required this.isValid,
  });

  final String label;
  final bool isValid;

  @override
  Widget build(BuildContext context) {
    final color = isValid ? const Color(0xFF2D7B52) : const Color(0xFFAF2A2A);
    final text = isValid ? 'Vigente' : 'Vencida / no registrada';

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: AppColors.line),
        color: Colors.white,
      ),
      child: Row(
        children: <Widget>[
          Expanded(child: Text(label)),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
            decoration: BoxDecoration(
              color: color.withOpacity(0.12),
              borderRadius: BorderRadius.circular(999),
            ),
            child: Text(
              text,
              style: TextStyle(color: color),
            ),
          ),
        ],
      ),
    );
  }
}

class _VehicleIllustration extends StatelessWidget {
  const _VehicleIllustration({
    required this.photoUrl,
  });

  final String? photoUrl;

  @override
  Widget build(BuildContext context) {
    if (photoUrl != null && photoUrl!.trim().isNotEmpty) {
      return Image.network(
        photoUrl!,
        fit: BoxFit.cover,
        errorBuilder: (_, __, ___) => _fallback(),
      );
    }

    return _fallback();
  }

  Widget _fallback() {
    return Stack(
      fit: StackFit.expand,
      children: <Widget>[
        Image.asset(
          'assets/branding/saico_wave_bg.png',
          fit: BoxFit.cover,
        ),
        DecoratedBox(
          decoration: BoxDecoration(
            color: Colors.white.withOpacity(0.72),
          ),
        ),
        Center(
          child: Image.asset(
            'assets/branding/saico_logo.png',
            height: 74,
            fit: BoxFit.contain,
          ),
        ),
      ],
    );
  }
}
