import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../../core/layout/adaptive_page_scaffold.dart';
import '../../../core/network/network_error_formatter.dart';
import '../../../core/widgets/app_button.dart';
import '../../../core/widgets/app_card.dart';
import '../../../core/widgets/empty_state_widget.dart';
import '../../../core/widgets/saico_brand_header.dart';
import '../../../core/widgets/status_badge.dart';
import '../../../core/widgets/vehicle_card.dart';
import '../../auth/application/session_provider.dart';
import '../application/vehicle_operations_controller.dart';
import '../domain/vehicle_exit_models.dart';

class VehicleOperationsHomeScreen extends ConsumerWidget {
  const VehicleOperationsHomeScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final session = ref.watch(sessionProvider);
    final operationsState = ref.watch(vehicleOperationsControllerProvider);

    return AdaptivePageScaffold(
      title: 'Salidas y checklists',
      child: operationsState.when(
        data: (data) {
          final activeExit = data.activeExit;

          return Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: <Widget>[
              Text(
                'Hola, ${session.userName ?? 'operador'}',
                style: Theme.of(context).textTheme.headlineSmall,
              ),
              const SizedBox(height: 8),
              Text(
                'Administra las salidas, el checklist de salida y el checklist de entrada desde una vista alineada al modulo web.',
                style: Theme.of(context).textTheme.bodyLarge,
              ),
              const SizedBox(height: 24),
              Expanded(
                child: LayoutBuilder(
                  builder: (context, constraints) {
                    final wide = constraints.maxWidth >= 920;
                    final hero = const SaicoBrandHeader(
                      compact: true,
                      height: 230,
                      title: 'Modulo gestion de vehiculos',
                      subtitle:
                          'Consulta salidas activas, registra solicitudes y completa checklists con la misma logica del sistema principal.',
                    );
                    final summary = _SummaryCard(
                      role: session.currentUser?.role ?? 'Operativo',
                      activeExits: data.exits.where((item) => item.status == 'activo').length,
                      availableVehicles: data.vehicles.length,
                    );
                    final focalCard = activeExit != null
                        ? _ActiveExitCard(activeExit: activeExit)
                        : _NoActiveExitCard(
                            onCreate: () {
                              HapticFeedback.selectionClick();
                              context.push('/request');
                            },
                          );
                    final history = _HistorySection(exits: data.exits);

                    if (!wide) {
                      return ListView(
                        children: <Widget>[
                          hero,
                          const SizedBox(height: 16),
                          summary,
                          const SizedBox(height: 16),
                          focalCard,
                          const SizedBox(height: 16),
                          history,
                        ],
                      );
                    }

                      return Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: <Widget>[
                          Expanded(
                            flex: 4,
                            child: Column(
                              children: <Widget>[
                                hero,
                                const SizedBox(height: 16),
                                summary,
                                const SizedBox(height: 16),
                                focalCard,
                            ],
                          ),
                        ),
                        const SizedBox(width: 16),
                        Expanded(
                          flex: 6,
                          child: history,
                        ),
                      ],
                    );
                  },
                ),
              ),
            ],
          );
        },
        error: (error, _) => Center(child: Text(formatAppError(error))),
        loading: () => const Center(child: CircularProgressIndicator()),
      ),
    );
  }
}

class _SummaryCard extends StatelessWidget {
  const _SummaryCard({
    required this.role,
    required this.activeExits,
    required this.availableVehicles,
  });

  final String role;
  final int activeExits;
  final int availableVehicles;

  @override
  Widget build(BuildContext context) {
    return AppCard(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
          Row(
            children: <Widget>[
              Container(
                width: 42,
                height: 42,
                decoration: BoxDecoration(
                  color: const Color(0xFFEAF2FF),
                  borderRadius: BorderRadius.circular(14),
                ),
                child: const Icon(Icons.dashboard_customize_outlined, color: Color(0xFF0D6EFD)),
              ),
              const SizedBox(width: 12),
              Text('Resumen operativo', style: Theme.of(context).textTheme.titleLarge),
            ],
          ),
          const SizedBox(height: 16),
          Text('Rol actual: $role'),
          const SizedBox(height: 8),
          Text('Salidas activas: $activeExits'),
          const SizedBox(height: 8),
          Text('Vehiculos disponibles: $availableVehicles'),
        ],
      ),
    );
  }
}

class _ActiveExitCard extends StatelessWidget {
  const _ActiveExitCard({required this.activeExit});

  final VehicleExitSummary activeExit;

  @override
  Widget build(BuildContext context) {
    return AppCard(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
          Text('Vehiculo activo', style: Theme.of(context).textTheme.titleLarge),
          const SizedBox(height: 16),
          if (activeExit.vehicle != null)
            VehicleCard(
              vehicle: activeExit.vehicle!,
              subtitle: 'Hora de salida: ${activeExit.departureAtLabel ?? 'Sin dato'}',
              trailing: StatusBadge(label: activeExit.status),
            ),
          const SizedBox(height: 12),
          Text('Chofer: ${activeExit.driverName}'),
          const SizedBox(height: 6),
          Text('Solicitado por: ${activeExit.requestedByName}'),
          if ((activeExit.reason ?? '').isNotEmpty) ...<Widget>[
            const SizedBox(height: 6),
            Text('Motivo: ${activeExit.reason}'),
          ],
          const SizedBox(height: 16),
          Wrap(
            spacing: 10,
            runSpacing: 10,
            children: <Widget>[
              AppButton(
                label: activeExit.departureChecklistCompleted
                    ? 'Ver checklist salida'
                    : 'Registrar checklist salida',
                isSecondary: true,
                onPressed: () => context.push(
                  activeExit.departureChecklistCompleted
                      ? '/checklists/view/salida/${activeExit.id}'
                      : '/checklists/departure/${activeExit.id}',
                ),
              ),
              if (activeExit.departureChecklistCompleted)
                AppButton(
                  label: activeExit.arrivalChecklistCompleted
                      ? 'Ver checklist entrada'
                      : 'Registrar checklist entrada',
                  onPressed: () => context.push(
                    activeExit.arrivalChecklistCompleted
                        ? '/checklists/view/entrada/${activeExit.id}'
                        : '/checklists/arrival/${activeExit.id}',
                  ),
                ),
            ],
          ),
        ],
      ),
    );
  }
}

class _NoActiveExitCard extends StatelessWidget {
  const _NoActiveExitCard({required this.onCreate});

  final VoidCallback onCreate;

  @override
  Widget build(BuildContext context) {
    return AppCard(
      child: EmptyStateWidget(
        title: 'No hay vehiculo asignado',
        message: 'Cuando no exista una salida activa puedes generar una nueva solicitud desde aqui.',
        action: AppButton(
          label: 'Nueva solicitud',
          onPressed: onCreate,
        ),
      ),
    );
  }
}

class _HistorySection extends StatelessWidget {
  const _HistorySection({required this.exits});

  final List<VehicleExitSummary> exits;

  @override
  Widget build(BuildContext context) {
    return AppCard(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
          Text('Ultimas salidas', style: Theme.of(context).textTheme.titleLarge),
          const SizedBox(height: 16),
          if (exits.isEmpty)
            const EmptyStateWidget(
              title: 'Sin historial',
              message: 'Las salidas registradas apareceran aqui para consulta rapida.',
            ),
          ...exits.take(5).map((item) {
            final route = item.arrivalChecklistCompleted
                ? '/checklists/view/entrada/${item.id}'
                : item.departureChecklistCompleted
                    ? '/checklists/view/salida/${item.id}'
                    : '/checklists/departure/${item.id}';

            return ListTile(
              contentPadding: const EdgeInsets.symmetric(vertical: 6),
              title: Text(item.vehicleLabel),
              subtitle: Text('${item.driverName} - ${item.folio}'),
              trailing: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.end,
                children: <Widget>[
                  StatusBadge(label: item.status),
                  const SizedBox(height: 6),
                  Text(item.departureAtLabel ?? ''),
                ],
              ),
              onTap: () => context.push(route),
            );
          }),
        ],
      ),
    );
  }
}
