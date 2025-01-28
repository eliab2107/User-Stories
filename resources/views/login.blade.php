<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form onsubmit="event.preventDefault(); login()">
        @csrf
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
    <script>
        async function login() {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                }),
            });

            const data = await response.json();
            if (response.ok) {
                localStorage.setItem('auth_token', data.token);
                fetchDashboard(data.token);
            } else {
                alert('Login failed');
            }
        }

        async function fetchDashboard(token) {
            const response = await fetch('/api/dashboard', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                },
            });

            const data = await response.json();
            if (response.ok) {
                console.log('Dashboard data:', data);
                // Redirecionar para a página do dashboard ou exibir os dados
                window.location.href = '/dashboard';
            } else {
                alert('Failed to fetch dashboard data');
            }
        }
    </script>
</body>
</html>