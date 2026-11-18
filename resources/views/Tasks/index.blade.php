@extends('layouts.app')

@section('content')
    <h1>Task Manager</h1>

    <form class="create" method="POST" action="{{ route('tasks.store') }}">
        @csrf
        <input type="text" name="name" value="{{ old('name') }}" placeholder="New task" required>
        <button type="submit">Add</button>
    </form>

    @error('name')
        <p class="error">{{ $message }}</p>
    @enderror

    <ul>
        @forelse ($tasks as $task)
            <li>
                <span>#{{ $task->priority }} {{ $task->name }}</span>
                <span class="actions">
                    <a href="{{ route('tasks.edit', $task) }}">Edit</a>
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </span>
            </li>
            @empty
            <li>No tasks yet.</li>   
        @endforelse
    </ul>
@endsection