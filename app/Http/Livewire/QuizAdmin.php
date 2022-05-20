<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Quiz;
use App\Models\Question;
use Livewire\WithPagination;

class QuizAdmin extends Component
{
    use WithPagination;

    const QUIZZES_PER_PAGE = 20;
    const QUESTIONS_PER_PAGE = 10;

    public $title;
    public $quizzes;
    public $questions;
    public $currentQuiz;
    public $quizQuestions;

    protected $listeners = ['deleteQuiz','setQuiz'];

    public function mount()
    {
        $this->quizzes =[];
        $this->questions = [];
        $this->quizQuestions = [];
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
            'paginatedQuestions' => $paginatedQuestions,

            'quizQuestions' => $this->quizQuestions,
        ]);
    }

    public function setQuiz($quizId)
    {
        $this->currentQuiz = $quizId;
        $this->quizQuestions = Quiz::findOrFail($quizId)->questions()->get();
       // dd($this->quizQuestions);
    }

    public function detachQuestion($quizId, $questionId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $quiz->questions()->detach($questionId);
        $this->quizQuestions = Quiz::findOrFail($quizId)->questions()->get();
    }

    public function attachQuestion($questionId)
    {

        if (empty($this->currentQuiz)) {
            dd("Qurrent quiz is not set check event setQuiz");
        }
        $quiz = Quiz::findOrFail($this->currentQuiz);

        $quiz->questions()->attach($questionId);
        $this->quizQuestions = $quiz->questions()->get();

        //dd$this->quizQuestions);
    }

    public function createQuiz()
    {
        $data = $this->validate([
            "title" => 'required|min:6'
        ]);

        $quiz = Quiz::create($data);
        $quiz->save();

        $this->quizzes = Quiz::all();
        $this->title = '';

    }

    public function deleteQuiz($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $quiz->delete();
        $paginatedQuizzes = Quiz::query()
            ->orderBy('id')
            ->paginate(self::QUIZZES_PER_PAGE,['*'],'quizPage');

        $this->quizzes = $paginatedQuizzes->items();
    }


}
