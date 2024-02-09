<?php

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::put('categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
