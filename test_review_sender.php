<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\OC\OCController;
use App\Models\Notificacion\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

$controller = app(OCController::class);

DB::beginTransaction();

try {
    Notification::fake();
    Auth::loginUsingId(1);
    $controller->updateOC_Revision(Request::create('/OC/updateOC_Revision/3', 'POST', [
        'Revision_OC' => 'Comentario de revisión para copia remitente.',
    ]), 3);

    $revision = Notificacion::where('Mensaje_Corto', 'like', '%comentario de revisión%')
        ->get(['idNotificaciones', 'users_id', 'Mensaje_Corto']);

    echo 'count=' . $revision->count() . PHP_EOL;
    foreach ($revision as $n) {
        echo $n->idNotificaciones . ' user=' . $n->users_id . ' ' . $n->Mensaje_Corto . PHP_EOL;
    }
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
    echo $e->getFile() . ':' . $e->getLine() . PHP_EOL;
} finally {
    DB::rollBack();
}
