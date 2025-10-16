<?php
/**
 * Controlador de Usuários
 */

class UserController {
    public function index() {
        Security::checkPermissions('admin');
        
        $usersFile = DATA_PATH . '/users/users.json';
        $users = DataManager::load($usersFile);
        
        require_once APP_PATH . '/views/admin/users.php';
    }
    
    public function delete() {
        Security::checkPermissions('admin');
        
        $id = sanitize($_GET['id'] ?? '');
        $currentUserId = $_SESSION['user_id'];
        
        if ($id === $currentUserId) {
            setFlashMessage('error', 'Você não pode excluir sua própria conta.');
            redirect('users');
        }
        
        $usersFile = DATA_PATH . '/users/users.json';
        $users = DataManager::load($usersFile);
        
        $newUsers = array_filter($users, function($u) use ($id) {
            return $u['id'] !== $id;
        });
        
        DataManager::save($usersFile, array_values($newUsers));
        Security::auditLog('user_deleted', ['user_id' => $id]);
        setFlashMessage('success', 'Usuário excluído com sucesso!');
        redirect('users');
    }
}

