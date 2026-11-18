<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Manager</title>
    <style>
        :root {
            --ink: #24352f;
            --muted: #687970;
            --paper: #f3f7f3;
            --surface: #fff;
            --line: #dce7df;
            --accent: #287354;
            --accent-hover: #205d45;
            --danger: #a1433d;
        }
        body { margin: 0; background: var(--paper); color: var(--ink); font: 16px/1.5 "Segoe UI", sans-serif; }
        main { max-width: 680px; margin: 48px auto; padding: 0 20px; }
        h1 { margin: 0 0 24px; font-size: 30px; font-weight: 650; }
        form.create { display: flex; gap: 10px; margin-bottom: 14px; }
        input[type=text], select { border: 1px solid var(--line); border-radius: 5px; background: var(--surface); color: var(--ink); font: inherit; }
        input[type=text] { flex: 1; min-width: 0; padding: 9px 11px; }
        select { max-width: 100%; padding: 8px 11px; }
        input[type=text]:focus-visible, select:focus-visible { outline: 3px solid #cfe5d6; border-color: var(--accent); }
        button { border: 0; border-radius: 5px; padding: 9px 14px; background: var(--accent); color: #fff; font: inherit; font-weight: 600; cursor: pointer; }
        button:hover { background: var(--accent-hover); }
        ul { margin: 18px 0; padding: 0; list-style: none; }
        li { display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 8px; padding: 12px 14px; border: 1px solid var(--line); border-radius: 6px; background: var(--surface); }
        li > span:first-child { min-width: 0; overflow-wrap: anywhere; }
        .error { margin: 4px 0 14px; color: var(--danger); }
        .actions { display: flex; flex-shrink: 0; align-items: center; gap: 10px; }
        .actions a { color: var(--accent); font-weight: 600; text-decoration: none; }
        .actions a:hover { text-decoration: underline; }
        .actions form { margin: 0; }
        .actions button { border: 1px solid var(--line); background: #fff; color: var(--danger); }
        .actions button:hover { background: #fff3f1; }
        .handle { margin-right: 10px; color: var(--muted); cursor: grab; }
        .priority { color: var(--accent); font-weight: 700; font-variant-numeric: tabular-nums; }
        @media (max-width: 520px) {
            main { margin: 32px auto; }
            li { align-items: flex-start; }
            .actions { gap: 7px; }
        }
    </style>
</head>
<body>
    <main>
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>