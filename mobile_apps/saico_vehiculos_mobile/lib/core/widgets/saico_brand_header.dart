import 'package:flutter/material.dart';

import '../theme/app_colors.dart';

class SaicoBrandHeader extends StatelessWidget {
  const SaicoBrandHeader({
    required this.title,
    required this.subtitle,
    this.height = 260,
    this.compact = false,
    super.key,
  });

  final String title;
  final String subtitle;
  final double height;
  final bool compact;

  @override
  Widget build(BuildContext context) {
    return Container(
      height: height,
      width: double.infinity,
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(compact ? 26 : 34),
        color: Colors.white,
        boxShadow: const <BoxShadow>[
          BoxShadow(
            color: AppColors.shadow,
            blurRadius: 28,
            offset: Offset(0, 14),
          ),
        ],
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(compact ? 26 : 34),
        child: Stack(
          fit: StackFit.expand,
          children: <Widget>[
            Image.asset(
              'assets/branding/saico_wave_bg.png',
              fit: BoxFit.cover,
            ),
            DecoratedBox(
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  colors: <Color>[
                    Colors.white.withOpacity(0.92),
                    Colors.white.withOpacity(0.82),
                    const Color(0xFFF7FBFF).withOpacity(0.9),
                  ],
                  begin: Alignment.topCenter,
                  end: Alignment.bottomCenter,
                ),
              ),
            ),
            Padding(
              padding: EdgeInsets.all(compact ? 18 : 24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: <Widget>[
                  Align(
                    alignment: Alignment.topCenter,
                    child: Image.asset(
                      'assets/branding/saico_logo.png',
                      height: compact ? 42 : 56,
                      fit: BoxFit.contain,
                    ),
                  ),
                  const Spacer(),
                  Container(
                    width: compact ? 56 : 64,
                    height: 5,
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(999),
                      gradient: const LinearGradient(
                        colors: <Color>[AppColors.primary, AppColors.accentRed],
                      ),
                    ),
                  ),
                  const SizedBox(height: 14),
                  Text(
                    title,
                    style: TextStyle(
                      fontSize: compact ? 24 : 31,
                      fontWeight: FontWeight.w500,
                      color: AppColors.ink,
                      height: 1.08,
                    ),
                  ),
                  const SizedBox(height: 10),
                  Text(
                    subtitle,
                    style: const TextStyle(
                      color: AppColors.slate,
                      fontSize: 15,
                      height: 1.5,
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
