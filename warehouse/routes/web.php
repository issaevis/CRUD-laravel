<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProfileController;
use App\Models\Categories;
use Illuminate\Support\Facades\Route;

Route::get('/', CategoriesController::class .'@index')->name('categories.index');

Route::get('/categories/create', CategoriesController::class . '@create')->name('categories.create');

Route::post('/categories', CategoriesController::class .'@store')->name('categories.store');

Route::get('/categories/{id}', CategoriesController::class .'@show')->name('categories.show');

Route::get('/categories/{id}/edit', CategoriesController::class .'@edit')->name('categories.edit');

Route::put('/categories/{id}', CategoriesController::class .'@update')->name('categories.update');

Route::delete('/categories/{id}', CategoriesController::class .'@destroy')->name('categories.destroy');




Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::resource('chirps', CategoriesController::class)

    ->only(['index', 'store']);

require __DIR__.'/auth.php';
