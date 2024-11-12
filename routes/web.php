<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pets', [PetsController::class, 'index'])->name('pets.index');
Route::get('/pets/add', [PetsController::class, 'add'])->name('pets.add');
Route::post('/pets/add', [PetsController::class, 'store'])->name('pets.store');
Route::get('/pets/{id}', [PetsController::class, 'details'])->name('pets.details');
Route::get('/pets/edit/{id}', [PetsController::class, 'edit'])->name('pets.edit');
Route::put('/pets/update/{id}', [PetsController::class, 'update'])->name('pets.update');
Route::get('/pets/delete/{id}', [PetsController::class, 'delete'])->name('pets.delete');

