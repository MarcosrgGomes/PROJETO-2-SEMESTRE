<?php
/**
 * Controlador de Produtos
 */

class ProductController {
    /**
     * Lista de produtos
     */
    public function index() {
        $productsFile = DATA_PATH . '/products/products.json';
        $categoriesFile = DATA_PATH . '/categories/categories.json';
        
        $products = DataManager::load($productsFile);
        $categories = DataManager::load($categoriesFile);
        
        // Filtros
        $search = sanitize($_GET['search'] ?? '');
        $category = sanitize($_GET['category'] ?? '');
        $status = sanitize($_GET['status'] ?? '');
        $stockAlert = sanitize($_GET['stock_alert'] ?? '');
        
        // Aplicar filtros
        if (!empty($search)) {
            $products = array_filter($products, function($p) use ($search) {
                return stripos($p['name'], $search) !== false 
                    || stripos($p['sku'], $search) !== false
                    || stripos($p['description'], $search) !== false;
            });
        }
        
        if (!empty($category)) {
            $products = array_filter($products, function($p) use ($category) {
                return $p['category'] === $category;
            });
        }
        
        if (!empty($status)) {
            $products = array_filter($products, function($p) use ($status) {
                return $p['status'] === $status;
            });
        }
        
        if ($stockAlert === 'low') {
            $products = array_filter($products, function($p) {
                return $p['quantity'] <= LOW_STOCK_THRESHOLD;
            });
        } elseif ($stockAlert === 'critical') {
            $products = array_filter($products, function($p) {
                return $p['quantity'] <= CRITICAL_STOCK_THRESHOLD;
            });
        }
        
        // Ordenação
        $sort = $_GET['sort'] ?? 'name';
        $order = $_GET['order'] ?? 'asc';
        
        usort($products, function($a, $b) use ($sort, $order) {
            $valueA = $a[$sort] ?? '';
            $valueB = $b[$sort] ?? '';
            
            $result = $valueA <=> $valueB;
            return $order === 'desc' ? -$result : $result;
        });
        
        require_once APP_PATH . '/views/products/list.php';
    }
    
    /**
     * Formulário de adição
     */
    public function add() {
        $categoriesFile = DATA_PATH . '/categories/categories.json';
        $categories = DataManager::load($categoriesFile);
        
        require_once APP_PATH . '/views/products/add.php';
    }
    
    /**
     * Salvar novo produto
     */
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('products', ['action' => 'add']);
        }
        
        $data = [
            'id' => generateId(),
            'sku' => sanitize($_POST['sku'] ?? generateSKU()),
            'name' => sanitize($_POST['name'] ?? ''),
            'description' => sanitize($_POST['description'] ?? ''),
            'category' => sanitize($_POST['category'] ?? ''),
            'cost_price' => floatval($_POST['cost_price'] ?? 0),
            'sale_price' => floatval($_POST['sale_price'] ?? 0),
            'quantity' => intval($_POST['quantity'] ?? 0),
            'min_quantity' => intval($_POST['min_quantity'] ?? LOW_STOCK_THRESHOLD),
            'status' => sanitize($_POST['status'] ?? 'active'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Validações
        $errors = [];
        if (empty($data['name'])) {
            $errors[] = 'Nome é obrigatório';
        }
        if ($data['cost_price'] < 0) {
            $errors[] = 'Preço de custo inválido';
        }
        if ($data['sale_price'] < 0) {
            $errors[] = 'Preço de venda inválido';
        }
        if ($data['quantity'] < 0) {
            $errors[] = 'Quantidade inválida';
        }
        
        if (!empty($errors)) {
            setFlashMessage('error', implode('<br>', $errors));
            redirect('products', ['action' => 'add']);
        }
        
        // Salvar
        $productsFile = DATA_PATH . '/products/products.json';
        $products = DataManager::load($productsFile);
        $products[] = $data;
        DataManager::save($productsFile, $products);
        
        Security::auditLog('product_created', ['product_id' => $data['id'], 'name' => $data['name']]);
        setFlashMessage('success', 'Produto cadastrado com sucesso!');
        redirect('products');
    }
    
    /**
     * Formulário de edição
     */
    public function edit() {
        $id = sanitize($_GET['id'] ?? '');
        
        if (empty($id)) {
            setFlashMessage('error', 'Produto não encontrado.');
            redirect('products');
        }
        
        $productsFile = DATA_PATH . '/products/products.json';
        $categoriesFile = DATA_PATH . '/categories/categories.json';
        
        $products = DataManager::load($productsFile);
        $categories = DataManager::load($categoriesFile);
        
        $product = null;
        foreach ($products as $p) {
            if ($p['id'] === $id) {
                $product = $p;
                break;
            }
        }
        
        if (!$product) {
            setFlashMessage('error', 'Produto não encontrado.');
            redirect('products');
        }
        
        require_once APP_PATH . '/views/products/edit.php';
    }
    
    /**
     * Atualizar produto
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('products');
        }
        
        $id = sanitize($_POST['id'] ?? '');
        
        if (empty($id)) {
            setFlashMessage('error', 'Produto não encontrado.');
            redirect('products');
        }
        
        $productsFile = DATA_PATH . '/products/products.json';
        $products = DataManager::load($productsFile);
        
        $index = null;
        foreach ($products as $i => $p) {
            if ($p['id'] === $id) {
                $index = $i;
                break;
            }
        }
        
        if ($index === null) {
            setFlashMessage('error', 'Produto não encontrado.');
            redirect('products');
        }
        
        // Atualizar dados
        $products[$index]['sku'] = sanitize($_POST['sku'] ?? $products[$index]['sku']);
        $products[$index]['name'] = sanitize($_POST['name'] ?? '');
        $products[$index]['description'] = sanitize($_POST['description'] ?? '');
        $products[$index]['category'] = sanitize($_POST['category'] ?? '');
        $products[$index]['cost_price'] = floatval($_POST['cost_price'] ?? 0);
        $products[$index]['sale_price'] = floatval($_POST['sale_price'] ?? 0);
        $products[$index]['quantity'] = intval($_POST['quantity'] ?? 0);
        $products[$index]['min_quantity'] = intval($_POST['min_quantity'] ?? LOW_STOCK_THRESHOLD);
        $products[$index]['status'] = sanitize($_POST['status'] ?? 'active');
        $products[$index]['updated_at'] = date('Y-m-d H:i:s');
        
        DataManager::save($productsFile, $products);
        
        Security::auditLog('product_updated', ['product_id' => $id, 'name' => $products[$index]['name']]);
        setFlashMessage('success', 'Produto atualizado com sucesso!');
        redirect('products');
    }
    
    /**
     * Excluir produto
     */
    public function delete() {
        $id = sanitize($_GET['id'] ?? '');
        
        if (empty($id)) {
            setFlashMessage('error', 'Produto não encontrado.');
            redirect('products');
        }
        
        $productsFile = DATA_PATH . '/products/products.json';
        $products = DataManager::load($productsFile);
        
        $newProducts = array_filter($products, function($p) use ($id) {
            return $p['id'] !== $id;
        });
        
        if (count($newProducts) === count($products)) {
            setFlashMessage('error', 'Produto não encontrado.');
        } else {
            DataManager::save($productsFile, array_values($newProducts));
            Security::auditLog('product_deleted', ['product_id' => $id]);
            setFlashMessage('success', 'Produto excluído com sucesso!');
        }
        
        redirect('products');
    }
    
    /**
     * Visualizar detalhes do produto
     */
    public function view() {
        $id = sanitize($_GET['id'] ?? '');
        
        if (empty($id)) {
            setFlashMessage('error', 'Produto não encontrado.');
            redirect('products');
        }
        
        $productsFile = DATA_PATH . '/products/products.json';
        $stockFile = DATA_PATH . '/stock/movements.json';
        
        $products = DataManager::load($productsFile);
        $movements = DataManager::load($stockFile);
        
        $product = null;
        foreach ($products as $p) {
            if ($p['id'] === $id) {
                $product = $p;
                break;
            }
        }
        
        if (!$product) {
            setFlashMessage('error', 'Produto não encontrado.');
            redirect('products');
        }
        
        // Filtrar movimentações do produto
        $productMovements = array_filter($movements, function($m) use ($id) {
            return $m['product_id'] === $id;
        });
        $productMovements = array_reverse($productMovements);
        
        require_once APP_PATH . '/views/products/view.php';
    }
}

