<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\TextUI\XmlConfiguration\Group;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\PostController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Models\Category;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//post
Route::resource('posts', PostController::class)->only(['index', 'show']);

// product
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);


// category
Route::get('/category', [CategoryController::class, 'index']);
Route::get('/category/list', [CategoryController::class, 'list']);
Route::get('/category/{id}', [CategoryController::class, 'show']);
Route::get('/category/{id}/products',[CategoryController::class,'listProductFromCateId']);


Route::prefix('admin')->group(function () {
    Route::group([
        'middleware' => 'authAdmin',
    ], function ($router) {

        Route::post('/login', [AuthController::class, 'login'])->name('login')->withoutMiddleware('authAdmin');
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/listAdmin',[AuthController::class,'getListAdmin']);
        Route::get('/profile', [AuthController::class, 'adminProfile']);
        Route::get('/profile/{id}',[AuthController::class,'adminProfileById']);
        Route::put('/update/{id}',[AuthController::class,'updateAdmin']);
        Route::delete('/{id}', [AuthController::class, 'deleteAdmin']);
        Route::put('/{id}/restore', [AuthController::class, 'restoreAdmin']);
        Route::get('/{id}/posts', [AuthController::class, 'getListPostbyIdAdmin']);
        Route::put('/reset-password/{id}',[AuthController::class,'resetPassword']);

        Route::get('/listUser',[AuthController::class,'getListUser']);

        Route::resource('posts', PostController::class);
        Route::put('/posts/{id}/restore', [PostController::class, 'restore']);
        Route::get('/posts/deleted/list', [PostController::class, 'getListPostDeleted']);

        Route::prefix('products')->group(function () {

            Route::post('/', [ProductController::class, 'store']);
            Route::put('/update/{id}', [ProductController::class, 'update']);
            Route::delete('/delete/{id}', [ProductController::class, 'destroy']);
            Route::put('/{id}/restore', [ProductController::class, 'restore']);
            Route::get('/listDeleted', [ProductController::class, 'getListProductDeleted']);
        });

        Route::prefix('category')->group(function () {
            Route::post('/', [CategoryController::class, 'store']);
            Route::put('/{id}', [CategoryController::class, 'update']);
            Route::delete('/{id}', [CategoryController::class, 'destroy']);
            Route::put('/{id}/restore', [CategoryController::class, 'restore']);
        });
    });
});


Route::prefix('user')->group(function () {
    Route::group([
        'middleware' => 'authUser',
    ], function ($router) {

        Route::post('/register', [UserController::class, 'register'])->withoutMiddleware('authUser');
        Route::post('/login', [UserController::class, 'login'])->name('login')->withoutMiddleware('authUser');
        Route::get('/profile',[UserController::class,'userProfile']);//profile from token login
        Route::get('/{id}',[UserController::class,'show']);//profile from user's ID
        Route::post('/logout', [UserController::class, 'logout']);
    });
});
