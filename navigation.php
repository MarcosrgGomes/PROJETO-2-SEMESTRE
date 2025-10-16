<aside class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            📦 Serenity
        </div>
        <p style="color: rgba(255,255,255,0.7); font-size: var(--text-sm); margin-top: var(--space-2);">
            Sistema de Estoque
        </p>
    </div>
    
    <nav>
        <ul class="sidebar-nav">
            <li class="sidebar-nav-item">
                <a href="index.php?page=dashboard" class="sidebar-nav-link">
                    <span class="sidebar-nav-icon">📊</span>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="sidebar-nav-item">
                <a href="index.php?page=products" class="sidebar-nav-link">
                    <span class="sidebar-nav-icon">📦</span>
                    <span>Produtos</span>
                </a>
            </li>
            
            <li class="sidebar-nav-item">
                <a href="index.php?page=suppliers" class="sidebar-nav-link">
                    <span class="sidebar-nav-icon">🏢</span>
                    <span>Fornecedores</span>
                </a>
            </li>
            
            <li class="sidebar-nav-item">
                <a href="index.php?page=stock&action=movements" class="sidebar-nav-link">
                    <span class="sidebar-nav-icon">📋</span>
                    <span>Movimentações</span>
                </a>
            </li>
            
            <li class="sidebar-nav-item">
                <a href="index.php?page=stock&action=inventory" class="sidebar-nav-link">
                    <span class="sidebar-nav-icon">📊</span>
                    <span>Inventário</span>
                </a>
            </li>
            
            <li class="sidebar-nav-item">
                <a href="index.php?page=stock&action=lowStock" class="sidebar-nav-link">
                    <span class="sidebar-nav-icon">⚠️</span>
                    <span>Estoque Baixo</span>
                </a>
            </li>
            
            <li class="sidebar-nav-item">
                <a href="index.php?page=categories" class="sidebar-nav-link">
                    <span class="sidebar-nav-icon">🏷️</span>
                    <span>Categorias</span>
                </a>
            </li>
            
            <?php if (hasPermission('admin')): ?>
            <li class="sidebar-nav-item" style="margin-top: var(--space-6); padding-top: var(--space-6); border-top: 1px solid rgba(255,255,255,0.1);">
                <a href="index.php?page=users" class="sidebar-nav-link">
                    <span class="sidebar-nav-icon">👥</span>
                    <span>Usuários</span>
                </a>
            </li>
            <?php endif; ?>
            
            <li class="sidebar-nav-item">
             <a href="index.php?page=login&action=logout" class="sidebar-nav-link" data-confirm="Tem certeza que deseja sair?">

                    <span class="sidebar-nav-icon">🚪</span>
                    <span>Sair</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

