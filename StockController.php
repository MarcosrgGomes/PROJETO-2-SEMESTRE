<?php
/**
 * Controlador de Estoque
 */

class StockController {
    public function index() {
        $this->movements();
    }
    
    public function movements() {
        $stockFile = DATA_PATH . '/stock/movements.json';
        $productsFile = DATA_PATH . '/products/products.json';
        
        $movements = DataManager::load($stockFile);
        $products = DataManager::load($productsFile);
        
        // Criar mapa de produtos para facilitar busca
        $productMap = [];
        foreach ($products as $product) {
            $productMap[$product['id']] = $product;
        }
        
        // Adicionar nome do produto às movimentações
        foreach ($movements as &$movement) {
            if (isset($productMap[$movement['product_id']])) {
                $movement['product_name'] = $productMap[$movement['product_id']]['name'];
            } else {
                $movement['product_name'] = 'Produto não encontrado';
            }
        }
        
        // Ordenar por data (mais recente primeiro)
        usort($movements, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        require_once APP_PATH . '/views/stock/movements.php';
    }
    
    public function adjustment() {
        $productsFile = DATA_PATH . '/products/products.json';
        $products = DataManager::load($productsFile);
        
        require_once APP_PATH . '/views/stock/adjustment.php';
    }
    
    public function saveAdjustment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('stock', ['action' => 'adjustment']);
        }
        
        $productId = sanitize($_POST['product_id'] ?? '');
        $type = sanitize($_POST['type'] ?? '');
        $quantity = intval($_POST['quantity'] ?? 0);
        $reason = sanitize($_POST['reason'] ?? '');
        
        if (empty($productId) || empty($type) || $quantity <= 0) {
            setFlashMessage('error', 'Dados inválidos.');
            redirect('stock', ['action' => 'adjustment']);
        }
        
        // Atualizar estoque do produto
        $productsFile = DATA_PATH . '/products/products.json';
        $products = DataManager::load($productsFile);
        
        $productIndex = null;
        foreach ($products as $i => $p) {
            if ($p['id'] === $productId) {
                $productIndex = $i;
                break;
            }
        }
        
        if ($productIndex === null) {
            setFlashMessage('error', 'Produto não encontrado.');
            redirect('stock', ['action' => 'adjustment']);
        }
        
        $oldQuantity = $products[$productIndex]['quantity'];
        
        // Aplicar movimentação
        if ($type === 'entry' || $type === 'return') {
            $products[$productIndex]['quantity'] += $quantity;
        } else {
            $products[$productIndex]['quantity'] -= $quantity;
            if ($products[$productIndex]['quantity'] < 0) {
                $products[$productIndex]['quantity'] = 0;
            }
        }
        
        $newQuantity = $products[$productIndex]['quantity'];
        DataManager::save($productsFile, $products);
        
        // Registrar movimentação
        $movement = [
            'id' => generateId(),
            'product_id' => $productId,
            'type' => $type,
            'quantity' => $quantity,
            'old_quantity' => $oldQuantity,
            'new_quantity' => $newQuantity,
            'reason' => $reason,
            'user_id' => $_SESSION['user_id'],
            'user_name' => $_SESSION['user_data']['name'],
            'date' => date('Y-m-d H:i:s')
        ];
        
        $stockFile = DATA_PATH . '/stock/movements.json';
        $movements = DataManager::load($stockFile);
        $movements[] = $movement;
        DataManager::save($stockFile, $movements);
        
        Security::auditLog('stock_adjustment', [
            'product_id' => $productId,
            'type' => $type,
            'quantity' => $quantity
        ]);
        
        setFlashMessage('success', 'Movimentação de estoque registrada com sucesso!');
        redirect('stock', ['action' => 'movements']);
    }
    
    public function inventory() {
        $productsFile = DATA_PATH . '/products/products.json';
        $products = DataManager::load($productsFile);
        
        // Calcular valores totais
        $totalValue = 0;
        $totalPotentialRevenue = 0;
        
        foreach ($products as &$product) {
            $product['total_cost'] = $product['cost_price'] * $product['quantity'];
            $product['total_sale'] = $product['sale_price'] * $product['quantity'];
            $totalValue += $product['total_cost'];
            $totalPotentialRevenue += $product['total_sale'];
        }
        
        $stats = [
            'total_value' => $totalValue,
            'total_potential_revenue' => $totalPotentialRevenue,
            'total_items' => count($products),
            'total_quantity' => array_sum(array_column($products, 'quantity'))
        ];
        
        require_once APP_PATH . '/views/stock/inventory.php';
    }
    
    public function lowStock() {
        $productsFile = DATA_PATH . '/products/products.json';
        $products = DataManager::load($productsFile);
        
        // Filtrar produtos com estoque baixo
        $lowStockProducts = array_filter($products, function($p) {
            return $p['quantity'] <= $p['min_quantity'];
        });
        
        // Ordenar por criticidade (menor quantidade primeiro)
        usort($lowStockProducts, function($a, $b) {
            return $a['quantity'] - $b['quantity'];
        });
        
        require_once APP_PATH . '/views/stock/low-stock.php';
    }
}

