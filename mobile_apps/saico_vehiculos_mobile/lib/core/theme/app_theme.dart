import 'package:flutter/material.dart';

import 'app_colors.dart';
import 'app_text_styles.dart';
import 'app_metrics.dart';
import 'saico_page_transition.dart';

class AppTheme {
  static ThemeData light() {
    final colorScheme = ColorScheme.fromSeed(
      seedColor: AppColors.primary,
      primary: AppColors.primary,
      secondary: AppColors.accentRed,
      surface: AppColors.card,
    );

    return ThemeData(
      useMaterial3: true,
      pageTransitionsTheme: const PageTransitionsTheme(builders: {
        TargetPlatform.android: SaicoPageTransition(),
        TargetPlatform.iOS: SaicoPageTransition(),
        TargetPlatform.windows: SaicoPageTransition(),
        TargetPlatform.linux: SaicoPageTransition(),
        TargetPlatform.macOS: SaicoPageTransition(),
      }),
      colorScheme: colorScheme,
      scaffoldBackgroundColor: AppColors.canvas,
      appBarTheme: const AppBarTheme(
        centerTitle: false,
        elevation: 0,
        backgroundColor: AppColors.canvas,
        surfaceTintColor: Colors.transparent,
        foregroundColor: AppColors.ink,
      ),
      textTheme: const TextTheme(
        headlineLarge: AppTextStyles.appTitle,
        headlineSmall: AppTextStyles.appTitle,
        titleLarge: AppTextStyles.sectionTitle,
        titleMedium: TextStyle(
          fontSize: 17,
          fontWeight: FontWeight.w500,
          color: AppColors.ink,
        ),
        titleSmall: TextStyle(
          fontSize: 15,
          fontWeight: FontWeight.w500,
          color: AppColors.ink,
        ),
        bodyLarge: AppTextStyles.body,
        bodyMedium: AppTextStyles.body,
        labelLarge: AppTextStyles.label,
      ),
      inputDecorationTheme: InputDecorationTheme(
        errorMaxLines: 3,
        helperMaxLines: 3,
        filled: true,
        fillColor: Colors.white,
        contentPadding:
            const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
        labelStyle: const TextStyle(color: AppColors.slate),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(AppMetrics.radius),
          borderSide: const BorderSide(color: AppColors.line),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(AppMetrics.radius),
          borderSide: const BorderSide(color: AppColors.line),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(AppMetrics.radius),
          borderSide: const BorderSide(color: AppColors.primary, width: 1.4),
        ),
      ),
      cardTheme: const CardTheme(
        color: AppColors.card,
        elevation: 0,
        margin: EdgeInsets.zero,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.all(Radius.circular(AppMetrics.radius)),
          side: BorderSide(color: AppColors.line),
        ),
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: AppColors.primary,
          foregroundColor: Colors.white,
          minimumSize: const Size.fromHeight(54),
          elevation: 0,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(18),
          ),
        ),
      ),
      outlinedButtonTheme: OutlinedButtonThemeData(
        style: OutlinedButton.styleFrom(
          minimumSize: const Size.fromHeight(54),
          side: const BorderSide(color: AppColors.line, width: 1.2),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(18),
          ),
        ),
      ),
      chipTheme: ChipThemeData(
        backgroundColor: Colors.white,
        disabledColor: AppColors.line,
        selectedColor: AppColors.primarySoft,
        secondarySelectedColor: AppColors.primarySoft,
        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 8),
        labelStyle: const TextStyle(
          color: AppColors.ink,
          fontWeight: FontWeight.w500,
        ),
        secondaryLabelStyle: const TextStyle(
          color: AppColors.primary,
          fontWeight: FontWeight.w500,
        ),
        brightness: Brightness.light,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(999),
          side: const BorderSide(color: AppColors.line),
        ),
      ),
      snackBarTheme: const SnackBarThemeData(
        behavior: SnackBarBehavior.floating,
      ),
      tabBarTheme: const TabBarTheme(
        labelColor: AppColors.primary,
        unselectedLabelColor: AppColors.slate,
        indicatorColor: AppColors.primary,
        dividerColor: AppColors.line,
      ),
      progressIndicatorTheme: const ProgressIndicatorThemeData(
        color: AppColors.primary,
        linearTrackColor: AppColors.primarySoft,
      ),
      bottomSheetTheme: const BottomSheetThemeData(
        showDragHandle: true,
        backgroundColor: AppColors.card,
      ),
      tooltipTheme: const TooltipThemeData(
        preferBelow: false,
        waitDuration: Duration(milliseconds: 350),
      ),
    );
  }
}
