<?php
/**
 * Controlador de Autenticação
 */

class AuthController {
    /**
     * Página de login
     */
    public function index() {
        $this->login();
    }
    
    /**
     * Exibe formulário de login
     */
    public function login() {
        if (isLoggedIn()) {
            redirect('dashboard');
        }
        require_once APP_PATH . '/views/auth/login.php';
    }
    
    /**
     * Processa login
     */
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('login');
        }
        
        // Rate limiting
        if (!Security::rateLimit('login', 5, 300)) {
            setFlashMessage('error', 'Muitas tentativas de login. Tente novamente em 5 minutos.');
            redirect('login');
        }
        
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        // Validar dados
        if (empty($email) || empty($password)) {
            setFlashMessage('error', 'Por favor, preencha todos os campos.');
            redirect('login');
        }
        
        // Buscar usuário
        $usersFile = DATA_PATH . '/users/users.json';
        $users = DataManager::load($usersFile);
        
        $user = null;
        foreach ($users as $u) {
            if ($u['email'] === $email) {
                $user = $u;
                break;
            }
        }
        
        // Verificar senha
        if (!$user || !password_verify($password, $user['password'])) {
            Security::auditLog('login_failed', ['email' => $email]);
            setFlashMessage('error', 'Email ou senha incorretos.');
            redirect('login');
        }
        
        // Verificar status
        if ($user['status'] !== 'active') {
            setFlashMessage('error', 'Sua conta está inativa. Entre em contato com o administrador.');
            redirect('login');
        }
        
        // Criar sessão
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_data'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        $_SESSION['login_time'] = time();
        
        // Lembrar-me
        if ($remember) {
            setcookie('remember_token', $user['id'], time() + (86400 * 30), '/');
        }
        
        Security::auditLog('login_success', ['user_id' => $user['id']]);
        setFlashMessage('success', 'Bem-vindo(a), ' . $user['name'] . '!');
        redirect('dashboard');
    }
    
    /**
     * Exibe formulário de registro
     */
    public function register() {
        if (isLoggedIn()) {
            redirect('dashboard');
        }
        require_once APP_PATH . '/views/auth/register.php';
    }
    
    /**
     * Processa registro
     */
    public function doRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('register');
        }
        
        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validações
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'Nome é obrigatório';
        }
        
        if (empty($email) || !isValidEmail($email)) {
            $errors[] = 'Email inválido';
        }
        
        if ($password !== $confirmPassword) {
            $errors[] = 'As senhas não coincidem';
        }
        
        $passwordErrors = Security::validatePasswordStrength($password);
        $errors = array_merge($errors, $passwordErrors);
        
        // Verificar email único
        $usersFile = DATA_PATH . '/users/users.json';
        $users = DataManager::load($usersFile);
        
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                $errors[] = 'Este email já está cadastrado';
                break;
            }
        }
        
        if (!empty($errors)) {
            setFlashMessage('error', implode('<br>', $errors));
            redirect('register');
        }
        
        // Criar usuário
        $newUser = [
            'id' => generateId(),
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'operator', // Perfil padrão
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $users[] = $newUser;
        DataManager::save($usersFile, $users);
        
        Security::auditLog('user_registered', ['user_id' => $newUser['id'], 'email' => $email]);
        setFlashMessage('success', 'Cadastro realizado com sucesso! Faça login para continuar.');
        redirect('login');
    }
    
    /**
     * Exibe formulário de recuperação de senha
     */
    public function forgotPassword() {
        if (isLoggedIn()) {
            redirect('dashboard');
        }
        require_once APP_PATH . '/views/auth/forgot-password.php';
    }
    
    /**
     * Processa recuperação de senha
     */
    public function sendResetLink() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('forgot-password');
        }
        
        $email = sanitize($_POST['email'] ?? '');
        
        if (empty($email) || !isValidEmail($email)) {
            setFlashMessage('error', 'Email inválido.');
            redirect('forgot-password');
        }
        
        // Buscar usuário
        $usersFile = DATA_PATH . '/users/users.json';
        $users = DataManager::load($usersFile);
        
        $userExists = false;
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                $userExists = true;
                break;
            }
        }
        
        // Sempre mostrar mensagem de sucesso (segurança)
        Security::auditLog('password_reset_requested', ['email' => $email]);
        setFlashMessage('success', 'Se o email estiver cadastrado, você receberá um link de recuperação.');
        redirect('login');
    }
    
    public function logout() {
    $userId = $_SESSION['user_id'] ?? null;
    
    if ($userId) {
        Security::auditLog('logout', ['user_id' => $userId]);
    }
    
    session_unset(); // Remove todas as variáveis de sessão
    session_destroy(); // Destrói os dados registrados na sessão
    setcookie(session_name(), '', time() - 3600, '/'); // Remove o cookie de sessão
    session_regenerate_id(true); // Regenera o ID da sessão para segurança
    
    // Certifique-se de que qualquer cookie 'remember_token' também seja removido, se estiver ativo
    setcookie('remember_token', '', time() - 3600, '/'); // Garante que o cookie de 'lembrar-me' seja invalidado

    redirect('login'); // Redireciona para a página de login
}
}

