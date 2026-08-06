<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('notificaciones:crear-certificados')->dailyAt('04:00');
// Los UUID permanecen el tiempo suficiente para recuperar una recarga y luego se depuran.
Schedule::command('procesamientos:limpiar-vencidos')->dailyAt('03:30')->withoutOverlapping();
//Schedule::command('notificaciones:crear-certificados')->daily();
//Schedule::command('notificaciones:crear-certificados')->everyMinute();
