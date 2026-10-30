import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/network/api_client.dart';

typedef AnalyticsFilter = ({String? from, String? to, int? vehicleId});
const emptyAnalyticsFilter = (from: null, to: null, vehicleId: null);

final analyticsRepositoryProvider =
    Provider((ref) => AnalyticsRepository(ref.watch(dioProvider)));
final analyticsReportProvider = FutureProvider.autoDispose
    .family<AnalyticsReport, AnalyticsFilter>((ref, filter) {
  final cancel = CancelToken();
  ref.onDispose(() => cancel.cancel());
  return ref.watch(analyticsRepositoryProvider).fetch(filter, cancel);
});
final analyticsVehiclesProvider =
    FutureProvider.autoDispose<List<AnalyticsVehicle>>((ref) {
  final cancel = CancelToken();
  ref.onDispose(() => cancel.cancel());
  return ref.watch(analyticsRepositoryProvider).vehicles(cancel);
});

class AnalyticsVehicle {
  const AnalyticsVehicle(this.id, this.label);
  final int id;
  final String label;
}

class AnalyticsReport {
  AnalyticsReport(this.data);
  final Map<String, dynamic> data;
  bool get isEmpty => data['sin_datos'] == true;
  Map<String, dynamic> metric(String name) => Map<String, dynamic>.from(
      (data['indicadores'] as Map)[name] as Map? ?? const {});
  List<Map<String, dynamic>> get months => (data['serie_mensual'] as List)
      .map((e) => Map<String, dynamic>.from(e as Map))
      .toList();
  Map<String, dynamic> get issues {
    final value = (data['calidad'] as Map)['conteos_por_alerta'];
    return value is Map ? Map<String, dynamic>.from(value) : {};
  }
}

class AnalyticsRepository {
  AnalyticsRepository(this.dio);
  final Dio dio;
  Future<AnalyticsReport> fetch(
      AnalyticsFilter filter, CancelToken cancel) async {
    final response = await dio.get<Map<String, dynamic>>('/analitica/kpis',
        options: Options(headers: {'Accept': 'application/json'}),
        cancelToken: cancel,
        queryParameters: {
          if (filter.from != null) 'desde': filter.from,
          if (filter.to != null) 'hasta': filter.to,
          if (filter.vehicleId != null) 'vehiculo_id': filter.vehicleId
        });
    return AnalyticsReport(
        Map<String, dynamic>.from(response.data!['data'] as Map));
  }

  Future<List<AnalyticsVehicle>> vehicles(CancelToken cancel) async {
    final response = await dio.get<Map<String, dynamic>>('/analitica/vehiculos',
        options: Options(headers: {'Accept': 'application/json'}),
        cancelToken: cancel);
    return (response.data!['data'] as List)
        .map((e) =>
            AnalyticsVehicle((e['id'] as num).toInt(), e['label'] as String))
        .toList();
  }
}
