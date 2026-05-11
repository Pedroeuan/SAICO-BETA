<?php

namespace App\Http\Resources\Mobile;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobileUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'rol' => $this->rol,
            'licencia_numero' => $this->licencia_numero,
            'licencia_vencimiento' => $this->licencia_vencimiento
                ? Carbon::parse($this->licencia_vencimiento)->toDateString()
                : null,
            'licencia_estatus' => $this->licencia_estatus,
            'foto' => $this->foto ?? null,
        ];
    }
}
