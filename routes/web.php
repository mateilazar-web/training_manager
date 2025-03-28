<?php

use App\Http\Controllers\DrillController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SessionDrillsController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sort;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RedirectIfNotAuthorized;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    return view('home');
});

Route::get('dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware([RedirectIfNotAuthorized::class])->group(function () {
    
    Route::any('drills/search', 'App\Http\Controllers\DrillController@search')->name('drills.search');
    Route::resource('drills', DrillController::class)->middleware(['auth']);

    Route::resource('tags', TagController::class);

    Route::any('sessions/search', 'App\Http\Controllers\SessionController@search');
    Route::any('sessions/team_sessions', 'App\Http\Controllers\SessionController@teamSessions')->name('sessions.teamSessions');
    Route::put('sessions/generate/{id}', [SessionController::class, 'generate'])->name('sessions.generate');
    Route::put('sessions/regenerate/{id}', [SessionController::class, 'regenerate'])->name('sessions.regenerate');
    Route::put('sessions/duplicate/{id}', [SessionController::class, 'duplicate'])->name('sessions.duplicate');
    Route::resource('sessions', SessionController::class);

    Route::match(['get', 'put'], 'session_drills/create/{session_id}', [SessionDrillsController::class, 'search'])->name('session_drills.add_to_session');
    Route::resource('session_drills', SessionDrillsController::class);

    Route::match(['get', 'put'], 'session_drills/replace_list/{id}', [SessionDrillsController::class, 'replaceList'])->name('session_drills.replace_list');
    Route::post('session_drills/replace/{id}', [SessionDrillsController::class, 'replace'])->name('session_drills.replace');
    Route::any('session_drills/search/{id}', 'App\Http\Controllers\SessionDrillsController@search');


    Route::resource('users', UserController::class);

    Route::get('users/{user}/password_edit', [App\Http\Controllers\UserController::class, 'editPassword'])->name('users.edit_password');

    Route::post('users/{user}/password_change', [App\Http\Controllers\UserController::class, 'changePassword'])->name('users.change_password');

    Route::get('users/{user}/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('users.profile');

    Route::resource('roles', RoleController::class);

    Route::get('teams/request_access', [TeamController::class, 'requestAccess'])->name('teams.request_access');

    Route::post('teams/process_access_request', [TeamController::class, 'processAccessRequest'])->name('teams.process_access_request');

    Route::delete('teams/{team}/remove_user/{user}', [TeamController::class, 'removeUser'])->name('teams.remove_user');

    Route::resource('teams', TeamController::class);
});
