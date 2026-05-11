import 'dart:io';

import 'package:flutter/material.dart';
import 'package:flutter_image_compress/flutter_image_compress.dart';
import 'package:image_picker/image_picker.dart';

import 'app_card.dart';

class PhotoEvidencePicker extends StatefulWidget {
  const PhotoEvidencePicker({
    required this.photos,
    required this.onPhotosChanged,
    this.minimumPhotos = 3,
    this.maximumPhotos,
    this.helperText,
    super.key,
  });

  final List<File> photos;
  final ValueChanged<List<File>> onPhotosChanged;
  final int minimumPhotos;
  final int? maximumPhotos;
  final String? helperText;

  @override
  State<PhotoEvidencePicker> createState() => _PhotoEvidencePickerState();
}

class _PhotoEvidencePickerState extends State<PhotoEvidencePicker> {
  final ImagePicker _picker = ImagePicker();

  Future<void> _pick(ImageSource source) async {
    final List<File> updated = List<File>.from(widget.photos);
    final remainingSlots = widget.maximumPhotos == null
        ? null
        : widget.maximumPhotos! - updated.length;

    if (remainingSlots != null && remainingSlots <= 0) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Solo puedes adjuntar ${widget.maximumPhotos} evidencias.'),
        ),
      );
      return;
    }

    if (source == ImageSource.gallery) {
      final files = await _picker.pickMultiImage(imageQuality: 88);
      final selectedFiles = remainingSlots == null
          ? files
          : files.take(remainingSlots);
      for (final file in selectedFiles) {
        final compressed = await _compress(file);
        updated.add(compressed);
      }
    } else {
      final file = await _picker.pickImage(source: source, imageQuality: 88);
      if (file != null) {
        updated.add(await _compress(file));
      }
    }

    widget.onPhotosChanged(updated);
  }

  Future<File> _compress(XFile file) async {
    final targetPath = '${file.path}_compressed.jpg';
    final compressed = await FlutterImageCompress.compressAndGetFile(
      file.path,
      targetPath,
      quality: 78,
    );

    return File((compressed ?? file).path);
  }

  @override
  Widget build(BuildContext context) {
    return AppCard(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
          Text(
            'Evidencia fotografica',
            style: Theme.of(context).textTheme.titleMedium,
          ),
          const SizedBox(height: 8),
          Text(
            widget.helperText ??
                '${widget.photos.length} de ${widget.minimumPhotos} minimas',
          ),
          const SizedBox(height: 14),
          Wrap(
            spacing: 12,
            runSpacing: 12,
            children: <Widget>[
              _ActionTile(
                icon: Icons.camera_alt_outlined,
                label: 'Camara',
                onTap: () => _pick(ImageSource.camera),
              ),
              _ActionTile(
                icon: Icons.collections_outlined,
                label: 'Galeria',
                onTap: () => _pick(ImageSource.gallery),
              ),
              ...widget.photos.asMap().entries.map((entry) {
                return Stack(
                  children: <Widget>[
                    ClipRRect(
                      borderRadius: BorderRadius.circular(16),
                      child: Image.file(
                        entry.value,
                        width: 108,
                        height: 108,
                        fit: BoxFit.cover,
                      ),
                    ),
                    Positioned(
                      right: 6,
                      top: 6,
                      child: InkWell(
                        onTap: () {
                          final updated = List<File>.from(widget.photos)..removeAt(entry.key);
                          widget.onPhotosChanged(updated);
                        },
                        child: Container(
                          width: 28,
                          height: 28,
                          decoration: const BoxDecoration(
                            color: Colors.black54,
                            shape: BoxShape.circle,
                          ),
                          child: const Icon(Icons.close, color: Colors.white, size: 18),
                        ),
                      ),
                    ),
                  ],
                );
              }),
            ],
          ),
        ],
      ),
    );
  }
}

class _ActionTile extends StatelessWidget {
  const _ActionTile({
    required this.icon,
    required this.label,
    required this.onTap,
  });

  final IconData icon;
  final String label;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(16),
      child: Ink(
        width: 108,
        height: 108,
        decoration: BoxDecoration(
          border: Border.all(color: const Color(0xFFD8E0DE)),
          borderRadius: BorderRadius.circular(16),
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: <Widget>[
            Icon(icon),
            const SizedBox(height: 8),
            Text(label),
          ],
        ),
      ),
    );
  }
}
