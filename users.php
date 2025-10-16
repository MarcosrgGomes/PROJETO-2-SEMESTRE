<?php
$pageTitle = 'Gerenciar Usuários';
require_once APP_PATH . '/views/templates/header.php';
?>

<div class="app-container">
    <?php require_once APP_PATH . '/views/templates/navigation.php'; ?>
    
    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <h1 class="topbar-title">Gerenciar Usuários</h1>
            </div>
            <div class="topbar-right">
                <div class="user-menu">
                    <div class="user-avatar"><?php echo strtoupper(substr(getCurrentUser()['name'], 0, 1)); ?></div>
                </div>
            </div>
        </header>
        
        <div class="content-area">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista de Usuários (<?php echo count($users); ?>)</h3>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Perfil</th>
                                    <th>Status</th>
                                    <th>Data de Cadastro</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td style="font-weight: 600;"><?php echo $user['name']; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $user['role'] === 'admin' ? 'error' : 'info'; ?>">
                                            <?php echo USER_ROLES[$user['role']]; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo $user['status'] === 'active' ? 'success' : 'neutral'; ?>">
                                            <?php echo $user['status'] === 'active' ? 'Ativo' : 'Inativo'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo formatDate($user['created_at'], 'd/m/Y'); ?></td>
                                    <td>
                                        <?php if ($user['id'] !== getCurrentUser()['id']): ?>
                                            <a href="index.php?page=users&action=delete&id=<?php echo $user['id']; ?>" 
                                               class="btn btn-sm btn-danger" 
                                               data-confirm="Tem certeza que deseja excluir este usuário?">
                                                🗑️
                                            </a>
                                        <?php else: ?>
                                            <span class="badge badge-neutral">Você</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once APP_PATH . '/views/templates/footer.php'; ?>

