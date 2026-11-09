<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\OC\OCController;
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
    echo "done\n";
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
} finally {
    DB::rollBack();
}
