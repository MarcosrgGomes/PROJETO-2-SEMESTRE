<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    header("Location: produtos.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto - Serenity</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body id="page-editar-produto">

    <?php include 'templates/header.php'; ?>

    <div class="container form-container">
        <h2>Editar Produto</h2>
        <form id="edit-product-form">
            <input type="hidden" id="edit-id" name="id">
            <div class="form-group">
                <label for="edit-nome">Nome do Produto</label>
                <input type="text" id="edit-nome" name="nome" required style="padding-left: 15px;">
            </div>
            <div class="form-group">
                <label for="edit-descricao">Descrição</label>
                <input type="text" id="edit-descricao" name="descricao" required style="padding-left: 15px;">
            </div>
            <div class="form-group">
                <label for="edit-estoque">Estoque Atual</label>
                <input type="number" id="edit-estoque" name="estoque" min="0" required style="padding-left: 15px;">
            </div>
            <div class="form-group">
                <label for="edit-minimo">Estoque Mínimo</label>
                <input type="number" id="edit-minimo" name="minimo" min="0" required style="padding-left: 15px;">
            </div>
            <button type="submit" class="btn">Salvar Alterações</button>
        </form>
        <div style="margin-top: 1.5rem;">
            <a href="produtos.php">Cancelar e voltar</a>
        </div>
    </div>

    <script>
        const productId = <?php echo json_encode($product_id); ?>;
    </script>
    <script src="js/app.js"></script>

</body>
</html>