<?php

use App\Enums\RedSocial;
use App\Enums\TipoPublicacion;
use App\Models\User;
use App\Services\Publicaciones\PublicacionService;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    Schema::dropIfExists('publicaciones');

    Schema::create('publicaciones', function (Blueprint $table): void {
        $table->id();
        $table->char('uuid', 36)->unique()->index();
        $table->string('titulo', 150);
        $table->string('slug', 160)->unique();
        $table->text('contenido');
        $table->string('tipo', 50);
        $table->string('imagen', 500)->nullable();
        $table->string('imagen_alt', 200)->nullable();
        $table->string('video', 500)->nullable();
        $table->string('url_destino', 500)->nullable();
        $table->json('redes_objetivo');
        $table->json('resultado_publicacion')->nullable();
        $table->boolean('publicado_en_redes')->default(false);
        $table->timestamp('publicado_at')->nullable();
        $table->timestamp('programado_at')->nullable();
        $table->boolean('activo')->default(true);
        $table->timestamps();
        $table->softDeletes();
    });
});

afterEach(function () {
    Schema::dropIfExists('publicaciones');
});

test('requiere fecha cuando se activa la programacion al crear', function () {
    Storage::fake('public');

    $user = User::factory()->make(['id' => 1]);

    $response = $this
        ->actingAs($user)
        ->from(route('publicaciones.create'))
        ->post(route('publicaciones.store'), [
            'titulo' => 'Publicacion corporativa programada',
            'contenido' => 'Contenido suficiente para pasar la validacion y comprobar la programacion pendiente.',
            'tipo' => TipoPublicacion::Servicio->value,
            'imagen' => UploadedFile::fake()->image('post.jpg'),
            'redes' => [RedSocial::LinkedIn->value],
            'programar_publicacion' => '1',
        ]);

    $response
        ->assertRedirect(route('publicaciones.create'))
        ->assertSessionHasErrors(['programado_at']);
});

test('no permite programar y republicar al mismo tiempo', function () {
    $user = User::factory()->make(['id' => 1]);

    $publicacion = \App\Models\Publicacion::query()->create([
        'titulo' => 'Publicacion existente para edicion',
        'contenido' => 'Contenido de prueba suficientemente largo para editar una publicacion existente sin problemas.',
        'tipo' => TipoPublicacion::Noticia->value,
        'redes_objetivo' => [RedSocial::Facebook->value],
        'publicado_en_redes' => false,
        'activo' => true,
    ]);

    $response = $this
        ->actingAs($user)
        ->from(route('publicaciones.edit', $publicacion))
        ->put(route('publicaciones.update', $publicacion), [
            'titulo' => $publicacion->titulo,
            'contenido' => $publicacion->contenido,
            'tipo' => $publicacion->tipo,
            'redes' => $publicacion->redes_objetivo,
            'programar_publicacion' => '1',
            'programado_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'republicar_redes' => '1',
        ]);

    $response
        ->assertRedirect(route('publicaciones.edit', $publicacion))
        ->assertSessionHasErrors(['republicar_redes']);
});

test('crear una publicacion programada no intenta publicarla de inmediato', function () {
    Config::set('publicaciones.autopublicar', true);
    Config::set('publicaciones.python_script', base_path('scripts-python/archivo-inexistente.py'));

    $service = app(PublicacionService::class);
    $fechaProgramada = Carbon::now()->addDays(4)->startOfHour();

    $publicacion = $service->crear([
        'titulo' => 'Publicacion senior programada',
        'contenido' => 'Contenido suficiente para asegurar que la publicacion quede programada sin enviarse al instante.',
        'tipo' => TipoPublicacion::Promocion->value,
        'redes' => [RedSocial::Twitter->value],
        'programar_publicacion' => true,
        'programado_at' => $fechaProgramada->toDateTimeString(),
    ], null);

    expect($publicacion->programado_at?->format('Y-m-d H:i:s'))->toBe($fechaProgramada->format('Y-m-d H:i:s'));
    expect($publicacion->publicado_en_redes)->toBeFalse();
    expect($publicacion->publicado_at)->toBeNull();
    expect($publicacion->resultado_publicacion)->toBeNull();
});
