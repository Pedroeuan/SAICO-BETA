import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import '../../../core/layout/adaptive_page_scaffold.dart';
import '../../../core/network/network_error_formatter.dart';
import '../../../core/widgets/app_card.dart';
import '../data/analytics_repository.dart';
import 'monthly_chart.dart';

class AnalyticsScreen extends ConsumerStatefulWidget {
  const AnalyticsScreen({super.key});
  @override
  ConsumerState<AnalyticsScreen> createState() => _AnalyticsScreenState();
}

class _AnalyticsScreenState extends ConsumerState<AnalyticsScreen> {
  AnalyticsFilter _filter = emptyAnalyticsFilter;
  Future<void> _pickDates() async {
    final range = await showDateRangePicker(
        context: context,
        firstDate: DateTime(2000),
        lastDate: DateTime.now(),
        initialDateRange: _filter.from == null
            ? null
            : DateTimeRange(
                start: DateTime.parse(_filter.from!),
                end: DateTime.parse(_filter.to!)),
        helpText: 'Periodo por fecha de salida');
    if (!mounted || range == null) return;
    setState(() => _filter = (
          from: DateFormat('yyyy-MM-dd').format(range.start),
          to: DateFormat('yyyy-MM-dd').format(range.end),
          vehicleId: _filter.vehicleId
        ));
  }

  @override
  Widget build(BuildContext context) {
    final report = ref.watch(analyticsReportProvider(_filter));
    ref.watch(analyticsVehiclesProvider);
    return DefaultTabController(
        length: 3,
        animationDuration: MediaQuery.disableAnimationsOf(context)
            ? Duration.zero
            : const Duration(milliseconds: 180),
        child: AdaptivePageScaffold(
          title: 'Analítica',
          actions: [
            IconButton(
                tooltip: 'Actualizar',
                icon: const Icon(Icons.refresh),
                onPressed: () {
                  ref.invalidate(analyticsReportProvider(_filter));
                  ref.invalidate(analyticsVehiclesProvider);
                })
          ],
          child:
              Column(crossAxisAlignment: CrossAxisAlignment.stretch, children: [
            Align(
                alignment: Alignment.centerLeft,
                child: TextButton.icon(
                  icon: const Icon(Icons.filter_alt_outlined),
                  label: Text(_filter.from == null && _filter.vehicleId == null
                      ? 'Filtros · Todo el historial'
                      : 'Filtros activos'),
                  onPressed: _showFilters,
                )),
            TabBar(
                labelPadding: const EdgeInsets.symmetric(horizontal: 2),
                tabs: [
                  _tab('Resumen', Icons.dashboard_outlined),
                  _tab('Tendencia', Icons.bar_chart),
                  _tab('Calidad', Icons.fact_check_outlined),
                ]),
            const SizedBox(height: 12),
            Expanded(
                child: report.when(
              loading: () => const Center(child: CircularProgressIndicator()),
              error: (error, _) => ListView(children: [
                Text(formatAppError(error)),
                const SizedBox(height: 12),
                FilledButton.icon(
                    onPressed: () =>
                        ref.invalidate(analyticsReportProvider(_filter)),
                    icon: const Icon(Icons.refresh),
                    label: const Text('Reintentar'))
              ]),
              data: (data) => TabBarView(children: [
                _Summary(report: data),
                _Trend(report: data),
                _Quality(report: data),
              ]),
            )),
          ]),
        ));
  }

  Widget _tab(String text, IconData icon) => Tab(
      height: MediaQuery.textScalerOf(context).scale(12) <= 15
          ? 70
          : 36 + MediaQuery.textScalerOf(context).scale(60),
      child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
        Icon(icon, size: 22),
        const SizedBox(height: 4),
        Text(text,
            textAlign: TextAlign.center,
            style: const TextStyle(fontSize: 12, height: 1.2))
      ]));

  Future<void> _showFilters() async {
    // A dedicated scrollable sheet keeps the dashboard compact on small screens.
    await showModalBottomSheet<void>(
        context: context,
        isScrollControlled: true,
        builder: (sheetContext) => Consumer(
            builder: (context, ref, _) => SafeArea(
                  child: Padding(
                      padding: const EdgeInsets.all(20),
                      child: SingleChildScrollView(
                          child: Column(
                              mainAxisSize: MainAxisSize.min,
                              crossAxisAlignment: CrossAxisAlignment.stretch,
                              children: [
                            Text('Filtrar indicadores',
                                style: Theme.of(context).textTheme.titleLarge),
                            const SizedBox(height: 16),
                            Text(
                                'Fecha de salida: ${_filter.from == null ? 'todo el historial' : '${_filter.from} a ${_filter.to}'}'),
                            TextButton.icon(
                                icon: const Icon(Icons.date_range),
                                label: const Text('Elegir periodo'),
                                onPressed: () {
                                  Navigator.pop(sheetContext);
                                  _pickDates();
                                }),
                            const SizedBox(height: 12),
                            ref.watch(analyticsVehiclesProvider).when(
                                  data: (options) =>
                                      DropdownButtonFormField<int>(
                                    value: options.any(
                                            (v) => v.id == _filter.vehicleId)
                                        ? _filter.vehicleId
                                        : null,
                                    isExpanded: true,
                                    itemHeight: null,
                                    hint: const Text('Todos los visibles'),
                                    decoration: const InputDecoration(
                                        labelText: 'Vehículo'),
                                    items: [
                                      const DropdownMenuItem<int>(
                                          value: null,
                                          child: Text('Todos los visibles')),
                                      ...options.map((v) => DropdownMenuItem(
                                          value: v.id, child: Text(v.label)))
                                    ],
                                    onChanged: (id) {
                                      setState(() => _filter = (
                                            from: _filter.from,
                                            to: _filter.to,
                                            vehicleId: id
                                          ));
                                      Navigator.pop(sheetContext);
                                    },
                                  ),
                                  loading: () =>
                                      const Text('Cargando vehículos…'),
                                  error: (_, __) => TextButton.icon(
                                      icon: const Icon(Icons.refresh),
                                      label: const Text(
                                          'Reintentar carga de vehículos'),
                                      onPressed: () {
                                        ref.invalidate(
                                            analyticsVehiclesProvider);
                                        Navigator.pop(sheetContext);
                                      }),
                                ),
                            const SizedBox(height: 16),
                            OutlinedButton.icon(
                                icon: const Icon(Icons.filter_alt_off),
                                label: const Text('Limpiar filtros'),
                                onPressed: () {
                                  setState(
                                      () => _filter = emptyAnalyticsFilter);
                                  Navigator.pop(sheetContext);
                                }),
                          ]))),
                )));
  }
}

String _number(dynamic value, {int decimals = 0}) => value == null
    ? 'Sin datos'
    : NumberFormat.decimalPatternDigits(
            locale: 'es_MX', decimalDigits: decimals)
        .format(value);

class _Summary extends StatelessWidget {
  const _Summary({required this.report});
  final AnalyticsReport report;
  @override
  Widget build(BuildContext context) {
    final duration = report.metric('duracion_promedio');
    final operational = report.metric('duracion_operativa');
    final operationalCoverage = report.metric('cobertura_operativa');
    final km = report.metric('kilometros_registrados');
    final coverage = report.metric('cobertura_checklists');
    final cards = <Widget>[
      _Metric(
          icon: Icons.timer_outlined,
          label: 'Tiempo real declarado',
          value: operational['valor'] == null
              ? 'Sin datos'
              : '${_number((operational['valor'] as num) / 60, decimals: 2)} h',
          detail:
              '${_number(operational['registros_validos'])} salidas con horas válidas'),
      _Metric(
          icon: Icons.fact_check_outlined,
          label: 'Cobertura de horas reales',
          coverage: operationalCoverage['valor'] as num?,
          value: operationalCoverage['valor'] == null
              ? 'Sin datos'
              : '${_number(operationalCoverage['valor'], decimals: 1)} %',
          detail:
              '${_number(operationalCoverage['numerador'])} de ${_number(operationalCoverage['denominador'])} finalizadas'),
      _Metric(
          icon: Icons.route,
          label: 'Salidas',
          value: _number(report.metric('salidas_total')['valor'])),
      _Metric(
          icon: Icons.check_circle_outline,
          label: 'Finalizadas',
          value: _number(report.metric('salidas_finalizadas')['valor'])),
      _Metric(
          icon: Icons.directions_car_outlined,
          label: 'Activas',
          value: _number(report.metric('salidas_activas')['valor'])),
      _Metric(
          icon: Icons.speed,
          label: 'Kilómetros',
          value: km['valor'] == null
              ? 'Sin datos'
              : '${_number(km['valor'], decimals: 1)} km',
          detail:
              '${_number(km['registros_validos'])} válidos · ${_number(km['registros_excluidos'])} excluidos'),
      _Metric(
          icon: Icons.schedule,
          label: 'Intervalo administrativo',
          value: duration['valor'] == null
              ? 'Sin datos'
              : '${_number((duration['valor'] as num) / 60, decimals: 2)} h',
          detail:
              '${_number(duration['registros_validos'])} válidos · ${_number(duration['registros_excluidos'])} excluidos'),
      _Metric(
          icon: Icons.checklist,
          label: 'Cobertura de checklists',
          coverage: coverage['valor'] as num?,
          value: coverage['valor'] == null
              ? 'Sin datos'
              : '${_number(coverage['valor'], decimals: 1)} %',
          detail:
              '${_number(coverage['numerador'])} de ${_number(coverage['denominador'])} finalizadas con ambos formularios'),
    ];
    return ListView(children: [
      Text(report.data['alcance'] == 'todas_las_salidas'
          ? 'Todas las salidas visibles'
          : 'Salidas como chofer o solicitante'),
      if (report.isEmpty) ...[
        const SizedBox(height: 12),
        const Text('No hay salidas para estos filtros.')
      ],
      const SizedBox(height: 12),
      LayoutBuilder(builder: (context, constraints) {
        final twoColumns = constraints.maxWidth >= 320 &&
            MediaQuery.textScalerOf(context).scale(14) <= 18;
        final width =
            twoColumns ? (constraints.maxWidth - 12) / 2 : constraints.maxWidth;
        return Wrap(
            spacing: 12,
            runSpacing: 12,
            children: cards
                .map((card) => SizedBox(width: width, child: card))
                .toList());
      }),
      const SizedBox(height: 12),
      if ((operational['intervalos_mayores_24h'] as num? ?? 0) > 0)
        AppCard(
            child: Text(
                '${operational['intervalos_mayores_24h']} duraciones reales superan 24 horas; revisar su captura. Estan incluidas en el promedio.')),
      const _IndicatorInfo(),
      if (duration['requiere_revision'] == true)
        AppCard(
            backgroundColor: const Color(0xFFFFF3D6),
            child: Text(
                'Revisar duración: ${duration['intervalos_mayores_24h']} intervalos superan 24 horas y están incluidos en el promedio.')),
    ]);
  }
}

class _Metric extends StatelessWidget {
  const _Metric(
      {required this.icon,
      required this.label,
      required this.value,
      this.detail,
      this.coverage});
  final IconData icon;
  final String label, value;
  final String? detail;
  final num? coverage;
  @override
  Widget build(BuildContext context) => AppCard(
          child:
              Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Icon(icon, color: Theme.of(context).colorScheme.primary),
        const SizedBox(height: 8),
        Text(label),
        const SizedBox(height: 8),
        Text(value, style: Theme.of(context).textTheme.titleLarge),
        if (coverage != null) ...[
          const SizedBox(height: 8),
          Tooltip(
              message: '$label: $value${detail == null ? '' : '\n$detail'}',
              triggerMode: TooltipTriggerMode.tap,
              child: Semantics(
                  label: '$label: $value',
                  child: LinearProgressIndicator(
                      value: (coverage! / 100).clamp(0, 1).toDouble(),
                      minHeight: 8,
                      borderRadius: BorderRadius.circular(4)))),
          const SizedBox(height: 4),
          const Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text('0 %', style: TextStyle(fontSize: 11)),
                Text('100 %', style: TextStyle(fontSize: 11))
              ]),
        ],
        if (detail != null) ...[
          const SizedBox(height: 6),
          Text(detail!, style: Theme.of(context).textTheme.bodySmall)
        ],
      ]));
}

class _Trend extends StatelessWidget {
  const _Trend({required this.report});
  final AnalyticsReport report;
  @override
  Widget build(BuildContext context) {
    return ListView(children: [
      MonthlyChart(months: report.months),
      const SizedBox(height: 12),
      const Text('Se muestran únicamente los meses con registros.'),
    ]);
  }
}

class _IndicatorInfo extends StatelessWidget {
  const _IndicatorInfo();
  @override
  Widget build(BuildContext context) => TextButton.icon(
        icon: const Icon(Icons.info_outline),
        label: const Text('Cómo leer los indicadores'),
        onPressed: () => showModalBottomSheet<void>(
            context: context,
            isScrollControlled: true,
            builder: (context) => const SafeArea(
                    child: SingleChildScrollView(
                  padding: EdgeInsets.all(24),
                  child: Text(
                      'Tiempo real declarado: horas capturadas por el usuario.\n\n'
                      'Intervalo administrativo: salida registrada a cierre; no representa el tiempo real de viaje.\n\n'
                      'Cobertura de horas reales: finalizadas con horas reales válidas.\n\n'
                      'Cobertura de checklists: finalizadas con ambos formularios; no garantiza campos completos.\n\n'
                      'El periodo se aplica a la salida programada. Los valores ausentes se muestran como Sin datos.'),
                ))),
      );
}

class _Quality extends StatelessWidget {
  const _Quality({required this.report});
  final AnalyticsReport report;
  @override
  Widget build(BuildContext context) => ListView(children: [
        Text('Revisión de datos',
            style: Theme.of(context).textTheme.titleMedium),
        const SizedBox(height: 8),
        const Text(
            'Una salida puede tener varias alertas. Los conteos no se suman como salidas distintas.'),
        const SizedBox(height: 16),
        if (report.issues.isEmpty)
          Text(report.isEmpty
              ? 'No hay registros para revisar.'
              : 'Sin alertas en los controles aplicados.'),
        ...report.issues.entries.map((entry) => Padding(
            padding: const EdgeInsets.only(bottom: 12),
            child: AppCard(
                child: Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                  const Icon(Icons.info_outline),
                  const SizedBox(width: 10),
                  Expanded(
                      child: Text(
                          '${_issueLabel(entry.key)}: ${_number(entry.value)}')),
                ])))),
      ]);
}

String _issueLabel(String code) {
  const labels = {
    'duracion_mayor_24h_revisar': 'Intervalos mayores a 24 horas',
    'duracion_no_positiva': 'Duraciones cero o negativas',
    'activa_mayor_30_dias': 'Salidas activas por más de 30 días',
    'solicitud_registrada_despues_de_salida':
        'Solicitudes registradas después de la salida',
    'duracion_guardada_difiere': 'Duración guardada distinta de las fechas',
    'duracion_guardada_ausente_o_invalida': 'Duración guardada sin dato válido',
    'fecha_creacion_ausente_o_invalida': 'Fecha de solicitud inválida',
    'fecha_salida_ausente_o_invalida': 'Fecha de salida inválida',
    'fecha_regreso_ausente_o_invalida': 'Fecha de regreso inválida',
    'fecha_regreso_invalida': 'Fecha de regreso inválida',
    'fecha_salida_futura': 'Fechas de salida futuras',
    'fecha_regreso_futura': 'Fechas de regreso futuras',
    'activa_con_regreso': 'Salidas activas con regreso registrado',
    'kilometraje_decreciente': 'Kilometraje de regreso menor al de salida',
    'kilometraje_sin_cambio': 'Kilometraje sin cambio',
    'vehiculo_inexistente': 'Vehículo no encontrado',
    'chofer_inexistente': 'Chofer no encontrado',
    'solicitante_inexistente': 'Solicitante no encontrado',
    'estado_desconocido': 'Estado de salida no reconocido',
  };
  return labels[code] ?? code.replaceAll('_', ' ');
}
