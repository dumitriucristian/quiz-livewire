<?php

namespace App\Http\Livewire;

use App\Models\Answer;
use Livewire\Component;
use App\Models\Question;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Questions extends Component
{
    use WithPagination;

    public $questionText;
    public $answerText;
    public $questions;
    public $answers;
    public $modalText;
    public $questionId;
    public $showAnswerForm;
    public $showModal;
    public $answerId;


    public function mount()
    {
        $this->showAnswerForm = 'hidden';
        $this->answers = [];
        $this->questions = [];
        $this->showModal = true;
    }

    public function render()
    {

        $paginatedQuestions = Question::query()->orderBy('order','asc')->orderBy('updated_at','desc')->paginate(20);
        $this->questions= $paginatedQuestions->items();

        return view('livewire.questions', [
            "paginatedQuestions" => $paginatedQuestions
            ]);
    }

    public function createQuestion() {
        $validatedData = $this->validate([
            'questionText' => 'required|min:6'
        ]);

        Question::create([
            'text' => $validatedData['questionText'],
            'order' => 0
        ]);

        $this->reset(['questionText']);
    }

    public function createAnswer() {

        $validatedData = $this->validate([
            'answerText' => 'required|min:6',
            'questionId' => 'required'
        ]);

        Answer::create([
            'text' => $validatedData['answerText'],
            'question_id' => $validatedData['questionId'],
            'order' => 0
        ]);

        $question = Question::findOrFail($validatedData['questionId']);
        $this->answers = $this->getAnswers($question);

        $this->answerText = '';
    }

    public function getAnswers(Question $question)
    {
        return $question->answers->sortBy('order')->sortByDesc('updated_at');
    }

    public function showAnswers(Question $question)
    {

        $this->showAnswerForm = 'block';
        $this->questionId = $question->id;
        $this->answers = $question->answers->sortBy('order')->sortByDesc('updated_at');

    }

    public function deleteAnswer($answerId)
    {
        $answer = Answer::findOrFail($answerId);
        $questionId = $answer->question->id;
        $answer->delete();

        $this->answers = Answer::query()->where('question_id', $questionId)->orderBy('order')->orderBy('updated_at','desc')->get();
    }

    public function deleteQuestion($questionId)
    {
        $question = Question::findOrFail($questionId);
        $question->delete();
        $this->answers = [];
    }

    public function openEditQuestion($question)
    {
       $this->questionId = $question['id'];
       $this->questionText = $question['text'];
    }


    public function updateQuestion()
    {
        $validatedData = $this->validate([
            'questionText' => 'required:min:6',
            'questionId' => 'required'
        ]);

        $question = Question::findOrFail($validatedData['questionId']);
        $question->text = $validatedData['questionText'];
        $question->save();

    }

    public function openEditAnswer($answer)
    {
        $this->answerId = $answer['id'];
        $this->answerText = $answer['text'];
    }

    public function updateAnswer()
    {
        $validatedData = $this->validate([
            'answerText' => 'required:min:6',
            'answerId' => 'required'
        ]);

        $answer = Answer::findOrFail($validatedData['answerId']);
        $answer->text = $validatedData['answerText'];
        $answer->save();

    }

    public function updateQuestionsOrder( $items)
    {
        Question::changeOrder($items);
    }

    public function updateAnswersOrder( $items)
    {
        $answers = Answer::changeOrder($items);
        $this->answers = $answers;
   }
}
