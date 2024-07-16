<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\PersonLeaveController;
use App\Http\Controllers\Api\V1\PersonMoveController;
use App\Http\Controllers\Api\V1\PersonResponsibleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', fn(Request $request) => $request->user())->middleware('auth:sanctum');
Route::prefix('v1')->name('v1.')->group(function (): void {
    Route::post('/person/{person}/responsible', PersonResponsibleController::class)->name('person.responsible');
    Route::post('/person/{person}/move', PersonMoveController::class)->name('person.move');
    Route::post('/person/{person}/leave', PersonLeaveController::class)->name('person.leave');
});
