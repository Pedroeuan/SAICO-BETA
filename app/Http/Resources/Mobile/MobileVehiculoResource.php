<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobileVehiculoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'placa' => $this->placa,
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'anio' => $this->anio,
            'estatus' => $this->estatus,
            'kilometraje_actual' => $this->kilometraje_actual,
            'foto_url' => $this->foto_url ? asset($this->foto_url) : null,
            'documentacion_estatus' => $this->documentacion_estatus,
            'poliza_seguro_vencimiento' => optional($this->poliza_seguro_vencimiento)->toDateString(),
            'tarjeta_circulacion_vencimiento' => optional($this->tarjeta_circulacion_vencimiento)->toDateString(),
        ];
    }
}
