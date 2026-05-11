<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobileSalidaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $this->loadMissing([
            'vehiculo',
            'chofer',
            'solicitante',
            'checklistSalida.condicion',
            'checklistSalida.documentos',
            'checklistSalida.herramientas',
            'checklistSalida.evidencias',
            'checklistEntrada.condicion',
            'checklistEntrada.evidencias',
        ]);

        return [
            'id' => $this->id,
            'vehiculo_id' => $this->vehiculo_id,
            'chofer_id' => $this->chofer_id,
            'solicitado_por' => $this->solicitado_por,
            'folio' => $this->Num_Reporte,
            'motivo' => $this->motivo,
            'estatus' => $this->estatus,
            'fecha_salida' => optional($this->fecha_salida)->toIso8601String(),
            'fecha_regreso' => optional($this->fecha_regreso)->toIso8601String(),
            'duracion_minutos' => $this->duracion_minutos,
            'kilometraje_salida' => optional(optional($this->checklistSalida)->condicion)->kilometraje,
            'checklist_salida_completo' => (bool) $this->checklistSalida,
            'checklist_entrada_completo' => (bool) $this->checklistEntrada,
            'vehiculo' => $this->vehiculo ? new MobileVehiculoResource($this->vehiculo) : null,
            'chofer' => $this->chofer ? new MobileUserResource($this->chofer) : null,
            'solicitante' => $this->solicitante ? new MobileUserResource($this->solicitante) : null,
            'checklist_salida' => $this->checklistSalida ? new MobileChecklistResource($this->checklistSalida) : null,
            'checklist_entrada' => $this->checklistEntrada ? new MobileChecklistResource($this->checklistEntrada) : null,
        ];
    }
}
