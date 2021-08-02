<?php

use Illuminate\Support\Facades\Route;

Route::view('/{any?}', 'home')->where('any', '[\/\w\.-]*')->middleware('auth:sanctum');
