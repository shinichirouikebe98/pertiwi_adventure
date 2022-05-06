<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\PermissionController;

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

//group route with prefix "admin"
Route::prefix('admin')->group(function () {

    //route login
    Route::post('/login', [App\Http\Controllers\Api\Admin\LoginController::class, 'index']);

    //group route with middleware "auth"
    Route::group(['middleware' => 'auth:api'], function() {

        //data user
        Route::get('/user', [App\Http\Controllers\Api\Admin\LoginController::class, 'getUser']);

        //refresh token JWT
        Route::get('/refresh', [App\Http\Controllers\Api\Admin\LoginController::class, 'refreshToken']);

        //logout
        Route::post('/logout', [App\Http\Controllers\Api\Admin\LoginController::class, 'logout']);

        //user crud route
        Route::group(['middleware' => 'role:Admin'], function() {
            Route::apiResource('users', App\Http\Controllers\Api\Admin\UserController::class);
        });

        //authorization
        Route::prefix('authorization')->group(function () {

            //gates data for vue gates
            Route::get('/permissions-gates', PermissionController::class);
            Route::get('/roles-gates', RoleController::class);
            //get roles name for checkbox
            Route::get('/roles-names', [App\Http\Controllers\Api\Admin\RoleController::class, 'getRoleNames']);

            //admin only
            Route::group(['middleware' => 'role:Admin'], function() {
                Route::apiResource('/permissions', App\Http\Controllers\Api\Admin\PermissionController::class);
                Route::apiResource('/roles', App\Http\Controllers\Api\Admin\RoleController::class);
            });

        });
    
    });


    Route::apiResource('/beritas', App\Http\Controllers\Api\Admin\BeritaController::class);
    Route::apiResource('/promotions', App\Http\Controllers\Api\Admin\PromotionController::class);
    Route::apiResource('/videos', App\Http\Controllers\Api\Admin\VideoController::class);
    Route::apiResource('/images', App\Http\Controllers\Api\Admin\ImageController::class);
    Route::apiResource('/articles', App\Http\Controllers\Api\Admin\ArticleController::class);

    Route::get('/dashboard', [App\Http\Controllers\Api\Admin\DashboardController::class, 'index']);

});


//group route with prefix "web"
Route::prefix('web')->group(function () {

    //videos routes
    Route::get('/videos', [App\Http\Controllers\Api\Web\VideoController::class, 'index']);

    //promotion routes
    Route::get('/promotions', [App\Http\Controllers\Api\Web\PromotionController::class, 'index']);
    Route::get('/promotions/slug',[App\Http\Controllers\Api\Web\PromotionController::class, 'getPromotionSlug']); 
    Route::get('/promotions/{id}',[App\Http\Controllers\Api\Web\PromotionController::class, 'show']);   


    //berita routes
    Route::get('/beritas', [App\Http\Controllers\Api\Web\BeritaController::class, 'index']);
    Route::get('/beritas/slug', [App\Http\Controllers\Api\Web\BeritaController::class, 'getBeritaSlug']); 
    Route::get('/beritas/{id}', [App\Http\Controllers\Api\Web\BeritaController::class, 'show']); 


    //image routes
    Route::get('/images', [App\Http\Controllers\Api\Web\ImageController::class, 'index']);
    Route::get('/images/atv', [App\Http\Controllers\Api\Web\ImageController::class, 'gallery_atv']);
    Route::get('/images/rafting', [App\Http\Controllers\Api\Web\ImageController::class, 'gallery_rafting']);
    Route::get('/images/paintball', [App\Http\Controllers\Api\Web\ImageController::class, 'gallery_paintball']);
    Route::get('/images/vw', [App\Http\Controllers\Api\Web\ImageController::class, 'gallery_vw']);
 
    
    //article routes
    Route::get('/article/bpa', [App\Http\Controllers\Api\Web\ArticleController::class, 'bannerPaintballArticle']);
    Route::get('/article/baa', [App\Http\Controllers\Api\Web\ArticleController::class, 'bannerAtvArticle']);
    Route::get('/article/bra', [App\Http\Controllers\Api\Web\ArticleController::class, 'bannerRaftingArticle']);
    Route::get('/article/bva', [App\Http\Controllers\Api\Web\ArticleController::class, 'bannerVwArticle']);


    //email
    Route::post('/send-email', [App\Http\Controllers\Api\Web\EmailController::class, 'sendMail']);

});

