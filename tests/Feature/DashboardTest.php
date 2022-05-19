<?php
use App\Models\User;
use App\Http\Livewire\Questions;
use App\Models\Question;
use App\Models\Answer;
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
    $component = Livewire::test(Questions::class);
    $component->assertStatus(200);
});

test('question text is required', function () {
    Livewire::actingAs($this->user);
    Livewire::test(Questions::class)
    ->set('questionText')
    ->call('createQuestion')
    ->assertHasErrors(['questionText' => 'required']);

});

test('new question is created', function () {

    Livewire::actingAs($this->user);
    Livewire::test(Questions::class)
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
    Livewire::test(Questions::class)
        ->call('deleteQuestion', 5);

    $question = Question::all();
    expect($question)->toHaveCount(4);

});

test('question can be edited', function(){
    Question::factory(1)->create(["text" => "First question"]);
    $question =  Question::all()->first();

    expect($question)->text->toEqual('First question');

    Livewire::actingAs($this->user);
    Livewire::test(Questions::class)
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

    Livewire::test(Questions::class)
    ->call('updateQuestionsOrder', $params);

    $question = Question::find($thirdQuestion[0]->id);

    expect($question->order)->toBe(2);


});

test('answer can be added', function(){
    Livewire::actingAs($this->user);
    $question = Question::factory(1)->create();

    expect($question[0]->id)->toBeInt();
    Livewire::test(Questions::class)
        ->set(['answerText'=>'Some smart answer', 'questionId' => $question[0]->id])
        ->call('createAnswer');
    $question = Question::find($question[0]->id);

    expect($question->answers)->toHaveCount(1);

});

test('answer can be edited', function(){
    Livewire::actingAs($this->user);
    $question = Question::factory(1)->create();
    $answer = Answer::factory(1)->create(
        [
            'question_id'=>$question[0]->id,
            'text' => "Some answer"
        ]);
    expect(Answer::all())->toHaveCount(1);

    Livewire::test(Questions::class)
        ->set(['answerText'=>'Updated smart answer', 'answerId' => $answer[0]->id])
        ->call('updateAnswer');
    $answer = Answer::find($answer[0]->id);

    expect($answer->text)->toBeString('Updated smart answer');


});

test('answer can be removed', function() {
    Livewire::actingAs($this->user);
    $question = Question::factory(1)->create();

    $firstAnswer = Answer::factory(1)->create(['question_id'=> $question[0]->id]);
    $secondAnswer = Answer::factory(1)->create(['question_id'=> $question[0]->id]);
    expect(Answer::all())->toHaveCount(2);
    Livewire::test(Questions::class)
        ->call('deleteAnswer', $firstAnswer[0]->id);


    expect(Answer::all())->toHaveCount(1);
});


test('answers can be order by order',function(){
    Livewire::actingAs($this->user);
    $question = Question::factory(1)->create();

    $firstAnswer = Answer::factory(1)->create(['question_id'=> $question[0]->id,'order'=>7]);
    $secondAnswer = Answer::factory(1)->create(['question_id'=> $question[0]->id,'order'=> 8]);
    expect(Answer::all())->toHaveCount(2);
    $params = [
        [
            'order'=>2,
            'value'=>$firstAnswer[0]->id
        ],
        [
            'order'=>1,
            'value'=>$secondAnswer[0]->id
        ]
    ];
    Livewire::test(Questions::class)
    ->call('updateAnswersOrder', $params);
    expect(Answer::find($firstAnswer[0]->id))->order->toBeInt(2);
});
