<?php

namespace App\Http\Controllers\Publicaciones;

use App\Enums\RedSocial;
use App\Enums\TipoPublicacion;
use App\Http\Controllers\Controller;
use App\Http\Requests\Publicaciones\StorePublicacionRequest;
use App\Http\Requests\Publicaciones\UpdatePublicacionRequest;
use App\Models\Publicacion;
use App\Services\Publicaciones\PublicacionService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        return view('publicaciones.index', [
            'publicaciones' => $publicaciones,
            'tipos' => TipoPublicacion::cases(),
            'tipoSeleccionado' => $tipo,
            'estadoSeleccionado' => $estado,
        ]);
    }

    /**
     * Muestra el formulario de alta.
     */
    public function create(): View
    {
        return view('publicaciones.create', [
            'tipos' => TipoPublicacion::cases(),
            'redes' => RedSocial::cases(),
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
        return view('publicaciones.show', [
            'publicacion' => $publicacion,
            'redes' => RedSocial::cases(),
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
            'redes' => RedSocial::cases(),
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
        $resultadoGeneral = $publicacion->resultado_publicacion['_general']['error'] ?? null;

        if ($resultadoGeneral === 'Publicacion automatica deshabilitada hasta configurar credenciales y entorno Python.') {
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
}
