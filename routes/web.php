<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\VerificationController;

use Illuminate\Support\Facades\Session;


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

// LANGUAGES
// Lang Public
Route::get('lang/{locale}', function ($locale){
    if (in_array($locale, ['en'])) {
        Session::put('lang',$locale);
        return redirect($locale.'/');
    } else {
        Session::put('lang', 'fr');
        return redirect('/');
    }
});

// Lang Auth
Route::get('console/lang/{locale}', function ($locale){
    if (in_array($locale, ['fr', 'en'])) {
        Session::put('lang',$locale);
    }
    return redirect('console/');
});


// SETLOCALE & PREFIX BASED ON URL
(in_array(request()->segment(1), ['en'])) ? app()->setLocale(request()->segment(1)) : app()->setLocale('fr');
$lang = (in_array(request()->segment(1), ['en'])) ? request()->segment(1).'/' : '';


// HOMEPAGE
Route::view($lang, 'welcome');

// DONNEE PERSONNELLES
Route::view($lang.__('donnees-personnelles'), 'donnees-personnelles')->name('donnees-personnelles');


// ============================================================================
// PUZZLES
Route::get('/p/{jeton}', function($jeton) {
    return view("puzzle", ["jeton"=>$jeton]);
})->name('puzzle');

// PUZZLE IFRAME
Route::get('/iframe/{jeton}', function($jeton) {
    return view("puzzle-iframe", ["jeton"=>$jeton]);
})->name('puzzle-iframe');
// ============================================================================

// ============================================================================
// AUTH ROUTES
// Login Routes...
Route::get($lang.__('se-connecter'), [LoginController::class, 'showLoginForm'])->name('login');
Route::post($lang.__('se-connecter'), [LoginController::class, 'login']);

// Logout Routes...
Route::post($lang.__('se-deconnecter'), [LoginController::class, 'logout'])->name('logout');

// Registration Routes...
Route::get($lang.__('creer-un-compte'), [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post($lang.__('creer-un-compte'), [RegisterController::class, 'register']);

// Password Reset Routes...
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Password Confirmation Routes...
Route::get('password/confirm', [ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
Route::post('password/confirm', [ConfirmPasswordController::class, 'confirm']);

// Email Verification Routes...
Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
// ============================================================================


// ============================================================================
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
// ============================================================================


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
