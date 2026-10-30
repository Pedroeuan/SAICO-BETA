import 'package:flutter/material.dart';

class AppButton extends StatelessWidget {
  const AppButton({
    required this.label,
    this.onPressed,
    this.icon,
    this.isLoading = false,
    this.isSecondary = false,
    super.key,
  });

  final String label;
  final VoidCallback? onPressed;
  final IconData? icon;
  final bool isLoading;
  final bool isSecondary;

  @override
  Widget build(BuildContext context) {
    final child = Row(
      mainAxisAlignment: MainAxisAlignment.center,
      mainAxisSize: MainAxisSize.min,
      children: <Widget>[
        if (isLoading)
          const SizedBox(
            width: 18,
            height: 18,
            child: CircularProgressIndicator(strokeWidth: 2),
          )
        else ...<Widget>[
          Icon(icon ?? Icons.check_circle_outline, size: 20),
          const SizedBox(width: 8),
        ],
        if (isLoading) const SizedBox(width: 8),
        Flexible(child: Text(label, textAlign: TextAlign.center)),
      ],
    );

    if (isSecondary) {
      return OutlinedButton(
        onPressed: isLoading ? null : onPressed,
        child: child,
      );
    }

    return ElevatedButton(
      onPressed: isLoading ? null : onPressed,
      child: child,
    );
  }
}
