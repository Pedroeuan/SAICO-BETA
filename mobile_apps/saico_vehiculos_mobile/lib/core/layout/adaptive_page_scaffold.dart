import 'package:flutter/material.dart';
import '../theme/app_metrics.dart';

class AdaptivePageScaffold extends StatelessWidget {
  const AdaptivePageScaffold({
    required this.title,
    required this.child,
    this.floatingActionButton,
    this.actions,
    super.key,
  });
  final String title;
  final Widget child;
  final Widget? floatingActionButton;
  final List<Widget>? actions;

  @override
  Widget build(BuildContext context) => Scaffold(
        appBar: AppBar(title: Text(title), actions: actions),
        floatingActionButton: floatingActionButton,
        body: SafeArea(
          child: Align(
            alignment: Alignment.topCenter,
            child: ConstrainedBox(
              constraints: const BoxConstraints(maxWidth: 1100),
              child: Padding(
                padding: const EdgeInsets.fromLTRB(AppMetrics.page,
                    AppMetrics.small, AppMetrics.page, AppMetrics.medium),
                child: child,
              ),
            ),
          ),
        ),
      );
}
