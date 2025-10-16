<?php
/**
 * Controlador de Fornecedores
 */

class SupplierController {
    public function index() {
        $suppliersFile = DATA_PATH . '/suppliers/suppliers.json';
        $suppliers = DataManager::load($suppliersFile);
        
        // Filtros
        $search = sanitize($_GET['search'] ?? '');
        $status = sanitize($_GET['status'] ?? '');
        
        if (!empty($search)) {
            $suppliers = array_filter($suppliers, function($s) use ($search) {
                return stripos($s['name'], $search) !== false 
                    || stripos($s['cnpj'], $search) !== false
                    || stripos($s['email'], $search) !== false;
            });
        }
        
        if (!empty($status)) {
            $suppliers = array_filter($suppliers, function($s) use ($status) {
                return $s['status'] === $status;
            });
        }
        
        require_once APP_PATH . '/views/suppliers/list.php';
    }
    
    public function add() {
        require_once APP_PATH . '/views/suppliers/add.php';
    }
    
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('suppliers', ['action' => 'add']);
        }
        
        $data = [
            'id' => generateId(),
            'name' => sanitize($_POST['name'] ?? ''),
            'cnpj' => sanitize($_POST['cnpj'] ?? ''),
            'email' => sanitize($_POST['email'] ?? ''),
            'phone' => sanitize($_POST['phone'] ?? ''),
            'address' => sanitize($_POST['address'] ?? ''),
            'city' => sanitize($_POST['city'] ?? ''),
            'state' => sanitize($_POST['state'] ?? ''),
            'status' => sanitize($_POST['status'] ?? 'active'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $suppliersFile = DATA_PATH . '/suppliers/suppliers.json';
        $suppliers = DataManager::load($suppliersFile);
        $suppliers[] = $data;
        DataManager::save($suppliersFile, $suppliers);
        
        Security::auditLog('supplier_created', ['supplier_id' => $data['id'], 'name' => $data['name']]);
        setFlashMessage('success', 'Fornecedor cadastrado com sucesso!');
        redirect('suppliers');
    }
    
    public function edit() {
        $id = sanitize($_GET['id'] ?? '');
        $suppliersFile = DATA_PATH . '/suppliers/suppliers.json';
        $suppliers = DataManager::load($suppliersFile);
        
        $supplier = null;
        foreach ($suppliers as $s) {
            if ($s['id'] === $id) {
                $supplier = $s;
                break;
            }
        }
        
        if (!$supplier) {
            setFlashMessage('error', 'Fornecedor não encontrado.');
            redirect('suppliers');
        }
        
        require_once APP_PATH . '/views/suppliers/edit.php';
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('suppliers');
        }
        
        $id = sanitize($_POST['id'] ?? '');
        $suppliersFile = DATA_PATH . '/suppliers/suppliers.json';
        $suppliers = DataManager::load($suppliersFile);
        
        $index = null;
        foreach ($suppliers as $i => $s) {
            if ($s['id'] === $id) {
                $index = $i;
                break;
            }
        }
        
        if ($index === null) {
            setFlashMessage('error', 'Fornecedor não encontrado.');
            redirect('suppliers');
        }
        
        $suppliers[$index]['name'] = sanitize($_POST['name'] ?? '');
        $suppliers[$index]['cnpj'] = sanitize($_POST['cnpj'] ?? '');
        $suppliers[$index]['email'] = sanitize($_POST['email'] ?? '');
        $suppliers[$index]['phone'] = sanitize($_POST['phone'] ?? '');
        $suppliers[$index]['address'] = sanitize($_POST['address'] ?? '');
        $suppliers[$index]['city'] = sanitize($_POST['city'] ?? '');
        $suppliers[$index]['state'] = sanitize($_POST['state'] ?? '');
        $suppliers[$index]['status'] = sanitize($_POST['status'] ?? 'active');
        $suppliers[$index]['updated_at'] = date('Y-m-d H:i:s');
        
        DataManager::save($suppliersFile, $suppliers);
        Security::auditLog('supplier_updated', ['supplier_id' => $id]);
        setFlashMessage('success', 'Fornecedor atualizado com sucesso!');
        redirect('suppliers');
    }
    
    public function delete() {
        $id = sanitize($_GET['id'] ?? '');
        $suppliersFile = DATA_PATH . '/suppliers/suppliers.json';
        $suppliers = DataManager::load($suppliersFile);
        
        $newSuppliers = array_filter($suppliers, function($s) use ($id) {
            return $s['id'] !== $id;
        });
        
        DataManager::save($suppliersFile, array_values($newSuppliers));
        Security::auditLog('supplier_deleted', ['supplier_id' => $id]);
        setFlashMessage('success', 'Fornecedor excluído com sucesso!');
        redirect('suppliers');
    }
}

