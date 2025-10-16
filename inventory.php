<?php
$pageTitle = 'Inventário';
require_once APP_PATH . '/views/templates/header.php';
?>

<div class="app-container">
    <?php require_once APP_PATH . '/views/templates/navigation.php'; ?>
    
    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <h1 class="topbar-title">Inventário</h1>
            </div>
            <div class="topbar-right">
                <div class="user-menu">
                    <div class="user-avatar"><?php echo strtoupper(substr(getCurrentUser()['name'], 0, 1)); ?></div>
                </div>
            </div>
        </header>
        
        <div class="content-area">
            <div class="stats-grid">
                <div class="stat-card success">
                    <div class="stat-header">
                        <div>
                            <div class="stat-label">Valor Total em Estoque</div>
                            <div class="stat-value"><?php echo formatMoney($stats['total_value']); ?></div>
                        </div>
                        <div class="stat-icon" style="background-color: var(--success-light); color: var(--success);">💰</div>
                    </div>
                </div>
                
                <div class="stat-card info">
                    <div class="stat-header">
                        <div>
                            <div class="stat-label">Receita Potencial</div>
                            <div class="stat-value"><?php echo formatMoney($stats['total_potential_revenue']); ?></div>
                        </div>
                        <div class="stat-icon" style="background-color: var(--info-light); color: var(--info);">📈</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-label">Total de Itens</div>
                            <div class="stat-value"><?php echo formatNumber($stats['total_items']); ?></div>
                        </div>
                        <div class="stat-icon">📦</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-label">Quantidade Total</div>
                            <div class="stat-value"><?php echo formatNumber($stats['total_quantity']); ?></div>
                        </div>
                        <div class="stat-icon">📊</div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Produtos em Estoque</h3>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                    <th>Preço Custo</th>
                                    <th>Preço Venda</th>
                                    <th>Valor Total (Custo)</th>
                                    <th>Valor Total (Venda)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                <tr>
                                    <td style="font-weight: 600;"><?php echo $product['name']; ?></td>
                                    <td><?php echo formatNumber($product['quantity']); ?></td>
                                    <td><?php echo formatMoney($product['cost_price']); ?></td>
                                    <td><?php echo formatMoney($product['sale_price']); ?></td>
                                    <td><?php echo formatMoney($product['total_cost']); ?></td>
                                    <td style="font-weight: 700; color: var(--success);">
                                        <?php echo formatMoney($product['total_sale']); ?>
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

