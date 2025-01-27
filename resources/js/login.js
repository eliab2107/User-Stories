document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('login-form').addEventListener('submit', async function(event) {
        event.preventDefault();

        const response = await fetch('/login', {
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
        console.log(data);
        console.log('aaaaa');
        if (response.ok) {
            localStorage.setItem('auth_token', data.token);
            alert('Login successful');
            window.location.href = '/dashboard';
        } else {
            alert('Login failed: ' + data.error);
        }
    });
});