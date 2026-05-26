<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibroController;

Route::redirect('/', '/libros');
Route::resource('libros', LibroController::class);
