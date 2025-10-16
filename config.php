<?php
/**
 * Configurações Gerais do Sistema Serenity
 */

// Configurações da aplicação
define('APP_NAME', 'Serenity');
define('APP_VERSION', '1.0.0 - Sprint 1');
define('APP_DESCRIPTION', 'Sistema de Gerenciamento de Estoque');

// Configurações de timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de sessão (antes de session_start)
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Mudar para 1 em produção com HTTPS

// Configurações de erro (desenvolvimento)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurações de segurança
define('CSRF_TOKEN_NAME', 'csrf_token');
define('SESSION_TIMEOUT', 3600); // 1 hora

// Configurações de paginação
define('ITEMS_PER_PAGE', 10);

// Configurações de upload
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);

// Configurações de estoque
define('LOW_STOCK_THRESHOLD', 10);
define('CRITICAL_STOCK_THRESHOLD', 5);

// Perfis de usuário
define('USER_ROLES', [
    'admin' => 'Administrador',
    'manager' => 'Gerenciador',
    'operator' => 'Usuário'
]);

// Tipos de movimentação de estoque
define('STOCK_MOVEMENT_TYPES', [
    'entry' => 'Entrada',
    'exit' => 'Saída',
    'adjustment' => 'Ajuste',
    'return' => 'Devolução',
    'loss' => 'Perda'
]);

// Status de produtos
define('PRODUCT_STATUS', [
    'active' => 'Ativo',
    'inactive' => 'Inativo',
    'discontinued' => 'Descontinuado'
]);
