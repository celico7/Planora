<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/connexion', function () {
    return view('connexion');
});

Route::get('/inscription', function () {
    return view('inscription');
});
