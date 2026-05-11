import 'package:flutter/material.dart';

import '../widgets/app_card.dart';

enum ChecklistVisualMode { condition, presence }

class ChecklistItemValue {
  const ChecklistItemValue({
    required this.status,
    this.notes = '',
  });

  final String status;
  final String notes;

  ChecklistItemValue copyWith({
    String? status,
    String? notes,
  }) {
    return ChecklistItemValue(
      status: status ?? this.status,
      notes: notes ?? this.notes,
    );
  }
}

class ChecklistItemTile extends StatefulWidget {
  const ChecklistItemTile({
    required this.title,
    required this.value,
    required this.onChanged,
    this.mode = ChecklistVisualMode.condition,
    super.key,
  });

  final String title;
  final ChecklistItemValue value;
  final ValueChanged<ChecklistItemValue> onChanged;
  final ChecklistVisualMode mode;

  @override
  State<ChecklistItemTile> createState() => _ChecklistItemTileState();
}

class _ChecklistItemTileState extends State<ChecklistItemTile> {
  late final TextEditingController _notesController;

  List<String> get _options => widget.mode == ChecklistVisualMode.condition
      ? const <String>['Bueno', 'Regular', 'Malo']
      : const <String>['Presente', 'Ausente'];

  bool get _notesRequired =>
      widget.mode == ChecklistVisualMode.condition && widget.value.status == 'Malo';

  @override
  void initState() {
    super.initState();
    _notesController = TextEditingController(text: widget.value.notes);
  }

  @override
  void didUpdateWidget(covariant ChecklistItemTile oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (oldWidget.value.notes != widget.value.notes &&
        _notesController.text != widget.value.notes) {
      _notesController.text = widget.value.notes;
    }
  }

  @override
  void dispose() {
    _notesController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return AppCard(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
          Text(widget.title, style: Theme.of(context).textTheme.titleSmall),
          const SizedBox(height: 12),
          Wrap(
            spacing: 8,
            runSpacing: 8,
            children: _options.map((option) {
              final selected = option == widget.value.status;
              return ChoiceChip(
                label: Text(option),
                selected: selected,
                onSelected: (_) {
                  widget.onChanged(widget.value.copyWith(status: option));
                },
              );
            }).toList(growable: false),
          ),
          if (_notesRequired || widget.value.notes.trim().isNotEmpty) ...<Widget>[
            const SizedBox(height: 12),
            TextFormField(
              controller: _notesController,
              minLines: 2,
              maxLines: 3,
              decoration: InputDecoration(
                labelText: _notesRequired ? 'Observacion obligatoria' : 'Observacion',
              ),
              onChanged: (value) => widget.onChanged(widget.value.copyWith(notes: value)),
              validator: (value) {
                if (_notesRequired && (value == null || value.trim().isEmpty)) {
                  return 'Describe el dano detectado.';
                }
                return null;
              },
            ),
          ],
        ],
      ),
    );
  }
}
