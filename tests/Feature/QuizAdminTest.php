<?php
use App\Models\User;
use App\Http\Livewire\Questions;
use App\Models\Question;
use App\Models\Answer;
use App\Http\Livewire\QuizAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Quiz;

uses(RefreshDatabase::class);


beforeEach(function() {
    $this->refreshDatabase();
    $this->user = User::factory()->create();
    Livewire::actingAs($this->user);
});


it('has quizadmin page', function () {

    $response = $this->get('/quiz-admin');
    $response->assertStatus(200);
});

test('user can create a new test ', function(){
   Livewire::test(QuizAdmin::class)
   ->set(['title' => 'some title'])
   ->call('createQuiz') ;
    $nrOfQuizzes = Quiz::count();
    expect($nrOfQuizzes)->toBe(1);
});

test('user can delete a quiz',function(){
    $quiz = Quiz::factory()->create();
    $nrOfQuizzes = Quiz::count();
    expect($nrOfQuizzes)->toBe(1);

    Livewire::test(QuizAdmin::class)
    ->call('deleteQuiz',$quiz->id);

    expect($nrOfQuizzes)->toBe(0);

});
