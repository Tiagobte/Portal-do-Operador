document.addEventListener('DOMContentLoaded', function() {
    const registros = {};

    document.getElementById('registro-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar o envio do formulário

        // Coletar os dados do formulário
        const usina = document.getElementById('usina').value;
        const unidadeGeradora = document.getElementById('unidade-geradora').value;
        const tipoEvento = document.getElementById('tipo-evento').value;
        const descricao = document.getElementById('descricao').value;

        // Criar o registro
        const registro = { usina, unidadeGeradora, tipoEvento, descricao };

        // Enviar o registro para o servidor
        fetch('http://localhost:3000/api/eventos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(registro)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erro na solicitação: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                console.log('Evento inserido com sucesso');
                if (!registros[usina]) {
                    registros[usina] = [];
                }
                registros[usina].push(registro);
                atualizarListaEventos();
                document.getElementById('registro-form').reset();
            } else {
                console.error('Erro ao inserir evento no banco de dados:', data.message);
            }
        })
        .catch(error => {
            console.error('Erro ao enviar solicitação:', error);
        });
    });

    function atualizarListaEventos() {
        const listaEventos = document.getElementById('lista-eventos');
        listaEventos.innerHTML = '';

        for (const [usina, registrosUsina] of Object.entries(registros)) {
            const usinaHeader = document.createElement('h3');
            usinaHeader.textContent = `Usina: ${usina}`;
            listaEventos.appendChild(usinaHeader);

            for (const registro of registrosUsina) {
                const eventoItem = document.createElement('div');
                eventoItem.classList.add('evento');
                eventoItem.innerHTML = `
                    <h5>${registro.tipoEvento}</h5>
                    <p>Descrição: ${registro.descricao}</p>
                    <p>Unidade Geradora: ${registro.unidadeGeradora}</p>
                `;
                listaEventos.appendChild(eventoItem);
            }
        }
    }
});

