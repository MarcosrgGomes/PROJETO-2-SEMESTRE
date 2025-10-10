<?php
session_start();

// Simulação de usuários (em produção, usar banco de dados)
$users = [
    'admin' => '123456'
];

// Verificar se usuário está logado
function checkAuth() {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }
}

// Fazer login
if ($_POST['action'] ?? '' === 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (isset($users[$username]) && $users[$username] === $password) {
        $_SESSION['user'] = $username;
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Usuário ou senha inválidos!";
    }
}
?>