import 'package:flutter/material.dart';

import '../core/theme/app_theme.dart';
import 'router/app_router.dart';

class SaicoVehiculosApp extends StatelessWidget {
  const SaicoVehiculosApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp.router(
      debugShowCheckedModeBanner: false,
      title: 'SAICO Vehiculos',
      theme: AppTheme.light(),
      routerConfig: appRouter,
    );
  }
}
