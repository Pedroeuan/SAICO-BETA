class AppConfig {
  const AppConfig._();

  static const String appName = 'SAICO Vehiculos';
  static const String apiBaseUrl = String.fromEnvironment(
    'API_BASE_URL',
    defaultValue: 'http://192.168.1.242:8000/api/mobile/v1',
  );
  static const Duration requestTimeout = Duration(seconds: 30);
  static const bool useMockData = bool.fromEnvironment(
    'USE_MOCK_DATA',
    defaultValue: false,
  );
}
