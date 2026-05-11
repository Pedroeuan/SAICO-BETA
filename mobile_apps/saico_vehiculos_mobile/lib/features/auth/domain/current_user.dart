class CurrentUser {
  const CurrentUser({
    required this.id,
    required this.name,
    required this.email,
    required this.role,
  });

  final int id;
  final String name;
  final String email;
  final String role;

  factory CurrentUser.fromJson(Map<String, dynamic> json) {
    return CurrentUser(
      id: _asInt(json['id']) ?? 0,
      name: json['name'] as String? ?? 'Usuario',
      email: json['email'] as String? ?? '',
      role: json['rol'] as String? ?? 'Operativo',
    );
  }
}

int? _asInt(dynamic value) {
  if (value == null) {
    return null;
  }

  if (value is int) {
    return value;
  }

  if (value is num) {
    return value.toInt();
  }

  if (value is String) {
    final normalized = value.trim();
    if (normalized.isEmpty) {
      return null;
    }

    return int.tryParse(normalized) ?? double.tryParse(normalized)?.toInt();
  }

  return null;
}
