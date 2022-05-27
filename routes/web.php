<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Quiz;
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
        "title" => "Some title asdfasdf",
        "test" => "some test"
    ]);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/quiz-admin', function () {
    return view('quiz-admin');
})->middleware(['auth'])->name('quiz-admin');

require __DIR__.'/auth.php';
