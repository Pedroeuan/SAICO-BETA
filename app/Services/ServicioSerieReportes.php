<?php

namespace App\Services;

use App\Models\Reporte\reporte;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Administra una serie sin tabla adicional. La configuracion vive dentro de
 * Reportes.Detalles_Generales, en la clave SERIE_REPORTES.
 */
class ServicioSerieReportes
{
    public const FORMATO_06_B_01 = 'FOR-PIMP-06_B/01';
    private const CLAVE = 'SERIE_REPORTES';

    public function iniciar(int $idReporte, int $cantidadPlanificada): Fluent
    {
        return DB::transaction(function () use ($idReporte, $cantidadPlanificada): Fluent {
            $reporte = $this->buscarReporte($idReporte, true);
            $existente = $this->leerSerie($reporte);
            if ($existente) {
                return $existente;
            }

            $cantidad = max(1, $cantidadPlanificada);
            $serie = [
                'serie_uuid' => (string) Str::uuid(),
                'id_reporte' => $idReporte,
                'formato' => self::FORMATO_06_B_01,
                'numero_orden' => 1,
                'cantidad_planificada' => $cantidad,
                'total_paginas' => null,
                'pagina_inicial' => null,
                'pagina_final' => null,
                'estado' => $cantidad === 1 ? 'completa' : 'abierta',
            ];
            $this->guardarSerie($reporte, $serie);

            return new Fluent($serie);
        });
    }

    public function obtener(int $idReporte): ?Fluent
    {
        $reporte = reporte::query()->find($idReporte);
        return $reporte ? $this->leerSerie($reporte) : null;
    }

    /** Usa los reportes ya cargados por el listado y evita otra consulta. */
    public function obtenerVarios(iterable $reportes): Collection
    {
        $series = collect();
        foreach ($reportes as $reporte) {
            $serie = $this->leerSerie($reporte);
            if ($serie) {
                $series->put((int) $reporte->idReportes, $serie);
            }
        }
        return $series;
    }

    public function actualizarCantidad(int $idReporte, int $cantidad): Fluent
    {
        return DB::transaction(function () use ($idReporte, $cantidad): Fluent {
            $actual = $this->leerSerie($this->buscarReporte($idReporte, true));
            if (!$actual) {
                throw new RuntimeException('El reporte no tiene una serie configurada.');
            }

            $miembros = $this->reportesDeSerie((string) $actual->serie_uuid, true);
            $creados = $miembros->count();
            $cantidad = max(1, $cantidad);
            if ($cantidad < $creados) {
                throw new RuntimeException("La serie ya contiene {$creados} reportes; no puede reducirse por debajo de esa cantidad.");
            }

            foreach ($miembros as $miembro) {
                $datos = $this->datosSerie($miembro);
                $datos['cantidad_planificada'] = $cantidad;
                $datos['estado'] = $cantidad === $creados ? 'completa' : 'abierta';
                $this->guardarSerie($miembro, $datos);
            }

            return $this->obtener($idReporte)
                ?? throw new RuntimeException('No fue posible actualizar la serie.');
        });
    }

    public function registrarConsecutivo(int $idReporteOrigen, int $idReporteNuevo): Fluent
    {
        return DB::transaction(function () use ($idReporteOrigen, $idReporteNuevo): Fluent {
            $origen = $this->leerSerie($this->buscarReporte($idReporteOrigen, true));
            if (!$origen) {
                throw new RuntimeException('El reporte anterior no tiene una serie configurada.');
            }

            $miembros = $this->reportesDeSerie((string) $origen->serie_uuid, true);
            $ultimoOrden = (int) $miembros->max(fn (reporte $item) => $this->datosSerie($item)['numero_orden'] ?? 0);
            if ((int) $origen->numero_orden !== $ultimoOrden) {
                throw new RuntimeException('El consecutivo debe crearse desde el ultimo reporte de la serie.');
            }

            $ordenNuevo = $ultimoOrden + 1;
            if ($ordenNuevo > (int) $origen->cantidad_planificada) {
                throw new RuntimeException('La serie ya alcanzo la cantidad planificada. Amplie la cantidad desde Editar antes de crear otro consecutivo.');
            }

            $nuevoReporte = $this->buscarReporte($idReporteNuevo, true);
            $serieNueva = [
                'serie_uuid' => (string) $origen->serie_uuid,
                'id_reporte' => $idReporteNuevo,
                'formato' => self::FORMATO_06_B_01,
                'numero_orden' => $ordenNuevo,
                'cantidad_planificada' => (int) $origen->cantidad_planificada,
                'total_paginas' => null,
                'pagina_inicial' => null,
                'pagina_final' => null,
                'estado' => $ordenNuevo === (int) $origen->cantidad_planificada ? 'completa' : 'abierta',
            ];
            $this->guardarSerie($nuevoReporte, $serieNueva);

            if ($serieNueva['estado'] === 'completa') {
                foreach ($miembros as $miembro) {
                    $datos = $this->datosSerie($miembro);
                    $datos['estado'] = 'completa';
                    $this->guardarSerie($miembro, $datos);
                }
            }

            return new Fluent($serieNueva);
        });
    }

    public function puedeCrearSiguiente(int $idReporte): bool
    {
        $actual = $this->obtener($idReporte);
        return !$actual || $this->ultimoOrden((string) $actual->serie_uuid) < (int) $actual->cantidad_planificada;
    }

    public function esUltimo(int $idReporte): bool
    {
        $actual = $this->obtener($idReporte);
        return $actual
            && (int) $actual->numero_orden === $this->ultimoOrden((string) $actual->serie_uuid);
    }

    public function eliminarReporte(int $idReporte): void
    {
        DB::transaction(function () use ($idReporte): void {
            $reporteEliminado = reporte::query()->lockForUpdate()->find($idReporte);
            if (!$reporteEliminado || !($actual = $this->leerSerie($reporteEliminado))) {
                return;
            }

            $miembros = $this->reportesDeSerie((string) $actual->serie_uuid, true)
                ->reject(fn (reporte $item) => (int) $item->idReportes === $idReporte)
                ->values();
            $pagina = 1;
            foreach ($miembros as $indice => $miembro) {
                $datos = $this->datosSerie($miembro);
                $paginas = max(1, (int) ($datos['total_paginas'] ?? 2));
                $datos['numero_orden'] = $indice + 1;
                $datos['pagina_inicial'] = $pagina;
                $datos['pagina_final'] = $pagina + $paginas - 1;
                $datos['estado'] = $miembros->count() >= (int) $actual->cantidad_planificada ? 'completa' : 'abierta';
                $this->guardarSerie($miembro, $datos);
                $pagina += $paginas;
            }
        });
    }

    public function registrarPaginas(int $idReporte, int $totalPaginas): array
    {
        return DB::transaction(function () use ($idReporte, $totalPaginas): array {
            $reporteActual = $this->buscarReporte($idReporte, true);
            $actual = $this->leerSerie($reporteActual);
            if (!$actual) {
                return ['pagina_inicial' => 1, 'pagina_final' => max(1, $totalPaginas), 'total_estimado' => max(1, $totalPaginas)];
            }

            $datosActuales = $this->datosSerie($reporteActual);
            $datosActuales['total_paginas'] = max(1, $totalPaginas);
            $this->guardarSerie($reporteActual, $datosActuales);

            $miembros = $this->reportesDeSerie((string) $actual->serie_uuid, true);
            $pagina = 1;
            foreach ($miembros as $miembro) {
                $datos = $this->datosSerie($miembro);
                $paginas = max(1, (int) ($datos['total_paginas'] ?? 2));
                $datos['pagina_inicial'] = $pagina;
                $datos['pagina_final'] = $pagina + $paginas - 1;
                $this->guardarSerie($miembro, $datos);
                $pagina += $paginas;
            }

            $actualizado = $this->obtener($idReporte)
                ?? throw new RuntimeException('No fue posible recalcular la paginacion.');
            $faltantes = max(0, (int) $actualizado->cantidad_planificada - $miembros->count());
            $totalEstimado = ($pagina - 1) + ($faltantes * 2);

            return [
                'pagina_inicial' => (int) $actualizado->pagina_inicial,
                'pagina_final' => (int) $actualizado->pagina_final,
                'total_estimado' => max((int) $actualizado->pagina_final, $totalEstimado),
            ];
        });
    }

    private function ultimoOrden(string $serieUuid): int
    {
        return (int) $this->reportesDeSerie($serieUuid)
            ->max(fn (reporte $item) => $this->datosSerie($item)['numero_orden'] ?? 0);
    }

    private function buscarReporte(int $idReporte, bool $bloquear = false): reporte
    {
        $consulta = reporte::query();
        if ($bloquear) {
            $consulta->lockForUpdate();
        }
        return $consulta->findOrFail($idReporte);
    }

    private function reportesDeSerie(string $serieUuid, bool $bloquear = false): EloquentCollection
    {
        $consulta = reporte::query()->whereNotNull('Detalles_Generales');
        $rutaJson = '$.' . self::CLAVE . '.serie_uuid';

        if (DB::connection()->getDriverName() === 'sqlite') {
            $consulta->whereRaw('json_valid(Detalles_Generales) = 1')
                ->whereRaw('json_extract(Detalles_Generales, ?) = ?', [$rutaJson, $serieUuid]);
        } else {
            $consulta->whereRaw('JSON_VALID(Detalles_Generales) = 1')
                ->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(Detalles_Generales, ?)) = ?', [$rutaJson, $serieUuid]);
        }

        if ($bloquear) {
            $consulta->lockForUpdate();
        }

        return $consulta->get()
            ->sortBy(fn (reporte $item) => $this->datosSerie($item)['numero_orden'] ?? PHP_INT_MAX)
            ->values();
    }

    private function leerSerie(reporte $reporte): ?Fluent
    {
        $datos = $this->datosSerie($reporte);
        return $datos === [] ? null : new Fluent($datos);
    }

    private function datosSerie(reporte $reporte): array
    {
        $detalles = json_decode((string) $reporte->Detalles_Generales, true);
        $serie = is_array($detalles) ? ($detalles[self::CLAVE] ?? null) : null;
        return is_array($serie) ? $serie : [];
    }

    private function guardarSerie(reporte $reporte, array $serie): void
    {
        $detalles = json_decode((string) $reporte->Detalles_Generales, true);
        $detalles = is_array($detalles) ? $detalles : [];
        $detalles[self::CLAVE] = $serie;
        $reporte->Detalles_Generales = json_encode($detalles, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $reporte->save();
    }
}
