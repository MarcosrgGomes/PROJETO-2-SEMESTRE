<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$auth->checkAuth();
if (!$auth->hasPermission('gerente')) {
    header("Location: dashboard.php");
    exit();
}

$page_title = "Cadastrar Usuário";

$usuario = new Usuario();
$usuario_data = [];
$is_edit = false;

// Verificar se é edição
if (isset($_GET['id'])) {
    $usuario_data = $usuario->readOne($_GET['id']);
    if ($usuario_data) {
        $is_edit = true;
        $page_title = "Editar Usuário";
    }
}

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'nome' => sanitizeInput($_POST['nome']),
        'email' => sanitizeInput($_POST['email']),
        'tipo' => sanitizeInput($_POST['tipo'])
    ];

    // Se for cadastro novo ou alteração de senha
    if (!empty($_POST['senha'])) {
        $data['senha'] = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    }

    try {
        if ($is_edit) {
            if ($usuario->update($_GET['id'], $data)) {
                showMessage('Usuário atualizado com sucesso!', 'success');
                header("Location: usuarios.php");
                exit();
            }
        } else {
            if (empty($_POST['senha'])) {
                showMessage('A senha é obrigatória para novo usuário!', 'error');
            } else {
                if ($usuario->create($data)) {
                    showMessage('Usuário cadastrado com sucesso!', 'success');
                    header("Location: usuarios.php");
                    exit();
                }
            }
        }
    } catch (Exception $e) {
        showMessage('Erro: ' . $e->getMessage(), 'error');
    }
}
?>
<?php include 'includes/header.php'; ?>

<div class="page-header">
    <div>
        <h2><?php echo $is_edit ? 'Editar Usuário' : 'Cadastrar Usuário'; ?></h2>
        <p class="muted"><?php echo $is_edit ? 'Atualize os dados do usuário' : 'Adicione um novo usuário ao sistema'; ?></p>
    </div>
    <a href="usuarios.php" class="btn btn-secondary">Voltar</a>
</div>

<div class="card">
    <form method="POST" class="form-card">
        <div class="form-row">
            <div class="form-group">
                <label for="nome">Nome Completo *</label>
                <input type="text" id="nome" name="nome" class="input" required value="<?php echo $usuario_data['nome'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label for="email">E-mail *</label>
                <input type="email" id="email" name="email" class="input" required value="<?php echo $usuario_data['email'] ?? ''; ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="tipo">Tipo de Usuário *</label>
                <select id="tipo" name="tipo" class="input" required>
                    <option value="">Selecione o tipo</option>
                    <option value="vendedor" <?php echo ($usuario_data['tipo'] ?? '') == 'vendedor' ? 'selected' : ''; ?>>Vendedor</option>
                    <option value="gerente" <?php echo ($usuario_data['tipo'] ?? '') == 'gerente' ? 'selected' : ''; ?>>Gerente</option>
                    <option value="admin" <?php echo ($usuario_data['tipo'] ?? '') == 'admin' ? 'selected' : ''; ?>>Administrador</option>
                </select>
            </div>
            <div class="form-group">
                <label for="senha">Senha <?php echo $is_edit ? '(deixe em branco para manter atual)' : '*'; ?></label>
                <input type="password" id="senha" name="senha" class="input" <?php echo $is_edit ? '' : 'required'; ?>>
            </div>
        </div>

        <div class="form-actions">
            <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <?php echo $is_edit ? 'Atualizar Usuário' : 'Cadastrar Usuário'; ?>
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>