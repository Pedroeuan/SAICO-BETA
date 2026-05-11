import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../../features/auth/presentation/login_screen.dart';
import '../../features/checklists/presentation/arrival_checklist_screen.dart';
import '../../features/checklists/presentation/checklist_detail_screen.dart';
import '../../features/checklists/presentation/departure_checklist_screen.dart';
import '../../features/vehicle_requests/presentation/request_vehicle_screen.dart';
import '../../features/vehicle_requests/presentation/vehicle_operations_home_screen.dart';

final GoRouter appRouter = GoRouter(
  initialLocation: '/',
  routes: <RouteBase>[
    GoRoute(
      path: '/',
      builder: (context, state) => const _StartupScreen(),
    ),
    GoRoute(
      path: '/login',
      builder: (context, state) => const LoginScreen(),
    ),
    GoRoute(
      path: '/home',
      builder: (context, state) => const VehicleOperationsHomeScreen(),
    ),
    GoRoute(
      path: '/request',
      builder: (context, state) => const RequestVehicleScreen(),
    ),
    GoRoute(
      path: '/checklists/departure/:exitId',
      builder: (context, state) => DepartureChecklistScreen(
        exitId: state.pathParameters['exitId']!,
      ),
    ),
    GoRoute(
      path: '/checklists/arrival/:exitId',
      builder: (context, state) => ArrivalChecklistScreen(
        exitId: state.pathParameters['exitId']!,
      ),
    ),
    GoRoute(
      path: '/checklists/view/:type/:exitId',
      builder: (context, state) => ChecklistDetailScreen(
        type: state.pathParameters['type']!,
        exitId: state.pathParameters['exitId']!,
      ),
    ),
  ],
);

class _StartupScreen extends StatelessWidget {
  const _StartupScreen();

  @override
  Widget build(BuildContext context) {
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (context.mounted) {
        context.go('/login');
      }
    });

    return const Scaffold(
      body: Center(
        child: CircularProgressIndicator(),
      ),
    );
  }
}
