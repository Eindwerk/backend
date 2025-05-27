<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Stel je wachtwoord in</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 text-gray-900 p-8">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Welkom, {{ $user->name }}</h1>
        <p class="mb-6">Kies hieronder je wachtwoord om toegang te krijgen tot het adminportaal.</p>

        <form method="POST" action="{{ route('admin.register.submit', $user) }}">
            @csrf

            <div class="mb-4">
                <label for="password" class="block font-medium">Wachtwoord</label>
                <input type="password" name="password" id="password" required class="w-full border rounded p-2 mt-1">
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block font-medium">Bevestig wachtwoord</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full border rounded p-2 mt-1">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Bevestigen en inloggen
            </button>
        </form>
    </div>
</body>

</html>