<?php
$pageTitle = 'Detalhes do Produto';
require_once APP_PATH . '/views/templates/header.php';
?>

<div class="app-container">
    <?php require_once APP_PATH . '/views/templates/navigation.php'; ?>
    
    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <h1 class="topbar-title">Detalhes do Produto</h1>
            </div>
            <div class="topbar-right">
                <a href="index.php?page=products" class="btn btn-secondary">← Voltar</a>
                <a href="index.php?page=products&action=edit&id=<?php echo $product['id']; ?>" class="btn btn-primary">✏️ Editar</a>
                <div class="user-menu">
                    <div class="user-avatar"><?php echo strtoupper(substr(getCurrentUser()['name'], 0, 1)); ?></div>
                </div>
            </div>
        </header>
        
        <div class="content-area">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo $product['name']; ?></h3>
                    <span class="badge badge-<?php echo $product['status'] === 'active' ? 'success' : 'neutral'; ?>">
                        <?php echo PRODUCT_STATUS[$product['status']]; ?>
                    </span>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--space-6);">
                        <div>
                            <strong>SKU:</strong><br>
                            <code style="font-size: var(--text-lg);"><?php echo $product['sku']; ?></code>
                        </div>
                        <div>
                            <strong>Categoria:</strong><br>
                            <?php echo $product['category']; ?>
                        </div>
                        <div>
                            <strong>Preço de Custo:</strong><br>
                            <?php echo formatMoney($product['cost_price']); ?>
                        </div>
                        <div>
                            <strong>Preço de Venda:</strong><br>
                            <span style="font-size: var(--text-xl); font-weight: 700; color: var(--success);">
                                <?php echo formatMoney($product['sale_price']); ?>
                            </span>
                        </div>
                        <div>
                            <strong>Quantidade em Estoque:</strong><br>
                            <span style="font-size: var(--text-xl); font-weight: 700;">
                                <?php echo $product['quantity']; ?>
                            </span>
                        </div>
                        <div>
                            <strong>Quantidade Mínima:</strong><br>
                            <?php echo $product['min_quantity']; ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($product['description'])): ?>
                    <div style="margin-top: var(--space-6); padding-top: var(--space-6); border-top: 1px solid var(--neutral-200);">
                        <strong>Descrição:</strong><br>
                        <p style="margin-top: var(--space-2);"><?php echo nl2br($product['description']); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if (!empty($productMovements)): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Histórico de Movimentações</h3>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Tipo</th>
                                    <th>Quantidade</th>
                                    <th>Estoque Anterior</th>
                                    <th>Estoque Novo</th>
                                    <th>Usuário</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productMovements as $movement): ?>
                                <tr>
                                    <td><?php echo formatDate($movement['date']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $movement['type'] === 'entry' ? 'success' : 'info'; ?>">
                                            <?php echo STOCK_MOVEMENT_TYPES[$movement['type']]; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $movement['quantity']; ?></td>
                                    <td><?php echo $movement['old_quantity']; ?></td>
                                    <td><?php echo $movement['new_quantity']; ?></td>
                                    <td><?php echo $movement['user_name']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require_once APP_PATH . '/views/templates/footer.php'; ?>

