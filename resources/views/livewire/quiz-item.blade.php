<tr x-data="{

}"
class="text-center bg-blue-100 m-1">
    <td>
        <button
            class="bg-blue-500 text-white rounded shadow py-1 px-4 shadow"

            x-on:click.prevent=" custom(event); selected = true; $wire.emit('setQuiz', {{$quiz->id}})"
        >Edit Quiz
        </button>
    </td>
    <td>{{$quiz->id}}</td>
    <td class="text-left">{{$quiz->title}}</td>
    <td>{{$quiz->created_at}}</td>
    <td>{{$quiz->updated_at}}</td>
    <td>
        <button
            type="submit"
            class="button bg-gray-500 color-white shadow-md rounded"
            wire:click="deleteQuiz({{$quiz->id}})"
        >Remove
        </button>
    </td>

</tr>

