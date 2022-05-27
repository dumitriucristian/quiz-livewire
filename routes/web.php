<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Quiz;
use App\Http\Controllers\QuizController;
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
    return Inertia::render('Welcome',[
        "quizzes" => Quiz::all(),
    ]);
});

Route::get('/quiz/{quiz}', [QuizController::class, 'show']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/quiz-admin', function () {
    return view('quiz-admin');
})->middleware(['auth'])->name('quiz-admin');

require __DIR__.'/auth.php';
