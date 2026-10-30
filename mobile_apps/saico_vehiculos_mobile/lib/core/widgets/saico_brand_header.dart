import 'package:flutter/material.dart';
import '../theme/app_colors.dart';
import '../theme/app_metrics.dart';

/// Encabezado adaptable. Los detalles secundarios se consultan bajo demanda.
class SaicoBrandHeader extends StatelessWidget {
  const SaicoBrandHeader(
      {required this.title,
      required this.subtitle,
      this.height = 260,
      this.compact = false,
      super.key});
  final String title;
  final String subtitle;
  final double height;
  final bool compact;

  @override
  Widget build(BuildContext context) => Container(
        padding: const EdgeInsets.all(AppMetrics.page),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(AppMetrics.radius),
          border: Border.all(color: AppColors.line),
        ),
        child: Row(children: [
          Image.asset('assets/branding/saico_symbol.png',
              width: compact ? 48 : 64,
              height: compact ? 48 : 64,
              semanticLabel: 'SAICO'),
          const SizedBox(width: AppMetrics.medium),
          Expanded(
              child:
                  Text(title, style: Theme.of(context).textTheme.titleLarge)),
          if (subtitle.isNotEmpty)
            IconButton(
              tooltip: 'Información',
              icon: const Icon(Icons.info_outline),
              onPressed: () => showModalBottomSheet<void>(
                  context: context,
                  builder: (context) => SafeArea(
                      child: SingleChildScrollView(
                          padding: const EdgeInsets.all(AppMetrics.large),
                          child: Text(subtitle)))),
            ),
        ]),
      );
}
