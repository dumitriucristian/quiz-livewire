<div  x-data="{
    selected: false,
    currentQuiz: ''
}">
    <div class="bg-white mx-auto shadow m-5 container" >
        <p>Define your quiz</p>
        <form  wire:submit.prevent="createQuiz" method="post">
            @error('title') <p class="text-red-500">{{ $message }}</p> @enderror
            <div class="w-full mx-auto p-6 flex">
                <input type="text" name="title" wire:model.defer="title" class="w-full" />
                <input type="submit" class="btn bg-blue-300 text-white" value="Create quiz" />
            </div>
        </form>
    </div>

    <div  class="bg-white mx-auto shadow m-5 container">
        <p>Edit and improve any quiz from list</p>
        <table class="table w-full">
            <thead class="table-header-group">
                <tr>
                    <th>Set</th>
                    <th>SSd</th>
                    <th>Quiz title</th>
                    <th>Created at</th>
                    <th>Updated at</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quizzes as $quiz)
                    @livewire('quiz-item',['quiz' => $quiz], key('quiz-id-'.$quiz->id))
                @endforeach
            </tbody>
        </table>
        {{$paginatedQuizzes->links('vendor.livewire.question-pagination')}}
    </div>
    <div class="grid lg:grid-cols-3" x-show="selected">
        <div class="bg-blue-600  shadow m-5">
            <p>Available questions</p>
            <table class="table w-full">
                <thead class="table-header-group">
                <tr class="table-row">
                    <th>id</th>
                    <th>title</th>
                    <th>Add</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($questions as $question)
                    <tr class="table-row">
                        <td wire:key="question-id-{{$question->id}}">{{$question->id}}</td>
                        <td>{{$question->text}}</td>
                        <td><button  wire:click.stop="attachQuestion({{$question->id}})" class="button rounded border shadow-md bg-gray-500 text-white">Add</td>
                        <td><button class="button rounded border shadow-md bg-gray-500 text-white">Details</button></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{$paginatedQuestions->links('vendor.livewire.question-pagination')}}
        </div>

        <div class="bg-green-300  shadow m-5">
            <div>
                <p>27 Questions</p>
            </div>
            <p>Quiz questions</p>
            <table>
                <thead>
                    <tr>
                        <th>id</th>
                        <th>title</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                @foreach($quizQuestions as $quizQuestion)
                    <tr>
                        <td>{{$quizQuestion->id}}</td>
                        <td>{{$question->text}}</td>
                        <td><button class="py-2 px-1 rounded shadow-md bg-indigo-200 text-white" wire:click="detachQuestion({{$quiz->id}},{{$quizQuestion->id}})">Remove</button></td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="bg-green-200 shadow m-5">
            <p>Anaswers</p>
            <table>
                <thead>
                    <tr>
                        <th>id</th>
                        <th>title</th>
                        <th>Add</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Question text</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        let custom = (event) => {
            const boxes = document.querySelectorAll('.bg-red-500');
            boxes.forEach(box => {
                box.classList.remove('bg-red-500');
            });
            event.target.classList.add('bg-red-500')
        }
    </script>
</div>
