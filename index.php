<?php
require_once 'includes/auth.php';

if ($auth->isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

header("Location: login.php");
exit();

$error = '';
if ($_POST) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($auth->login($email, $password)) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "E-mail ou senha inválidos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Serenity</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card fade-in">
            <div class="login-header">
                <div class="login-logo">Serenity</div>
                <p class="login-subtitle">Sistema de Gestão de Perfumaria</p>
            </div>

            <?php if ($error): ?>
            <div class="toast error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="input" placeholder="seu@email.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" class="input" placeholder="Sua senha" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Entrar</button>

                <div style="text-align: center; margin-top: 1rem;">
                    <a href="esqueci-senha.php" class="forgot-password">Esqueceu a senha?</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>