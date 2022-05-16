<?php
use App\Models\User;
use App\Http\Livewire\QuizAdmin;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


beforeEach(function() {
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
    Question::factory()->create(["text" => "First question"]);
    $question =  Question::find(1);
    dd($question);
    expect($question)->text->toEqual('First question');

    Livewire::actingAs($this->user);
    Livewire::test(QuizAdmin::class)
        ->call('deleteQuestion', 5);

    $question = Question::all();
    expect($question)->toHaveCount(4);

});
