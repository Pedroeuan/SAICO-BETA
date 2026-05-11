import 'package:flutter/material.dart';

import '../theme/app_colors.dart';

class StatusBadge extends StatelessWidget {
  const StatusBadge({
    required this.label,
    super.key,
  });

  final String label;

  @override
  Widget build(BuildContext context) {
    final tone = _toneFor(label.toLowerCase());

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: tone.background,
        borderRadius: BorderRadius.circular(999),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: tone.foreground,
          fontSize: 12,
          fontWeight: FontWeight.w500,
        ),
      ),
    );
  }

  _BadgeTone _toneFor(String value) {
    if (value.contains('disponible')) {
      return const _BadgeTone(AppColors.success, Color(0xFFE7F5EE));
    }
    if (value.contains('ocupado') || value.contains('activo') || value.contains('asignado')) {
      return const _BadgeTone(AppColors.info, Color(0xFFE6F0FB));
    }
    if (value.contains('ruta') || value.contains('pendiente')) {
      return const _BadgeTone(AppColors.warning, Color(0xFFFFF3DB));
    }
    return const _BadgeTone(AppColors.danger, Color(0xFFFCE8E8));
  }
}

class _BadgeTone {
  const _BadgeTone(this.foreground, this.background);

  final Color foreground;
  final Color background;
}
