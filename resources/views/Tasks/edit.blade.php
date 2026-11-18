@extends('layouts.app')

@section('content')
    <h1>Edit task</h1>

    <form class="create" method="POST" action="{{ route('tasks.update', $task) }}">
        @csrf
        @method('PUT')
        <input type="text" name="name" value="{{ old('name', $task->name) }}" required>
        <button type="submit">Save</button>
        <a href="{{ route('tasks.index') }}">Cancel</a>
    </form>

    @error('name')
        <p class="error">{{ $message }}</p>
    @enderror
@endsection