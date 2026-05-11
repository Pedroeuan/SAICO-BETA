import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';

import '../theme/app_colors.dart';
import '../../features/vehicle_requests/domain/vehicle_exit_models.dart';
import 'app_card.dart';
import 'status_badge.dart';

class VehicleCard extends StatelessWidget {
  const VehicleCard({
    required this.vehicle,
    this.trailing,
    this.subtitle,
    super.key,
  });

  final CatalogVehicle vehicle;
  final Widget? trailing;
  final String? subtitle;

  @override
  Widget build(BuildContext context) {
    return AppCard(
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
          ClipRRect(
            borderRadius: BorderRadius.circular(18),
            child: SizedBox(
              width: 96,
              height: 96,
              child: vehicle.photoUrl != null && vehicle.photoUrl!.isNotEmpty
                  ? CachedNetworkImage(
                      imageUrl: vehicle.photoUrl!,
                      fit: BoxFit.cover,
                      errorWidget: (_, __, ___) => const _VehiclePlaceholder(),
                    )
                  : const _VehiclePlaceholder(),
            ),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: <Widget>[
                Text(
                  vehicle.plate,
                  style: Theme.of(context).textTheme.titleMedium,
                ),
                const SizedBox(height: 6),
                Text('${vehicle.brand} ${vehicle.model} ${vehicle.yearLabel}'.trim()),
                const SizedBox(height: 8),
                Wrap(
                  spacing: 8,
                  runSpacing: 8,
                  children: <Widget>[
                    StatusBadge(label: vehicle.statusLabel),
                    if (vehicle.currentMileage != null)
                      Text('${vehicle.currentMileage} km'),
                  ],
                ),
                if (subtitle != null) ...<Widget>[
                  const SizedBox(height: 10),
                  Text(subtitle!),
                ],
              ],
            ),
          ),
          if (trailing != null) ...<Widget>[
            const SizedBox(width: 12),
            trailing!,
          ],
        ],
      ),
    );
  }
}

class _VehiclePlaceholder extends StatelessWidget {
  const _VehiclePlaceholder();

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: const BoxDecoration(
        gradient: LinearGradient(
          colors: <Color>[AppColors.primarySoft, Color(0xFFFDEDEE)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
      ),
      child: const Icon(
        Icons.directions_car_filled_outlined,
        size: 36,
        color: AppColors.primary,
      ),
    );
  }
}
