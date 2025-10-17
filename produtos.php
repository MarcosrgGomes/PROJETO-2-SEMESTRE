<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Produtos Cadastrados - Serenity</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body id="page-produtos">

    <?php include 'templates/header.php'; ?>

    <div class="container table-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
             <h2>Produtos Cadastrados</h2>
             <a href="cadastrar_produto.php" class="btn">Adicionar Novo</a>
        </div>
        <table class="product-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Estoque</th>
                    <th>Mínimo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="product-table-body">
                </tbody>
        </table>
        <br>
        <a href="dashboard.php" class="btn btn-secondary">Voltar ao Painel</a>
    </div>
    <script src="js/app.js"></script>
</body>
</html>