<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\TextUI\XmlConfiguration\Group;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\PostController;

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


Route::resource('posts',PostController::class)->only(['index','show']);


Route::prefix('admin')->group(function () {

    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::group([
        'middleware' => 'authAdmin',
    ], function ($router) {

        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/adminProfile',[AuthController::class,'adminProfile']);
        Route::delete('/deleteAdmin/{id}',[AuthController::class,'deleteAdmin']);
        Route::put('/restoreAdmin/{id}',[AuthController::class,'restoreAdmin']);

        Route::resource('posts',PostController::class);
        Route::put('/posts/restore/{id}',[PostController::class,'restore']);
        Route::get('/posts/deleted/list',[PostController::class,'getListPostDeleted']);
        Route::get('/posts/admin-post/{id}',[PostController::class,'getListPostbyIdAdmin']);

        Route::prefix('products')->group(function(){
            
        });

    });
});


Route::prefix('user')->group(function () {

    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register']);

    Route::group([
        'middleware' => 'authUser',
    ], function ($router) {

        Route::post('/logout', [AuthController::class, 'logout']);

    });
});

