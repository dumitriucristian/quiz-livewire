<?php

namespace App\Http\Livewire;

use App\Models\Answer;
use Livewire\Component;
use App\Models\Question;


class Questions extends Component
{
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
        $this->questions = Question::all()->sortBy('order');

        return view('livewire.questions', [
            "questions" => $this->questions,
            "answers" => $this->answers,
            ]);
    }



    public function createQuestion() {
        $validatedData = $this->validate([
            'questionText' => 'required:min:6'
        ]);

        $count = Question::count();

        Question::create([
            'text' => $validatedData['questionText'],
            'order' => $count
        ]);

        $this->reset(['questionText']);
    }

    public function createAnswer() {

        $validatedData = $this->validate([
            'answerText' => 'required:min:6',
            'questionId' => 'required'
        ]);

        $count = Answer::count();

        Answer::create([
            'text' => $validatedData['answerText'],
            'question_id' => $validatedData['questionId'],
            'order' => $count

        ]);

        $question = Question::findOrFail($validatedData['questionId']);
        $this->answers = $this->getAnswers($question);

        $this->answerText = '';
    }

    public function getAnswers(Question $question)
    {
        return $question->answers->sortBy('order');
    }

    public function showAnswers(Question $question)
    {

        $this->showAnswerForm = 'block';
        $this->questionId = $question->id;
        $this->answers = $question->answers->sortBy('order');

    }

    public function deleteAnswer($answerId)
    {

        $answer = Answer::findOrFail($answerId);
        $questionId = $answer->question->id;
        $answer->delete();
        $question = Question::findOrFail($questionId);
        $this->answers = $question->answers;
    }

    public function deleteQuestion($questionId)
    {

        $question = Question::findOrFail($questionId);
        $question->delete();
        $this->answers = [];
        $this->questions = Question::all()->sortBy('order');

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
        $this->answers = $answers->sortBy('order');
   }
}
