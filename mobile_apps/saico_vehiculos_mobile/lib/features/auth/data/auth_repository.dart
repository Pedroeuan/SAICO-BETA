import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../../core/config/app_config.dart';
import '../../../core/constants/api_endpoints.dart';
import '../../../core/network/api_client.dart';
import '../domain/current_user.dart';

final authRepositoryProvider = Provider<AuthRepository>((ref) {
  return AuthRepository(ref.watch(dioProvider));
});

class AuthRepository {
  AuthRepository(this._dio);

  final Dio _dio;

  Future<LoginResult> login({
    required String email,
    required String password,
    required String deviceName,
  }) async {
    if (AppConfig.useMockData) {
      return LoginResult(
        token: 'mock-mobile-token',
        user: const CurrentUser(
          id: 1,
          name: 'Francisco Cruz',
          email: 'francisco@saico.test',
          role: 'Administrador',
        ),
      );
    }

    final response = await _dio.post<Map<String, dynamic>>(
      ApiEndpoints.login,
      data: <String, Object?>{
        'email': email,
        'password': password,
        'device_name': deviceName,
      },
    );

    final body = response.data ?? <String, dynamic>{};
    final data = body['data'] as Map<String, dynamic>? ?? <String, dynamic>{};
    final user = data['user'] as Map<String, dynamic>? ??
        body['user'] as Map<String, dynamic>? ??
        <String, dynamic>{};

    return LoginResult(
      token: data['token'] as String? ?? body['token'] as String,
      user: CurrentUser.fromJson(user),
    );
  }

  Future<CurrentUser?> fetchProfile() async {
    if (AppConfig.useMockData) {
      return const CurrentUser(
        id: 1,
        name: 'Francisco Cruz',
        email: 'francisco@saico.test',
        role: 'Administrador',
      );
    }

    final response = await _dio.get<Map<String, dynamic>>(ApiEndpoints.me);
    final body = response.data ?? <String, dynamic>{};
    final user = body['data'] as Map<String, dynamic>? ??
        body['user'] as Map<String, dynamic>? ??
        <String, dynamic>{};

    if (user.isEmpty) {
      return null;
    }

    return CurrentUser.fromJson(user);
  }
}

class LoginResult {
  const LoginResult({
    required this.token,
    required this.user,
  });

  final String token;
  final CurrentUser user;
}
