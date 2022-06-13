<?php

use App\Http\Controllers\ConstructController;
//use App\Http\Controllers\HarIDController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');


/*Route::prefix('auth/harid')->group(function () {
    Route::get('/redirect', [HarIDController::class, 'redirect'])->name('auth.harid.redirect');
    Route::get('/callback', [HarIDController::class, 'callback'])->name('auth.harid.callback');
});*/

Route::resource('constructs', ConstructController::class);
Route::resource('questionnaires', QuestionnaireController::class);
Route::resource('users', UserController::class);

Route::get('/start', [QuestionnaireController::class, 'start'])->name('questionnaires.start');
Route::get('/statements', [QuestionnaireController::class, 'statements'])->name('questionnaires.statements');
Route::get('/finish', [QuestionnaireController::class, 'finish'])->name('questionnaires.finish');

Route::get('/results', [QuestionnaireController::class, 'results'])->name('results');
Route::get('/getQuestionnaireStatementAverageResult', [QuestionnaireController::class, 'getQuestionnaireStatementAverageResult']);
Route::get('/getStatementData', [QuestionnaireController::class, 'getStatementData']);
Route::get('/getConstructData', [QuestionnaireController::class, 'getConstructData']);


