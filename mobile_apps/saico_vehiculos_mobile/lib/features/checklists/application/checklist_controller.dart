import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../data/checklist_models.dart';
import '../data/checklist_repository.dart';

final checklistControllerProvider = AsyncNotifierProvider<ChecklistController, void>(
  ChecklistController.new,
);

class ChecklistController extends AsyncNotifier<void> {
  @override
  Future<void> build() async {}

  Future<void> submitDeparture(ChecklistPayload payload) async {
    state = const AsyncLoading();
    state = await AsyncValue.guard(() async {
      await ref.read(checklistRepositoryProvider).submitDepartureChecklist(payload);
    });
  }

  Future<void> submitArrival(ChecklistPayload payload) async {
    state = const AsyncLoading();
    state = await AsyncValue.guard(() async {
      await ref.read(checklistRepositoryProvider).submitArrivalChecklist(payload);
    });
  }
}
