<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use Inertia\Inertia;

class QuizController extends Controller
{
    public function show(Quiz $quiz)
    {
        //dd($quiz->questions);
        return Inertia::render('Quiz',[
            'title' => $quiz->title,
            'questions' => $quiz->questions,
        ]);
    }
}
