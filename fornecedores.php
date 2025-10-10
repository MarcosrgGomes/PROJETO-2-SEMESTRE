<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$auth->checkAuth();
$page_title = "Fornecedores";

$fornecedor = new Fornecedor();
$search = $_GET['search'] ?? '';

// Processar exclusão
if (isset($_GET['delete_id'])) {
    if ($fornecedor->delete($_GET['delete_id'])) {
        showMessage('Fornecedor excluído com sucesso!', 'success');
        header("Location: fornecedores.php");
        exit();
    } else {
        showMessage('Erro ao excluir fornecedor!', 'error');
    }
}

$fornecedores = $fornecedor->readAll($search);
?>
<?php include 'includes/header.php'; ?>

<div class="page-header">
    <div>
        <h2>Gerenciar Fornecedores</h2>
        <p class="muted">Cadastre e gerencie os fornecedores da perfumaria</p>
    </div>
    <a href="cadastro-fornecedor.php" class="btn btn-primary">Novo Fornecedor</a>
</div>

<!-- Filtros -->
<div class="filters">
    <form method="GET" class="filter-row">
        <div class="form-group">
            <label for="search">Pesquisar</label>
            <input type="text" id="search" name="search" class="input" placeholder="Nome, CNPJ ou E-mail..." value="<?php echo $search; ?>">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="fornecedores.php" class="btn btn-secondary">Limpar</a>
        </div>
    </form>
</div>

<!-- Tabela de Fornecedores -->
<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CNPJ</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Endereço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($fornecedores) > 0): ?>
                    <?php foreach ($fornecedores as $fornecedor): ?>
                    <tr>
                        <td><?php echo $fornecedor['nome']; ?></td>
                        <td><?php echo formatCNPJ($fornecedor['cnpj']); ?></td>
                        <td><?php echo $fornecedor['telefone']; ?></td>
                        <td><?php echo $fornecedor['email']; ?></td>
                        <td><?php echo $fornecedor['endereco']; ?></td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="cadastro-fornecedor.php?id=<?php echo $fornecedor['id']; ?>" class="btn btn-ghost">Editar</a>
                                <button onclick="confirmDelete(<?php echo $fornecedor['id']; ?>)" class="btn btn-danger">Excluir</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="muted text-center">
                            <?php echo $search ? 'Nenhum fornecedor encontrado' : 'Nenhum fornecedor cadastrado'; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Tem certeza que deseja excluir este fornecedor?')) {
        window.location.href = 'fornecedores.php?delete_id=' + id;
    }
}
</script>

<?php include 'includes/footer.php'; ?>