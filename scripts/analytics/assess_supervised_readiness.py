"""Review gate for real operational data. Never changes SQL records or trains a model."""
import argparse
import csv
import hashlib
import json
from collections import Counter
from datetime import datetime
from pathlib import Path
from zoneinfo import ZoneInfo

PROVENANCE = {'pendiente', 'operacion', 'prueba'}
START_MEANING = {'pendiente', 'salida_real', 'programada'}
END_MEANING = {'pendiente', 'regreso_real', 'cierre_tardio'}
DECISIONS = {'pendiente', 'aprobar', 'excluir'}


def assess(source, review_rows):
    originals = {str(row['salida_id']): row for row in source['rows']}
    if len(originals) != len(source['rows']):
        raise ValueError('Duplicate source identifiers.')
    ids = [row['salida_id'] for row in review_rows]
    if len(ids) != len(set(ids)) or set(ids) != set(originals):
        raise ValueError('Review must contain each original exit exactly once.')
    outcomes, eligible = [], []
    for review in review_rows:
        original = originals[review['salida_id']]
        # Only decision columns may be edited; changing evidence does not repair source dates.
        for key, value in original.items():
            if review.get(key) != ('' if value is None else str(value)):
                raise ValueError(f"Source field changed: exit {review['salida_id']}, {key}")
        for field, allowed in [('procedencia', PROVENANCE), ('fecha_salida_representa', START_MEANING),
                               ('fecha_regreso_representa', END_MEANING), ('decision_supervisado', DECISIONS)]:
            if review.get(field) not in allowed:
                raise ValueError(f'Unknown decision value: {field}')
        reasons = []
        if review['procedencia'] != 'operacion':
            reasons.append('origen_prueba' if review['procedencia'] == 'prueba' else 'procedencia_sin_confirmar')
        if review['fecha_salida_representa'] != 'salida_real':
            reasons.append('salida_real_sin_confirmar')
        if review['fecha_regreso_representa'] != 'regreso_real':
            reasons.append('regreso_real_sin_confirmar')
        if review['decision_supervisado'] != 'aprobar':
            reasons.append('exclusion_solicitada' if review['decision_supervisado'] == 'excluir' else 'revision_pendiente')
        if not review['evidencia_revision'].strip():
            reasons.append('sin_evidencia_revision')
        if original['estatus'] != 'finalizado':
            reasons.append('salida_no_finalizada')
        if original['checklists_salida'] != 1 or original['checklists_entrada'] != 1:
            reasons.append('sin_par_unico_checklists')
        zone = ZoneInfo(source['timezone_app'])
        parsed = {}
        for field in ['solicitud_creada', 'fecha_salida', 'fecha_regreso']:
            try:
                parsed[field] = datetime.strptime(original[field], '%Y-%m-%d %H:%M:%S').replace(tzinfo=zone)
            except (TypeError, ValueError):
                reasons.append(field + '_invalida')
        if len(parsed) == 3:
            if parsed['solicitud_creada'] > parsed['fecha_salida']:
                reasons.append('captura_posterior_a_salida')
            if parsed['fecha_regreso'] <= parsed['fecha_salida']:
                reasons.append('intervalo_no_positivo')
            extraction = datetime.strptime(source['database_clock']['reloj_bd'], '%Y-%m-%d %H:%M:%S').replace(tzinfo=zone)
            if parsed['fecha_regreso'] > extraction:
                reasons.append('regreso_posterior_al_corte')
        outcomes.append({'salida_id': int(review['salida_id']), 'admitida_revision': not reasons, 'motivos': reasons})
        if not reasons:
            start = parsed['fecha_salida']
            eligible.append({'salida_id': int(review['salida_id']), 'vehiculo_id': original['vehiculo_id'],
                             'fecha_solicitud': original['solicitud_creada'], 'fecha_salida': original['fecha_salida'],
                             'fecha_regreso': original['fecha_regreso'], 'salida_hora': start.hour,
                             'salida_dia_semana': start.isoweekday(), 'salida_mes': start.month,
                             'duracion_minutos': (parsed['fecha_regreso'].timestamp() - start.timestamp()) / 60})
    counts = Counter(reason for row in outcomes for reason in row['motivos'])
    summary = {'registros_revisados': len(outcomes), 'admitidos_revision': len(eligible), 'bloqueos': dict(counts),
               'predicciones_produccion_habilitadas': False,
               'pasos_adicionales': ['Confirmar disponibilidad historica de entradas al solicitar.',
                                    'Evaluar suficiencia y diversidad de los datos admitidos.',
                                    'Separar entrenamiento y prueba por tiempo sin usar resultados aun desconocidos en cada corte.',
                                    'Comparar modelo con referencia y evaluar error fuera del entrenamiento.',
                                    'Registrar version, monitorizar errores y habilitar servicio solo con evaluacion aprobada.']}
    return summary, outcomes, sorted(eligible, key=lambda row: (row['fecha_solicitud'], row['salida_id']))


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('review_directory', type=Path)
    args = parser.parse_args()
    root = args.review_directory.resolve()
    source_bytes = (root / 'fuente_temporal.json').read_bytes()
    manifest = json.loads((root / 'manifest.json').read_text(encoding='utf-8-sig'))
    if hashlib.sha256(source_bytes).hexdigest() != manifest['files']['fuente_temporal.json']:
        raise ValueError('Source snapshot changed; generate a new review.')
    source = json.loads(source_bytes)
    with (root / 'revision_por_salida.csv').open(encoding='utf-8-sig', newline='') as stream:
        reviews = list(csv.DictReader(stream))
    summary, outcomes, eligible = assess(source, reviews)
    import secrets
    output = root / ('evaluacion_' + datetime.now().strftime('%Y%m%d_%H%M%S') + '_' + secrets.token_hex(3))
    output.mkdir()
    summary['source_sha256'] = hashlib.sha256(source_bytes).hexdigest()
    summary['review_sha256'] = hashlib.sha256((root / 'revision_por_salida.csv').read_bytes()).hexdigest()
    for name, value in [('resumen.json', summary), ('decisiones.json', outcomes), ('candidatos_revisados.json', eligible)]:
        (output / name).write_text(json.dumps(value, ensure_ascii=False, indent=2) + '\n', encoding='utf-8')
    print(json.dumps({'directory': str(output), 'summary': summary}, ensure_ascii=True, indent=2))


if __name__ == '__main__':
    main()
