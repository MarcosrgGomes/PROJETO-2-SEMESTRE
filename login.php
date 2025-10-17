<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Serenity</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container login-container">
        <div class="logo">
            <img src="img/logo.png" alt="Serenity Logo">
        </div>
        <?php 
            if (isset($_SESSION['login_error'])) {
                echo '<p style="color: red; margin-bottom: 1rem;">' . $_SESSION['login_error'] . '</p>';
                unset($_SESSION['login_error']);
            }
            if (isset($_SESSION['register_success'])) {
                echo '<p style="color: green; margin-bottom: 1rem;">' . $_SESSION['register_success'] . '</p>';
                unset($_SESSION['register_success']);
            }
        ?>
        <form action="php_actions/handle_login.php" method="POST">
            <div class="form-group">
                <i class="fas fa-user input-icon"></i>
                <input type="text" name="usuario" placeholder="Usuário" required>
            </div>
            <div class="form-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" name="senha" placeholder="Senha" required>
            </div>
            <button type="submit" class="btn">Entrar</button>
            <div class="login-links">
                <a href="#">Esqueci a Senha</a>
                <a href="registrar.php">Criar Conta</a>
            </div>
        </form>
    </div>
</body>
</html>