<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register</title>
</head>
<body>
    <div class="register-container">
        <h2>Admin Register</h2>
        @if(session('error'))
            <p style="color: red;">{{ session('error') }}</p>
        @endif
        @if($errors->any())
            <p style="color: red;">{{ $errors->first() }}</p>
        @endif

        <form action="{{ route('admin.register.post') }}" method="POST">
            @csrf
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
