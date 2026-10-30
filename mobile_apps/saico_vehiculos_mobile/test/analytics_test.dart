import 'dart:convert';
import 'dart:io';
import 'package:dio/dio.dart';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:saico_vehiculos_mobile/core/theme/app_theme.dart';
import 'package:saico_vehiculos_mobile/features/analytics/data/analytics_repository.dart';
import 'package:saico_vehiculos_mobile/features/analytics/presentation/analytics_screen.dart';

Map<String, dynamic> fixture() =>
    jsonDecode(File('test/fixtures/kpis.json').readAsStringSync())
        as Map<String, dynamic>;

class FakeAnalytics extends AnalyticsRepository {
  FakeAnalytics(this.data) : super(Dio());
  final Map<String, dynamic> data;
  AnalyticsFilter? lastFilter;
  bool fail = false;
  @override
  Future<AnalyticsReport> fetch(
      AnalyticsFilter filter, CancelToken cancel) async {
    lastFilter = filter;
    if (fail) throw Exception('Error de prueba');
    return AnalyticsReport(data);
  }

  @override
  Future<List<AnalyticsVehicle>> vehicles(CancelToken cancel) async =>
      const [AnalyticsVehicle(2, 'Unidad de prueba')];
}

void main() {
  Future<void> mount(WidgetTester tester, FakeAnalytics repository, Size size,
      double scale) async {
    await tester.binding.setSurfaceSize(size);
    addTearDown(() => tester.binding.setSurfaceSize(null));
    await tester.pumpWidget(ProviderScope(
        overrides: [analyticsRepositoryProvider.overrideWithValue(repository)],
        child: MaterialApp(
            theme: AppTheme.light(),
            builder: (context, child) => MediaQuery(
                data: MediaQuery.of(context)
                    .copyWith(textScaler: TextScaler.linear(scale)),
                child: child!),
            home: const AnalyticsScreen())));
    await tester.pumpAndSettle();
  }

  for (final scale in [1.0, 1.6]) {
    testWidgets('Analytics tabs and warnings at scale $scale', (tester) async {
      await mount(
          tester, FakeAnalytics(fixture()), const Size(320, 700), scale);
      expect(find.text('Analítica'), findsOneWidget);
      expect(tester.takeException(), isNull);
      await tester.scrollUntilVisible(
          find.textContaining('Revisar duración:'), 250,
          scrollable: find.byType(Scrollable).last, maxScrolls: 30);
      await tester.pumpAndSettle();
      expect(tester.takeException(), isNull);
      await tester.tap(find.text('Tendencia'));
      await tester.pumpAndSettle();
      expect(find.text('Salidas por mes'), findsOneWidget);
      expect(find.text('Salidas por mes'), findsOneWidget);
      expect(tester.takeException(), isNull);
      await tester.tap(find.text('Calidad'));
      await tester.pumpAndSettle();
      expect(find.text('Revisión de datos'), findsOneWidget);
      expect(tester.takeException(), isNull);
    });
  }
  testWidgets('Operational duration and coverage are separate', (tester) async {
    final data = fixture();
    data['indicadores']['duracion_operativa'] = {
      'valor': 90,
      'registros_validos': 2,
      'intervalos_mayores_24h': 0
    };
    data['indicadores']['cobertura_operativa'] = {
      'valor': 25,
      'numerador': 2,
      'denominador': 8
    };
    await mount(tester, FakeAnalytics(data), const Size(393, 850), 1);
    expect(find.text('Tiempo real declarado'), findsOneWidget);
    expect(find.text('1.50 h'), findsOneWidget);
    expect(find.text('25.0 %'), findsOneWidget);
    expect(find.text('2 de 8 finalizadas'), findsOneWidget);
    expect(tester.takeException(), isNull);
  });
  testWidgets('Vehicle filter is sent and reset', (tester) async {
    final repository = FakeAnalytics(fixture());
    await mount(tester, repository, const Size(393, 850), 1);
    await tester.tap(find.text('Filtros · Todo el historial'));
    await tester.pumpAndSettle();
    await tester.tap(find.byType(DropdownButtonFormField<int>));
    await tester.pumpAndSettle();
    await tester.tap(find.text('Unidad de prueba').last);
    await tester.pumpAndSettle();
    expect(repository.lastFilter?.vehicleId, 2);
    await tester.tap(find.text('Filtros activos'));
    await tester.pumpAndSettle();
    await tester.tap(find.text('Limpiar filtros'));
    await tester.pumpAndSettle();
    expect(repository.lastFilter, emptyAnalyticsFilter);
  });
  testWidgets('Empty metrics and retry', (tester) async {
    final data = fixture();
    data['sin_datos'] = true;
    data['serie_mensual'] = [];
    data['calidad'] = {'conteos_por_alerta': []};
    for (final entry in (data['indicadores'] as Map).entries) {
      entry.value['valor'] = null;
    }
    final repository = FakeAnalytics(data)..fail = true;
    await mount(tester, repository, const Size(393, 850), 1);
    expect(find.text('Reintentar'), findsOneWidget);
    repository.fail = false;
    await tester.tap(find.text('Reintentar'));
    await tester.pumpAndSettle();
    expect(find.text('No hay salidas para estos filtros.'), findsOneWidget);
    expect(find.text('Sin datos'), findsWidgets);
    await tester.tap(find.text('Tendencia'));
    await tester.pumpAndSettle();
    expect(find.text('No hay datos para graficar.'), findsOneWidget);
  });
  test('Repository sends dates and vehicle with JSON accept header', () async {
    final dio = Dio(BaseOptions(baseUrl: 'http://localhost/api/mobile/v1'));
    RequestOptions? sent;
    dio.interceptors.add(InterceptorsWrapper(onRequest: (options, handler) {
      sent = options;
      handler.resolve(Response(
          requestOptions: options, data: {'data': fixture()}, statusCode: 200));
    }));
    await AnalyticsRepository(dio).fetch(
        (from: '2026-03-01', to: '2026-05-31', vehicleId: 2), CancelToken());
    expect(sent!.queryParameters,
        {'desde': '2026-03-01', 'hasta': '2026-05-31', 'vehiculo_id': 2});
    expect(sent!.headers['Accept'], 'application/json');
  });
}
