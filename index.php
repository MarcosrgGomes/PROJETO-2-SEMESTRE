<?php
$pageTitle = 'Dashboard';
require_once APP_PATH . '/views/templates/header.php';
?>

<div class="app-container">
    <?php require_once APP_PATH . '/views/templates/navigation.php'; ?>
    
    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <h1 class="topbar-title">Dashboard</h1>
            </div>
            <div class="topbar-right">
                <div class="user-menu">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr(getCurrentUser()['name'], 0, 1)); ?>
                    </div>
                    <div>
                        <div style="font-weight: 600; font-size: var(--text-sm);">
                            <?php echo getCurrentUser()['name']; ?>
                        </div>
                        <div style="font-size: var(--text-xs); color: var(--neutral-600);">
                            <?php echo USER_ROLES[getCurrentUser()['role']]; ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="content-area">
            <!-- Cards de Estatísticas -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-label">Total de Produtos</div>
                            <div class="stat-value"><?php echo formatNumber($stats['total_products']); ?></div>
                        </div>
                        <div class="stat-icon">📦</div>
                    </div>
                    <div class="stat-description">
                        <?php echo $stats['active_products']; ?> ativos
                    </div>
                </div>
                
                <div class="stat-card success">
                    <div class="stat-header">
                        <div>
                            <div class="stat-label">Valor em Estoque</div>
                            <div class="stat-value"><?php echo formatMoney($stats['total_stock_value']); ?></div>
                        </div>
                        <div class="stat-icon" style="background-color: var(--success-light); color: var(--success);">💰</div>
                    </div>
                    <div class="stat-description">
                        Receita potencial: <?php echo formatMoney($stats['total_potential_revenue']); ?>
                    </div>
                </div>
                
                <div class="stat-card info">
                    <div class="stat-header">
                        <div>
                            <div class="stat-label">Fornecedores</div>
                            <div class="stat-value"><?php echo formatNumber($stats['total_suppliers']); ?></div>
                        </div>
                        <div class="stat-icon" style="background-color: var(--info-light); color: var(--info);">🏢</div>
                    </div>
                    <div class="stat-description">
                        <?php echo $stats['active_suppliers']; ?> ativos
                    </div>
                </div>
                
                <div class="stat-card <?php echo $stats['low_stock_count'] > 0 ? 'warning' : 'success'; ?>">
                    <div class="stat-header">
                        <div>
                            <div class="stat-label">Estoque Baixo</div>
                            <div class="stat-value"><?php echo formatNumber($stats['low_stock_count']); ?></div>
                        </div>
                        <div class="stat-icon" style="background-color: <?php echo $stats['low_stock_count'] > 0 ? 'var(--warning-light)' : 'var(--success-light)'; ?>; color: <?php echo $stats['low_stock_count'] > 0 ? 'var(--warning)' : 'var(--success)'; ?>;">
                            <?php echo $stats['low_stock_count'] > 0 ? '⚠️' : '✅'; ?>
                        </div>
                    </div>
                    <div class="stat-description">
                        <?php echo $stats['critical_stock_count']; ?> críticos
                    </div>
                </div>
            </div>
            
            <!-- Produtos com Estoque Baixo -->
            <?php if (!empty($stats['low_stock_products'])): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">⚠️ Produtos com Estoque Baixo</h3>
                    <a href="index.php?page=stock&action=lowStock" class="btn btn-sm btn-outline">
                        Ver Todos
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>SKU</th>
                                    <th>Quantidade</th>
                                    <th>Mínimo</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['low_stock_products'] as $product): ?>
                                <tr>
                                    <td style="font-weight: 600;"><?php echo $product['name']; ?></td>
                                    <td><?php echo $product['sku']; ?></td>
                                    <td>
                                        <span style="font-weight: 700; color: <?php echo $product['quantity'] <= CRITICAL_STOCK_THRESHOLD ? 'var(--error)' : 'var(--warning)'; ?>;">
                                            <?php echo $product['quantity']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $product['min_quantity']; ?></td>
                                    <td>
                                        <?php if ($product['quantity'] <= CRITICAL_STOCK_THRESHOLD): ?>
                                            <span class="badge badge-error">Crítico</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">Baixo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="index.php?page=stock&action=adjustment" class="btn btn-sm btn-primary">
                                            Ajustar
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Produtos Mais Valiosos -->
            <?php if (!empty($stats['top_valuable_products'])): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">💎 Produtos Mais Valiosos em Estoque</h3>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                    <th>Preço Unitário</th>
                                    <th>Valor Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['top_valuable_products'] as $product): ?>
                                <tr>
                                    <td style="font-weight: 600;"><?php echo $product['name']; ?></td>
                                    <td><?php echo formatNumber($product['quantity']); ?></td>
                                    <td><?php echo formatMoney($product['sale_price']); ?></td>
                                    <td style="font-weight: 700; color: var(--success);">
                                        <?php echo formatMoney($product['sale_price'] * $product['quantity']); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Movimentações Recentes -->
            <?php if (!empty($stats['recent_movements'])): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">📋 Movimentações Recentes</h3>
                    <a href="index.php?page=stock&action=movements" class="btn btn-sm btn-outline">
                        Ver Todas
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Tipo</th>
                                    <th>Quantidade</th>
                                    <th>Usuário</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['recent_movements'] as $movement): ?>
                                <tr>
                                    <td><?php echo formatDate($movement['date']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php 
                                            echo $movement['type'] === 'entry' ? 'success' : 
                                                ($movement['type'] === 'exit' ? 'info' : 'warning'); 
                                        ?>">
                                            <?php echo STOCK_MOVEMENT_TYPES[$movement['type']]; ?>
                                        </span>
                                    </td>
                                    <td><?php echo formatNumber($movement['quantity']); ?></td>
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

