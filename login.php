<?php
require_once 'includes/auth.php';

if ($auth->isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card fade-in">
            <div class="login-header">
                <div class="login-logo">Serenity</div>
                <p class="login-subtitle">Sistema de Gestão de Perfumaria</p>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="input" placeholder="seu@email.com" required value="<?php echo $_POST['email'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <div class="password-input-container">
                        <input type="password" id="password" name="password" class="input password-input" placeholder="Sua senha" required>
                        <button type="button" class="password-toggle" id="togglePassword" aria-label="Mostrar senha">
                            <span class="password-icon">👁️</span>
                        </button>
                    </div>
                </div>

                <div class="form-group" style="display: flex; justify-content: space-between; align-items: center;">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span>Lembrar-me</span>
                    </label>
                    <a href="esqueci-senha.php" class="forgot-password">Esqueceu a senha?</a>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Entrar
                </button>

                <div class="login-footer" style="text-align: center; margin-top: 2rem;">
                    <p class="muted">Não tem acesso? <a href="cadastro-usuario.php">Solicitar conta</a></p>
                </div>

                <!-- Credenciais de teste -->
                <div class="test-credentials" style="margin-top: 1.5rem; padding: 1rem; background: rgba(212, 175, 55, 0.1); border-radius: 8px; border-left: 4px solid var(--dourado-suave);">
                    <p style="margin: 0; font-size: 0.85rem; color: var(--cinza-medio);">
                        <strong>Credenciais de teste:</strong><br>
                        Email: <code>admin@serenity.com</code><br>
                        Senha: <code>123456</code>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const passwordIcon = togglePassword.querySelector('.password-icon');
        const loginForm = document.querySelector('.login-form');
        
        // Botão mostrar/ocultar senha
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Ícones animados
            if (type === 'text') {
                passwordIcon.textContent = '🔒';
                passwordIcon.style.transform = 'scale(1.2)';
                setTimeout(() => passwordIcon.style.transform = 'scale(1)', 200);
            } else {
                passwordIcon.textContent = '👁️';
                passwordIcon.style.transform = 'scale(1.2)';
                setTimeout(() => passwordIcon.style.transform = 'scale(1)', 200);
            }
            
            this.setAttribute('aria-label', type === 'password' ? 'Mostrar senha' : 'Ocultar senha');
            
            // Focar de volta no input
            passwordInput.focus();
        });

        // Feedback visual ao digitar
        passwordInput.addEventListener('input', function() {
            const container = this.parentElement;
            if (this.value.length > 0) {
                container.classList.add('has-value');
            } else {
                container.classList.remove('has-value');
            }
        });

        // Tecla Escape para ocultar senha se estiver visível
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && passwordInput.type === 'text') {
                passwordInput.type = 'password';
                passwordIcon.textContent = '👁️';
                togglePassword.setAttribute('aria-label', 'Mostrar senha');
            }
        });

        // Enter para submeter formulário
        loginForm.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.click();
            }
        });

        // Auto-preenchimento para desenvolvimento
        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
            setTimeout(() => {
                const emailField = document.getElementById('email');
                if (!emailField.value) {
                    emailField.value = 'admin@serenity.com';
                    passwordInput.value = '123456';
                    
                    // Feedback visual
                    emailField.style.borderColor = 'var(--dourado-suave)';
                    passwordInput.style.borderColor = 'var(--dourado-suave)';
                    
                    setTimeout(() => {
                        emailField.style.borderColor = '';
                        passwordInput.style.borderColor = '';
                    }, 2000);
                }
            }, 500);
        }

        // Efeito de foco suave
        const inputs = loginForm.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });

        console.log('🔐 Sistema de login Serenity carregado!');
    });
</script>
</body>
</html>