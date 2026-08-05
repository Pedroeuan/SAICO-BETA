<?php

namespace App\Http\Controllers\Normas_IM;

use App\Http\Controllers\Controller;
use App\Models\Normas_IM\PatronGranoIM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PatronesGranoIMController extends Controller
{
    public function index()
    {
        $patrones = PatronGranoIM::query()->orderBy('valor_grano')->get();

        return view('Normas_IM.Patrones_Grano.index', compact('patrones'));
    }

    public function create()
    {
        return view('Normas_IM.Patrones_Grano.create');
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request, null, true);
        $rutaNueva = $this->guardarImagen($request);

        try {
            PatronGranoIM::create([
                'nombre' => $this->normalizarNombre($datos['nombre']),
                'valor_grano' => $this->valorDesdeNombre($datos['nombre']),
                'ruta_imagen' => $rutaNueva,
            ]);
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($rutaNueva);
            throw $exception;
        }

        return redirect()->route('Patrones_Grano_IM.index')
            ->with('success', 'Patrón comparativo registrado correctamente.');
    }

    public function edit(PatronGranoIM $patron)
    {
        return view('Normas_IM.Patrones_Grano.edit', compact('patron'));
    }

    public function update(Request $request, PatronGranoIM $patron)
    {
        $datos = $this->validar($request, $patron, false);
        $rutaAnterior = $patron->ruta_imagen;
        $rutaNueva = $request->hasFile('imagen') ? $this->guardarImagen($request) : null;

        try {
            $patron->update([
                'nombre' => $this->normalizarNombre($datos['nombre']),
                'valor_grano' => $this->valorDesdeNombre($datos['nombre']),
                'ruta_imagen' => $rutaNueva ?: $rutaAnterior,
            ]);
        } catch (\Throwable $exception) {
            if ($rutaNueva) {
                Storage::disk('public')->delete($rutaNueva);
            }
            throw $exception;
        }

        // La imagen anterior se elimina solo después de confirmar que el registro apunta a la nueva.
        if ($rutaNueva && $rutaAnterior !== $rutaNueva) {
            Storage::disk('public')->delete($rutaAnterior);
        }

        return redirect()->route('Patrones_Grano_IM.index')
            ->with('success', 'Patrón comparativo actualizado correctamente.');
    }

    public function destroy(PatronGranoIM $patron)
    {
        $ruta = $patron->ruta_imagen;
        $patron->delete();
        Storage::disk('public')->delete($ruta);

        return redirect()->route('Patrones_Grano_IM.index')
            ->with('success', 'Patrón comparativo eliminado correctamente.');
    }

    /** Valida nombre e imagen sin confiar en la extensión enviada por el navegador. */
    private function validar(Request $request, ?PatronGranoIM $patron, bool $imagenObligatoria): array
    {
        $nombreRecibido = preg_replace('/\s+/', ' ', trim((string) $request->input('nombre', '')));
        if (preg_match('/^Grano\s+-?\d{1,3}(?:\.\d)?$/i', $nombreRecibido)) {
            $nombreRecibido = $this->normalizarNombre($nombreRecibido);
        }
        // La validación de unicidad recibe la misma representación canónica que se guardará.
        $request->merge(['nombre' => $nombreRecibido]);

        return $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:50',
                'regex:/^Grano\s+-?\d{1,3}(?:\.\d)?$/i',
                Rule::unique('patrones_grano_im', 'nombre')->ignore($patron?->id),
            ],
            'imagen' => [
                $imagenObligatoria ? 'required' : 'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png',
                'max:25600',
            ],
        ], [
            'nombre.regex' => 'Escriba el nombre con el formato Grano 3, Grano 3.5 o un valor futuro equivalente.',
            'nombre.unique' => 'Ese nombre de grano ya está registrado.',
            'imagen.image' => 'El archivo debe ser una imagen JPG o PNG válida.',
            'imagen.max' => 'La imagen no puede superar 25 MB.',
        ]);
    }

    /** Guarda con UUID para impedir colisiones y conservar el nombre visible separado del archivo. */
    private function guardarImagen(Request $request): string
    {
        $imagen = $request->file('imagen');
        $extension = strtolower($imagen->extension() ?: 'png');
        $nombreArchivo = Str::uuid() . '.' . $extension;

        return $imagen->storeAs('Catalogos_IM/Patrones_Grano', $nombreArchivo, 'public');
    }

    /** Estandariza mayúsculas, espacios y ceros para impedir duplicados visuales. */
    private function normalizarNombre(string $nombre): string
    {
        return 'Grano ' . $this->formatearValor($this->valorDesdeNombre($nombre));
    }

    /** Extrae el valor solo para ordenar el catálogo; el dato administrado sigue siendo el nombre. */
    private function valorDesdeNombre(string $nombre): float
    {
        preg_match('/-?\d{1,3}(?:\.\d)?/', $nombre, $coincidencias);

        return (float) ($coincidencias[0] ?? 0);
    }

    private function formatearValor(float $valor): string
    {
        return rtrim(rtrim(number_format($valor, 1, '.', ''), '0'), '.');
    }
}
