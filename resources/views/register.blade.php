<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
    <form id="registerForm" onsubmit="event.preventDefault(); registerUser();" class="form-inline">
        @csrf
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" required>
        <br>
        <button type="submit">Register</button>
    </form>

    <script>
        async function registerUser() {
            const form = document.getElementById('registerForm');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            const response = await fetch('api/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });
            console.log(response);
            if (response.ok) {
                if (response.ok) {
                window.location.href = '/login';
            } else {
                const errorData = await response.json();
                alert('Registration failed: ' + errorData.message);
            }
         }
        }
    </script>
</body>
</html>