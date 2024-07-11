<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\MemberPromoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', fn(Request $request) => $request->user())->middleware('auth:sanctum');
Route::post('/members/{member}/promote', MemberPromoteController::class)->name('member.promote');
