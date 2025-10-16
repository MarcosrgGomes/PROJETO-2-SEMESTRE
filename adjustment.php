<?php
$pageTitle = 'Ajuste de Estoque';
require_once APP_PATH . '/views/templates/header.php';
?>

<div class="app-container">
    <?php require_once APP_PATH . '/views/templates/navigation.php'; ?>
    
    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <h1 class="topbar-title">Ajuste de Estoque</h1>
            </div>
            <div class="topbar-right">
                <a href="index.php?page=stock&action=movements" class="btn btn-secondary">← Voltar</a>
                <div class="user-menu">
                    <div class="user-avatar"><?php echo strtoupper(substr(getCurrentUser()['name'], 0, 1)); ?></div>
                </div>
            </div>
        </header>
        
        <div class="content-area">
            <div class="card">
                <div class="card-body">
                    <form action="index.php?page=stock&action=saveAdjustment" method="POST" data-validate>
                        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                        
                        <div class="form-group">
                            <label for="product_id" class="form-label required">Produto</label>
                            <select id="product_id" name="product_id" class="form-control" required>
                                <option value="">Selecione um produto...</option>
                                <?php foreach ($products as $product): ?>
                                <option value="<?php echo $product['id']; ?>">
                                    <?php echo $product['name']; ?> (Estoque atual: <?php echo $product['quantity']; ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--space-6);">
                            <div class="form-group">
                                <label for="type" class="form-label required">Tipo de Movimentação</label>
                                <select id="type" name="type" class="form-control" required>
                                    <?php foreach (STOCK_MOVEMENT_TYPES as $key => $label): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="quantity" class="form-label required">Quantidade</label>
                                <input type="number" id="quantity" name="quantity" class="form-control" min="1" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="reason" class="form-label">Motivo/Observação</label>
                            <textarea id="reason" name="reason" class="form-control" rows="3"></textarea>
                        </div>
                        
                        <div style="display: flex; gap: var(--space-3); justify-content: flex-end; margin-top: var(--space-8);">
                            <a href="index.php?page=stock&action=movements" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success">💾 Registrar Movimentação</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once APP_PATH . '/views/templates/footer.php'; ?>

