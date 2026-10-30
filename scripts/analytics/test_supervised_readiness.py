import copy
import unittest
from assess_supervised_readiness import assess


class ReviewGateTests(unittest.TestCase):
    def setUp(self):
        self.row = {'salida_id': 1, 'vehiculo_id': 1, 'estatus': 'finalizado',
                    'solicitud_creada': '2026-03-01 08:00:00', 'fecha_salida': '2026-03-01 09:00:00',
                    'fecha_regreso': '2026-03-01 10:00:00', 'checklists_salida': 1, 'checklists_entrada': 1}
        self.source = {'rows': [self.row], 'timezone_app': 'America/Mexico_City',
                       'database_clock': {'reloj_bd': '2026-10-07 12:00:00'}}

    def review(self):
        return {**{k: str(v) if v is not None else '' for k, v in self.row.items()},
                'procedencia': 'operacion', 'fecha_salida_representa': 'salida_real',
                'fecha_regreso_representa': 'regreso_real', 'decision_supervisado': 'aprobar',
                'evidencia_revision': 'Confirmed test fixture for rule validation only.'}

    def test_approved_data_never_automatically_deploys_a_model(self):
        summary, _, rows = assess(self.source, [self.review()])
        self.assertEqual(len(rows), 1)
        self.assertEqual(rows[0]['duracion_minutos'], 60)
        self.assertFalse(summary['predicciones_produccion_habilitadas'])

    def test_unconfirmed_or_test_origin_not_admitted(self):
        for origin in ['pendiente', 'prueba']:
            review = self.review(); review['procedencia'] = origin
            self.assertEqual(assess(self.source, [review])[2], [])

    def test_approval_requires_evidence_and_real_return(self):
        review = self.review(); review['evidencia_revision'] = ' '
        self.assertEqual(assess(self.source, [review])[2], [])
        review = self.review(); review['fecha_regreso_representa'] = 'cierre_tardio'
        self.assertEqual(assess(self.source, [review])[2], [])

    def test_original_dates_cannot_be_edited_in_review(self):
        review = self.review(); review['fecha_salida'] = '2026-03-01 08:30:00'
        with self.assertRaises(ValueError): assess(self.source, [review])

    def test_review_cannot_omit_or_duplicate_records(self):
        with self.assertRaises(ValueError): assess(self.source, [])
        with self.assertRaises(ValueError): assess(self.source, [self.review(), self.review()])

    def test_temporal_errors_survive_manual_approval(self):
        for field, value in [('solicitud_creada', '2026-03-01 09:15:00'),
                             ('fecha_regreso', '2026-03-01 08:59:00'),
                             ('fecha_regreso', '2027-01-01 10:00:00')]:
            source = copy.deepcopy(self.source); source['rows'][0][field] = value
            review = self.review(); review[field] = value
            self.assertEqual(assess(source, [review])[2], [])


if __name__ == '__main__':
    unittest.main()
