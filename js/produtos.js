// Arquivo: js/produtos.js
document.addEventListener('DOMContentLoaded', function() {
    initProductPage();
});

function initProductPage() {
    // Gestão de modais
    setupModals();
    
    // Inicializar eventos da tabela
    setupTableEvents();
    
    // Inicializar eventos de busca e filtros
    setupSearchAndFilters();
    
    // Inicializar formulário de produto
    setupProductForm();
}

// Configuração dos modais
function setupModals() {
    // Botões que abrem modais
    const modalTriggers = document.querySelectorAll('[data-toggle="modal"]');
    
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const modalId = this.getAttribute('data-target');
            const modal = document.querySelector(modalId);
            
            if (modal) {
                modal.classList.add('show');
            }
        });
    });
    
    // Botões que fecham modais
    const closeButtons = document.querySelectorAll('.close-modal, [data-dismiss="modal"]');
    
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                modal.classList.remove('show');
            }
        });
    });
    
    // Fechar modal ao clicar fora dele
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.classList.remove('show');
        }
    });
}

// Eventos da tabela de produtos
function setupTableEvents() {
    // Checkbox mestre para selecionar todos
    const masterCheckbox = document.querySelector('th input[type="checkbox"]');
    if (masterCheckbox) {
        masterCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    // Botões de edição
    const editButtons = document.querySelectorAll('.btn-icon .fa-edit');
    editButtons.forEach(button => {
        button.parentElement.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            if (productId) {
                // Aqui você pode carregar os dados do produto e abrir o modal de edição
                console.log(`Editar produto ${productId}`);
                
                // Exemplo: abrir modal de edição
                const modal = document.querySelector('#addProductModal');
                if (modal) {
                    modal.classList.add('show');
                    // Carregar dados do produto no formulário...
                }
            }
        });
    });
    
    // Botões de exclusão
    const deleteButtons = document.querySelectorAll('.btn-icon .fa-trash');
    deleteButtons.forEach(button => {
        button.parentElement.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            if (productId && confirm('Tem certeza que deseja excluir este produto?')) {
                // Aqui você enviaria uma requisição para excluir o produto
                console.log(`Excluir produto ${productId}`);
            }
        });
    });
}

// Configuração de busca e filtros
function setupSearchAndFilters() {
    // Formulário de busca
    const searchForm = document.querySelector('.search-box');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchTerm = this.querySelector('input').value.trim();
            
            if (searchTerm) {
                // Aqui você enviaria uma requisição para buscar produtos
                console.log(`Buscar produtos com o termo: ${searchTerm}`);
                
                // Exemplo de implementação fake para demonstração
                filterTableRows(searchTerm);
            }
        });
    }
    
    // Filtros de seleção
    const filterSelects = document.querySelectorAll('.filters select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            const filterType = this.getAttribute('name') || this.id;
            const filterValue = this.value;
            
            // Aqui você aplicaria o filtro na tabela
            console.log(`Filtrar por ${filterType}: ${filterValue}`);
            
            // Exemplo: filtrar por categoria ou status
            if (filterType === 'categoria' && filterValue) {
                filterTableByColumn('Categoria', filterValue);
            } else if (filterType === 'status' && filterValue) {
                filterTableByColumn('Status', filterValue);
            }
        });
    });
}

// Função que filtra as linhas da tabela pelo termo de busca
function filterTableRows(term) {
    const table = document.querySelector('.data-table table');
    if (!table) return;
    
    const rows = table.querySelectorAll('tbody tr');
    const lowercaseTerm = term.toLowerCase();
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const shouldShow = text.includes(lowercaseTerm);
        
        row.style.display = shouldShow ? '' : 'none';
    });
}

// Função que filtra por coluna específica
function filterTableByColumn(columnName, value) {
    const table = document.querySelector('.data-table table');
    if (!table) return;
    
    // Encontra o índice da coluna pelo nome
    const headers = table.querySelectorAll('th');
    let columnIndex = -1;
    
    headers.forEach((header, index) => {
        if (header.textContent.trim() === columnName) {
            columnIndex = index;
        }
    });
    
    if (columnIndex === -1) return;
    
    // Filtra as linhas
    const rows = table.querySelectorAll('tbody tr');
    const lowercaseValue = value.toLowerCase();
    
    rows.forEach(row => {
        const cell = row.cells[columnIndex];
        const cellText = cell.textContent.toLowerCase();
        
        const shouldShow = value === '' || cellText.includes(lowercaseValue);
        row.style.display = shouldShow ? '' : 'none';
    });
}

// Configuração do formulário de produto
function setupProductForm() {
    const productForm = document.getElementById('productForm');
    if (productForm) {
        productForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validar formulário
            if (!validateProductForm(this)) {
                return;
            }
            
            // Recolher dados do formulário
            const formData = new FormData(this);
            
            // Aqui você enviaria os dados para o servidor
            console.log('Enviando dados do produto...');
            
            // Simulação: Fechar o modal após envio
            const modal = this.closest('.modal');
            if (modal) {
                modal.classList.remove('show');
            }
            
            // Simulação: Adicionar produto à tabela
            addProductToTable(formData);
            
            // Limpar o formulário
            this.reset();
        });
        
        // Ajustar campos baseado no tipo de produto
        const tipoCampo = document.getElementById('tipoProduto');
        if (tipoCampo) {
            tipoCampo.addEventListener('change', function() {
                adjustFormFields(this.value);
            });
        }
    }
}

// Validação do formulário de produto
function validateProductForm(form) {
    let isValid = true;
    
    // Verificar campos obrigatórios
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('invalid');
            
            // Adicionar mensagem de erro
            let errorSpan = field.nextElementSibling;
            if (!errorSpan || !errorSpan.classList.contains('error-message')) {
                errorSpan = document.createElement('span');
                errorSpan.classList.add('error-message');
                errorSpan.style.color = 'red';
                errorSpan.style.fontSize = '0.8rem';
                field.parentNode.insertBefore(errorSpan, field.nextSibling);
            }
            errorSpan.textContent = 'Este campo é obrigatório';
        } else {
            field.classList.remove('invalid');
            const errorSpan = field.nextElementSibling;
            if (errorSpan && errorSpan.classList.contains('error-message')) {
                errorSpan.textContent = '';
            }
        }
    });
    
    // Validações específicas
    // Exemplo: Validar preço (deve ser maior que zero)
    const precoField = form.querySelector('[name="preco"]');
    if (precoField && parseFloat(precoField.value) <= 0) {
        isValid = false;
        precoField.classList.add('invalid');
        
        let errorSpan = precoField.nextElementSibling;
        if (!errorSpan || !errorSpan.classList.contains('error-message')) {
            errorSpan = document.createElement('span');
            errorSpan.classList.add('error-message');
            errorSpan.style.color = 'red';
            errorSpan.style.fontSize = '0.8rem';
            precoField.parentNode.insertBefore(errorSpan, precoField.nextSibling);
        }
        errorSpan.textContent = 'O preço deve ser maior que zero';
    }
    
    return isValid;
}

// Ajustar campos do formulário com base no tipo de produto
function adjustFormFields(productType) {
    const concentracaoGroup = document.querySelector('.form-group-concentracao');
    const volumeGroup = document.querySelector('.form-group-volume');
    
    if (productType === 'oleo') {
        // Mostrar campos específicos para óleos
        if (concentracaoGroup) concentracaoGroup.style.display = 'block';
        if (volumeGroup) volumeGroup.style.display = 'block';
    } else if (productType === 'capsulas') {
        // Mostrar campos específicos para cápsulas
        if (concentracaoGroup) concentracaoGroup.style.display = 'block';
        if (volumeGroup) {
            volumeGroup.style.display = 'block';
            const label = volumeGroup.querySelector('label');
            if (label) label.textContent = 'Quantidade (cápsulas)';
        }
    } else {
        // Configuração padrão
        if (concentracaoGroup) concentracaoGroup.style.display = 'block';
        if (volumeGroup) volumeGroup.style.display = 'block';
    }
}

// Adicionar produto à tabela (simulação)
function addProductToTable(formData) {
    const table = document.querySelector('.data-table table tbody');
    if (!table) return;
    
    // Criar nova linha
    const newRow = document.createElement('tr');
    
    // Preencher com os dados do formulário
    newRow.innerHTML = `
        <td><input type="checkbox"></td>
        <td>${formData.get('codigo')}</td>
        <td><img src="img/produto-placeholder.jpg" alt="Novo Produto" width="50"></td>
        <td>${formData.get('nome')}</td>
        <td>${document.querySelector('select[name="categoria"] option:checked').textContent}</td>
        <td>R$ ${parseFloat(formData.get('preco')).toFixed(2)}</td>
        <td>${formData.get('estoque')}</td>
        <td><span class="badge badge-success">Ativo</span></td>
        <td>
            <button class="btn-icon"><i class="fa-solid fa-edit"></i></button>
            <button class="btn-icon"><i class="fa-solid fa-trash"></i></button>
        </td>
    `;
    
    // Adicionar à tabela
    table.prepend(newRow);
    
    // Animar a linha recém-adicionada
    newRow.style.backgroundColor = 'rgba(118, 185, 71, 0.2)';
    setTimeout(() => {
        newRow.style.transition = 'background-color 1s';
        newRow.style.backgroundColor = '';
    }, 100);
}