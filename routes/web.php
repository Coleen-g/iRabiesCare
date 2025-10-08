<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/{any}', function () {
    return view('app'); // your main Blade that loads React
})->where('any', '.*');

Route::get('/sanctum/csrf-cookie', function () {
    return response()->noContent();
});



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



require __DIR__.'/auth.php';

