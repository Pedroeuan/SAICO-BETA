import 'package:flutter/material.dart';

import '../theme/app_colors.dart';

class AppCard extends StatelessWidget {
  const AppCard({
    required this.child,
    this.padding = const EdgeInsets.all(20),
    this.backgroundColor,
    super.key,
  });

  final Widget child;
  final EdgeInsetsGeometry padding;
  final Color? backgroundColor;

  @override
  Widget build(BuildContext context) {
    return Card(
      shadowColor: AppColors.shadow,
      color: backgroundColor,
      clipBehavior: Clip.antiAlias,
      child: Padding(
        padding: padding,
        child: child,
      ),
    );
  }
}
