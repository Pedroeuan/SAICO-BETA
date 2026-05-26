<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\EncuestaSatisfaccionVehicularRequest;
use App\Models\Vehiculos\EncuestaSatisfaccionVehicular;
use App\Models\Vehiculos\SalidaVehiculo;
use App\Services\Vehiculos\EncuestaSatisfaccionVehicularService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class EncuestaSatisfaccionVehicularController extends Controller
{
    public function __construct(
        protected EncuestaSatisfaccionVehicularService $service
    ) {
    }

    public function index(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfTableMissing()) {
            return $redirect;
        }

        $hoy = now();
        $mesSeleccionado = (int) $request->input('mes', $hoy->month);
        $anioSeleccionado = (int) $request->input('anio', $hoy->year);

        if ($mesSeleccionado < 1 || $mesSeleccionado > 12) {
            $mesSeleccionado = $hoy->month;
        }

        $query = EncuestaSatisfaccionVehicular::query()
            ->with([
                'vehiculo:id,placa,marca,modelo',
                'usuario:id,name,rol',
                'salidaVehiculo:id,fecha_salida,fecha_regreso,vehiculo_id',
            ])
            ->whereMonth('fecha_encuesta', $mesSeleccionado)
            ->whereYear('fecha_encuesta', $anioSeleccionado);

        $encuestas = (clone $query)
            ->latest('respondida_en')
            ->paginate(15)
            ->appends([
                'mes' => $mesSeleccionado,
                'anio' => $anioSeleccionado,
            ]);

        $coleccion = (clone $query)->get();
        $resumen = $this->buildResumen($coleccion);

        $sentimientoPorTipo = $coleccion
            ->groupBy('sentimiento')
            ->map(fn ($items) => $items->count());

        $topVehiculos = $coleccion
            ->groupBy('vehiculo_id')
            ->map(function ($items) {
                $vehiculo = $items->first()->vehiculo;

                return (object) [
                    'vehiculo' => $vehiculo,
                    'encuestas' => $items->count(),
                    'promedio' => round($items->avg('promedio_general'), 2),
                    'nps_promedio' => round($items->avg('nps'), 2),
                ];
            })
            ->sortByDesc('encuestas')
            ->take(5)
            ->values();

        return view('vehiculos.encuestas.index', compact(
            'encuestas',
            'resumen',
            'mesSeleccionado',
            'anioSeleccionado',
            'sentimientoPorTipo',
            'topVehiculos'
        ));
    }

    public function create(int $salidaId): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfTableMissing()) {
            return $redirect;
        }

        $salida = $this->resolveSalidaParaEncuesta($salidaId);
        if ($salida instanceof RedirectResponse) {
            return $salida;
        }

        $existente = EncuestaSatisfaccionVehicular::query()
            ->where('salida_vehiculo_id', $salida->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existente) {
            return redirect()
                ->route('salidas.index')
                ->with('warning', 'Ya registraste tu encuesta para este servicio vehicular.');
        }

        return view('vehiculos.encuestas.create', compact('salida'));
    }

    public function store(EncuestaSatisfaccionVehicularRequest $request, int $salidaId): RedirectResponse
    {
        if ($redirect = $this->redirectIfTableMissing()) {
            return $redirect;
        }

        $salida = $this->resolveSalidaParaEncuesta($salidaId);
        if ($salida instanceof RedirectResponse) {
            return $salida;
        }

        $existente = EncuestaSatisfaccionVehicular::query()
            ->where('salida_vehiculo_id', $salida->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($existente) {
            return redirect()
                ->route('salidas.index')
                ->with('warning', 'Ya registraste tu encuesta para este servicio vehicular.');
        }

        $this->service->createFromSalida($salida, auth()->user(), $request->validated());

        return redirect()
            ->route('salidas.index')
            ->with('success', 'Encuesta registrada correctamente. Gracias por tu retroalimentacion.');
    }

    private function resolveSalidaParaEncuesta(int $salidaId): SalidaVehiculo|RedirectResponse
    {
        $salida = SalidaVehiculo::query()
            ->with(['vehiculo:id,placa,marca,modelo', 'chofer:id,name', 'solicitante:id,name'])
            ->findOrFail($salidaId);

        $esAdmin = $this->isAdminRole((string) auth()->user()->rol);
        $esRelacionado = in_array(auth()->id(), [(int) $salida->chofer_id, (int) $salida->solicitado_por], true);

        if (!$esAdmin && !$esRelacionado) {
            return redirect()
                ->route('salidas.index')
                ->with('warning', 'No tienes permiso para responder esta encuesta.');
        }

        if (!in_array($salida->estatus, ['finalizado', 'finaliizado'], true)) {
            return redirect()
                ->route('salidas.index')
                ->with('warning', 'La encuesta solo esta disponible para salidas finalizadas.');
        }

        return $salida;
    }

    private function redirectIfTableMissing(): ?RedirectResponse
    {
        if (Schema::hasTable('encuestas_satisfaccion_vehicular')) {
            return null;
        }

        return redirect()
            ->route('salidas.index')
            ->with('warning', 'El modulo de encuestas aun no esta habilitado en esta base local. Ejecuta su migracion especifica.');
    }

    private function buildResumen($encuestas): array
    {
        $promedioSatisfaccion = $encuestas->count() > 0
            ? round($encuestas->avg('promedio_general'), 2)
            : 0.0;

        $npsPromedio = $encuestas->count() > 0
            ? round($encuestas->avg('nps'), 2)
            : 0.0;

        $promotores = $encuestas->filter(fn ($item) => (int) $item->nps >= 9)->count();
        $detractores = $encuestas->filter(fn ($item) => (int) $item->nps <= 6)->count();
        $total = max(1, $encuestas->count());
        $indiceNps = round((($promotores / $total) * 100) - (($detractores / $total) * 100), 2);

        return [
            'total_encuestas' => $encuestas->count(),
            'promedio_satisfaccion' => $promedioSatisfaccion,
            'nps_promedio' => $npsPromedio,
            'indice_nps' => $indiceNps,
            'positivas' => $encuestas->where('sentimiento', 'positivo')->count(),
            'neutras' => $encuestas->where('sentimiento', 'neutro')->count(),
            'negativas' => $encuestas->where('sentimiento', 'negativo')->count(),
        ];
    }

    private function isAdminRole(string $rol): bool
    {
        $rol = str($rol)
            ->trim()
            ->lower()
            ->replace('_', ' ')
            ->replace('-', ' ')
            ->squish()
            ->value();

        return in_array($rol, [
            'admin',
            'administrador',
            'super admin',
            'super administrador',
            'superadministrador',
        ], true);
    }
}
