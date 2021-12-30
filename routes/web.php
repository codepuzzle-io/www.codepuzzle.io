<?php

use Illuminate\Support\Facades\Route;


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

// AUTH ROUTES
Auth::routes(['verify' => true]);


// ============================================================================
// == PUBLIC
// ============================================================================

// HOMEPAGE
Route::view('/', 'welcome');

// DONNEE PERSONNELLES
Route::view('/donnees-personnelles', 'donnees-personnelles')->name('donnees-personnelles');

// CLEAR COOKIE
Route::get('/direct-register', function(){
   Cookie::queue(Cookie::forget(strtolower(str_replace(' ', '_', config('app.name'))) . '_session'));
   return redirect('/register');
});

Route::get('/direct-login', function(){
   Cookie::queue(Cookie::forget(strtolower(str_replace(' ', '_', config('app.name'))) . '_session'));
   return redirect('/login');
});

Route::get('/direct-welcome', function(){
   Cookie::queue(Cookie::forget(strtolower(str_replace(' ', '_', config('app.name'))) . '_session'));
   return redirect('/');
});

// PUZZLE
Route::get('/p/{jeton}', function($jeton) {
    return view("puzzle", ["jeton"=>$jeton]);
})->name('puzzle');

// PUZZLE IFRAME
Route::get('/iframe/{jeton}', function($jeton) {
    return view("puzzle-iframe", ["jeton"=>$jeton]);
})->name('puzzle-iframe');


// ============================================================================
// == CONSOLE
// ============================================================================

Route::get('/console', [App\Http\Controllers\ConsoleController::class, 'console_get'])->name('console_get');

// code creer
Route::get('/console/code-creer', [App\Http\Controllers\ConsoleController::class, 'code_creer_get'])->name('code-creer-get');
Route::post('/console/code-creer', [App\Http\Controllers\ConsoleController::class, 'code_creer_post'])->name('code-creer-post');

// code modifier
Route::get('/console/code-modifier/', [App\Http\Controllers\ConsoleController::class, 'redirect']);
Route::get('/console/code-modifier/{code_id}', [App\Http\Controllers\ConsoleController::class, 'code_modifier_get'])->name('code-modifier-get');
Route::post('/console/code-modifier', [App\Http\Controllers\ConsoleController::class, 'code_modifier_post'])->name('code-modifier-post');

// code supprimer
Route::any('/console/code-supprimer', [App\Http\Controllers\ConsoleController::class, 'redirect']);
Route::any('/console/code-supprimer/{code_id}', [App\Http\Controllers\ConsoleController::class, 'code_supprimer'])->name('code-supprimer');
