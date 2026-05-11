import 'package:flutter/material.dart';

import 'app_colors.dart';

class AppTextStyles {
  const AppTextStyles._();

  static const TextStyle appTitle = TextStyle(
    fontSize: 31,
    fontWeight: FontWeight.w500,
    color: AppColors.ink,
    height: 1.08,
  );

  static const TextStyle sectionTitle = TextStyle(
    fontSize: 21,
    fontWeight: FontWeight.w500,
    color: AppColors.ink,
  );

  static const TextStyle body = TextStyle(
    fontSize: 15,
    fontWeight: FontWeight.w400,
    color: AppColors.slate,
    height: 1.5,
  );

  static const TextStyle label = TextStyle(
    fontSize: 13,
    fontWeight: FontWeight.w500,
    color: AppColors.slate,
  );
}
