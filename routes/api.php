<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\EventTypeController;
use App\Http\Controllers\Api\ReservationController;


/**
 * Auth routes
 */
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::middleware('auth:sanctum')->post('/logout',[AuthController::class,'logout']);

Route::middleware('auth:sanctum')->group(function () {

    /**
     * Events
     */
    Route::prefix('events')->controller(EventController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store')->middleware('can:create events');
        Route::get('{event}', 'show');
        Route::post('{event}', 'update')->middleware('can:edit events');
        Route::delete('{event}', 'destroy')->middleware('can:delete events');
    });

    /**
     * Event Types
     */
    Route::prefix('event-types')->controller(EventTypeController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store')->middleware('can:manage event_types');
        Route::get('{event_type}', 'show');
        Route::put('{event_type}', 'update')->middleware('can:manage event_types');
        Route::delete('{event_type}', 'destroy')->middleware('can:manage event_types');
    });

    /**
     * Locations
     */
    Route::prefix('locations')->controller(LocationController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store')->middleware('can:manage locations');
        Route::get('{location}', 'show');
        Route::post('{location}', 'update')->middleware('can:manage locations');
        Route::delete('{location}', 'destroy')->middleware('can:manage locations');
    });

    /**
     * Reservations
     */
    Route::prefix('reservations')->controller(ReservationController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store')->middleware('can:create reservations');
        Route::post('{reservation}', 'update')->middleware('can:edit reservations');
        Route::delete('{reservation}', 'destroy')->middleware('can:delete reservations');
    });

    /**
     * Users
     */
    Route::prefix('users')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->middleware('can:view users');
        Route::delete('{user}', 'destroy')->middleware('can:delete users');
    });
});

