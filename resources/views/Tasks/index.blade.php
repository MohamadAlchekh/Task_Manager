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

    <ul id="task-list">
        @forelse ($tasks as $task)
            <li data-id="{{ $task->id }}">
                <span>
                    <span class="handle">&#9776;</span>
                    <span class="priority">#{{ $task->priority }}</span> {{ $task->name }}
                </span>
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

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>
    <script>
        const list = document.getElementById('task-list');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        Sortable.create(list, {
            handle: '.handle',
            draggable: 'li[data-id]',
            animation: 150,
            onEnd: async () => {
                const order = [...list.querySelectorAll('li[data-id]')].map(li => li.dataset.id);

                const response = await fetch('{{ route('tasks.reorder') }}', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ order }),
                });

                if (!response.ok) {
                    alert('Could not save the new order.');
                    location.reload();
                    return;
                }

                list.querySelectorAll('.priority').forEach((el, i) => el.textContent = '#' + (i + 1));
            },
        });
    </script>
@endpush