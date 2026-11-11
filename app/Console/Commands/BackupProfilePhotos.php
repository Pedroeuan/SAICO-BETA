<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class BackupProfilePhotos extends Command
{
    // Uso manual: php artisan perfil:respaldar-fotos
    protected $signature = 'perfil:respaldar-fotos';

    protected $description = 'Genera un ZIP privado con las fotos de perfil y sus carpetas de usuario';

    public function handle(): int
    {
        // ZipArchive es necesario para conservar todas las fotos en un solo archivo.
        if (!class_exists(ZipArchive::class)) {
            $this->error('La extensión ZIP de PHP no está habilitada.');
            return self::FAILURE;
        }

        $disk = Storage::disk('local');
        // Las fotos permanecen privadas y organizadas por el ID de cada usuario.
        $files = $disk->allFiles('profile-photos');
        if (!$files) {
            $this->info('No hay fotos para respaldar.');
            return self::SUCCESS;
        }

        $disk->makeDirectory('backups/profile-photos');
        // El sufijo aleatorio evita sobrescribir dos respaldos creados el mismo segundo.
        $path = 'backups/profile-photos/'.now()->format('Y-m-d_His').'_'.Str::random(8).'.zip';
        $archive = new ZipArchive;
        if ($archive->open($disk->path($path), ZipArchive::CREATE | ZipArchive::EXCL) !== true) {
            $this->error('No se pudo crear el respaldo.');
            return self::FAILURE;
        }

        try {
            // Se conserva la ruta original dentro del ZIP para facilitar una restauración.
            foreach ($files as $file) {
                if (!$archive->addFile($disk->path($file), $file)) {
                    throw new \RuntimeException('No se pudo agregar una foto al respaldo.');
                }
            }
            if (!$archive->close()) {
                throw new \RuntimeException('No se pudo completar el respaldo.');
            }
        } catch (\Throwable $exception) {
            // Un ZIP incompleto no debe quedar disponible como respaldo válido.
            $disk->delete($path);
            $this->error($exception->getMessage());
            return self::FAILURE;
        }

        $this->info('Respaldo creado: '.$disk->path($path));
        return self::SUCCESS;
    }
}
