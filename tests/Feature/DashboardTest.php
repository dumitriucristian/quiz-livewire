<?php
use App\Models\User;
use App\Http\Livewire\QuizAdmin;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


beforeEach(function() {
    $this->refreshDatabase();
    $this->user = User::factory()->create();
});

it('has dashboard page', function () {

    Livewire::actingAs($this->user);
    $response = $this->get('/dashboard');

    $response->assertStatus(200);


});

test('QuizAdmin component exist and return 200', function () {
    Livewire::actingAs($this->user);
    $component = Livewire::test(QuizAdmin::class);
    $component->assertStatus(200);
});

test('question text is required', function () {
    Livewire::actingAs($this->user);
    Livewire::test(QuizAdmin::class)
    ->set('questionText')
    ->call('createQuestion')
    ->assertHasErrors(['questionText' => 'required']);

});

test('new question is created', function () {

    Livewire::actingAs($this->user);
    Livewire::test(QuizAdmin::class)
        ->set(['questionText' => 'This is a new question'])
        ->call('createQuestion');

     $question = Question::find(1);

     expect($question)->text->toEqual('This is a new question');
});

test('question is deleted', function(){
    Question::factory(5)->create();
    $questions =  Question::all();
    expect($questions)->toHaveCount(5);

    Livewire::actingAs($this->user);
    Livewire::test(QuizAdmin::class)
        ->call('deleteQuestion', 5);

    $question = Question::all();
    expect($question)->toHaveCount(4);

});

test('question can be edited', function(){
    Question::factory(1)->create(["text" => "First question"]);
    $question =  Question::all()->first();

    expect($question)->text->toEqual('First question');

    Livewire::actingAs($this->user);
    Livewire::test(QuizAdmin::class)
        ->set(['questionText' => 'Edited question', 'questionId' => $question->id])
        ->call('updateQuestion', $question->id,);

    $question =  Question::all()->first();
    expect($question)->text->toEqual('Edited question');

});

test('question order can be changed', function(){

    Livewire::actingAs($this->user);
    $firstQuestion = Question::factory(1)->create( ["text"=>"First question", "order"=>1]);
    $secondQuestion = Question::factory(1)->create( ["text"=>"Second question", "order"=>2]);
    $thirdQuestion = Question::factory(1)->create(["text"=>"Third question",  "order"=>3]);
    $questions = Question::all();
    expect($questions)->toHaveCount(3);

    $params = [
        ["value" => $firstQuestion[0]->id, "order" => 2],
        ["value" => $secondQuestion[0]->id , "order" => 3],
        ["value"=> $thirdQuestion[0]->id, "order" => 2]
    ];

    Livewire::test(QuizAdmin::class)
    ->call('updateQuestionsOrder', $params);

    $question = Question::find($thirdQuestion[0]->id);

    expect($question->order)->toBe(2);


});
