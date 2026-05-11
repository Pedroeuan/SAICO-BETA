import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../config/app_config.dart';
import 'dio_interceptor.dart';
import '../../features/auth/application/session_provider.dart';

final dioProvider = Provider<Dio>((ref) {
  final dio = Dio(
    BaseOptions(
      baseUrl: AppConfig.apiBaseUrl,
      connectTimeout: AppConfig.requestTimeout,
      receiveTimeout: AppConfig.requestTimeout,
    ),
  );

  ref.watch(sessionProvider);
  dio.interceptors.add(DioInterceptor(ref));

  return dio;
});
