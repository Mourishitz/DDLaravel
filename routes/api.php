<?php

use Illuminate\Http\Request;
use App\Core\Routers\RouteFactory;
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


$router = new RouteFactory();

$router->registerRoutes([

]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
