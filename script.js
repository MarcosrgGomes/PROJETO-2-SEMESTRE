// ===== SISTEMA DE GERENCIAMENTO SERENITY =====

class SerenityApp {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupUIComponents();
        this.checkAuthState();
    }

    // Verificar estado de autenticação
    checkAuthState() {
        const currentPage = window.location.pathname.split('/').pop();
        const publicPages = ['index.php', 'login.php', 'esqueci-senha.php', 'recuperar-senha.php'];
        
        // Simulação - em produção isso viria do servidor
        const isLoggedIn = document.cookie.includes('serenity_logged_in=true') || 
                          sessionStorage.getItem('serenity_logged_in') === 'true';
        
        if (!isLoggedIn && !publicPages.includes(currentPage)) {
            window.location.href = 'login.php';
            return;
        }
        
        if (isLoggedIn && publicPages.includes(currentPage) && currentPage !== 'logout.php') {
            window.location.href = 'dashboard.php';
            return;
        }
    }

    // Configurar event listeners
    setupEventListeners() {
        // Logout
        const logoutButtons = document.querySelectorAll('#logoutBtn, [data-logout]');
        logoutButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleLogout();
            });
        });

        // Confirmações de exclusão
        const deleteButtons = document.querySelectorAll('.btn-danger');
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                if (!confirm('Tem certeza que deseja excluir este item?')) {
                    e.preventDefault();
                }
            });
        });

        // Validação de formulários
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => this.validateForm(e));
        });

        // Pesquisa em tempo real
        const searchInputs = document.querySelectorAll('input[type="search"], #search');
        searchInputs.forEach(input => {
            input.addEventListener('input', (e) => this.handleSearch(e));
        });

        // Filtros
        const filterSelects = document.querySelectorAll('select[name="categoria"], select[name="status"]');
        filterSelects.forEach(select => {
            select.addEventListener('change', (e) => this.handleFilterChange(e));
        });
    }

    // Configurar componentes UI
    setupUIComponents() {
        this.setupDataTables();
        this.setupCharts();
        this.setupNotifications();
    }

    // Configurar tabelas com funcionalidades extras
    setupDataTables() {
        const tables = document.querySelectorAll('.table');
        
        tables.forEach(table => {
            const headers = table.querySelectorAll('th');
            headers.forEach((header, index) => {
                header.style.cursor = 'pointer';
                header.addEventListener('click', () => {
                    this.sortTable(table, index);
                });
            });
        });
    }

    // Ordenar tabela
    sortTable(table, columnIndex) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const isNumeric = this.isColumnNumeric(table, columnIndex);
        const isDate = this.isColumnDate(table, columnIndex);
        
        const direction = table.getAttribute('data-sort-direction') === 'asc' ? 'desc' : 'asc';
        table.setAttribute('data-sort-direction', direction);
        
        rows.sort((a, b) => {
            const aValue = a.cells[columnIndex].textContent.trim();
            const bValue = b.cells[columnIndex].textContent.trim();
            
            if (isNumeric) {
                return direction === 'asc' ? 
                    parseFloat(aValue) - parseFloat(bValue) : 
                    parseFloat(bValue) - parseFloat(aValue);
            } else if (isDate) {
                return direction === 'asc' ?
                    new Date(aValue) - new Date(bValue) :
                    new Date(bValue) - new Date(aValue);
            } else {
                return direction === 'asc' ?
                    aValue.localeCompare(bValue) :
                    bValue.localeCompare(aValue);
            }
        });
        
        // Remover linhas existentes
        while (tbody.firstChild) {
            tbody.removeChild(tbody.firstChild);
        }
        
        // Adicionar linhas ordenadas
        rows.forEach(row => tbody.appendChild(row));
        
        // Atualizar indicadores de ordenação
        this.updateSortIndicators(table, columnIndex, direction);
    }

    // Verificar se coluna é numérica
    isColumnNumeric(table, columnIndex) {
        const sampleCell = table.querySelector(`td:nth-child(${columnIndex + 1})`);
        if (!sampleCell) return false;
        
        const value = sampleCell.textContent.trim();
        return !isNaN(parseFloat(value)) && isFinite(value);
    }

    // Verificar se coluna é data
    isColumnDate(table, columnIndex) {
        const sampleCell = table.querySelector(`td:nth-child(${columnIndex + 1})`);
        if (!sampleCell) return false;
        
        const value = sampleCell.textContent.trim();
        return !isNaN(Date.parse(value));
    }

    // Atualizar indicadores de ordenação
    updateSortIndicators(table, columnIndex, direction) {
        const headers = table.querySelectorAll('th');
        headers.forEach(header => {
            header.classList.remove('sort-asc', 'sort-desc');
            header.querySelector('.sort-indicator')?.remove();
        });
        
        const activeHeader = headers[columnIndex];
        activeHeader.classList.add(`sort-${direction}`);
        
        const indicator = document.createElement('span');
        indicator.className = 'sort-indicator';
        indicator.textContent = direction === 'asc' ? ' ↑' : ' ↓';
        activeHeader.appendChild(indicator);
    }

    // Configurar gráficos (simulação)
    setupCharts() {
        // Em produção, integrar com biblioteca de gráficos como Chart.js
        const chartContainers = document.querySelectorAll('#topProdutosChart, #categoriasChart');
        
        chartContainers.forEach(container => {
            if (container.querySelector('.chart-placeholder')) {
                this.loadSampleChart(container);
            }
        });
    }

    // Carregar gráfico de exemplo
    loadSampleChart(container) {
        const placeholder = container.querySelector('.chart-placeholder');
        if (placeholder) {
            placeholder.innerHTML = `
                <div style="text-align: center; padding: 2rem; color: var(--cinza-medio);">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📊</div>
                    <p>Gráfico interativo</p>
                    <small>Em produção, mostraria dados reais</small>
                </div>
            `;
        }
    }

    // Sistema de notificações
    setupNotifications() {
        // Mostrar notificações salvas
        this.showStoredNotifications();
        
        // Configurar auto-hide para alerts
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });
    }

    // Mostrar notificações salvas
    showStoredNotifications() {
        const notifications = JSON.parse(sessionStorage.getItem('serenity_notifications') || '[]');
        notifications.forEach(notification => {
            this.showNotification(notification.message, notification.type);
        });
        sessionStorage.removeItem('serenity_notifications');
    }

    // Mostrar notificação
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close">&times;</button>
            </div>
        `;
        
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${this.getNotificationColor(type)};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            max-width: 400px;
        `;
        
        document.body.appendChild(notification);
        
        // Animar entrada
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Configurar fechamento
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.addEventListener('click', () => {
            notification.style.transform = 'translateX(400px)';
            setTimeout(() => notification.remove(), 300);
        });
        
        // Auto-remover após 5 segundos
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.transform = 'translateX(400px)';
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
    }

    // Cor da notificação baseada no tipo
    getNotificationColor(type) {
        const colors = {
            success: '#28A745',
            error: '#DC3545',
            warning: '#FFC107',
            info: '#17A2B8'
        };
        return colors[type] || colors.info;
    }

    // Manipular logout
    handleLogout() {
        if (confirm('Tem certeza que deseja sair do sistema?')) {
            // Limpar dados locais
            document.cookie = 'serenity_logged_in=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            sessionStorage.removeItem('serenity_logged_in');
            localStorage.removeItem('serenity_user_data');
            
            // Redirecionar para login
            window.location.href = 'logout.php';
        }
    }

    // Validar formulário
    validateForm(e) {
        const form = e.target;
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                this.highlightInvalidField(field);
            } else {
                this.removeHighlight(field);
            }
        });
        
        // Validações específicas
        if (form.id === 'loginForm') {
            isValid = this.validateLoginForm(form) && isValid;
        }
        
        if (form.querySelector('#email')) {
            isValid = this.validateEmail(form.querySelector('#email')) && isValid;
        }
        
        if (form.querySelector('#cnpj')) {
            isValid = this.validateCNPJ(form.querySelector('#cnpj')) && isValid;
        }
        
        if (!isValid) {
            e.preventDefault();
            this.showNotification('Por favor, preencha todos os campos obrigatórios corretamente.', 'error');
        }
    }

    // Validar formulário de login
    validateLoginForm(form) {
        const email = form.querySelector('#email');
        const password = form.querySelector('#password');
        
        if (email && !this.validateEmail(email)) {
            return false;
        }
        
        if (password && password.value.length < 6) {
            this.highlightInvalidField(password);
            this.showNotification('A senha deve ter pelo menos 6 caracteres.', 'error');
            return false;
        }
        
        return true;
    }

    // Validar email
    validateEmail(emailField) {
        const email = emailField.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!emailRegex.test(email)) {
            this.highlightInvalidField(emailField);
            return false;
        }
        
        this.removeHighlight(emailField);
        return true;
    }

    // Validar CNPJ (validação básica)
    validateCNPJ(cnpjField) {
        const cnpj = cnpjField.value.replace(/\D/g, '');
        
        if (cnpj.length !== 14) {
            this.highlightInvalidField(cnpjField);
            return false;
        }
        
        this.removeHighlight(cnpjField);
        return true;
    }

    // Destacar campo inválido
    highlightInvalidField(field) {
        field.style.borderColor = 'var(--erro)';
        field.style.boxShadow = '0 0 0 2px rgba(220, 53, 69, 0.1)';
    }

    // Remover destaque do campo
    removeHighlight(field) {
        field.style.borderColor = '';
        field.style.boxShadow = '';
    }

    // Manipular pesquisa
    handleSearch(e) {
        const searchTerm = e.target.value.toLowerCase();
        const table = e.target.closest('.filters')?.nextElementSibling?.querySelector('.table');
        
        if (!table) return;
        
        const rows = table.querySelectorAll('tbody tr');
        let hasResults = false;
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const isVisible = text.includes(searchTerm);
            row.style.display = isVisible ? '' : 'none';
            
            if (isVisible) {
                hasResults = true;
            }
        });
        
        // Mostrar mensagem se não houver resultados
        this.toggleNoResultsMessage(table, hasResults);
    }

    // Mostrar/ocultar mensagem de nenhum resultado
    toggleNoResultsMessage(table, hasResults) {
        let noResultsRow = table.querySelector('.no-results-message');
        
        if (!hasResults && !noResultsRow) {
            noResultsRow = document.createElement('tr');
            noResultsRow.className = 'no-results-message';
            noResultsRow.innerHTML = `<td colspan="100" class="muted text-center">Nenhum resultado encontrado</td>`;
            table.querySelector('tbody').appendChild(noResultsRow);
        } else if (hasResults && noResultsRow) {
            noResultsRow.remove();
        }
    }

    // Manipular mudança de filtro
    handleFilterChange(e) {
        const form = e.target.closest('form');
        if (form) {
            form.submit();
        }
    }

    // Exportar dados
    exportData(format = 'csv') {
        const table = document.querySelector('.table');
        if (!table) return;
        
        let csv = '';
        const headers = [];
        
        // Obter cabeçalhos
        table.querySelectorAll('thead th').forEach(header => {
            headers.push(header.textContent.trim());
        });
        csv += headers.join(',') + '\n';
        
        // Obter dados
        table.querySelectorAll('tbody tr').forEach(row => {
            if (row.style.display !== 'none') {
                const rowData = [];
                row.querySelectorAll('td').forEach((cell, index) => {
                    // Ignorar coluna de ações
                    if (!cell.querySelector('.btn')) {
                        rowData.push(`"${cell.textContent.trim().replace(/"/g, '""')}"`);
                    }
                });
                csv += rowData.join(',') + '\n';
            }
        });
        
        // Download
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `serenity-export-${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
        
        this.showNotification('Dados exportados com sucesso!', 'success');
    }

    // Ações rápidas
    showQuickActions() {
        const actions = [
            { label: 'Novo Produto', url: 'cadastro-produto.php', icon: '➕' },
            { label: 'Novo Fornecedor', url: 'cadastro-fornecedor.php', icon: '🏢' },
            { label: 'Relatório de Estoque', url: 'produtos.php?filter=low-stock', icon: '📊' },
            { label: 'Exportar Dados', action: () => this.exportData(), icon: '📥' }
        ];
        
        const modal = this.createModal('Ações Rápidas', '');
        modal.querySelector('.modal-content').innerHTML = `
            <div style="display: grid; gap: 0.5rem;">
                ${actions.map(action => `
                    <button class="quick-action-btn" data-action="${action.url || ''}">
                        <span class="action-icon">${action.icon}</span>
                        <span class="action-label">${action.label}</span>
                    </button>
                `).join('')}
            </div>
        `;
        
        modal.querySelectorAll('.quick-action-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const action = btn.getAttribute('data-action');
                if (action) {
                    window.location.href = action;
                } else {
                    // Ação customizada (export)
                    this.exportData();
                }
                modal.remove();
            });
        });
    }

    // Criar modal
    createModal(title, content) {
        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        `;
        
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.style.cssText = `
            background: var(--branco-perolado);
            border-radius: 16px;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        `;
        
        modal.innerHTML = `
            <div class="modal-header" style="margin-bottom: 1.5rem;">
                <h3 style="margin: 0;">${title}</h3>
                <button class="modal-close" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
            </div>
            <div class="modal-content">${content}</div>
        `;
        
        overlay.appendChild(modal);
        document.body.appendChild(overlay);
        
        // Fechar modal
        const closeBtn = modal.querySelector('.modal-close');
        closeBtn.addEventListener('click', () => overlay.remove());
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) overlay.remove();
        });
        
        return modal;
    }
}

// ===== INICIALIZAÇÃO =====
document.addEventListener('DOMContentLoaded', () => {
    window.serenityApp = new SerenityApp();
});

// ===== FUNÇÕES GLOBAIS =====
// Para uso em onclick nos HTMLs
function confirmDelete(id) {
    if (confirm('Tem certeza que deseja excluir este item?')) {
        window.location.href = window.location.pathname + '?delete_id=' + id;
    }
}

function exportData() {
    window.serenityApp.exportData();
}

function showQuickActions() {
    window.serenityApp.showQuickActions();
}

// Formatação de máscaras
function formatCNPJ(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.length <= 14) {
        value = value.replace(/(\d{2})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1/$2');
        value = value.replace(/(\d{4})(\d)/, '$1-$2');
    }
    
    input.value = value;
}

function formatPhone(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.length <= 11) {
        value = value.replace(/(\d{2})(\d)/, '($1) $2');
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
    }
    
    input.value = value;
}

function formatCurrency(input) {
    let value = input.value.replace(/\D/g, '');
    value = (value / 100).toFixed(2);
    value = value.replace('.', ',');
    value = value.replace(/(\d)(?=(\d{3})+,)/g, '$1.');
    input.value = 'R$ ' + value;
}