import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../../core/layout/adaptive_page_scaffold.dart';
import '../../../core/network/network_error_formatter.dart';
import '../../../core/widgets/app_card.dart';
import '../../../core/widgets/empty_state_widget.dart';
import '../../../core/widgets/saico_brand_header.dart';
import '../../vehicle_requests/data/vehicle_request_repository.dart';
import '../../vehicle_requests/domain/vehicle_exit_models.dart';

class ChecklistDetailScreen extends ConsumerWidget {
  const ChecklistDetailScreen({
    required this.exitId,
    required this.type,
    super.key,
  });

  final String exitId;
  final String type;

  bool get _isDeparture => type == 'salida';

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final detailState = ref.watch(exitDetailProvider(exitId));

    return AdaptivePageScaffold(
      title: _isDeparture ? 'Ver checklist salida' : 'Ver checklist entrada',
      child: detailState.when(
        data: (detail) {
          final checklist =
              _isDeparture ? detail.exit.departureChecklist : detail.exit.arrivalChecklist;

          if (checklist == null || detail.exit.vehicle == null) {
            return const EmptyStateWidget(
              title: 'Checklist no disponible',
              message: 'No fue posible cargar el checklist solicitado.',
            );
          }

          return LayoutBuilder(
            builder: (context, constraints) {
              final wide = constraints.maxWidth >= 960;
              final summary = _SummaryPanel(
                exit: detail.exit,
                checklist: checklist,
              );
              final detailCard = _ChecklistDetailCard(
                checklist: checklist,
                showTools: _isDeparture,
                showDocuments: _isDeparture,
              );

              if (!wide) {
                return ListView(
                  children: <Widget>[
                    SaicoBrandHeader(
                      compact: true,
                      height: 210,
                      title: _isDeparture ? 'Ver checklist salida' : 'Ver checklist entrada',
                      subtitle:
                          'Consulta el registro guardado sin editar datos, siguiendo la misma regla del sistema principal.',
                    ),
                    const SizedBox(height: 16),
                    summary,
                    const SizedBox(height: 16),
                    detailCard,
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
                        SaicoBrandHeader(
                          compact: true,
                          height: 210,
                          title: _isDeparture ? 'Ver checklist salida' : 'Ver checklist entrada',
                          subtitle:
                              'Consulta el registro guardado sin editar datos, siguiendo la misma regla del sistema principal.',
                        ),
                        const SizedBox(height: 16),
                        summary,
                      ],
                    ),
                  ),
                  const SizedBox(width: 16),
                  Expanded(flex: 6, child: detailCard),
                ],
              );
            },
          );
        },
        error: (error, _) => Center(child: Text(formatAppError(error))),
        loading: () => const Center(child: CircularProgressIndicator()),
      ),
    );
  }
}

class _SummaryPanel extends StatelessWidget {
  const _SummaryPanel({
    required this.exit,
    required this.checklist,
  });

  final VehicleExitSummary exit;
  final VehicleChecklistSnapshot checklist;

  @override
  Widget build(BuildContext context) {
    return AppCard(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
          Text(
            'Detalle registrado',
            style: Theme.of(context).textTheme.titleLarge,
          ),
          const SizedBox(height: 16),
          _InfoRow(label: 'Vehiculo', value: exit.vehicle?.displayLabel ?? exit.vehicleLabel),
          _InfoRow(label: 'Chofer', value: exit.driverName),
          _InfoRow(label: 'Solicitado por', value: exit.requestedByName),
          if ((exit.departureAtLabel ?? '').isNotEmpty)
            _InfoRow(label: 'Fecha salida', value: exit.departureAtLabel!),
          _InfoRow(label: 'Nivel gasolina', value: checklist.condition.fuelLevel),
          _InfoRow(label: 'Kilometraje', value: checklist.condition.mileage.toString()),
        ],
      ),
    );
  }
}

class _ChecklistDetailCard extends StatelessWidget {
  const _ChecklistDetailCard({
    required this.checklist,
    required this.showTools,
    required this.showDocuments,
  });

  final VehicleChecklistSnapshot checklist;
  final bool showTools;
  final bool showDocuments;

  @override
  Widget build(BuildContext context) {
    return AppCard(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
          Text('Estado del vehiculo', style: Theme.of(context).textTheme.titleLarge),
          const SizedBox(height: 16),
          _InfoRow(label: 'Liquido limpia parabrisas', value: _normalize(checklist.condition.windshieldWasherFluid)),
          _InfoRow(label: 'Aceite', value: _normalize(checklist.condition.oilLevel)),
          _InfoRow(label: 'Anticongelante', value: _normalize(checklist.condition.coolantLevel)),
          _InfoRow(label: 'Liquido de frenos', value: _normalize(checklist.condition.brakeFluidLevel)),
          _InfoRow(label: 'Estado llantas', value: _normalize(checklist.condition.tireCondition)),
          _InfoRow(label: 'Delantera izquierda', value: _normalize(checklist.condition.frontLeftTirePressure)),
          _InfoRow(label: 'Delantera derecha', value: _normalize(checklist.condition.frontRightTirePressure)),
          _InfoRow(label: 'Trasera izquierda', value: _normalize(checklist.condition.rearLeftTirePressure)),
          _InfoRow(label: 'Trasera derecha', value: _normalize(checklist.condition.rearRightTirePressure)),
          _InfoRow(label: 'Limpio exterior', value: checklist.condition.cleanedExterior ? 'Si' : 'No'),
          _InfoRow(label: 'Limpio interior', value: checklist.condition.cleanedInterior ? 'Si' : 'No'),
          _InfoRow(
            label: 'Observaciones',
            value: (checklist.condition.observations ?? '').trim().isEmpty
                ? 'N/A'
                : checklist.condition.observations!,
          ),
          if (showTools && checklist.tools.isNotEmpty) ...<Widget>[
            const SizedBox(height: 20),
            Text('Herramientas', style: Theme.of(context).textTheme.titleMedium),
            const SizedBox(height: 12),
            Wrap(
              spacing: 10,
              runSpacing: 10,
              children: checklist.tools.map((tool) {
                return _BadgeCard(
                  label: _normalize(tool.tool),
                  value: tool.available ? 'Si' : 'No',
                  isPositive: tool.available,
                );
              }).toList(growable: false),
            ),
          ],
          if (showDocuments && checklist.documents.isNotEmpty) ...<Widget>[
            const SizedBox(height: 20),
            Text('Documentos', style: Theme.of(context).textTheme.titleMedium),
            const SizedBox(height: 12),
            Wrap(
              spacing: 10,
              runSpacing: 10,
              children: checklist.documents.map((document) {
                return _BadgeCard(
                  label: _normalize(document.document),
                  value: document.isValid ? 'Si' : 'No',
                  isPositive: document.isValid,
                );
              }).toList(growable: false),
            ),
          ],
          const SizedBox(height: 20),
          Text('Evidencias', style: Theme.of(context).textTheme.titleMedium),
          const SizedBox(height: 12),
          if (checklist.evidenceUrls.isEmpty)
            const Text('No hay evidencias fotograficas registradas.')
          else
            Wrap(
              spacing: 12,
              runSpacing: 12,
              children: checklist.evidenceUrls.map((url) {
                return ClipRRect(
                  borderRadius: BorderRadius.circular(16),
                  child: Image.network(
                    url,
                    width: 140,
                    height: 110,
                    fit: BoxFit.cover,
                    errorBuilder: (_, __, ___) => Container(
                      width: 140,
                      height: 110,
                      color: const Color(0xFFEAF2FF),
                      alignment: Alignment.center,
                      child: const Icon(Icons.image_not_supported_outlined),
                    ),
                  ),
                );
              }).toList(growable: false),
            ),
        ],
      ),
    );
  }

  String _normalize(String value) {
    return value.replaceAll('_', ' ');
  }
}

class _InfoRow extends StatelessWidget {
  const _InfoRow({
    required this.label,
    required this.value,
  });

  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 10),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
          SizedBox(
            width: 140,
            child: Text(
              label,
              style: Theme.of(context).textTheme.bodyMedium,
            ),
          ),
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

class _BadgeCard extends StatelessWidget {
  const _BadgeCard({
    required this.label,
    required this.value,
    required this.isPositive,
  });

  final String label;
  final String value;
  final bool isPositive;

  @override
  Widget build(BuildContext context) {
    final color = isPositive ? const Color(0xFF2D7B52) : const Color(0xFFAF2A2A);

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFD8E0DE)),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: <Widget>[
          Text(label),
          const SizedBox(width: 10),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
            decoration: BoxDecoration(
              color: color.withOpacity(0.12),
              borderRadius: BorderRadius.circular(999),
            ),
            child: Text(
              value,
              style: TextStyle(color: color),
            ),
          ),
        ],
      ),
    );
  }
}
