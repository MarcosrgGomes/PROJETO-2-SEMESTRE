<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto - Serenity</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body id="page-cadastrar-produto">

    <?php include 'templates/header.php'; ?>

    <div class="container form-container">
        <h2>Cadastrar Novo Produto</h2>
        <form id="add-product-form">
            <div class="form-group">
                <label for="nome">Nome do Produto</label>
                <input type="text" id="nome" name="nome" required style="padding-left: 15px;">
            </div>
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <input type="text" id="descricao" name="descricao" required style="padding-left: 15px;">
            </div>
             <div class="form-group">
                <label for="estoque">Estoque Inicial</label>
                <input type="number" id="estoque" name="estoque" min="0" value="0" required style="padding-left: 15px;">
            </div>
             <div class="form-group">
                <label for="minimo">Estoque Mínimo</label>
                <input type="number" id="minimo" name="minimo" min="0" value="5" required style="padding-left: 15px;">
            </div>
            <button type="submit" class="btn">Cadastrar Produto</button>
        </form>
         <div style="margin-top: 1.5rem;">
            <a href="produtos.php">Voltar para a lista de produtos</a>
        </div>
    </div>
    <script src="js/app.js"></script>
</body>
</html>