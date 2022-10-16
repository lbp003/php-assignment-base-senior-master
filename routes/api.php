<?php

use App\Http\Controllers\DamageReportController;
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

/*
 * Register damage report routes
 */
Route::put(
    'damage-reports/{id}/approval',
    [
        DamageReportController::class, 'approval',
    ]
)->name('approval');

Route::resource('damage-reports', DamageReportController::class);
