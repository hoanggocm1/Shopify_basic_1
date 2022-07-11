<?php

use App\Http\Controllers\Admin\API\LoginController;
// use App\Http\Controllers\Admin\API\ProductController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\ProductController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('admin/createWebHook_createCustomer/{infoShop}', [MainController::class, 'createWebHook_createCustomer']);
Route::get('admin/createWebHook_createProduct/{infoShop}', [MainController::class, 'createWebHook_createProduct']);
Route::get('admin/createWebHook_updateProduct/{infoShop}', [MainController::class, 'createWebHook_updateProduct']);
Route::get('admin/createWebHook_deleteProduct/{infoShop}', [MainController::class, 'createWebHook_deleteProduct']);

Route::post('createProduct/{infoShop}', [ProductController::class, 'createProductShopify']);
Route::post('updateProduct', [ProductController::class, 'updateProductShopify']);
Route::post('deleteProduct', [ProductController::class, 'deleteProductShopify']);

Route::get('deleteProductByApp/{idProduct}/{idShop}', [ProductController::class, 'deleteProductByApp']);

Route::post('editProductByApp/{idProduct}/{idShop}', [ProductController::class, 'updateProductByApp']);
