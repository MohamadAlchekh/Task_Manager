@extends('layouts.app')

@section('content')
    <h1>Task Manager</h1>

    <form class="create" method="GET" action="{{ route('tasks.index') }}">
        <select name="project" onchange="this.form.submit()">
            @foreach ($projects as $option)
                <option value="{{ $option->id }}" @selected($option->id === $project?->id)>
                    {{ $option->name }}
                </option>
            @endforeach
        </select>
    </form>

    <form class="create" method="POST" action="{{ route('projects.store') }}">
        @csrf
        <input type="text" name="project_name" value="{{ old('project_name') }}" placeholder="New project" required>
        <button type="submit">Add project</button>
    </form>

    @error('project_name')
        <p class="error">{{ $message }}</p>
    @enderror

    @if ($project)
        <form class="create" method="POST" action="{{ route('tasks.store') }}">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">
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
                <li>No tasks in this project yet.</li>
            @endforelse
        </ul>
    @else
        <p>No projects yet. Add one above.</p>
    @endif
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>
    <script>
        const list = document.getElementById('task-list');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        if (list) {
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
                        body: JSON.stringify({ project_id: {{ $project?->id ?? 'null' }}, order }),
                    });

                    if (!response.ok) {
                        alert('Could not save the new order.');
                        location.reload();
                        return;
                    }

                    list.querySelectorAll('.priority').forEach((el, i) => el.textContent = '#' + (i + 1));
                },
            });
        }
    </script>
@endpush