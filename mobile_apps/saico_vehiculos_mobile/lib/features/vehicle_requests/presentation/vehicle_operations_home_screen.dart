import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../../core/layout/adaptive_page_scaffold.dart';
import '../../../core/network/network_error_formatter.dart';
import '../../../core/widgets/app_button.dart';
import '../../../core/widgets/app_card.dart';
import '../../../core/widgets/status_badge.dart';
import '../../auth/application/session_provider.dart';
import '../application/vehicle_operations_controller.dart';
import '../domain/vehicle_exit_models.dart';

class VehicleOperationsHomeScreen extends ConsumerWidget {
  const VehicleOperationsHomeScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final session = ref.watch(sessionProvider);
    final operationsState = ref.watch(vehicleOperationsControllerProvider);
    return DefaultTabController(
      length: 3,
      animationDuration: MediaQuery.disableAnimationsOf(context)
          ? Duration.zero
          : const Duration(milliseconds: 180),
      child: AdaptivePageScaffold(
        title: 'SAICO',
        actions: [
          TextButton.icon(
              onPressed: () => context.push('/analytics'),
              icon: const Icon(Icons.insights),
              label: const Text('Analítica'))
        ],
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Text('Hola, ${session.userName ?? 'operador'}',
                style: Theme.of(context).textTheme.titleLarge),
            const SizedBox(height: 12),
            TabBar(
              labelPadding: const EdgeInsets.symmetric(horizontal: 4),
              labelStyle: Theme.of(context).textTheme.labelMedium,
              tabs: [
                _tab(context, 'Solicitar', Icons.add_circle_outline),
                _tab(context, 'Salida y\nentrada', Icons.swap_horiz),
                _tab(context, 'Historial', Icons.history),
              ],
            ),
            const SizedBox(height: 16),
            Expanded(
              child: operationsState.when(
                data: (data) => TabBarView(children: [
                  _RequestTab(data: data),
                  _MovementTab(activeExit: data.activeExit),
                  _HistoryTab(exits: data.exits),
                ]),
                error: (error, _) => Center(child: Text(formatAppError(error))),
                loading: () => const Center(child: CircularProgressIndicator()),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _tab(BuildContext context, String label, IconData icon) {
    return Tab(
      height: MediaQuery.textScalerOf(context).scale(12) <= 15
          ? 80
          : 36 + MediaQuery.textScalerOf(context).scale(60),
      child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
        Icon(icon, size: 24),
        const SizedBox(height: 4),
        Text(label,
            textAlign: TextAlign.center,
            style: const TextStyle(fontSize: 12, height: 1.2)),
      ]),
    );
  }
}

class _RequestTab extends StatelessWidget {
  const _RequestTab({required this.data});
  final VehicleOperationsState data;

  @override
  Widget build(BuildContext context) {
    final hasActiveExit = data.activeExit != null;
    return ListView(
      key: const PageStorageKey('request-tab'),
      children: [
        AppCard(
            child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const _SectionTitle(
                icon: Icons.directions_car_outlined,
                text: 'Solicitar vehículo'),
            const SizedBox(height: 16),
            Text(hasActiveExit
                ? 'Tienes una salida activa. Finalízala antes de solicitar otro vehículo.'
                : '${data.vehicles.length} vehículos disponibles'),
            const SizedBox(height: 20),
            AppButton(
              icon: hasActiveExit ? Icons.swap_horiz : Icons.add,
              label:
                  hasActiveExit ? 'Ir a salida y entrada' : 'Nueva solicitud',
              onPressed: () {
                if (hasActiveExit) {
                  DefaultTabController.of(context).animateTo(1);
                } else {
                  context.push('/request');
                }
              },
            ),
          ],
        )),
      ],
    );
  }
}

class _MovementTab extends StatelessWidget {
  const _MovementTab({required this.activeExit});
  final VehicleExitSummary? activeExit;

  @override
  Widget build(BuildContext context) {
    final exit = activeExit;
    return ListView(
      key: const PageStorageKey('movement-tab'),
      children: [
        AppCard(
            child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const _SectionTitle(
                icon: Icons.fact_check_outlined, text: 'Salida y entrada'),
            const SizedBox(height: 16),
            if (exit == null) ...[
              const Text('No tienes una salida activa.'),
              const SizedBox(height: 20),
              AppButton(
                  label: 'Solicitar vehículo',
                  icon: Icons.add,
                  onPressed: () =>
                      DefaultTabController.of(context).animateTo(0)),
            ] else ...[
              Text(exit.vehicleLabel,
                  style: Theme.of(context).textTheme.titleMedium),
              const SizedBox(height: 8),
              Text('Chofer: ${exit.driverName}'),
              if (exit.departureAtLabel != null) ...[
                const SizedBox(height: 6),
                Text('Salida: ${exit.departureAtLabel}'),
              ],
              const SizedBox(height: 16),
              AppButton(
                icon: Icons.logout,
                label: exit.departureChecklistCompleted
                    ? 'Ver salida'
                    : 'Registrar salida',
                isSecondary: exit.departureChecklistCompleted,
                onPressed: () => context.push(exit.departureChecklistCompleted
                    ? '/checklists/view/salida/${exit.id}'
                    : '/checklists/departure/${exit.id}'),
              ),
              const SizedBox(height: 12),
              AppButton(
                icon: Icons.login,
                label: exit.arrivalChecklistCompleted
                    ? 'Ver entrada'
                    : 'Registrar entrada',
                onPressed: !exit.departureChecklistCompleted
                    ? null
                    : () => context.push(exit.arrivalChecklistCompleted
                        ? '/checklists/view/entrada/${exit.id}'
                        : '/checklists/arrival/${exit.id}'),
              ),
              if (!exit.departureChecklistCompleted) ...[
                const SizedBox(height: 8),
                const Text(
                    'Registra primero la salida para habilitar la entrada.'),
              ],
            ],
          ],
        )),
      ],
    );
  }
}

class _HistoryTab extends StatelessWidget {
  const _HistoryTab({required this.exits});
  final List<VehicleExitSummary> exits;

  @override
  Widget build(BuildContext context) {
    return ListView.separated(
      key: const PageStorageKey('history-tab'),
      itemCount: exits.isEmpty ? 1 : exits.length,
      separatorBuilder: (_, __) => const SizedBox(height: 12),
      itemBuilder: (context, index) {
        if (exits.isEmpty) {
          return const AppCard(
              child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _SectionTitle(icon: Icons.history, text: 'Historial de salidas'),
              SizedBox(height: 16),
              Text('Aún no hay salidas registradas.'),
            ],
          ));
        }
        final exit = exits[index];
        final route = exit.arrivalChecklistCompleted
            ? '/checklists/view/entrada/${exit.id}'
            : exit.departureChecklistCompleted
                ? '/checklists/view/salida/${exit.id}'
                : '/checklists/departure/${exit.id}';
        return AppCard(
            child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            _SectionTitle(
                icon: Icons.directions_car_outlined, text: exit.vehicleLabel),
            const SizedBox(height: 8),
            Text('${exit.folio} · ${exit.driverName}'),
            const SizedBox(height: 8),
            Wrap(spacing: 12, runSpacing: 8, children: [
              StatusBadge(label: exit.status),
              if (exit.departureAtLabel != null) Text(exit.departureAtLabel!),
            ]),
            const SizedBox(height: 12),
            AppButton(
                label: exit.departureChecklistCompleted
                    ? 'Ver registro'
                    : 'Registrar salida',
                icon: Icons.assignment_outlined,
                isSecondary: true,
                onPressed: () => context.push(route)),
          ],
        ));
      },
    );
  }
}

class _SectionTitle extends StatelessWidget {
  const _SectionTitle({required this.icon, required this.text});
  final IconData icon;
  final String text;

  @override
  Widget build(BuildContext context) => Row(children: [
        Icon(icon, color: Theme.of(context).colorScheme.primary),
        const SizedBox(width: 10),
        Expanded(
            child: Text(text, style: Theme.of(context).textTheme.titleMedium)),
      ]);
}
