<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$auth->checkAuth();
$page_title = "Produtos";

$produto = new Produto();
$fornecedor = new Fornecedor();

$filters = [
    'search' => $_GET['search'] ?? '',
    'categoria' => $_GET['categoria'] ?? ''
];

// Processar exclusão
if (isset($_GET['delete_id'])) {
    if ($produto->delete($_GET['delete_id'])) {
        showMessage('Produto excluído com sucesso!', 'success');
        header("Location: produtos.php");
        exit();
    } else {
        showMessage('Erro ao excluir produto!', 'error');
    }
}

$produtos = $produto->readAll($filters);
$fornecedores = $fornecedor->readAll();
?>
<?php include 'includes/header.php'; ?>

<div class="page-header">
    <div>
        <h2>Gerenciar Produtos</h2>
        <p class="muted">Cadastre e gerencie os produtos da perfumaria</p>
    </div>
    <a href="cadastro-produto.php" class="btn btn-primary">Novo Produto</a>
</div>

<!-- Filtros -->
<div class="filters">
    <form method="GET" class="filter-row">
        <div class="form-group">
            <label for="search">Pesquisar</label>
            <input type="text" id="search" name="search" class="input" placeholder="Nome, código ou fornecedor..." value="<?php echo $filters['search']; ?>">
        </div>
        <div class="form-group">
            <label for="categoria">Categoria</label>
            <select id="categoria" name="categoria" class="input">
                <option value="">Todas as categorias</option>
                <option value="masculino" <?php echo $filters['categoria'] == 'masculino' ? 'selected' : ''; ?>>Masculino</option>
                <option value="feminino" <?php echo $filters['categoria'] == 'feminino' ? 'selected' : ''; ?>>Feminino</option>
                <option value="unissex" <?php echo $filters['categoria'] == 'unissex' ? 'selected' : ''; ?>>Unissex</option>
                <option value="infantil" <?php echo $filters['categoria'] == 'infantil' ? 'selected' : ''; ?>>Infantil</option>
            </select>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="produtos.php" class="btn btn-secondary">Limpar</a>
        </div>
    </form>
</div>

<!-- Tabela de Produtos -->
<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Código</th>
                    <th>Categoria</th>
                    <th>Estoque</th>
                    <th>Preço</th>
                    <th>Fornecedor</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($produtos) > 0): ?>
                    <?php foreach ($produtos as $prod): ?>
                    <tr>
                        <td><?php echo $prod['nome']; ?></td>
                        <td><?php echo $prod['codigo']; ?></td>
                        <td>
                            <span class="badge category-<?php echo $prod['categoria']; ?>">
                                <?php echo ucfirst($prod['categoria']); ?>
                            </span>
                        </td>
                        <td>
                            <span class="<?php echo $prod['quantidade_estoque'] <= 10 ? 'text-danger' : ''; ?>">
                                <?php echo $prod['quantidade_estoque']; ?>
                            </span>
                        </td>
                        <td><?php echo formatCurrency($prod['preco_unitario']); ?></td>
                        <td><?php echo $prod['fornecedor_nome'] ?? 'N/A'; ?></td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="cadastro-produto.php?id=<?php echo $prod['id']; ?>" class="btn btn-ghost">Editar</a>
                                <button onclick="confirmDelete(<?php echo $prod['id']; ?>)" class="btn btn-danger">Excluir</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="muted text-center">
                            <?php echo $filters['search'] || $filters['categoria'] ? 'Nenhum produto encontrado' : 'Nenhum produto cadastrado'; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Tem certeza que deseja excluir este produto?')) {
        window.location.href = 'produtos.php?delete_id=' + id;
    }
}
</script>

<?php include 'includes/footer.php'; ?>