<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$auth->checkAuth();
if (!$auth->hasPermission('gerente')) {
    header("Location: dashboard.php");
    exit();
}

$page_title = "Usuários";

$usuario = new Usuario();

// Processar exclusão
if (isset($_GET['delete_id'])) {
    if ($usuario->delete($_GET['delete_id'])) {
        showMessage('Usuário excluído com sucesso!', 'success');
        header("Location: usuarios.php");
        exit();
    } else {
        showMessage('Erro ao excluir usuário!', 'error');
    }
}

$usuarios = $usuario->readAll();
?>
<?php include 'includes/header.php'; ?>

<div class="page-header">
    <div>
        <h2>Gerenciar Usuários</h2>
        <p class="muted">Gerencie os usuários do sistema</p>
    </div>
    <a href="cadastro-usuario.php" class="btn btn-primary">Novo Usuário</a>
</div>

<!-- Tabela de Usuários -->
<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Tipo</th>
                    <th>Data de Cadastro</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($usuarios) > 0): ?>
                    <?php foreach ($usuarios as $user): ?>
                    <tr>
                        <td><?php echo $user['nome']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td>
                            <span class="badge tipo-<?php echo $user['tipo']; ?>">
                                <?php echo ucfirst($user['tipo']); ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="cadastro-usuario.php?id=<?php echo $user['id']; ?>" class="btn btn-ghost">Editar</a>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <button onclick="confirmDelete(<?php echo $user['id']; ?>)" class="btn btn-danger">Excluir</button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="muted text-center">Nenhum usuário cadastrado</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Tem certeza que deseja excluir este usuário?')) {
        window.location.href = 'usuarios.php?delete_id=' + id;
    }
}
</script>

<?php include 'includes/footer.php'; ?>