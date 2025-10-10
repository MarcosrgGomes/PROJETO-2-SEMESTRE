<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$auth->checkAuth();
$page_title = "Dashboard";

$fornecedor = new Fornecedor();
$produto = new Produto();

$fornecedores = $fornecedor->readAll();
$produtos = $produto->readAll();
$produtos_estoque_baixo = $produto->getLowStock();

$total_produtos = count($produtos);
$total_fornecedores = count($fornecedores);
$valor_estoque = array_sum(array_column($produtos, 'preco_unitario'));
$estoque_baixo = count($produtos_estoque_baixo);
?>
<?php include 'includes/header.php'; ?>

<div class="page-header">
    <div>
        <h2>Dashboard</h2>
        <p class="muted">Bem-vindo, <?php echo $_SESSION['user_name']; ?>!</p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <a href="cadastro-produto.php" class="btn btn-primary">Novo Produto</a>
        <a href="cadastro-fornecedor.php" class="btn btn-secondary">Novo Fornecedor</a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Total de Produtos</h3>
        <div class="value"><?php echo $total_produtos; ?></div>
        <div class="trend positive">+12% este mês</div>
    </div>

    <div class="stat-card">
        <h3>Fornecedores Ativos</h3>
        <div class="value"><?php echo $total_fornecedores; ?></div>
        <div class="trend positive">+5% este mês</div>
    </div>

    <div class="stat-card">
        <h3>Valor em Estoque</h3>
        <div class="value"><?php echo formatCurrency($valor_estoque); ?></div>
        <div class="trend positive">+8% este mês</div>
    </div>

    <div class="stat-card">
        <h3>Produtos com Estoque Baixo</h3>
        <div class="value"><?php echo $estoque_baixo; ?></div>
        <div class="trend negative">Precisa de atenção</div>
    </div>
</div>

<div class="grid grid-2" style="gap: 2rem; margin-top: 2rem;">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3>Produtos com Estoque Baixo</h3>
            <a href="produtos.php?filter=low-stock" class="btn btn-ghost">Ver Todos</a>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Estoque</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($produtos_estoque_baixo) > 0): ?>
                        <?php foreach (array_slice($produtos_estoque_baixo, 0, 5) as $prod): ?>
                        <tr>
                            <td><?php echo $prod['nome']; ?></td>
                            <td><?php echo $prod['quantidade_estoque']; ?></td>
                            <td>
                                <span class="badge <?php echo $prod['quantidade_estoque'] <= 5 ? 'danger' : 'warning'; ?>">
                                    <?php echo $prod['quantidade_estoque'] <= 5 ? 'Crítico' : 'Atenção'; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="muted text-center">Nenhum produto com estoque baixo</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3>Fornecedores Recentes</h3>
            <a href="fornecedores.php" class="btn btn-ghost">Ver Todos</a>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Fornecedor</th>
                        <th>CNPJ</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($fornecedores) > 0): ?>
                        <?php foreach (array_slice($fornecedores, 0, 5) as $forn): ?>
                        <tr>
                            <td><?php echo $forn['nome']; ?></td>
                            <td><?php echo formatCNPJ($forn['cnpj']); ?></td>
                            <td><span class="badge success">Ativo</span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="muted text-center">Nenhum fornecedor cadastrado</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>