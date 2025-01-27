async function fetchCategories() {
    const token = localStorage.getItem('auth_token');
    const response = await fetch('/api/category/get', {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        },
    });

    const data = await response.json();
    if (response.ok) {
        displayUserCategories(data);
        document.getElementById('categoriesSection').style.display = 'block'; // Mostrar a seção de categorias
    } else {
        alert('Failed to fetch categories');
    }
}

async function fetchTransactions() {
    const token = localStorage.getItem('auth_token');
    const response = await fetch('/api/transaction/get', {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        },
    });

    const data = await response.json();
    if (response.ok) {
        document.getElementById('transactions').innerText = JSON.stringify(data, null, 2);
    } else {
        alert('Failed to fetch transactions');
    }
}

async function createCategory() {
    const token = localStorage.getItem('auth_token');
    const response = await fetch('/api/category/create', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            name: document.getElementById('categoryName').value,
        }),
    });

    const data = await response.json();
    if (response.ok) {
        document.getElementById('createCategoryResult').innerText = 'Categoria cadastrada com sucesso!';
        fetchCategories(); // Atualiza a lista de categorias
    } else {
        alert('Failed to create category');
    }
}

function displayUserCategories(categories) {
    const userCategoriesDiv = document.getElementById('userCategories');
    userCategoriesDiv.innerHTML = ''; // Clear previous categories

    categories.forEach(category => {
        const categoryElement = document.createElement('div');
        categoryElement.className = 'category-item';
        categoryElement.innerText = `ID: ${category.id} - ${category.name}`;

        const closeButton = document.createElement('button');
        closeButton.className = 'close-btn';
        closeButton.innerHTML = '&times;';
        closeButton.onclick = () => alert(`Delete category: ${category.name}`);
        categoryElement.appendChild(closeButton);

        userCategoriesDiv.appendChild(categoryElement);
    });
}

function hideCategoriesSection() {
    document.getElementById('categoriesSection').style.display = 'none';
}

// Remove the fetchCategories call on page load
// window.onload = fetchCategories;