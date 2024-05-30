document.getElementById('adicionar-evento-btn').addEventListener('click', function() {
  const usina = document.getElementById('usina').value;
  const unidadeGeradora = document.getElementById('unidade-geradora').value;
  const tipoEvento = document.getElementById('tipo-evento').value;
  const dataHora = document.getElementById('data-hora').value;
  const descricao = document.getElementById('descricao').value;

  const eventoHTML = `
      <div class="list-group-item">
          <h5 class="mb-1">${tipoEvento}</h5>
          <p class="mb-1">${descricao}</p>
          <small>Usina: ${usina}, Unidade: ${unidadeGeradora}, Data/Hora: ${dataHora}</small>
      </div>
  `;

  document.getElementById('lista-eventos').insertAdjacentHTML('beforeend', eventoHTML);
});

document.getElementById('gerar-pdf-btn').addEventListener('click', function() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();

  doc.text("Registro de Eventos", 10, 10);
  const eventos = document.getElementById('lista-eventos').children;

  for (let i = 0; i < eventos.length; i++) {
      doc.text(eventos[i].innerText, 10, 20 + (i * 10));
  }

  doc.save('registro_eventos.pdf');
});
