import 'package:dio/dio.dart';

String formatAppError(Object error) {
  if (error is DioException) {
    final responseData = error.response?.data;

    if (responseData is Map<String, dynamic>) {
      final message = responseData['message'] as String?;
      if (message != null && message.trim().isNotEmpty) {
        return message.trim();
      }

      final errors = responseData['errors'];
      if (errors is Map<String, dynamic>) {
        for (final value in errors.values) {
          if (value is List && value.isNotEmpty) {
            final first = value.first;
            if (first is String && first.trim().isNotEmpty) {
              return first.trim();
            }
          }
        }
      }
    }

    if (error.type == DioExceptionType.connectionError ||
        error.type == DioExceptionType.connectionTimeout ||
        error.type == DioExceptionType.receiveTimeout ||
        error.type == DioExceptionType.sendTimeout) {
      return 'No fue posible conectar con el servidor. Verifica que el backend este encendido y que el celular siga conectado a la misma red o por USB.';
    }

    return error.message?.trim().isNotEmpty == true
        ? error.message!.trim()
        : 'Ocurrio un error de comunicacion con el servidor.';
  }

  final message = error.toString().replaceFirst('Exception: ', '').replaceFirst('Bad state: ', '');
  return message.trim().isEmpty ? 'Ocurrio un error inesperado.' : message.trim();
}
