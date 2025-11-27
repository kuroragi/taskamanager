<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/kanban');
});

Route::get('/kanban', function () {
    return view('pages.kanban');
})->name('kanban');

Route::get('/priority', function () {
    return view('pages.priority');
})->name('priority');

Route::get('/tasks', function () {
    return view('pages.tasks');
})->name('tasks');

Route::get('/stats', function () {
    return view('pages.stats');
})->name('stats');
