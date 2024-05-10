<?php
//use App\Http\Controllers\HomeController;

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;

use App\Http\Controllers\EquiposyConsumibles\general_eycController;

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

/*PDF*/
Route::middleware('auth')->group(function () {
Route::post('/upload-pdf', [PDFController::class, 'upload'])->name('upload.pdf');
});

/*Equipos y Consumibles*/ 
Route::middleware('auth')->group(function () {

    Route::get('inventario', [general_eycController::class, 'index'])->name('inventario');
    Route::get('registro/create', [general_eycController::class, 'create'])->name('registro/create');
});

require __DIR__.'/auth.php';

Auth::routes();

//Route::get('/home',[App\Http\Controller\HomeController::class,'index'])->name('home');