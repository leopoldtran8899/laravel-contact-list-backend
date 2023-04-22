<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\NoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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


Route::middleware('auth:sanctum')->prefix('auth')->group(function () {
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::get('/logout', [AuthController::class, 'logoutUser']);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'createUser']);
    Route::post('/login', [AuthController::class, 'loginUser']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/groups/{id}/companies', [GroupController::class, 'getGroupWithCompanies']);
    Route::apiResource('groups', GroupController::class);
    Route::get('/companies/{id}/contacts', [CompanyController::class, 'getCompanyWithContacts']);
    Route::apiResource('companies', CompanyController::class);
    Route::get('/notes-by-contact/{id}', [NoteController::class, 'getNotesByContactFromUser']);
    Route::post('/note', [NoteController::class, 'createNoteForContact']);
    Route::apiResource('notes', NoteController::class);
    Route::get('/contacts/{id}/notes', [ContactController::class, 'getContactWithNotes']);
    Route::apiResource('contacts', ContactController::class)->only([
        'show', 'index', 'update'
    ]);
});