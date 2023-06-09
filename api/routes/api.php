<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Stores
// change the current store for the current user
Route::put('/stores/{store}', [StoreController::class, 'update']);
// return all the stores belonging to the current user
Route::get('/stores', [StoreController::class, 'index']);

// Products
// new product
Route::post('/products', [ProductController::class, 'store']);
// update product
Route::put('/products/{product}', [ProductController::class, 'update']);
// delete product
Route::delete('/products/{product}', [ProductController::class, 'destroy']);
// autocomplete route
Route::get('/products/autocomplete', [ProductController::class, 'autocomplete']);
// route to get the current store's sections and products flagged as "to buy"
Route::get('/products', [ProductController::class, 'index']);