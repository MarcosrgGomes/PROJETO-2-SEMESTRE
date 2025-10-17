<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Movimentar Estoque - Serenity</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body id="page-movimentar-estoque">

    <?php include 'templates/header.php'; ?>

    <div class="container form-container">
        <h2>Movimentar Estoque</h2>
        <form id="movement-form">
            <div class="form-group">
                <label for="produto-select">Produto</label>
                <select id="produto-select" name="produto" required>
                    <option value="">Carregando produtos...</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tipo_movimentacao">Tipo de Movimentação</label>
                <select id="tipo_movimentacao" name="tipo_movimentacao" required>
                    <option value="Entrada">Entrada</option>
                    <option value="Saída">Saída</option>
                </select>
            </div>
             <div class="form-group">
                <label for="quantidade">Quantidade</label>
                <input type="number" id="quantidade" name="quantidade" min="1" placeholder="0" required style="padding-left: 15px;">
            </div>
             <div class="form-group">
                <label for="data">Data</label>
                <input type="date" id="data" name="data" required style="padding-left: 15px;">
            </div>
            <button type="submit" class="btn">Confirmar Movimentação</button>
            <div style="margin-top: 1.5rem;">
                <a href="dashboard.php">Voltar ao painel</a>
            </div>
        </form>
    </div>
    <script src="js/app.js"></script>
</body>
</html>