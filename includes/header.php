<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acir Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Acir Estoque</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=itens">Itens</a>
                    </li>
                    <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'estoquista'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=entradas">Entradas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=saidas">Saídas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=fornecedores">Fornecedores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=centros_de_custo">Centros de Custo</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=adicionar_usuario">Adicionar Usuário</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=gerenciar_requisicoes">Gerenciar Requisições</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=relatorios">Relatórios</a>
                        </li>
                    <?php endif; ?>
                    <?php if ($_SESSION['role'] === 'funcionario'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=fazer_requisicao">Fazer Requisição</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=minhas_requisicoes">Minhas Requisições</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <span class="nav-link text-light">Bem-vindo, <?= htmlspecialchars($_SESSION['username']) ?> (<?= htmlspecialchars($_SESSION['role']) ?>)</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?logout=true">Sair</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container mt-4">
