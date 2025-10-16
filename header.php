<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo APP_DESCRIPTION; ?>">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?><?php echo APP_NAME; ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="public/css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📦</text></svg>">
</head>
<body class="light-mode"> <!-- Default to light mode -->
    <div class="accessibility-bar">
        <button id="dark-mode-toggle" title="Alternar Modo Escuro/Claro">🌙</button>
        <div class="accessibility-menu">
            <button id="increase-font" title="Aumentar Fonte">A+</button>
            <button id="decrease-font" title="Diminuir Fonte">A-</button>
            <button id="high-contrast-toggle" title="Alternar Alto Contraste">C</button>
        </div>
    </div>
    <?php
    $flashMessage = getFlashMessage();
    if ($flashMessage):
    ?>
    <div class="alert alert-<?php echo $flashMessage['type']; ?> fade-in" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;" data-auto-close="5000">
        <?php echo $flashMessage['message']; ?>
    </div>
    <?php endif; ?>

