<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Session;


// ============================================================================
// == SETLOCALE & PREFIX BASED ON URL OR PREVIOUS URL
// ============================================================================

(in_array(request()->segment(1), ['en']) OR strpos(url()->previous(), '/en/')) ? app()->setLocale('en') : app()->setLocale('fr');
$lang = (in_array(request()->segment(1), ['en']) OR strpos(url()->previous(), '/en/')) ? 'en/' : '';


// ============================================================================
// == LANGUAGES
// ============================================================================

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


// ============================================================================
// == AUTH ROUTES
// ============================================================================

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
// == LIENS USUELS
// ============================================================================

// ACCUEIL
Route::view($lang, 'welcome')->name('home');

// A PROPOS
Route::view($lang.__('a-propos'), 'a-propos')->name('about');

// DONNEE PERSONNELLES
Route::view($lang.__('donnees-personnelles'), 'donnees-personnelles')->name('donnees-personnelles');


// DEFIS BANQUE
Route::view($lang.__('defis-banque'), 'defis-banque')->name('defis-banque');

// DEFIS REPL
Route::view($lang.__('REPL'), 'repl')->name('REPL');
Route::view($lang.__('repl'), 'repl')->name('REPL');


// ============================================================================
// == SAVE OPENGRAPH IMAGE
// ============================================================================
Route::get('/save-opengraph-image', [App\Http\Controllers\SiteController::class, 'redirect']);
Route::post('/save-opengraph-image', [App\Http\Controllers\SiteController::class, 'save_opengraph_image']);


// ============================================================================
// == CONSOLE CONTROLLER
// ============================================================================

Route::any('/console', [App\Http\Controllers\ConsoleController::class, 'console'])->name('console');
Route::any('/console/puzzles', [App\Http\Controllers\ConsoleController::class, 'console_puzzles'])->name('console-puzzles');
Route::any('/console/defis', [App\Http\Controllers\ConsoleController::class, 'console_defis'])->name('console-defis');
Route::any('/console/devoirs', [App\Http\Controllers\ConsoleController::class, 'console_devoirs'])->name('console-devoirs');
Route::any('/console/classes', [App\Http\Controllers\ConsoleController::class, 'console_classes'])->name('console-classes');
Route::any('/console/programmes', [App\Http\Controllers\ConsoleController::class, 'console_programmes'])->name('console-programmes');

// PUZZLES
// puzzle modifier
Route::get('/console/puzzle-modifier/', [App\Http\Controllers\ConsoleController::class, 'redirect']);
Route::get('/console/puzzle-modifier/{puzzle_id}', [App\Http\Controllers\ConsoleController::class, 'puzzle_modifier_get'])->name('puzzle-modifier-get');
Route::post('/console/puzzle-modifier', [App\Http\Controllers\ConsoleController::class, 'puzzle_modifier_post'])->name('puzzle-modifier-post');
// puzzle supprimer
Route::any('/console/puzzle-supprimer', [App\Http\Controllers\ConsoleController::class, 'redirect']);
Route::any('/console/puzzle-supprimer/{puzzle_id}', [App\Http\Controllers\ConsoleController::class, 'puzzle_supprimer'])->name('puzzle-supprimer');

// DEFIS
// defi modifier
Route::get('/console/defi-modifier/', [App\Http\Controllers\ConsoleController::class, 'redirect']);
Route::get('/console/defi-modifier/{defi_id}', [App\Http\Controllers\ConsoleController::class, 'defi_modifier_get'])->name('defi-modifier-get');
Route::post('/console/defi-modifier', [App\Http\Controllers\ConsoleController::class, 'defi_modifier_post'])->name('defi-modifier-post');
// defi supprimer
Route::any('/console/defi-supprimer', [App\Http\Controllers\ConsoleController::class, 'redirect']);
Route::any('/console/defi-supprimer/{defi_id}', [App\Http\Controllers\ConsoleController::class, 'defi_supprimer'])->name('defi-supprimer');
// importer codes defis
Route::post('/console/defis-importer-codes', [App\Http\Controllers\ConsoleController::class, 'defis_importer_codes'])->name('defis-importer-codes');

// PROGRAMMES
// programme modifier
Route::get('/console/programme-modifier/', [App\Http\Controllers\ConsoleController::class, 'redirect']);
Route::get('/console/programme-modifier/{programme_id}', [App\Http\Controllers\ConsoleController::class, 'programme_modifier_get'])->name('programme-modifier-get');
Route::post('/console/programme-modifier', [App\Http\Controllers\ConsoleController::class, 'programme_modifier_post'])->name('programme-modifier-post');
// programme supprimer
Route::any('/console/programme-supprimer', [App\Http\Controllers\ConsoleController::class, 'redirect']);
Route::any('/console/programme-supprimer/{programme_id}', [App\Http\Controllers\ConsoleController::class, 'programme_supprimer'])->name('programme-supprimer');


// DEVOIRS
// devoir ajouter console
Route::post('/console/devoir-ajouter-console', [App\Http\Controllers\ConsoleController::class, 'devoir_ajouter_console'])->name('devoir-ajouter-console');
// devoir supprimer
Route::any('/console/devoir-supprimer', [App\Http\Controllers\ConsoleController::class, 'redirect']);
Route::any('/console/devoir-supprimer/{devoir_id}', [App\Http\Controllers\ConsoleController::class, 'devoir_supprimer'])->name('devoir-supprimer');

// CLASSES
// classe ajouter console
Route::post('/console/classe-ajouter-console', [App\Http\Controllers\ConsoleController::class, 'classe_ajouter_console'])->name('classe-ajouter-console');
// classe supprimer
Route::any('/console/classe-supprimer', [App\Http\Controllers\ConsoleController::class, 'redirect']);
Route::any('/console/classe-supprimer/{devoir_id}', [App\Http\Controllers\ConsoleController::class, 'classe_supprimer'])->name('classe-supprimer');


// ============================================================================
// == SITE CONTROLLER
// ============================================================================

// PUZZLES
// puzzle creer
Route::get('/puzzle-creer', [App\Http\Controllers\SiteController::class, 'puzzle_creer_get'])->name('puzzle-creer-get');
Route::post('/puzzle-creer', [App\Http\Controllers\SiteController::class, 'puzzle_creer_post'])->name('puzzle-creer-post');
// puzzle info
Route::any('/puzzle-info/{puzzle_jeton}', [App\Http\Controllers\SiteController::class, 'puzzle_info'])->name('puzzle-info');

// DEFIS
// defi creer
Route::get('/defi-creer', [App\Http\Controllers\SiteController::class, 'defi_creer_get'])->name('defi-creer-get');
Route::post('/defi-creer', [App\Http\Controllers\SiteController::class, 'defi_creer_post'])->name('defi-creer-post');
// defi info
Route::any('/defi-info/{defi_jeton}', [App\Http\Controllers\SiteController::class, 'defi_info'])->name('defi-info');

// PROGRAMMES
// programme creer
Route::get('/programme-creer', [App\Http\Controllers\SiteController::class, 'programme_creer_get'])->name('programme-creer-get');
Route::post('/programme-creer', [App\Http\Controllers\SiteController::class, 'programme_creer_post'])->name('programme-creer-post');
// programme info
Route::any('/programme-info/{programme_jeton}', [App\Http\Controllers\SiteController::class, 'programme_info'])->name('programme-info');

// DEVOIRS
Route::get('/devoir-creer', [App\Http\Controllers\SiteController::class, 'devoir_creer_get'])->name('devoir-creer-get');
Route::get('/devoir-creer/{jeton_secret}', [App\Http\Controllers\SiteController::class, 'devoir_modifier_get'])->name('devoir-modifier-get');
Route::post('/devoir-creer', [App\Http\Controllers\SiteController::class, 'devoir_creer_post'])->name('devoir-creer-post');
Route::any('/devoir-info', [App\Http\Controllers\SiteController::class, 'redirect']);
Route::any('/devoir-info/{jeton_secret}', [App\Http\Controllers\SiteController::class, 'devoir_info'])->name('devoir-info');
Route::post('/devoir', [App\Http\Controllers\SiteController::class, 'devoir_post'])->name('devoir_post');
Route::get('/devoir', [App\Http\Controllers\SiteController::class, 'devoir_get'])->name('devoir_get');
Route::post('/devoir-unlock', [App\Http\Controllers\SiteController::class, 'devoir_unlock'])->name('devoir-unlock');
Route::post('/devoir-eleve-check-lock-status', [App\Http\Controllers\SiteController::class, 'devoir_eleve_check_lock_status'])->name('devoir-eleve-check-lock-status');
Route::post('/devoir-any-check-lock-status', [App\Http\Controllers\SiteController::class, 'devoir_any_check_lock_status'])->name('devoir-any-check-lock-status');
Route::post('/devoir-unlock-from-supervision', [App\Http\Controllers\SiteController::class, 'devoir_unlock_from_supervision'])->name('devoir-unlock-from-supervision');
Route::post('/devoir-autosave', [App\Http\Controllers\SiteController::class, 'devoir_autosave']);
Route::post('/devoir-save-commentaires', [App\Http\Controllers\SiteController::class, 'devoir_save_commentaires']);
Route::post('/devoir-rendre', [App\Http\Controllers\SiteController::class, 'devoir_rendre']);
Route::post('/devoir-fin', [App\Http\Controllers\SiteController::class, 'devoir_rendre']);
Route::any('/devoir-console/{jeton_secret}', [App\Http\Controllers\SiteController::class, 'devoir_console'])->name('devoir-console');
Route::any('/devoir-supervision/{jeton_secret}', [App\Http\Controllers\SiteController::class, 'devoir_supervision'])->name('devoir-supervision');
Route::any('/devoir-imprimer/{jeton_secret}', [App\Http\Controllers\SiteController::class, 'devoir_imprimer'])->name('devoir-imprimer');
Route::any('/devoir-eleve-supprimer/{devoir_eleve_id}', [App\Http\Controllers\SiteController::class, 'devoir_eleve_supprimer'])->name('devoir-eleve-supprimer');


// CLASSES
Route::get('/classe-creer', [App\Http\Controllers\SiteController::class, 'classe_creer_get'])->name('classe-creer-get');
Route::post('/classe-creer', [App\Http\Controllers\SiteController::class, 'classe_creer_post'])->name('classe-creer-post');
Route::get('/classe-modifier/{jeton_secret}', [App\Http\Controllers\SiteController::class, 'classe_modifier_get'])->name('classe-modifier-get');
Route::post('/classe-modifier', [App\Http\Controllers\SiteController::class, 'classe_modifier_post'])->name('classe-modifier-post');
Route::any('/classe-console/{jeton_secret}', [App\Http\Controllers\SiteController::class, 'classe_console'])->name('classe-console');
Route::any('/classe-eleve-supprimer/{eleve_id}', [App\Http\Controllers\SiteController::class, 'classe_eleve_supprimer'])->name('classe-eleve-supprimer');
Route::post('/classe-activite-enregistrer', [App\Http\Controllers\SiteController::class, 'classe_activite_enregistrer'])->name('classe-activite-enregistrer');
Route::any('/classe-eleve-activite-supprimer/{classes_activites_id}', [App\Http\Controllers\SiteController::class, 'classe_eleve_activite_supprimer'])->name('classe-eleve-activite-supprimer');


// SUJETS
Route::get('/sujet-creer', [App\Http\Controllers\SujetController::class, 'sujet_creer_get'])->name('sujet-creer-get');
Route::get('/sujet-creer/{jeton_secret}', [App\Http\Controllers\SujetController::class, 'sujet_modifier_get'])->name('sujet-modifier-get');
Route::post('/sujet-creer', [App\Http\Controllers\SujetController::class, 'sujet_creer_post'])->name('sujet-creer-post');
Route::get('/sujet-creer/{jeton_secret}/md', [App\Http\Controllers\SujetController::class, 'sujet_creer_md_get'])->name('sujet-creer-md-get');
Route::post('/sujet-creer/{jeton_secret}/md', [App\Http\Controllers\SujetController::class, 'sujet_creer_md_post'])->name('sujet-creer-md-post');
Route::get('/sujet-creer/{jeton_secret}/pdf', [App\Http\Controllers\SujetController::class, 'sujet_creer_pdf_get'])->name('sujet-creer-pdf-get');
Route::post('/sujet-creer/{jeton_secret}/pdf', [App\Http\Controllers\SujetController::class, 'sujet_creer_pdf_post'])->name('sujet-creer-pdf-post');
Route::any('/sujet-console/{jeton_secret}', [App\Http\Controllers\SujetController::class, 'sujet_console'])->name('sujet-view');
Route::post('/sujet-change-type', [App\Http\Controllers\SujetController::class, 'sujet_change_type'])->name('sujet-change-type');


// ELEVES
Route::get('/@/{jeton_eleve}', function ($jeton_eleve) {
    return view('eleve-console')->with(['jeton_eleve' => $jeton_eleve]);
});



// ============================================================================
// == RETRO COMPATIBILITE
// ============================================================================
Route::any('/p/{puzzle_jeton}', function ($puzzle_jeton) {
    return view('puzzle')->with(['jeton' => $puzzle_jeton, 'iframe' => false]);
});

Route::any('/iframe/{puzzle_jeton}', function ($puzzle_jeton) {
    return view('puzzle')->with(['jeton' => $puzzle_jeton, 'iframe' => true]);
});


// ============================================================================
// == DEVOIR FIN
// ============================================================================

Route::view('/devoir-fin', 'devoir-fin');

// ============================================================================
// == HUB
// ============================================================================

Route::any('/{hub_jeton}/{extra?}', [App\Http\Controllers\SiteController::class, 'hub'])->name('hub');


// ============================================================================
// == CLEAR COOKIE
// ============================================================================

Route::get($lang.'direct-'.__('creer-un-compte'), function(){
   Cookie::queue(Cookie::forget(strtolower(str_replace(' ', '_', config('app.name'))) . '_session'));
   return redirect()->route('register');
})->name('direct-register');

Route::get($lang.'direct-'.__('se-connecter'), function(){
   Cookie::queue(Cookie::forget(strtolower(str_replace(' ', '_', config('app.name'))) . '_session'));
   return redirect()->route('login');
})->name('direct-login');
