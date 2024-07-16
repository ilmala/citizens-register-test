<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Family;
use App\Http\Controllers\Api\V1\FamilyMemberController;
use App\Http\Controllers\Api\V1\LeaveController;
use App\Http\Controllers\Api\V1\MoveController;
use App\Http\Controllers\Api\V1\Person;
use App\Http\Controllers\Api\V1\ResponsibleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', fn(Request $request) => $request->user())->middleware('auth:sanctum');
Route::prefix('v1')->name('v1.')->group(function (): void {
    Route::post('/responsible', ResponsibleController::class)->name('responsible');
    Route::post('/move', MoveController::class)->name('move');
    Route::post('/leave', LeaveController::class)->name('leave');

    Route::post('/families/{family}/member', FamilyMemberController::class)->name('family.member');

    Route::get('/members', Person\IndexController::class)->name('members.index');
    Route::get('/families', Family\IndexController::class)->name('families.index');
});
