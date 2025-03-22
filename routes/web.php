<?php

use Illuminate\Support\Facades\Route;

// Ruta principal que servirá la aplicación Vue
Route::get('/', function () {
    return view('welcome');
});

// Rutas de la API que no deben ser interceptadas por Vue
Route::prefix('api')->group(function () {
    // Las rutas de la API se manejan en api.php
});

// Todas las demás rutas serán manejadas por Vue
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');
