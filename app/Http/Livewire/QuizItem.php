<?php

namespace App\Http\Livewire;

use App\Models\Quiz;
use Livewire\Component;

class QuizItem extends Component
{
    public $quiz;
    public $isActive;

    public function mount(){
        $this->isActive = false;
    }


    public function render()
    {
        return view('livewire.quiz-item',[
            'quizzes' => $this->quiz
        ]);
    }

    public function deleteQuiz($quizId){
        $this->emitUp('deleteQuiz', $quizId);
    }
    public function setQuiz($quizId){
        $this->emitUp('setQuiz', $quizId);
    }
/*
    public function deleteQuiz($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $quiz->delete();
        //reload quizzes emit event to quizAdmin
        $this->emitUp('getQuizzes');
       //$this->quizzes = Quiz::all();
    }
*/
    public function setActive()
    {
        $this->isActive = true;
    }
}
