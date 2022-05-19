<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Quiz;
use App\Models\Question;
use Livewire\WithPagination;

class QuizAdmin extends Component
{
    use WithPagination;

    const QUIZZES_PER_PAGE = 4;
    const QUESTIONS_PER_PAGE = 10;

    public $title;
    public $quizzes;
    public $questions;
    public $currentQuiz;

    public function mount()
    {
        $this->quizzes =[];
        $this->questions = [];
        $this->currentQuiz = '';
    }

    public function render()
    {
        $paginatedQuestions = Question::query()
            ->orderBy('order')
            ->paginate(self::QUESTIONS_PER_PAGE,['*'],'queryPage');

        $this->questions = $paginatedQuestions->items();

        $paginatedQuizzes = Quiz::query()
            ->orderBy('id')
            ->paginate(self::QUIZZES_PER_PAGE,['*'],'quizPage');

        $this->quizzes = $paginatedQuizzes->items();

        return view('livewire.quiz-admin',[
            'quizzes'=>  $this->quizzes,
            'paginatedQuizzes' => $paginatedQuizzes,
            'questions' => $this->questions,
            'paginatedQuestions' => $paginatedQuestions
        ]);
    }

    public function setQuiz($quizId)
    {
        $this->currentQuiz = $quizId;
    }

    public function setQuestion($quizId)
    {
        dd($this->currentQuiz);
        if (empty($this->currentQuiz))
        {

        }
        dd($this->currentQuiz, $quizId);
    }

    public function createQuiz()
    {
        $data = $this->validate([
            "title" => 'required|min:6'
        ]);

        $quiz = Quiz::create($data);
        $quiz->save();

        $this->quizzes = Quiz::all();
    }

    public function deleteQuiz($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $quiz->delete();
        $this->quizzes = Quiz::all();
    }


}
