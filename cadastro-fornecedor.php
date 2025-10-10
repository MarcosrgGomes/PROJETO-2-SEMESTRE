<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$auth->checkAuth();
$page_title = "Cadastrar Fornecedor";

$fornecedor = new Fornecedor();
$fornecedor_data = [];
$is_edit = false;

// Verificar se é edição
if (isset($_GET['id'])) {
    $fornecedor_data = $fornecedor->readOne($_GET['id']);
    if ($fornecedor_data) {
        $is_edit = true;
        $page_title = "Editar Fornecedor";
    }
}

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'nome' => sanitizeInput($_POST['nome']),
        'cnpj' => sanitizeInput($_POST['cnpj']),
        'endereco' => sanitizeInput($_POST['endereco']),
        'telefone' => sanitizeInput($_POST['telefone']),
        'email' => sanitizeInput($_POST['email']),
        'observacoes' => sanitizeInput($_POST['observacoes'])
    ];

    try {
        if ($is_edit) {
            if ($fornecedor->update($_GET['id'], $data)) {
                showMessage('Fornecedor atualizado com sucesso!', 'success');
                header("Location: fornecedores.php");
                exit();
            }
        } else {
            if ($fornecedor->create($data)) {
                showMessage('Fornecedor cadastrado com sucesso!', 'success');
                header("Location: fornecedores.php");
                exit();
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
        <h2><?php echo $is_edit ? 'Editar Fornecedor' : 'Cadastrar Fornecedor'; ?></h2>
        <p class="muted"><?php echo $is_edit ? 'Atualize os dados do fornecedor' : 'Adicione um novo fornecedor ao sistema'; ?></p>
    </div>
    <a href="fornecedores.php" class="btn btn-secondary">Voltar</a>
</div>

<div class="card">
    <form method="POST" class="form-card">
        <div class="form-row">
            <div class="form-group">
                <label for="nome">Nome do Fornecedor *</label>
                <input type="text" id="nome" name="nome" class="input" required value="<?php echo $fornecedor_data['nome'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label for="cnpj">CNPJ *</label>
                <input type="text" id="cnpj" name="cnpj" class="input" required value="<?php echo $fornecedor_data['cnpj'] ?? ''; ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="endereco">Endereço *</label>
            <input type="text" id="endereco" name="endereco" class="input" required value="<?php echo $fornecedor_data['endereco'] ?? ''; ?>">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="telefone">Telefone *</label>
                <input type="text" id="telefone" name="telefone" class="input" required value="<?php echo $fornecedor_data['telefone'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label for="email">E-mail *</label>
                <input type="email" id="email" name="email" class="input" required value="<?php echo $fornecedor_data['email'] ?? ''; ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="observacoes">Observações</label>
            <textarea id="observacoes" name="observacoes" class="input" rows="4"><?php echo $fornecedor_data['observacoes'] ?? ''; ?></textarea>
        </div>

        <div class="form-actions">
            <a href="fornecedores.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <?php echo $is_edit ? 'Atualizar Fornecedor' : 'Cadastrar Fornecedor'; ?>
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>