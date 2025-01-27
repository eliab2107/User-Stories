<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Dashboard</h1>

        <!-- Seção de Categorias -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Categorias</h2>
            </div>
            <form id="createCategoryForm" onsubmit="event.preventDefault(); createCategory();" class="form-inline mb-3">
                @csrf
                <div class="form-group mr-2">
                    <label for="categoryName" class="sr-only">Nome da Categoria:</label>
                    <input type="text" id="categoryName" name="categoryName" class="form-control" placeholder="Nome da Categoria" required>
                </div>
                <button type="submit" class="btn btn-success">Cadastrar Categoria</button>
            </form>
            <form id="categoryForm" onsubmit="event.preventDefault(); fetchCategories();" class="form-inline">
                @csrf
                <button type="submit" class="btn btn-primary">Buscar Categorias</button>
            </form>
            <div id="categoriesSection" class="mt-3" style="display: none;">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Categorias</h2>
                    <button class="btn btn-danger btn-sm" onclick="hideCategoriesSection()">Fechar</button>
                </div>
                <div id="userCategories" class="d-flex flex-wrap"></div>
            </div>
            <div id="createCategoryResult" class="mt-3"></div>
        </div>

        <!-- Seção de Transações -->
        <div class="mb-4">
            <h2>Transações</h2>
            <form id="transactionForm" onsubmit="event.preventDefault(); fetchTransactions();" class="form-inline">
                @csrf
                <button type="submit" class="btn btn-primary">Buscar Transações</button>
            </form>
            <div id="transactions" class="mt-3"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>