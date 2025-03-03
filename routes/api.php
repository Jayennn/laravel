<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function (){
    return response()->json([
        'message' => 'Hello, world!'
    ]);
});

Route::group(['prefix' => 'v1', 'middleware' => ['cors']], function () {

    Route::get('/', function() {
        return response()->json([
            'message' => 'Hello, world!'
        ]);
    });


    /*'middleware' => ['auth:sanctum']*/
    Route::group(['prefix' => 'books'], function () {
        Route::get('/', [BookController::class, 'index']);
        Route::post('/', [BookController::class, 'store']);
        Route::get('/{id}', [BookController::class, 'show']);
        Route::put('/{id}', [BookController::class, 'update']);
        Route::delete('/{id}', [BookController::class, 'destroy']);
    });

    Route::group(['prefix' => 'categories'], function () {
            Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
    });

    Route::group(['prefix' => 'auth'], function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/register', [UserController::class, 'register']);
        Route::post('/login', [UserController::class, 'login']);
    });
});
