import 'package:flutter/material.dart';

import '../theme/app_colors.dart';
import '../theme/app_text_styles.dart';

class EmptyStateWidget extends StatelessWidget {
  const EmptyStateWidget({
    required this.title,
    required this.message,
    this.action,
    super.key,
  });

  final String title;
  final String message;
  final Widget? action;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: ConstrainedBox(
        constraints: const BoxConstraints(maxWidth: 420),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: <Widget>[
            Container(
              width: 88,
              height: 88,
              decoration: BoxDecoration(
                color: AppColors.primarySoft,
                borderRadius: BorderRadius.circular(28),
              ),
              child: const Icon(Icons.directions_car_outlined, size: 40, color: AppColors.primary),
            ),
            const SizedBox(height: 18),
            Text(title, style: AppTextStyles.sectionTitle, textAlign: TextAlign.center),
            const SizedBox(height: 10),
            Text(message, style: AppTextStyles.body, textAlign: TextAlign.center),
            if (action != null) ...<Widget>[
              const SizedBox(height: 18),
              action!,
            ],
          ],
        ),
      ),
    );
  }
}
