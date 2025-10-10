<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$auth->checkAuth();
$page_title = "Cadastrar Produto";

$produto = new Produto();
$fornecedor = new Fornecedor();
$produto_data = [];
$is_edit = false;

// Verificar se é edição
if (isset($_GET['id'])) {
    $produto_data = $produto->readOne($_GET['id']);
    if ($produto_data) {
        $is_edit = true;
        $page_title = "Editar Produto";
    }
}

// Obter fornecedores para o select
$fornecedores = $fornecedor->readAll();

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'nome' => sanitizeInput($_POST['nome']),
        'codigo' => sanitizeInput($_POST['codigo']),
        'descricao' => sanitizeInput($_POST['descricao']),
        'quantidade_estoque' => intval($_POST['quantidade_estoque']),
        'preco_unitario' => floatval($_POST['preco_unitario']),
        'fornecedor_id' => intval($_POST['fornecedor_id']),
        'categoria' => sanitizeInput($_POST['categoria']),
        'volume' => sanitizeInput($_POST['volume'])
    ];

    try {
        if ($is_edit) {
            if ($produto->update($_GET['id'], $data)) {
                showMessage('Produto atualizado com sucesso!', 'success');
                header("Location: produtos.php");
                exit();
            }
        } else {
            if ($produto->create($data)) {
                showMessage('Produto cadastrado com sucesso!', 'success');
                header("Location: produtos.php");
                exit();
            }
        }
    } catch (Exception $e) {
        showMessage('Erro: ' . $e->getMessage(), 'error');
    }
}
?>
<?php include 'includes/header.php'; ?>

<div class="page-header">
    <div>
        <h2><?php echo $is_edit ? 'Editar Produto' : 'Cadastrar Produto'; ?></h2>
        <p class="muted"><?php echo $is_edit ? 'Atualize os dados do produto' : 'Adicione um novo produto ao catálogo'; ?></p>
    </div>
    <a href="produtos.php" class="btn btn-secondary">Voltar</a>
</div>

<div class="card">
    <form method="POST" class="form-card">
        <div class="form-row">
            <div class="form-group">
                <label for="nome">Nome do Produto *</label>
                <input type="text" id="nome" name="nome" class="input" required value="<?php echo $produto_data['nome'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label for="codigo">Código do Produto *</label>
                <input type="text" id="codigo" name="codigo" class="input" required value="<?php echo $produto_data['codigo'] ?? ''; ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" class="input" rows="3"><?php echo $produto_data['descricao'] ?? ''; ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="quantidade_estoque">Quantidade em Estoque *</label>
                <input type="number" id="quantidade_estoque" name="quantidade_estoque" class="input" min="0" required value="<?php echo $produto_data['quantidade_estoque'] ?? 0; ?>">
            </div>
            <div class="form-group">
                <label for="preco_unitario">Preço Unitário (R$) *</label>
                <input type="number" id="preco_unitario" name="preco_unitario" class="input" step="0.01" min="0" required value="<?php echo $produto_data['preco_unitario'] ?? 0; ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="fornecedor_id">Fornecedor *</label>
                <select id="fornecedor_id" name="fornecedor_id" class="input" required>
                    <option value="">Selecione um fornecedor</option>
                    <?php foreach ($fornecedores as $forn): ?>
                    <option value="<?php echo $forn['id']; ?>" <?php echo ($produto_data['fornecedor_id'] ?? '') == $forn['id'] ? 'selected' : ''; ?>>
                        <?php echo $forn['nome']; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="categoria">Categoria *</label>
                <select id="categoria" name="categoria" class="input" required>
                    <option value="">Selecione uma categoria</option>
                    <option value="masculino" <?php echo ($produto_data['categoria'] ?? '') == 'masculino' ? 'selected' : ''; ?>>Masculino</option>
                    <option value="feminino" <?php echo ($produto_data['categoria'] ?? '') == 'feminino' ? 'selected' : ''; ?>>Feminino</option>
                    <option value="unissex" <?php echo ($produto_data['categoria'] ?? '') == 'unissex' ? 'selected' : ''; ?>>Unissex</option>
                    <option value="infantil" <?php echo ($produto_data['categoria'] ?? '') == 'infantil' ? 'selected' : ''; ?>>Infantil</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="volume">Volume (ML)</label>
            <input type="text" id="volume" name="volume" class="input" value="<?php echo $produto_data['volume'] ?? ''; ?>">
        </div>

        <div class="form-actions">
            <a href="produtos.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <?php echo $is_edit ? 'Atualizar Produto' : 'Cadastrar Produto'; ?>
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>