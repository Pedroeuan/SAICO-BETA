<?php

namespace App\Http\Controllers\Publicaciones;

use App\Enums\RedSocial;
use App\Enums\TipoPublicacion;
use App\Http\Controllers\Controller;
use App\Http\Requests\Publicaciones\StorePublicacionRequest;
use App\Http\Requests\Publicaciones\UpdatePublicacionRequest;
use App\Models\Publicacion;
use App\Models\PublicacionMetricaHistorial;
use App\Services\Publicaciones\PublicacionService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Throwable;

class PublicacionController extends Controller
{
    public function __construct(
        protected PublicacionService $service
    ) {
    }

    /**
     * Muestra el listado paginado de publicaciones.
     */
    public function index(Request $request): View
    {
        $tipo = $request->string('tipo')->toString();
        $estado = $request->string('estado')->toString();

        $publicaciones = Publicacion::withTrashed()
            ->when($tipo !== '', fn ($query) => $query->porTipo($tipo))
            ->when($estado === 'publicados', fn ($query) => $query->publicados())
            ->when($estado === 'pendientes', fn ($query) => $query->where('publicado_en_redes', false))
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        $resumenMetricas = (object) [
            'reacciones' => 0,
            'comentarios' => 0,
            'compartidos' => 0,
            'alcance' => 0,
            'impresiones' => 0,
            'clicks' => 0,
            'engagement_promedio' => 0,
        ];

        if (Schema::hasTable('publicacion_metricas_historial')) {
            $resumenMetricas = PublicacionMetricaHistorial::query()
                ->whereDate('fecha_corte', now()->toDateString())
                ->selectRaw('COALESCE(SUM(reacciones), 0) as reacciones')
                ->selectRaw('COALESCE(SUM(comentarios), 0) as comentarios')
                ->selectRaw('COALESCE(SUM(compartidos), 0) as compartidos')
                ->selectRaw('COALESCE(SUM(alcance), 0) as alcance')
                ->selectRaw('COALESCE(SUM(impresiones), 0) as impresiones')
                ->selectRaw('COALESCE(SUM(clicks), 0) as clicks')
                ->selectRaw('COALESCE(AVG(engagement), 0) as engagement_promedio')
                ->first() ?? $resumenMetricas;
        }

        $panelAnalitica = $this->resolverPanelAnalitica();

        return view('publicaciones.index', [
            'publicaciones' => $publicaciones,
            'tipos' => TipoPublicacion::cases(),
            'tipoSeleccionado' => $tipo,
            'estadoSeleccionado' => $estado,
            'redesCatalogo' => $this->redesDisponibles(),
            'resumenMetricas' => $resumenMetricas,
            'panelAnalitica' => $panelAnalitica,
        ]);
    }

    /**
     * Muestra el formulario de alta.
     */
    public function create(): View
    {
        return view('publicaciones.create', [
            'tipos' => TipoPublicacion::cases(),
            'redes' => $this->redesDisponibles(),
        ]);
    }

    /**
     * Guarda una nueva publicación.
     */
    public function store(StorePublicacionRequest $request): RedirectResponse
    {
        try {
            $publicacion = $this->service->crear($request->validated(), $request->file('imagen'));
            [$tipoMensaje, $mensaje] = $this->resolverMensajeResultado($publicacion);

            return redirect()
                ->route('publicaciones.show', $publicacion)
                ->with($tipoMensaje, $mensaje);
        } catch (Throwable $exception) {
            return back()
                ->withInput()
                ->with('error', 'No fue posible crear la publicacion: ' . $exception->getMessage());
        }
    }

    /**
     * Muestra el detalle de la publicación.
     */
    public function show(Publicacion $publicacion): View
    {
        if (Schema::hasTable('publicacion_metricas_historial')) {
            $publicacion->load([
                'metricasHistorial' => fn ($query) => $query
                    ->latest('fecha_corte')
                    ->limit(7),
            ]);
        } else {
            $publicacion->setRelation('metricasHistorial', new EloquentCollection());
        }

        return view('publicaciones.show', [
            'publicacion' => $publicacion,
            'redes' => $this->redesDisponibles(),
        ]);
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(Publicacion $publicacion): View
    {
        return view('publicaciones.edit', [
            'publicacion' => $publicacion,
            'tipos' => TipoPublicacion::cases(),
            'redes' => $this->redesDisponibles(),
        ]);
    }

    /**
     * Actualiza una publicación existente.
     */
    public function update(UpdatePublicacionRequest $request, Publicacion $publicacion): RedirectResponse
    {
        try {
            $publicacion = $this->service->actualizar($publicacion, $request->validated(), $request->file('imagen'));
            $republicada = (bool) $request->boolean('republicar_redes');

            if (!$republicada) {
                if ($publicacion->estaProgramada()) {
                    $fecha = optional($publicacion->programado_at)->format('d/m/Y H:i');

                    return redirect()
                        ->route('publicaciones.show', $publicacion)
                        ->with('success', "La publicacion se actualizo correctamente y quedo programada para el {$fecha}.");
                }

                return redirect()
                    ->route('publicaciones.show', $publicacion)
                    ->with('success', 'La publicacion se actualizo correctamente.');
            }

            [$tipoMensaje, $mensaje] = $this->resolverMensajeResultado($publicacion, true);

            return redirect()
                ->route('publicaciones.show', $publicacion)
                ->with($tipoMensaje, $mensaje);
        } catch (Throwable $exception) {
            return back()
                ->withInput()
                ->with('error', 'No fue posible actualizar la publicacion: ' . $exception->getMessage());
        }
    }

    /**
     * Elimina lógicamente una publicación.
     */
    public function destroy(Publicacion $publicacion): RedirectResponse
    {
        $this->service->eliminar($publicacion);

        return redirect()
            ->route('publicaciones.index')
            ->with('success', 'La publicacion fue eliminada correctamente.');
    }

    /**
     * Restaura una publicación eliminada por soft delete.
     */
    public function restaurar(int $id): RedirectResponse
    {
        $publicacion = Publicacion::withTrashed()->findOrFail($id);
        $publicacion->restore();

        return redirect()
            ->route('publicaciones.index')
            ->with('success', 'La publicacion fue restaurada correctamente.');
    }

    /**
     * @return array{0:string,1:string}
     */
    protected function resolverMensajeResultado(Publicacion $publicacion, bool $republicada = false): array
    {
        if ($publicacion->estaProgramada()) {
            $accion = $republicada ? 'actualizo' : 'guardo';
            $fecha = optional($publicacion->programado_at)->format('d/m/Y H:i');

            return ['success', "La publicacion se {$accion} correctamente y quedo programada para el {$fecha}."];
        }

        $resultadoGeneral = $publicacion->resultado_publicacion['_general']['error'] ?? null;

        if (in_array($resultadoGeneral, [
            'Publicacion automatica deshabilitada hasta configurar credenciales y entorno Python.',
            'Publicacion automatica bloqueada por modo solo lectura para analitica local.',
        ], true)) {
            return ['warning', 'La publicacion se guardo correctamente. La publicacion automatica en redes esta deshabilitada por ahora.'];
        }

        $estado = $publicacion->estadoRedes();
        $accion = $republicada ? 'actualizo' : 'guardo';

        return match ($estado) {
            'exito' => ['success', "La publicacion se {$accion} y se publico correctamente en todas las redes seleccionadas."],
            'parcial' => ['warning', "La publicacion se {$accion}, pero solo algunas redes sociales respondieron correctamente."],
            'error' => ['warning', "La publicacion se {$accion}, pero fallo la publicacion en redes sociales."],
            default => ['success', "La publicacion se {$accion} correctamente."],
        };
    }

    /**
     * @return array<int, RedSocial>
     */
    protected function redesDisponibles(): array
    {
        $habilitadas = config('publicaciones.redes_habilitadas', ['facebook']);

        return array_values(array_filter(
            RedSocial::cases(),
            fn (RedSocial $red): bool => in_array($red->value, $habilitadas, true)
        ));
    }

    /**
     * @return array<string, mixed>
     */
    protected function resolverPanelAnalitica(): array
    {
        $base = [
            'totalConInteraccion' => 0,
            'totalSinInteraccion' => 0,
            'interaccionesVisibles' => 0,
            'publicacionDestacada' => null,
            'topPublicaciones' => [],
            'graficaTop' => [
                'labels' => [],
                'reacciones' => [],
                'comentarios' => [],
                'compartidos' => [],
            ],
            'graficaLinea' => [
                'labels' => [],
                'interacciones' => [],
            ],
            'graficaEstadoInteraccion' => [
                'labels' => ['Con interacción', 'Sin interacción'],
                'values' => [0, 0],
            ],
            'graficaTipoPublicacion' => [
                'labels' => [],
                'values' => [],
            ],
            'graficaComentarios' => [
                'labels' => [],
                'values' => [],
            ],
            'graficaReacciones' => [
                'labels' => [],
                'values' => [],
            ],
        ];

        if (!Schema::hasColumn('publicaciones', 'metricas_facebook_json')) {
            return $base;
        }

        $publicacionesMetricas = Publicacion::query()
            ->whereNotNull('facebook_post_id')
            ->whereNull('deleted_at')
            ->get();

        if ($publicacionesMetricas->isEmpty()) {
            return $base;
        }

        $metricasNormalizadas = $publicacionesMetricas->map(function (Publicacion $publicacion): array {
            $metricas = $publicacion->metricas_facebook_json ?? [];
            $reacciones = (int) ($metricas['reacciones'] ?? 0);
            $comentarios = (int) ($metricas['comentarios'] ?? 0);
            $compartidos = (int) ($metricas['compartidos'] ?? 0);
            $interacciones = $reacciones + $comentarios + $compartidos;
            $folio = $this->resolverFolioPublicacion($publicacion->id);

            return [
                'id' => $publicacion->id,
                'folio' => $folio,
                'titulo' => $publicacion->titulo,
                'titulo_corto' => $this->resumirTituloPublicacion($publicacion->titulo, 58),
                'etiqueta_corta' => $this->resolverEtiquetaPublicacion($folio, $publicacion->titulo, 38),
                'etiqueta_media' => $this->resolverEtiquetaPublicacion($folio, $publicacion->titulo, 34),
                'slug' => $publicacion->slug,
                'tipo' => $publicacion->tipo,
                'reacciones' => $reacciones,
                'comentarios' => $comentarios,
                'compartidos' => $compartidos,
                'interacciones' => $interacciones,
                'sincronizado_metricas_at' => optional($publicacion->sincronizado_metricas_at)?->format('d/m/Y H:i'),
            ];
        });

        $topPublicaciones = $metricasNormalizadas
            ->sortByDesc('interacciones')
            ->sortByDesc('comentarios')
            ->take(5)
            ->values();

        $publicacionDestacada = $topPublicaciones->first();

        $linea = collect();

        if (Schema::hasTable('publicacion_metricas_historial')) {
            $linea = PublicacionMetricaHistorial::query()
                ->whereDate('fecha_corte', '>=', now()->subDays(6)->toDateString())
                ->orderBy('fecha_corte')
                ->get()
                ->groupBy(fn (PublicacionMetricaHistorial $item): string => $item->fecha_corte->format('d/m'))
                ->map(function (Collection $items): int {
                    return $items->sum(function (PublicacionMetricaHistorial $item): int {
                        return (int) $item->reacciones + (int) $item->comentarios + (int) $item->compartidos;
                    });
                });
        }

        $porTipo = $metricasNormalizadas
            ->groupBy('tipo')
            ->map(fn (Collection $items): int => $items->sum('interacciones'))
            ->sortDesc();

        $topComentarios = $metricasNormalizadas
            ->sortByDesc('comentarios')
            ->take(5)
            ->values();

        $topReacciones = $metricasNormalizadas
            ->sortByDesc('reacciones')
            ->take(5)
            ->values();

        $conInteraccion = $metricasNormalizadas->where('interacciones', '>', 0)->count();
        $sinInteraccion = $metricasNormalizadas->where('interacciones', 0)->count();

        return [
            'totalConInteraccion' => $conInteraccion,
            'totalSinInteraccion' => $sinInteraccion,
            'interaccionesVisibles' => $metricasNormalizadas->sum('interacciones'),
            'publicacionDestacada' => $publicacionDestacada,
            'topPublicaciones' => $topPublicaciones->all(),
            'graficaTop' => [
                'labels' => $topPublicaciones->pluck('etiqueta_corta')->all(),
                'reacciones' => $topPublicaciones->pluck('reacciones')->all(),
                'comentarios' => $topPublicaciones->pluck('comentarios')->all(),
                'compartidos' => $topPublicaciones->pluck('compartidos')->all(),
            ],
            'graficaLinea' => [
                'labels' => $linea->keys()->values()->all(),
                'interacciones' => $linea->values()->all(),
            ],
            'graficaEstadoInteraccion' => [
                'labels' => ['Con interacción', 'Sin interacción'],
                'values' => [$conInteraccion, $sinInteraccion],
            ],
            'graficaTipoPublicacion' => [
                'labels' => $porTipo->keys()->map(function (string $tipo): string {
                    return TipoPublicacion::from($tipo)->label();
                })->values()->all(),
                'values' => $porTipo->values()->all(),
            ],
            'graficaComentarios' => [
                'labels' => $topComentarios->pluck('etiqueta_media')->all(),
                'values' => $topComentarios->pluck('comentarios')->all(),
            ],
            'graficaReacciones' => [
                'labels' => $topReacciones->pluck('etiqueta_media')->all(),
                'values' => $topReacciones->pluck('reacciones')->all(),
            ],
        ];
    }

    protected function resolverFolioPublicacion(int $id): string
    {
        return 'PUB-' . $id;
    }

    protected function resumirTituloPublicacion(string $titulo, int $limite = 60): string
    {
        return mb_strimwidth(trim($titulo), 0, $limite, '...');
    }

    protected function resolverEtiquetaPublicacion(string $folio, string $titulo, int $limiteTitulo = 36): string
    {
        return $folio . ' | ' . $this->resumirTituloPublicacion($titulo, $limiteTitulo);
    }
}
