<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
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

// CHECK CCM & CAT
Route::post('/ccm_cat', [App\Http\Controllers\SiteController::class, 'ccm_cat_verifier']);


// SETLOCALE & PREFIX BASED ON URL OR PREVIOUS URL
(in_array(request()->segment(1), ['en']) OR strpos(url()->previous(), '/en/')) ? app()->setLocale('en') : app()->setLocale('fr');
$lang = (in_array(request()->segment(1), ['en']) OR strpos(url()->previous(), '/en/')) ? 'en/' : '';


// ACCUEIL
Route::view($lang, 'welcome')->name('home');


// DONNEE PERSONNELLES
Route::view($lang.__('donnees-personnelles'), 'donnees-personnelles')->name('donnees-personnelles');


// ============================================================================
// GUEST PUZZLES

// puzzle creer
Route::get($lang.__('creer-un-puzzle'), [App\Http\Controllers\SiteController::class, 'puzzle_creer_get'])->name('guest-puzzle-creer-get');
Route::post($lang.__('creer-un-puzzle'), [App\Http\Controllers\SiteController::class, 'puzzle_creer_post'])->name('guest-puzzle-creer-post');
Route::get($lang.'puzzle/{jeton}', function($jeton) {
    return view("guest-puzzle", ["jeton"=>$jeton]);
})->name('guest-puzzle');



// ============================================================================
// AFFICHAGE PUZZLES

// affichage
Route::get('/p/{jeton}', function($jeton) {
    return view("puzzle", ["jeton"=>$jeton]);
})->name('puzzle');

// affichage iframe
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
Route::get($lang.__('mot-de-passe').'/'.__('reinitialisation'), [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post($lang.__('mot-de-passe').'/'.__('courriel'), [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get($lang.__('mot-de-passe').'/'.__('reinitialisation').'/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post($lang.__('mot-de-passe').'/'.__('reinitialisation'), [ResetPasswordController::class, 'reset'])->name('password.update');

// Password Confirmation Routes...
Route::get($lang.__('mot-de-passe').'/'.__('confirmer'), [ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
Route::post($lang.__('mot-de-passe').'/'.__('confirmer'), [ConfirmPasswordController::class, 'confirm']);

// Email Verification Routes...
Route::get($lang.__('courriel').'/'.__('verifier'), [VerificationController::class, 'show'])->name('verification.notice');
Route::get($lang.__('courriel').'/'.__('verifier').'/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post($lang.__('courriel').'/'.__('renvoyer'), [VerificationController::class, 'resend'])->name('verification.resend');

// ============================================================================


// ============================================================================
// CLEAR COOKIE

Route::get($lang.'direct-'.__('creer-un-compte'), function(){
   Cookie::queue(Cookie::forget(strtolower(str_replace(' ', '_', config('app.name'))) . '_session'));
   return redirect()->route('register');
})->name('direct-register');

Route::get($lang.'direct-'.__('se-connecter'), function(){
   Cookie::queue(Cookie::forget(strtolower(str_replace(' ', '_', config('app.name'))) . '_session'));
   return redirect()->route('login');
})->name('direct-login');

// ============================================================================


// ============================================================================
// == CONSOLE
// ============================================================================

Route::get('/console', [App\Http\Controllers\ConsoleController::class, 'console_get'])->name('console');

// puzzle creer
Route::get('/console/puzzle-creer', [App\Http\Controllers\ConsoleController::class, 'puzzle_creer_get'])->name('puzzle-creer-get');
Route::post('/console/puzzle-creer', [App\Http\Controllers\ConsoleController::class, 'puzzle_creer_post'])->name('puzzle-creer-post');

// puzzle modifier
Route::get('/console/puzzle-modifier/', [App\Http\Controllers\ConsoleController::class, 'redirect']);
Route::get('/console/puzzle-modifier/{code_id}', [App\Http\Controllers\ConsoleController::class, 'puzzle_modifier_get'])->name('puzzle-modifier-get');
Route::post('/console/puzzle-modifier', [App\Http\Controllers\ConsoleController::class, 'puzzle_modifier_post'])->name('puzzle-modifier-post');

// puzzle supprimer
Route::any('/console/puzzle-supprimer', [App\Http\Controllers\ConsoleController::class, 'redirect']);
Route::any('/console/puzzle-supprimer/{code_id}', [App\Http\Controllers\ConsoleController::class, 'puzzle_supprimer'])->name('puzzle-supprimer');
