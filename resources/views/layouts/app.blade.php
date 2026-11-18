<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Manager</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f5f7; margin: 0; }
        main { max-width: 600px; margin: 40px auto; padding: 0 16px; }
        form.create { display: flex; gap: 8px; margin-bottom: 16px; }
        input[type=text] { flex: 1; padding: 8px; }
        button { padding: 8px 14px; cursor: pointer; }
        ul { list-style: none; padding: 0; }
        li { background: #fff; padding: 10px 12px; margin-bottom: 6px; border-radius: 4px; }
        .error { color: #c0392b; margin: 4px 0; }
    </style>
</head>
<body>
    <main>
        @yield('content')
    </main>
</body>
</html>