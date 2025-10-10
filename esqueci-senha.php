<?php
require_once 'includes/auth.php';

if ($auth->isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    
    // Simular envio de email (em produção, integrar com serviço de email)
    $message = "Se o e-mail existir em nosso sistema, enviaremos instruções para redefinição de senha.";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci a Senha - Serenity</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card fade-in">
            <div class="login-header">
                <div class="login-logo">Serenity</div>
                <p class="login-subtitle">Recuperação de Senha</p>
            </div>

            <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="input" placeholder="seu@email.com" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Enviar Instruções
                </button>

                <div class="login-footer" style="text-align: center; margin-top: 2rem;">
                    <p class="muted"><a href="login.php">Voltar para o login</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>