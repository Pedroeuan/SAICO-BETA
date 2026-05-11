class ApiEndpoints {
  static const String login = '/auth/login';
  static const String me = '/auth/me';
  static const String logout = '/auth/logout';
  static const String availableVehicles = '/catalogos/vehiculos-disponibles';
  static const String operationalUsers = '/catalogos/usuarios-operativos';
  static const String exits = '/salidas';
  static const String activeExit = '/salidas/activa';
  static const String history = '/salidas/historial';

  static String departureChecklist(String exitId) => '/salidas/$exitId/checklist-salida';
  static String arrivalChecklist(String exitId) => '/salidas/$exitId/checklist-entrada';
  static String exitDetail(String exitId) => '/salidas/$exitId';
}
