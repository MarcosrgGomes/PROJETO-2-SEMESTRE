<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Conta - Serenity</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container login-container">
        <div class="logo">
            <img src="img/logo.png" alt="Serenity Logo">
        </div>
        <h2>Criar Conta</h2>
        <?php 
            if (isset($_SESSION['register_error'])) {
                echo '<p style="color: red; margin-bottom: 1rem;">' . $_SESSION['register_error'] . '</p>';
                unset($_SESSION['register_error']);
            }
        ?>
        <form action="php_actions/handle_registration.php" method="POST">
            <div class="form-group">
                <i class="fas fa-user input-icon"></i>
                <input type="text" name="usuario" placeholder="Escolha um nome de usuário" required>
            </div>
            <div class="form-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" name="senha" placeholder="Crie uma senha" required>
            </div>
            <button type="submit" class="btn">Registrar</button>
            <div class="login-links" style="justify-content: center; margin-top: 1.5rem;">
                <a href="login.php">Já tem uma conta? Faça o login</a>
            </div>
        </form>
    </div>
</body>
</html>