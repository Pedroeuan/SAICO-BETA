<?php

use App\Http\Controllers\ProfileController;
//use App\Http\Controllers\HomeController;
use App\Http\Controllers\Equipos\EquiposController;
use App\Http\Controllers\GeneralEycController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('Equipos', [EquiposController::class, 'index'])->name('Equipos');
});

/*Route::middleware('auth')->group(function () {
    Route::get('general_eyc', [GeneralEycController::class, 'index'])->name('general_eyc');
});*/


require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home',[App\Http\Controller\HomeController::class,'index'])->name('home');

//Route::resource('Equipos', EquiposController::class);