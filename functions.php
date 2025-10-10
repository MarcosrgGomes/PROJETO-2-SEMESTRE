<?php
// Arrays para simular banco de dados
$fornecedores = $_SESSION['fornecedores'] ?? [];
$produtos = $_SESSION['produtos'] ?? [];

// Funções para Fornecedores
function salvarFornecedor($dados) {
    global $fornecedores;
    $id = uniqid();
    $dados['id'] = $id;
    $fornecedores[$id] = $dados;
    $_SESSION['fornecedores'] = $fornecedores;
    return $id;
}

function getFornecedores() {
    global $fornecedores;
    return $fornecedores;
}

function getFornecedor($id) {
    global $fornecedores;
    return $fornecedores[$id] ?? null;
}

function atualizarFornecedor($id, $dados) {
    global $fornecedores;
    if (isset($fornecedores[$id])) {
        $dados['id'] = $id;
        $fornecedores[$id] = $dados;
        $_SESSION['fornecedores'] = $fornecedores;
        return true;
    }
    return false;
}

function excluirFornecedor($id) {
    global $fornecedores;
    if (isset($fornecedores[$id])) {
        unset($fornecedores[$id]);
        $_SESSION['fornecedores'] = $fornecedores;
        return true;
    }
    return false;
}

// Funções para Produtos
function salvarProduto($dados) {
    global $produtos;
    $id = uniqid();
    $dados['id'] = $id;
    $produtos[$id] = $dados;
    $_SESSION['produtos'] = $produtos;
    return $id;
}

function getProdutos() {
    global $produtos;
    return $produtos;
}

function getProduto($id) {
    global $produtos;
    return $produtos[$id] ?? null;
}

function atualizarProduto($id, $dados) {
    global $produtos;
    if (isset($produtos[$id])) {
        $dados['id'] = $id;
        $produtos[$id] = $dados;
        $_SESSION['produtos'] = $produtos;
        return true;
    }
    return false;
}

function excluirProduto($id) {
    global $produtos;
    if (isset($produtos[$id])) {
        unset($produtos[$id]);
        $_SESSION['produtos'] = $produtos;
        return true;
    }
    return false;
}

function getNomeFornecedor($fornecedorId) {
    global $fornecedores;
    return $fornecedores[$fornecedorId]['nome'] ?? 'Fornecedor não encontrado';
}
?>