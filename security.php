<?php
/**
 * Funções de Segurança do Sistema
 */

class Security {
    /**
     * Valida requisição com CSRF token
     */
    public static function validateRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                die('Erro de segurança: Token CSRF inválido');
            }
        }
        return true;
    }
    
    /**
     * Sanitiza dados de entrada
     */
    public static function sanitizeData($data, $type = 'string') {
        switch ($type) {
            case 'email':
                return filter_var($data, FILTER_SANITIZE_EMAIL);
            case 'int':
                return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
            case 'float':
                return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            case 'url':
                return filter_var($data, FILTER_SANITIZE_URL);
            default:
                return sanitize($data);
        }
    }
    
    /**
     * Registra ação no log de auditoria
     */
    public static function auditLog($action, $details = []) {
        $user = getCurrentUser();
        $logEntry = [
            'id' => generateId(),
            'user_id' => $user['id'] ?? 'guest',
            'user_name' => $user['name'] ?? 'Visitante',
            'action' => $action,
            'details' => $details,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        $logFile = DATA_PATH . '/audit/audit.json';
        $logs = DataManager::load($logFile);
        $logs[] = $logEntry;
        
        // Manter apenas os últimos 1000 registros
        if (count($logs) > 1000) {
            $logs = array_slice($logs, -1000);
        }
        
        DataManager::save($logFile, $logs);
    }
    
    /**
     * Verifica permissões de acesso
     */
    public static function checkPermissions($requiredRole) {
        if (!hasPermission($requiredRole)) {
            setFlashMessage('error', 'Você não tem permissão para acessar esta área.');
            redirect('dashboard');
        }
        return true;
    }
    
    /**
     * Gera token seguro
     */
    public static function generateSecureToken($length = 32) {
        return bin2hex(random_bytes($length));
    }
    
    /**
     * Valida força da senha
     */
    public static function validatePasswordStrength($password) {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = 'A senha deve ter no mínimo 8 caracteres';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'A senha deve conter pelo menos uma letra maiúscula';
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'A senha deve conter pelo menos uma letra minúscula';
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'A senha deve conter pelo menos um número';
        }
        
        return $errors;
    }
    
    /**
     * Rate limiting simples baseado em sessão
     */
    public static function rateLimit($action, $maxAttempts = 5, $timeWindow = 300) {
        $key = 'rate_limit_' . $action;
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [
                'attempts' => 0,
                'first_attempt' => time()
            ];
        }
        
        $data = $_SESSION[$key];
        
        // Resetar se passou o tempo
        if (time() - $data['first_attempt'] > $timeWindow) {
            $_SESSION[$key] = [
                'attempts' => 1,
                'first_attempt' => time()
            ];
            return true;
        }
        
        // Verificar limite
        if ($data['attempts'] >= $maxAttempts) {
            return false;
        }
        
        $_SESSION[$key]['attempts']++;
        return true;
    }
}

