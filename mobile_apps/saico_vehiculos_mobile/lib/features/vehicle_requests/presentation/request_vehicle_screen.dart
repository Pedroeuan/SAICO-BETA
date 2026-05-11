import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:intl/intl.dart';

import '../../../core/layout/adaptive_page_scaffold.dart';
import '../../../core/network/network_error_formatter.dart';
import '../../../core/widgets/app_button.dart';
import '../../../core/widgets/app_card.dart';
import '../../../core/widgets/empty_state_widget.dart';
import '../../../core/widgets/saico_brand_header.dart';
import '../../../core/widgets/vehicle_card.dart';
import '../../auth/application/session_provider.dart';
import '../application/vehicle_operations_controller.dart';
import '../domain/vehicle_exit_models.dart';

class RequestVehicleScreen extends ConsumerStatefulWidget {
  const RequestVehicleScreen({super.key});

  @override
  ConsumerState<RequestVehicleScreen> createState() => _RequestVehicleScreenState();
}

class _RequestVehicleScreenState extends ConsumerState<RequestVehicleScreen> {
  final GlobalKey<FormState> _formKey = GlobalKey<FormState>();
  final TextEditingController _reasonController = TextEditingController();

  DateTime _departureAt = DateTime.now().add(const Duration(minutes: 15));
  int? _vehicleId;
  int? _driverId;
  int? _requestedById;
  bool _isSubmitting = false;

  @override
  void dispose() {
    _reasonController.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) {
      return;
    }

    if (_departureAt.isBefore(DateTime.now())) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('La fecha de salida no puede ser menor a la actual.')),
      );
      return;
    }

    setState(() => _isSubmitting = true);
    HapticFeedback.selectionClick();

    try {
      await ref.read(vehicleOperationsControllerProvider.notifier).createExit(
            vehicleId: _vehicleId!,
            driverId: _driverId!,
            requestedById: _requestedById!,
            departureAt: _departureAt,
            reason: _reasonController.text.trim(),
          );

      if (!mounted) {
        return;
      }

      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Salida registrada correctamente.')),
      );
      context.go('/home');
    } catch (error) {
      if (!mounted) {
        return;
      }

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(formatAppError(error))),
      );
    } finally {
      if (mounted) {
        setState(() => _isSubmitting = false);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(vehicleOperationsControllerProvider);
    final session = ref.watch(sessionProvider);

    return AdaptivePageScaffold(
      title: 'Nueva salida',
      child: state.when(
        data: (data) {
          if (data.activeExit != null) {
            return const EmptyStateWidget(
              title: 'Ya existe una salida activa',
              message: 'Primero finaliza la salida actual antes de registrar una nueva solicitud.',
            );
          }

          _requestedById ??= _findInitialRequesterId(data.users, session.currentUser?.id);

          final selectedVehicle = _findVehicle(data.vehicles, _vehicleId);
          final selectedDriver = _findUser(data.users, _driverId);
          final selectedRequester = _findUser(data.users, _requestedById);

          return Form(
            key: _formKey,
            child: LayoutBuilder(
              builder: (context, constraints) {
                final isWide = constraints.maxWidth >= 900;

                final leftColumn = <Widget>[
                  const SaicoBrandHeader(
                    compact: true,
                    height: 220,
                    title: 'Solicitud de vehiculo',
                    subtitle:
                        'Captura la solicitud con el mismo criterio del modulo web y deja visibles las validaciones de operacion.',
                  ),
                  const SizedBox(height: 16),
                  AppCard(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: <Widget>[
                        Text(
                          'Nueva salida de vehiculo',
                          style: Theme.of(context).textTheme.titleLarge,
                        ),
                        const SizedBox(height: 8),
                        Text(
                          'Replica del formulario web original para registrar la solicitud y asignacion de vehiculo.',
                          style: Theme.of(context).textTheme.bodyMedium,
                        ),
                        const SizedBox(height: 20),
                        DropdownButtonFormField<int>(
                          value: _vehicleId,
                          decoration: const InputDecoration(labelText: 'Vehiculo'),
                          items: data.vehicles
                              .map(
                                (vehicle) => DropdownMenuItem<int>(
                                  value: vehicle.id,
                                  child: Text(vehicle.displayLabel),
                                ),
                              )
                              .toList(growable: false),
                          onChanged: (value) => setState(() => _vehicleId = value),
                          validator: (value) =>
                              value == null ? 'Selecciona un vehiculo.' : null,
                        ),
                        if (data.vehicles.isEmpty) ...<Widget>[
                          const SizedBox(height: 8),
                          const Text(
                            'No hay vehiculos disponibles con documentacion completa.',
                            style: TextStyle(color: Color(0xFFAF2A2A)),
                          ),
                        ],
                        const SizedBox(height: 16),
                        DropdownButtonFormField<int>(
                          value: _driverId,
                          decoration: const InputDecoration(labelText: 'Chofer'),
                          items: data.users
                              .map(
                                (user) => DropdownMenuItem<int>(
                                  value: user.id,
                                  child: Text('${user.name} - ${user.role}'),
                                ),
                              )
                              .toList(growable: false),
                          onChanged: (value) => setState(() => _driverId = value),
                          validator: (value) =>
                              value == null ? 'Selecciona un chofer.' : null,
                        ),
                        const SizedBox(height: 16),
                        DropdownButtonFormField<int>(
                          value: _requestedById,
                          decoration: const InputDecoration(labelText: 'Solicitado por'),
                          items: data.users
                              .map(
                                (user) => DropdownMenuItem<int>(
                                  value: user.id,
                                  child: Text('${user.name} - ${user.role}'),
                                ),
                              )
                              .toList(growable: false),
                          onChanged: (value) => setState(() => _requestedById = value),
                          validator: (value) => value == null
                              ? 'Selecciona quien solicita el vehiculo.'
                              : null,
                        ),
                        const SizedBox(height: 16),
                        ListTile(
                          contentPadding: EdgeInsets.zero,
                          title: const Text('Fecha de salida'),
                          subtitle: Text(
                            DateFormat('dd/MM/yyyy HH:mm').format(_departureAt),
                          ),
                          trailing: FilledButton.tonal(
                            onPressed: _pickDepartureDateTime,
                            child: const Text('Editar'),
                          ),
                        ),
                        const SizedBox(height: 16),
                        TextFormField(
                          controller: _reasonController,
                          maxLines: 4,
                          decoration: const InputDecoration(labelText: 'Motivo'),
                          validator: (value) {
                            final text = value?.trim() ?? '';
                            if (text.isEmpty) {
                              return 'Describe el motivo de la salida.';
                            }
                            if (text.length > 255) {
                              return 'El motivo no puede superar 255 caracteres.';
                            }
                            return null;
                          },
                        ),
                      ],
                    ),
                  ),
                ];

                final rightColumn = <Widget>[
                  if (selectedVehicle != null)
                    VehicleCard(
                      vehicle: selectedVehicle,
                      subtitle: 'Unidad seleccionada para esta salida',
                    ),
                  if (selectedVehicle != null)
                    _InfoAlertCard(
                      title: 'Estado del vehiculo',
                      message: selectedVehicle.isDocumentationComplete
                          ? 'Documentacion completa. El vehiculo puede asignarse.'
                          : 'Documentacion ${selectedVehicle.documentationStatus}. No debe asignarse.',
                      isSuccess: selectedVehicle.isDocumentationComplete,
                    ),
                  if (selectedDriver != null)
                    _InfoAlertCard(
                      title: 'Validacion de chofer',
                      message: _driverMessage(selectedDriver, _departureAt),
                      isSuccess: selectedDriver.isLicenseValidAt(_departureAt),
                    ),
                  if (selectedRequester != null)
                    _InfoAlertCard(
                      title: 'Validacion de solicitante',
                      message: selectedRequester.canRequestVehicle
                          ? 'Este usuario puede solicitar vehiculos.'
                          : 'Este usuario no tiene permiso para solicitar vehiculos.',
                      isSuccess: selectedRequester.canRequestVehicle,
                    ),
                  AppCard(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.stretch,
                      children: <Widget>[
                        Text(
                          'Confirmacion',
                          style: Theme.of(context).textTheme.titleMedium,
                        ),
                        const SizedBox(height: 8),
                        const Text(
                          'Al guardar, la salida quedara disponible para checklist de salida desde la pantalla principal.',
                        ),
                        const SizedBox(height: 18),
                        AppButton(
                          label: _isSubmitting ? 'Guardando salida...' : 'Guardar salida',
                          isLoading: _isSubmitting,
                          onPressed: _submit,
                        ),
                      ],
                    ),
                  ),
                ];

                if (!isWide) {
                  return ListView(
                    children: <Widget>[
                      ...leftColumn,
                      const SizedBox(height: 16),
                      ...rightColumn.expand((item) => <Widget>[item, const SizedBox(height: 16)]),
                    ],
                  );
                }

                return Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: <Widget>[
                    Expanded(
                      flex: 6,
                      child: Column(children: leftColumn),
                    ),
                    const SizedBox(width: 16),
                    Expanded(
                      flex: 4,
                      child: Column(
                        children: rightColumn
                            .expand((item) => <Widget>[item, const SizedBox(height: 16)])
                            .toList(growable: false),
                      ),
                    ),
                  ],
                );
              },
            ),
          );
        },
        error: (error, _) => Center(child: Text(formatAppError(error))),
        loading: () => const Center(child: CircularProgressIndicator()),
      ),
    );
  }

  Future<void> _pickDepartureDateTime() async {
    final now = DateTime.now();
    final date = await showDatePicker(
      context: context,
      firstDate: now,
      lastDate: now.add(const Duration(days: 30)),
      initialDate: _departureAt.isBefore(now) ? now : _departureAt,
    );

    if (date == null || !mounted) {
      return;
    }

    final time = await showTimePicker(
      context: context,
      initialTime: TimeOfDay.fromDateTime(_departureAt),
    );

    if (time == null) {
      return;
    }

    setState(() {
      _departureAt = DateTime(
        date.year,
        date.month,
        date.day,
        time.hour,
        time.minute,
      );
    });
  }

  int? _findInitialRequesterId(List<CatalogUser> users, int? currentUserId) {
    for (final user in users) {
      if (user.id == currentUserId) {
        return user.id;
      }
    }

    return users.isEmpty ? null : users.first.id;
  }

  CatalogVehicle? _findVehicle(List<CatalogVehicle> vehicles, int? id) {
    if (id == null) {
      return null;
    }

    for (final vehicle in vehicles) {
      if (vehicle.id == id) {
        return vehicle;
      }
    }

    return null;
  }

  CatalogUser? _findUser(List<CatalogUser> users, int? id) {
    if (id == null) {
      return null;
    }

    for (final user in users) {
      if (user.id == id) {
        return user;
      }
    }

    return null;
  }

  String _driverMessage(CatalogUser user, DateTime departureAt) {
    if (!user.hasRegisteredLicense) {
      return 'El chofer no tiene licencia registrada.';
    }

    if (!user.isLicenseValidAt(departureAt)) {
      return 'La licencia del chofer esta vencida para la fecha seleccionada.';
    }

    return 'Licencia vigente para la fecha de salida.';
  }
}

class _InfoAlertCard extends StatelessWidget {
  const _InfoAlertCard({
    required this.title,
    required this.message,
    required this.isSuccess,
  });

  final String title;
  final String message;
  final bool isSuccess;

  @override
  Widget build(BuildContext context) {
    final background = isSuccess ? const Color(0xFFE8F4EE) : const Color(0xFFFCEAEA);
    final border = isSuccess ? const Color(0xFF2D7B52) : const Color(0xFFAF2A2A);

    return AppCard(
      padding: EdgeInsets.zero,
      child: Container(
        decoration: BoxDecoration(
          color: background,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: border),
        ),
        padding: const EdgeInsets.all(18),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: <Widget>[
            Text(title, style: Theme.of(context).textTheme.titleMedium),
            const SizedBox(height: 8),
            Text(message),
          ],
        ),
      ),
    );
  }
}
