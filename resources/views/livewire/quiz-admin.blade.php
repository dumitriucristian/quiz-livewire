<div
    x-data="{   open: false,
                questionModal: false,
                answerModal: false
            }"
>
    <div>
        <div class="max-w-7xl mx-auto p-6  m-4">
            <h1 class="font-medium">Quiz admin panel</h1>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid md:grid-cols-2 gap-4 bg-white shadow rounded m-4 p-6">
            <div class="bg-gray-100 p-6">
                <p>Questions</p>
                <form class="p-6 bg-white border-b border-gray-200 "  wire:submit.prevent="createQuestion">
                    @error('questionText') <p class="text-red-500">{{ $message }}</p> @enderror
                    <div class="flex w-full">
                        <textarea placeholder="Add a new question" class="w-full" wire:model.defer="questionText"></textarea>
                        <button type="submit" class="btn  px-6 shadow-md bg-green-500">Add Question</button>
                    </div>
                </form>
                <ul class="grid bg-white gap-4" wire:sortable="updateQuestionsOrder" >
                    @if($questions)

                        @foreach ($questions as $index => $question)
                            <li
                                class="p-2 bg-gray-500 text-white flex justify-between"
                                wire:sortable.item="{{$question->id}}"
                                wire:key="question-id-{{ $question->id }}"
                            >
                                <span wire:sortable.handle>MOVE</span>
                                <span>{{ $question->text }}</span>
                                <span><button wire:click.stop="deleteQuestion({{ $question->id}})">Delete</button></span>
                                <span><button wire:click.stop="openEditQuestion({{$question}})" @click.stop="open = true; questionModal = true;">Edit</button></span>
                                <span><button wire:click="showAnswers({{$question}})">Answers</button></span>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <!--//https://laravel-livewire.com/screencasts/s8-dragging-list -->
            <div class="bg-gray-100 p-6">
                <p>Answers</p>
                <div>
                    <form
                        class="p-6 bg-white border-b border-gray-200 {{$showAnswerForm}}"
                          wire:submit.prevent="createAnswer"
                    >
                        @error('answerText') <p class="text-red-500">{{ $message }}</p> @enderror
                        <div class="flex w-full">
                            <textarea
                                placeholder="Add a new answer"
                                class="w-full"
                                wire:model.defer="answerText"></textarea>
                            <button type="submit" class="btn  px-6 shadow-md bg-green-500">Add Answer</button>
                        </div>
                    </form>
                </div>
                <div class="grid bg-white gap-4">
                    @if($answers)
                        @foreach ($answers as $index => $answer)
                            <div wire:key="answer-id-{{ $answer->id }}" class="p-2 bg-gray-500 text-white flex justify-between">
                                <p>{{ $answer->text }}</p>
                                <span><button wire:click="deleteAnswer({{ $answer->id}})">Delete</button></span>
                                <span><button  wire:click.stop="openEditAnswer({{$answer}})" @click.stop="open = true; answerModal = true;" >Edit</button></span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div x-show="open" x-cloak x-transition
        class="bg-slate-500/50  fixed inset-0 flex justify-center items-center">
        <div
        class="bg-white w-full mx-auto relative max-w-6xl md:rounded shadow-lg"
        @click.outside="open = false; questionModal = false; answerModal = false;" >
            <!--header -->
            <div class="flex justify-between border-gray-300 border-b items-center">
                <div class="p-2 text-xl" x-cloak x-transition>Edit</div>
                <button @click="open = false; questionModal = false; answerModal = false;">
                    <svg
                        class="w-10 m-2 p-3 flex"
                        version="1.1"
                        id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        viewBox="0 0 460.775 460.775" style="enable-background:new 0 0 460.775 460.775;" xml:space="preserve">
                        <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55
                            c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55
                            c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505
                            c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55
                            l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719
                            c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>
                    </svg>
                </button>
            <!--header end -->
            </div>
            <div>
                <div x-show="questionModal" x-cloak x-transition>
                        <form wire:submit.prevent="updateQuestion">
                        <div class="flex p-6">
                            <textarea class="w-full" name="questionText"  wire:model.defer="questionText">{{$questionText}}</textarea>
                            <input type="hidden" name="questionId" value="{{$questionId}}">
                            <button type="submit" class="bg-blue-300 text-white">Update Question</button>
                        </div>
                    </form>
                </div>
                <div x-show="answerModal" x-cloak x-transition>
                    <form wire:submit.prevent="updateAnswer" >
                        <div class="flex p-6">
                            <textarea class="w-full" name="answerText"  wire:model.defer="answerText">{{$answerText}}</textarea>
                            <input type="hidden" name="answerId" value="{{$answerId}}">
                            <button type="submit" class="bg-blue-300 text-white">Update answer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->
</div>
