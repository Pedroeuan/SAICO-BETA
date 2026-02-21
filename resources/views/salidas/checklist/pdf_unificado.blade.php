<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Checklist Vehicular</title>
    <style>
        @page {
            size: letter portrait;
            margin: 10mm 9mm;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #111;
            line-height: 1.15;
        }
        h1, h2, h3, h4, p { margin: 0; }
        .title { text-align: center; margin-bottom: 6px; }
        .title h2 { font-size: 13px; margin-bottom: 2px; }
        .title p { font-size: 8px; color: #444; }
        .logo { height: 42px; margin-bottom: 2px; }
        .box {
            border: 1px solid #222;
            margin-bottom: 6px;
            page-break-inside: avoid;
        }
        .box-h {
            background: #f1f1f1;
            padding: 3px 5px;
            font-weight: bold;
            border-bottom: 1px solid #222;
            font-size: 9px;
        }
        .box-b { padding: 4px 5px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 2px 4px; vertical-align: top; }
        th { background: #f7f7f7; text-align: left; width: 26%; }
        .two-col td { width: 50%; border: 0; padding: 0 2px; vertical-align: top; }
        .mini td, .mini th { padding: 2px 3px; font-size: 8.6px; }
        .muted { color: #555; }
        .ok { font-weight: bold; color: #0a6d36; }
        .bad { font-weight: bold; color: #a01111; }
        /* ===== EVIDENCIAS VERTICALES ===== */
        .photo-grid { width: 100%; border-collapse: collapse; margin-top: 4px;}  
        .photo-grid td { border: 0; width: 100%; padding: 4px 0;} 
        .thumb { width: 100%; height: 120px; /* MÁS GRANDE pero cabe en el cuadro */ 
        object-fit: cover; border: 1px solid #666;}
        .photo-empty { border: 1px dashed #aaa; height: 120px; line-height: 120px; font-size: 9px; color: #666; text-align: center;}
  
    </style>
</head>
<body>
    @php
        $vehiculo = $salida->vehiculo;
        $chofer = $salida->chofer;
        $cs = $checklistSalida;
        $ce = $checklistEntrada;
        $Logo = $Logo ?? public_path('images/Logo_AICO_R.jpg');

        $docs = $cs ? $cs->documentos->keyBy('documento') : collect();
        $herr = $cs ? $cs->herramientas->keyBy('herramienta') : collect();

        $docEstatus = function($key) use ($docs) {
            $item = $docs->get($key);
            if (!$item) return 'N/A';
            return strtoupper($item->estatus) === 'OK' ? 'OK' : 'VENCIDO';
        };
        $herrDisp = function($key) use ($herr) {
            $item = $herr->get($key);
            if (!$item) return 'N/A';
            return (int) $item->disponible === 1 ? 'SI' : 'NO';
        };
        $fmtFecha = function($value) {
            return $value ? \Carbon\Carbon::parse($value)->format('d/m/Y H:i') : 'N/A';
        };
    @endphp

    <table class="mini" style="margin-bottom: 6px;">
        <tr>
            <th rowspan="3" style="width: 20%; text-align: center; background: #fff;">
                <img src="{{ $Logo }}" alt="Logo" style="width: auto; max-width: 95px; max-height: 30px; height: auto; display: block; margin: 0 auto;">
            </th>
            <th style="width: 80%; text-align: center;">Checklist de Vehiculo - Resumen</th>
        </tr>
        <tr>
            <td><strong>Checklist</strong></td>
        </tr>
        <tr>
            <td>Salida ID: {{ $salida->id }} | Fecha: {{ now()->format('d/m/Y') }}</td>
        </tr>
    </table>

    <div class="box">
        <div class="box-h">Datos Generales</div>
        <div class="box-b">
            <table class="mini">
                <tr>
                    <th>Salida ID</th>
                    <td>{{ $salida->id }}</td>
                    <th>Estatus Salida</th>
                    <td>{{ strtoupper($salida->estatus ?? 'N/A') }}</td>
                </tr>
                <tr>
                    <th>Placa</th>
                    <td>{{ $vehiculo->placa ?? 'N/A' }}</td>
                    <th>Vehiculo</th>
                    <td>{{ ($vehiculo->marca ?? 'N/A') . ' ' . ($vehiculo->modelo ?? '') }}</td>
                </tr>
                <tr>
                    <th>Chofer</th>
                    <td>{{ $chofer->name ?? 'N/A' }}</td>
                    <th>Solicitado por</th>
                    <td>{{ $salida->solicitante->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Fecha salida</th>
                    <td>{{ $fmtFecha($salida->fecha_salida) }}</td>
                    <th>Fecha regreso</th>
                    <td>{{ $fmtFecha($salida->fecha_regreso) }}</td>
                </tr>
                <tr>
                    <th>Motivo</th>
                    <td colspan="3">{{ $salida->motivo ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <table class="two-col">
        <tr>
            <td>
                <div class="box">
                    <div class="box-h">Checklist SALIDA</div>
                    <div class="box-b">
                        <table class="mini">
                            <tr><th>Nivel gasolina</th><td>{{ $cs?->condicion?->nivel_gasolina ?? 'N/A' }}</td></tr>
                            <tr><th>Kilometraje</th><td>{{ $cs?->condicion?->kilometraje ?? 'N/A' }}</td></tr>
                            <tr><th>Limpio exterior</th><td>{{ $cs?->condicion ? ($cs->condicion->limpio_exterior ? 'SI' : 'NO') : 'N/A' }}</td></tr>
                            <tr><th>Limpio interior</th><td>{{ $cs?->condicion ? ($cs->condicion->limpio_interior ? 'SI' : 'NO') : 'N/A' }}</td></tr>
                            <tr><th>Observaciones</th><td>{{ $cs?->condicion?->observaciones ?? 'N/A' }}</td></tr>
                        </table>
                    </div>
                </div>
            </td>
            <td>
                <div class="box">
                    <div class="box-h">Checklist ENTRADA</div>
                    <div class="box-b">
                        @if($ce)
                            <table class="mini">
                                <tr><th>Nivel gasolina</th><td>{{ $ce?->condicion?->nivel_gasolina ?? 'N/A' }}</td></tr>
                                <tr><th>Kilometraje</th><td>{{ $ce?->condicion?->kilometraje ?? 'N/A' }}</td></tr>
                                <tr><th>Limpio exterior</th><td>{{ $ce?->condicion ? ($ce->condicion->limpio_exterior ? 'SI' : 'NO') : 'N/A' }}</td></tr>
                                <tr><th>Limpio interior</th><td>{{ $ce?->condicion ? ($ce->condicion->limpio_interior ? 'SI' : 'NO') : 'N/A' }}</td></tr>
                                <tr><th>Observaciones</th><td>{{ $ce?->condicion?->observaciones ?? 'N/A' }}</td></tr>
                            </table>
                        @else
                            <p class="muted">Entrada aun no registrada.</p>
                        @endif
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <table class="two-col">
        <tr>
            <td>
                <div class="box">
                    <div class="box-h">Documentos (salida)</div>
                    <div class="box-b">
                        <table class="mini">
                            @php
                                $lic = $docEstatus('licencia_conducir');
                                $tar = $docEstatus('tarjeta_circulacion');
                                $pol = $docEstatus('poliza_seguro');
                            @endphp
                            <tr><th>Licencia conducir</th><td class="{{ $lic === 'OK' ? 'ok' : 'bad' }}">{{ $lic }}</td></tr>
                            <tr><th>Tarjeta circulacion</th><td class="{{ $tar === 'OK' ? 'ok' : 'bad' }}">{{ $tar }}</td></tr>
                            <tr><th>Poliza seguro</th><td class="{{ $pol === 'OK' ? 'ok' : 'bad' }}">{{ $pol }}</td></tr>
                        </table>
                    </div>
                </div>
            </td>
            <td>
                <div class="box">
                    <div class="box-h">Herramientas (salida)</div>
                    <div class="box-b">
                        <table class="mini">
                            @php
                                $herramientasKeys = [
                                    'llantas' => 'Llantas',
                                    'extintor' => 'Extintor',
                                    'cables_corriente' => 'Cables corriente',
                                    'gato_hidraulico' => 'Gato hidraulico',
                                    'llave_cruz' => 'Llave cruz',
                                    'llanta_refaccion' => 'Llanta refaccion',
                                ];
                            @endphp
                            @foreach($herramientasKeys as $key => $label)
                                @php $val = $herrDisp($key); @endphp
                                <tr>
                                    <th>{{ $label }}</th>
                                    <td class="{{ $val === 'SI' ? 'ok' : 'bad' }}">{{ $val }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <table class="two-col">
    <tr>

        <!-- SALIDA -->
        <td>
            <div class="box">
                <div class="box-h">Evidencias SALIDA (max 3)</div>
                <div class="box-b">

                    <table class="mini">
                        <tr>
                            <th>Total fotos</th>
                            <td>{{ $cs ? $cs->evidencias->count() : 0 }}</td>
                        </tr>
                    </table>

                    <table class="photo-grid">
                        @forelse(($cs ? $cs->evidencias->take(3) : collect()) as $evidencia)
                            <tr>
                                <td>
                                    <img class="thumb"
                                         src="{{ public_path('storage/' . $evidencia->foto) }}"
                                         alt="foto salida">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td><div class="photo-empty">Sin foto</div></td>
                            </tr>
                        @endforelse

                        @for($i = ($cs ? $cs->evidencias->take(3)->count() : 0); $i < 3; $i++)
                            <tr>
                                <td><div class="photo-empty">N/A</div></td>
                            </tr>
                        @endfor
                    </table>

                </div>
            </div>
        </td>

        <!-- ENTRADA -->
        <td>
            <div class="box">
                <div class="box-h">Evidencias ENTRADA (max 3)</div>
                <div class="box-b">

                    <table class="mini">
                        <tr>
                            <th>Total fotos</th>
                            <td>{{ $ce ? $ce->evidencias->count() : 0 }}</td>
                        </tr>
                    </table>

                    <table class="photo-grid">
                        @forelse(($ce ? $ce->evidencias->take(3) : collect()) as $evidencia)
                            <tr>
                                <td>
                                    <img class="thumb"
                                         src="{{ public_path('storage/' . $evidencia->foto) }}"
                                         alt="foto entrada">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td><div class="photo-empty">Sin foto</div></td>
                            </tr>
                        @endforelse

                        @for($i = ($ce ? $ce->evidencias->take(3)->count() : 0); $i < 3; $i++)
                            <tr>
                                <td><div class="photo-empty">N/A</div></td>
                            </tr>
                        @endfor
                    </table>

                </div>
            </div>
        </td>

    </tr>
</table>
</body>
</html>
