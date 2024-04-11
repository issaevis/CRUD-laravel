<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProfileController;
use App\Models\Categories;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


Route::get('/', CategoriesController::class .'@index')->name('categories.index')->middleware('auth');

Route::get('/categories/create', CategoriesController::class . '@create')->name('categories.create')->middleware('auth');

Route::post('/categories', CategoriesController::class .'@store')->name('categories.store')->middleware('auth');

Route::get('/categories/{id}', CategoriesController::class .'@show')->name('categories.show')->middleware('auth');

Route::get('/categories/{id}/edit', CategoriesController::class .'@edit')->name('categories.edit')->middleware('auth');

Route::put('/categories/{id}', CategoriesController::class .'@update')->name('categories.update')->middleware('auth');

Route::delete('/categories/{id}', CategoriesController::class .'@destroy')->name('categories.destroy')->middleware('auth');




Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])
->name('logout');

require __DIR__.'/auth.php';
