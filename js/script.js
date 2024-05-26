document.addEventListener('DOMContentLoaded', function() {
  const { jsPDF } = window.jspdf;

  // Array para armazenar os eventos
  let eventos = [];

  // Função para adicionar evento
  function adicionarEvento() {
    const usina = document.getElementById('usina').value;
    const unidadeGeradora = document.getElementById('unidade-geradora').value;
    const tipoEvento = document.getElementById('tipo-evento').value;
    const dataHora = document.getElementById('data-hora').value;
    const descricao = document.getElementById('descricao').value;

    const novoEvento = {
      usina,
      unidadeGeradora,
      tipoEvento,
      dataHora,
      descricao,
      prioridade: definirPrioridade(tipoEvento)
    };

    eventos.push(novoEvento);
    mostrarEventos();
    limparFormulario();
  }

  // Função para limpar o formulário após adicionar um evento
  function limparFormulario() {
    document.getElementById('registro-form').reset();
  }

  // Função para exibir os eventos na página
  function mostrarEventos() {
    const listaEventos = document.getElementById('lista-eventos');
    listaEventos.innerHTML = ''; // Limpa a lista antes de atualizar

    // Objeto para armazenar os eventos agrupados por usina
    const eventosPorUsina = {};

    // Agrupa os eventos por usina
    eventos.forEach(function(evento) {
      if (!eventosPorUsina[evento.usina]) {
        eventosPorUsina[evento.usina] = [];
      }
      eventosPorUsina[evento.usina].push(evento);
    });

    // Ordena e exibe os eventos para cada usina
    for (const usina in eventosPorUsina) {
      const eventosUsina = eventosPorUsina[usina];
      eventosUsina.sort((a, b) => a.prioridade - b.prioridade); // Ordena por prioridade

      const usinaHeader = document.createElement('h3');
      usinaHeader.textContent = nomeUsina(usina);
      listaEventos.appendChild(usinaHeader);

      eventosUsina.forEach(function(evento) {
        const itemLista = document.createElement('div');
        itemLista.classList.add('evento-item');
        itemLista.innerHTML = `
          <div class="evento-header">
            <i class="fas fa-${iconeEvento(evento.tipoEvento)}"></i>
            <strong>${nomeUnidadeGeradora(evento.unidadeGeradora)}</strong> - ${evento.tipoEvento}
          </div>
          <div class="evento-body">
            ${evento.dataHora} - ${evento.descricao}
          </div>
        `;
        listaEventos.appendChild(itemLista);
      });
    }
  }

  // Função para retornar o nome correto da usina com base no código
  function nomeUsina(codigoUsina) {
    switch (codigoUsina) {
      case 'ASU':
        return 'PCH ALTO SUCURIÚ';
      case 'BOC':
        return 'PCH BOCAIUVA';
      // Adicione mais casos conforme necessário
      default:
        return 'Usina Desconhecida';
    }
  }

  // Função para retornar o nome correto da unidade geradora com base no código
  function nomeUnidadeGeradora(codigoUnidade) {
    // Adicione aqui a lógica para retornar o nome da unidade geradora com base no código
    return codigoUnidade; // Por enquanto, apenas retorna o código como nome
  }

  // Função para retornar o ícone correto com base no tipo de evento
  function iconeEvento(tipoEvento) {
    switch (tipoEvento) {
      case 'Desligamento':
        return 'power-off';
      case 'Partida':
        return 'play';
      case 'Parada':
        return 'stop';
      case 'Acionamento':
        return 'bell';
      case 'Alarme':
        return 'exclamation-triangle';
      case 'Comunicado':
        return 'info-circle';
      default:
        return 'question-circle';
    }
  }

  // Função para definir a prioridade do evento
  function definirPrioridade(tipoEvento) {
    // Adicione aqui a lógica para definir a prioridade com base no tipo de evento
    // Por exemplo, você pode retornar um valor numérico onde 1 é a mais alta prioridade
    // e 3 é a mais baixa, e então classificar os eventos com base nesse valor.
    return 0; // Por enquanto, todos os eventos têm a mesma prioridade
  }

  // Função para gerar o PDF
  function gerarPDF() {
    const doc = new jsPDF();

    // Definindo estilos de texto
    const tituloStyle = { fontSize: 18, fontName: 'helvetica', fontType: 'bold', textColor: 'black' };
    const eventoStyle = { fontSize: 12, fontName: 'helvetica', fontType: 'normal', textColor: 'blue' };

    // Adicionando título
    doc.setTextColor(tituloStyle.textColor);
    doc.setFont(tituloStyle.fontName, tituloStyle.fontType);
    doc.setFontSize(tituloStyle.fontSize);
    doc.text("Relatório de Eventos", 10, 10);

    let y = 30;

    // Objeto para armazenar os eventos agrupados por usina
    const eventosPorUsina = {};

    // Agrupa os eventos por usina
    eventos.forEach(function(evento) {
      if (!eventosPorUsina[evento.usina]) {
        eventosPorUsina[evento.usina] = [];
      }
      eventosPorUsina[evento.usina].push(evento);
    });

    // Percorre o objeto eventosPorUsina e adiciona os eventos para cada usina no PDF
    for (const usina in eventosPorUsina) {
      const eventosUsina = eventosPorUsina[usina];

      // Ordena por prioridade
      eventosUsina.sort((a, b) => a.prioridade - b.prioridade);

      // Adicionando título da usina
      doc.setTextColor(tituloStyle.textColor);
      doc.setFont(tituloStyle.fontName, tituloStyle.fontType);
      doc.setFontSize(tituloStyle.fontSize);
      doc.text(nomeUsina(usina), 10, y);

      y += 10;

      eventosUsina.forEach((evento, index) => {
        const dataHora = new Date(evento.dataHora).toLocaleString();
        const unidadeGeradora = nomeUnidadeGeradora(evento.unidadeGeradora);
        const tipoEvento = evento.tipoEvento;
        const descricao = evento.descricao;

        const texto = `Data/Hora: ${dataHora}\nUnidade Geradora: ${unidadeGeradora}\nTipo de Evento: ${tipoEvento}\nDescrição: ${descricao}\n`;

        // Adicionando separador visual
        if (index !== 0) {
          doc.setDrawColor(0);
          doc.setLineWidth(0.5);
          doc.line(10, y, 200, y); // Adiciona a linha separadora
          y += 5; // Espaçamento após o separador
        }

        // Adicionando evento
        doc.setTextColor(eventoStyle.textColor);
        doc.setFont(eventoStyle.fontName, eventoStyle.fontType);
        doc.setFontSize(eventoStyle.fontSize);
        doc.text(texto, 10, y);

        y += 30; // Espaçamento entre eventos
      });

      y += 20; // Espaçamento entre usinas
    }

    doc.save('relatorio_eventos.pdf');
  }

  // Adicionando evento de clique para o botão de gerar PDF
  document.getElementById('gerar-pdf-btn').addEventListener('click', gerarPDF);

  // Associando a função adicionarEvento ao botão "Adicionar Evento"
  document.getElementById('adicionar-evento-btn').addEventListener('click', adicionarEvento);
});
