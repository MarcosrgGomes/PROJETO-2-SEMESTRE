<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Serenity</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php include 'templates/header.php'; ?>

    <div class="container dashboard-container">
        <main class="stock-main">
            <h2>Painel de Controle</h2>
            <img src="img/imagem_ilustrativa4.png" alt="Produto Serenity" style="width: 100%; max-width: 350px; border-radius: 8px; margin-top: 1rem;">
        </main>
        <nav class="nav-menu">
            <a href="dashboard.php" class="nav-item">Início <span>&gt;</span></a>
            <a href="cadastrar_produto.php" class="nav-item">Cadastrar Produto <span>&gt;</span></a>
            <a href="produtos.php" class="nav-item">Visualizar Produtos <span>&gt;</span></a>
            <a href="movimentar_estoque.php" class="nav-item">Movimentar Estoque <span>&gt;</span></a>
            <a href="logout.php" class="nav-item">Sair <span>&gt;</span></a>
        </nav>
    </div>
</body>
</html>