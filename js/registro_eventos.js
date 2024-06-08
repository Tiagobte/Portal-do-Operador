// Estrutura de dados para armazenar os registros
const registros = {};

// Lidar com o envio do formulário de registro de evento
document.getElementById('registro-form').addEventListener('submit', function(event) {
  event.preventDefault(); // Evitar o envio do formulário

  // Coletar os dados do formulário
  const usina = document.getElementById('usina').value;
  const unidadeGeradora = document.getElementById('unidade-geradora').value;
  const tipoEvento = document.getElementById('tipo-evento').value;
  const descricao = document.getElementById('descricao').value;

  // Criar o registro
  const registro = { usina, unidadeGeradora, tipoEvento, descricao };

  // Adicionar o registro ao objeto de registros, agrupado por usina
  if (!registros[usina]) {
    registros[usina] = [];
  }
  registros[usina].push(registro);

  // Atualizar a lista de eventos na interface do usuário
  atualizarListaEventos();

  // Limpar o formulário
  document.getElementById('registro-form').reset();
});

// Função para atualizar a lista de eventos na interface do usuário
function atualizarListaEventos() {
  const listaEventos = document.getElementById('lista-eventos');
  listaEventos.innerHTML = ''; // Limpar a lista antes de atualizar

  // Iterar sobre as usinas e seus registros correspondentes
  for (const [usina, registrosUsina] of Object.entries(registros)) {
    const usinaHeader = document.createElement('h3');
    usinaHeader.textContent = `Usina: ${usina}`;
    listaEventos.appendChild(usinaHeader);

    // Iterar sobre os registros da usina
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
