document.addEventListener('DOMContentLoaded', function() {
    const listaPedidos = document.querySelectorAll('.ver-itens-btn');
    const popout = document.getElementById('popout');
    const closeBtn = document.querySelector('.close-btn');
    const listaItens = document.getElementById('lista-itens');

    listaPedidos.forEach(button => {
        button.addEventListener('click', function() {
            const pedidoId = this.getAttribute('data-pedido-id');
            
            fetch(`obter_itens_pedido.php?pedido_id=${pedidoId}`)
                .then(response => response.json())
                .then(data => {
                    // Limpa os itens anteriores
                    listaItens.innerHTML = '';

                    // Adiciona os novos itens
                    data.forEach(item => {
                        const li = document.createElement('li');
                        li.textContent = `${item.nome} - Quantidade: ${item.quantidade}`;
                        listaItens.appendChild(li);
                    });

                    // Mostra o popout
                    popout.style.display = 'block';
                })
                .catch(error => console.error('Erro:', error));
        });
    });

    closeBtn.addEventListener('click', function() {
        popout.style.display = 'none';
    });

    // Fechar o popout ao clicar fora dele
    window.addEventListener('click', function(event) {
        if (event.target == popout) {
            popout.style.display = 'none';
        }
    });
});
