<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobileChecklistResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $this->loadMissing(['condicion', 'documentos', 'herramientas', 'evidencias']);

        return [
            'id' => $this->id,
            'tipo' => $this->tipo,
            'condicion' => [
                'nivel_gasolina' => optional($this->condicion)->nivel_gasolina,
                'kilometraje' => optional($this->condicion)->kilometraje,
                'limpio_exterior' => (bool) optional($this->condicion)->limpio_exterior,
                'limpio_interior' => (bool) optional($this->condicion)->limpio_interior,
                'observaciones' => optional($this->condicion)->observaciones,
                'liquido_limpiaparabrisas' => optional($this->condicion)->liquido_limpiaparabrisas,
                'aceite' => optional($this->condicion)->aceite,
                'anticongelante' => optional($this->condicion)->anticongelante,
                'liquido_frenos' => optional($this->condicion)->liquido_frenos,
                'estado_llantas' => optional($this->condicion)->estado_llantas,
                'llanta_delantera_izq_calibracion' => optional($this->condicion)->llanta_delantera_izq_calibracion,
                'llanta_delantera_der_calibracion' => optional($this->condicion)->llanta_delantera_der_calibracion,
                'llanta_trasera_izq_calibracion' => optional($this->condicion)->llanta_trasera_izq_calibracion,
                'llanta_trasera_der_calibracion' => optional($this->condicion)->llanta_trasera_der_calibracion,
            ],
            'documentos' => $this->documentos->map(function ($documento) {
                return [
                    'documento' => $documento->documento,
                    'estatus' => $documento->estatus,
                ];
            })->values(),
            'herramientas' => $this->herramientas->map(function ($herramienta) {
                return [
                    'herramienta' => $herramienta->herramienta,
                    'disponible' => (bool) $herramienta->disponible,
                ];
            })->values(),
            'evidencias' => $this->evidencias->map(function ($evidencia) {
                return [
                    'id' => $evidencia->id,
                    'foto' => $evidencia->foto,
                    'foto_url' => asset('storage/' . $evidencia->foto),
                ];
            })->values(),
        ];
    }
}
