<?php

namespace App\Http\Livewire;

use App\Models\Answer;
use Livewire\Component;
use App\Models\Question;

class QuizAdmin extends Component
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
        $this->questions = Question::all()->sortDesc();

        return view('livewire.quiz-admin', [
            "questions" => $this->questions,
            "answers" => $this->answers,
            ]);
    }



    public function createQuestion() {
        $validatedData = $this->validate([
            'questionText' => 'required:min:6'
        ]);

        Question::create([
            'text' => $validatedData['questionText']
        ]);

        $this->reset(['questionText']);
    }

    public function createAnswer() {
        $validatedData = $this->validate([
            'answerText' => 'required:min:6',
            'questionId' => 'required'
        ]);

        Answer::create([
            'text' => $validatedData['answerText'],
            'question_id' => $validatedData['questionId']
        ]);

        $question = Question::findOrFail($validatedData['questionId']);
        $this->answers = $this->getAnswers($question);

        $this->answerText = '';
    }

    public function getAnswers(Question $question)
    {
        return $question->answers;
    }

    public function showAnswers(Question $question)
    {

        $this->showAnswerForm = 'block';
        $this->questionId = $question->id;
        $this->answers = $question->answers;

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
        $this->questions = Question::all()->sortDesc();

    }

    public function openEditQuestion($question)
    {
       $this->questionId = $question['id'];
       $this->questionText = $question['text'];
    }


    public function updateQuestion(){
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

    public function updateAnswer(){
        $validatedData = $this->validate([
            'answerText' => 'required:min:6',
            'answerId' => 'required'
        ]);

        $answer = Answer::findOrFail($validatedData['answerId']);
        $answer->text = $validatedData['answerText'];
        $answer->save();

    }

}
