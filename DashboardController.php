<?php
/**
 * Controlador do Dashboard
 */

class DashboardController {
    /**
     * Dashboard principal
     */
    public function index() {
        // Carregar dados para o dashboard
        $productsFile = DATA_PATH . '/products/products.json';
        $suppliersFile = DATA_PATH . '/suppliers/suppliers.json';
        $stockFile = DATA_PATH . '/stock/movements.json';
        
        $products = DataManager::load($productsFile);
        $suppliers = DataManager::load($suppliersFile);
        $movements = DataManager::load($stockFile);
        
        // Calcular estatísticas
        $stats = [
            'total_products' => count($products),
            'active_products' => count(array_filter($products, function($p) { return $p['status'] === 'active'; })),
            'total_suppliers' => count($suppliers),
            'active_suppliers' => count(array_filter($suppliers, function($s) { return $s['status'] === 'active'; })),
            'low_stock_count' => count(array_filter($products, function($p) { 
                return $p['quantity'] <= LOW_STOCK_THRESHOLD; 
            })),
            'critical_stock_count' => count(array_filter($products, function($p) { 
                return $p['quantity'] <= CRITICAL_STOCK_THRESHOLD; 
            })),
            'total_stock_value' => 0,
            'total_potential_revenue' => 0,
            'recent_movements' => array_slice(array_reverse($movements), 0, 10)
        ];
        
        // Calcular valores
        foreach ($products as $product) {
            $stats['total_stock_value'] += $product['cost_price'] * $product['quantity'];
            $stats['total_potential_revenue'] += $product['sale_price'] * $product['quantity'];
        }
        
        // Produtos com estoque baixo
        $lowStockProducts = array_filter($products, function($p) {
            return $p['quantity'] <= LOW_STOCK_THRESHOLD;
        });
        usort($lowStockProducts, function($a, $b) {
            return $a['quantity'] - $b['quantity'];
        });
        $stats['low_stock_products'] = array_slice($lowStockProducts, 0, 5);
        
        // Produtos mais valiosos
        $valuableProducts = $products;
        usort($valuableProducts, function($a, $b) {
            $valueA = $a['sale_price'] * $a['quantity'];
            $valueB = $b['sale_price'] * $b['quantity'];
            return $valueB - $valueA;
        });
        $stats['top_valuable_products'] = array_slice($valuableProducts, 0, 5);
        
        // Movimentações por tipo (últimos 30 dias)
        $thirtyDaysAgo = strtotime('-30 days');
        $recentMovements = array_filter($movements, function($m) use ($thirtyDaysAgo) {
            return strtotime($m['date']) >= $thirtyDaysAgo;
        });
        
        $movementsByType = [];
        foreach ($recentMovements as $movement) {
            $type = $movement['type'];
            if (!isset($movementsByType[$type])) {
                $movementsByType[$type] = 0;
            }
            $movementsByType[$type]++;
        }
        $stats['movements_by_type'] = $movementsByType;
        
        require_once APP_PATH . '/views/dashboard/index.php';
    }
}

