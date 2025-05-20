<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyLocationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\Api\ProfileApiController;
use App\Http\Controllers\AuthController;

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
Route::get('companies/{company}/locations', [CompanyController::class, 'getLocations']);
Route::get('locations/{location}/address', [CompanyLocationController::class, 'getAddress']);
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserProfileController::class, 'userRegister']);


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/protected-route', function (Request $request) {
        return response()->json(['message' => 'Welcome, authenticated user!']);
    });
    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('changePassword');
    Route::post('/saveProfile', [ProfileApiController::class, 'store']);
    Route::post('/showProfile', [ProfileApiController::class, 'show']);
    Route::post('/jobs', [UserProfileController::class, 'getJobListing']);
    Route::post('/jobdetails', [UserProfileController::class, 'getJobDetails']);
    Route::post('/getJobListing', [UserProfileController::class, 'getJobListing']);
    Route::post('/forgotPassword', [UserProfileController::class, 'forgotPassword']);
});
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
