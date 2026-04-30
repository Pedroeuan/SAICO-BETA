<?php

namespace Database\Seeders;

use App\Enums\RedSocial;
use App\Enums\TipoPublicacion;
use App\Models\Publicacion;
use Illuminate\Database\Seeder;

class PublicacionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $redes = [
            RedSocial::LinkedIn->value,
            RedSocial::Facebook->value,
            RedSocial::Twitter->value,
        ];

        Publicacion::query()->firstOrCreate(
            ['titulo' => 'Servicio integral de mantenimiento preventivo'],
            [
                'contenido' => 'Ofrecemos mantenimiento preventivo para equipos y servicios de campo con cobertura programada, reportes de seguimiento y evidencia fotográfica para cada visita.',
                'tipo' => TipoPublicacion::Servicio->value,
                'imagen' => null,
                'imagen_alt' => 'Tecnico realizando mantenimiento preventivo',
                'url_destino' => 'https://example.com/servicios/mantenimiento-preventivo',
                'redes_objetivo' => $redes,
                'resultado_publicacion' => null,
                'publicado_en_redes' => false,
                'activo' => true,
            ]
        );
    }
}
