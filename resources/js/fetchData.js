async function fetchData() {
    const token = localStorage.getItem('auth_token');
    const response = await fetch('/some-protected-route', {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        },
    });

    const data = await response.json();
    if (response.ok) {
        console.log(data);
    } else {
        alert('Failed to fetch data: ' + data.error);
    }
}

async function fetchWithAuth(url, options = {}) {
    const token = localStorage.getItem('auth_token');
    if (!options.headers) {
        options.headers = {};
    }
    options.headers['Authorization'] = `Bearer ${token}`;
    const response = await fetch(url, options);
    return response;
}