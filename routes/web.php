<?php


use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Admin\MainController;
use \App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UploadController;




Route::get('/', [MainController::class, 'shopify'])->name('123');

// Hàm lấy code install app -> tạo AccessToken -> lấy thông tin shop từ AccessToken lưu vào database.
Route::post('/shopify', [MainController::class, 'shopify_']);
Route::prefix('admin')->group(function () {
  // Route::get('/', [MainController::class, 'index']);
  Route::get('/', [MainController::class, 'index']);
  Route::get('/home', [MainController::class, 'homeAdmin'])->name('admin');

  //shopify

  // Route::get('createWebHook_createProduct/{infoShop}', [MainController::class, 'createWebHook_createProduct'])->name('admin');

  // product
  Route::prefix('products')->group(function () {
    Route::get('add', [ProductController::class, 'addProduct']);
    Route::post('add', [ProductController::class, 'createproduct']);
    Route::get('list/{infoShop}', [ProductController::class, 'listProduct']);  //shopify

    Route::get('detailProduct/{id}', [ProductController::class, 'detailProduct']);

    Route::get('editProductByApp/{id}', [ProductController::class, 'editProductByApp']);
    Route::POST('editProduct/{product}', [ProductController::class, 'updateProduct']);
    Route::get('editProductImage/{id}', [ProductController::class, 'editProductImage']);
    Route::post('/change-image-product/{id}', [ProductController::class, 'changeImageProductAjax']);
    // xóa hình ảnh chi tiết sản phẩm ax
    Route::post('/delete-image-product/{id}', [ProductController::class, 'deleteImageProductAjax']);
    //Thêm hình ảnh chi tiết của sản phẩm 
    Route::post('/add-images-product/{id}', [ProductController::class, 'add_images_product']);

    //change active
    Route::post('/update_ProductActive/{id}/{idActive}', [ProductController::class, 'update_ProductActive']);
    // delete product 
    Route::DELETE('delete-product', [ProductController::class, 'deleteProductAjax']);
    // Tim san pham theo tên
    Route::post('search_product_byName', [ProductController::class, 'search_product_byName']);
    // Tim san pham theo giá
    Route::post('search_product_byPrice', [ProductController::class, 'search_product_byPrice']);
    //
    Route::post('search_product_byNameAndPrice', [ProductController::class, 'search_product_byNameAndPrice']);
    //
    Route::post('refresh_listProduct', [ProductController::class, 'refresh_listProduct']);
    Route::post('filter', [ProductController::class, 'filter']);
  });

  //Một ảnh product
  Route::post('/upload/services', [UploadController::class, 'store']);
  // upload 4 ảnh vào storage
  Route::post('/upload/services/images', [UploadController::class, 'stores']);
  // add ảnh
  Route::post('/add-images-product/{id}', [ProductController::class, 'add_images_product']);
  //
  Route::get('/shopify', [UploadController::class, 'shopify']);
  //
});
