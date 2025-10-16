<?php
/**
 * Controlador de Categorias
 */

class CategoryController {
    public function index() {
        $categoriesFile = DATA_PATH . '/categories/categories.json';
        $categories = DataManager::load($categoriesFile);
        
        require_once APP_PATH . '/views/products/categories.php';
    }
}

