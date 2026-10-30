import 'package:flutter/material.dart';
import '../../../core/theme/app_colors.dart';
import '../../../core/theme/app_metrics.dart';
import '../../../core/widgets/app_card.dart';

/// Barras agrupadas de los conteos entregados por la API, sin completar meses.
class MonthlyChart extends StatefulWidget {
  const MonthlyChart({required this.months, super.key});
  final List<Map<String, dynamic>> months;
  @override
  State<MonthlyChart> createState() => _MonthlyChartState();
}

class _MonthlyChartState extends State<MonthlyChart> {
  String? _selected;
  @override
  Widget build(BuildContext context) {
    final max = widget.months.fold<double>(1, (max, month) {
      for (final key in ['salidas', 'finalizadas']) {
        final value = month[key];
        if (value is num && value > max) max = value.toDouble();
      }
      return max;
    });
    final selected = widget.months.where((m) => m['mes'] == _selected);
    return AppCard(
        child: Column(
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: [
        Text('Salidas por mes', style: Theme.of(context).textTheme.titleMedium),
        const SizedBox(height: 12),
        const Wrap(spacing: 16, runSpacing: 8, children: [
          _Legend('Salidas', AppColors.primary),
          _Legend('Finalizadas', AppColors.accentRed),
        ]),
        const SizedBox(height: 12),
        Text(
            selected.isEmpty
                ? 'Selecciona un mes para consultar valores'
                : '${selected.first['mes']} · ${selected.first['salidas'] ?? 'Sin datos'} salidas · ${selected.first['finalizadas'] ?? 'Sin datos'} finalizadas',
            semanticsLabel: selected.isEmpty
                ? null
                : 'Mes seleccionado: ${selected.first['mes']}, ${selected.first['salidas'] ?? 'Sin datos'} salidas, ${selected.first['finalizadas'] ?? 'Sin datos'} finalizadas'),
        const SizedBox(height: 16),
        if (widget.months.isEmpty)
          const Padding(
              padding: EdgeInsets.symmetric(vertical: 24),
              child: Text('No hay datos para graficar.')),
        if (widget.months.isNotEmpty) ...[
          Row(children: [
            const SizedBox(width: 76),
            Expanded(
                child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                  const Text('0'),
                  Text((max / 2).toStringAsFixed(1)),
                  Text(max.toStringAsFixed(0))
                ])),
          ]),
          const Divider(),
          ...widget.months.map((month) {
            final label = '${month['mes']}';
            return Semantics(
              button: true,
              selected: _selected == label,
              label:
                  '$label, ${month['salidas'] ?? 'Sin datos'} salidas, ${month['finalizadas'] ?? 'Sin datos'} finalizadas',
              child: Material(
                  color: _selected == label
                      ? AppColors.primarySoft
                      : Colors.transparent,
                  borderRadius: BorderRadius.circular(12),
                  child: InkWell(
                    borderRadius: BorderRadius.circular(12),
                    onTap: () => setState(() => _selected = label),
                    child: Padding(
                        padding: const EdgeInsets.symmetric(
                            vertical: 10, horizontal: 4),
                        child: Row(children: [
                          SizedBox(
                              width: 72,
                              child: Text(label,
                                  style:
                                      Theme.of(context).textTheme.labelMedium)),
                          Expanded(
                              child: Column(children: [
                            _bar(month['salidas'], max, AppColors.primary),
                            const SizedBox(height: 5),
                            _bar(
                                month['finalizadas'], max, AppColors.accentRed),
                          ])),
                        ])),
                  )),
            );
          }),
          const SizedBox(height: 8),
          const Text('Número de salidas · Fecha de salida programada',
              style: TextStyle(color: AppColors.slate, fontSize: 12)),
        ],
      ],
    ));
  }

  Widget _bar(dynamic value, double max, Color color) {
    if (value is! num) {
      return const Align(
          alignment: Alignment.centerLeft, child: Text('Sin datos'));
    }
    return LayoutBuilder(
        builder: (context, constraints) => Align(
              alignment: Alignment.centerLeft,
              child: AnimatedContainer(
                duration: MediaQuery.disableAnimationsOf(context)
                    ? Duration.zero
                    : AppMetrics.motion,
                width: constraints.maxWidth * (value / max).clamp(0, 1),
                height: 14,
                decoration: BoxDecoration(
                    color: color, borderRadius: BorderRadius.circular(4)),
              ),
            ));
  }
}

class _Legend extends StatelessWidget {
  const _Legend(this.label, this.color);
  final String label;
  final Color color;
  @override
  Widget build(BuildContext context) =>
      Row(mainAxisSize: MainAxisSize.min, children: [
        Container(
            width: 10,
            height: 10,
            decoration: BoxDecoration(
                color: color, borderRadius: BorderRadius.circular(3))),
        const SizedBox(width: 6),
        Flexible(child: Text(label)),
      ]);
}
