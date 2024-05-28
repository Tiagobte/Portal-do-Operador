document.addEventListener('DOMContentLoaded', function() {
    // Seu código aqui
});

// Função para calcular os responsáveis pela escala de sobreaviso
function calcularEscaladeSobreaviso() {
    // Lista de responsáveis
    const responsaveis = [
        document.getElementById('responsavel1').value,
        document.getElementById('responsavel2').value,
        document.getElementById('responsavel3').value,
        document.getElementById('responsavel4').value
    ];

    // Data atual
    const dataAtual = new Date();
    const proximaSegunda = new Date(dataAtual);
    proximaSegunda.setDate(proximaSegunda.getDate() + (1 + 7 - proximaSegunda.getDay()) % 7);
    proximaSegunda.setHours(17, 0, 0, 0); // Define a hora para 17:00

    // Calcula a data de início e fim para cada responsável
    const proximosDias = [];
    let dataInicio = new Date(proximaSegunda);
    let dataFim = new Date(dataInicio);
    dataFim.setDate(dataFim.getDate() + 7);

    responsaveis.forEach((responsavel, index) => {
        proximosDias.push({
            responsavel: responsavel,
            inicio: new Date(dataInicio),
            fim: new Date(dataFim)
        });

        // Atualiza as datas para a próxima semana
        dataInicio.setDate(dataInicio.getDate() + 7);
        dataFim.setDate(dataFim.getDate() + 7);
    });

    return proximosDias;
}

// Função para exibir a escala de sobreaviso
function exibirEscalaSobreaviso() {
    const escaladeSobreaviso = calcularEscaladeSobreaviso();
    const escalasobreavisoDiv = document.getElementById('escalasobreaviso');
    const mensagemDiv = document.getElementById('mensagem');

    // Limpa o conteúdo anterior
    escalasobreavisoDiv.innerHTML = '';
    mensagemDiv.innerHTML = '';

    if (!escaladeSobreaviso) {
        exibirMensagem('Preencha todos os campos de responsável.', 'erro');
        return; // Se houver erro, interrompe a execução
    }

    // Exibir os resultados
    escaladeSobreaviso.forEach(dia => {
        const diaDiv = document.createElement('div');
        diaDiv.textContent = `Responsável: ${dia.responsavel} - Início: ${formatarData(dia.inicio)} - Fim: ${formatarData(dia.fim)}`;
        escalasobreavisoDiv.appendChild(diaDiv);
    });

    exibirMensagem('Escala de sobreaviso gerada com sucesso!', 'sucesso');
}

// Função para formatar a data no formato dd/mm/yyyy
function formatarData(data) {
    const dia = data.getDate().toString().padStart(2, '0');
    const mes = (data.getMonth() + 1).toString().padStart(2, '0');
    const ano = data.getFullYear();
    return `${dia}/${mes}/${ano}`;
}

// Função para exibir mensagens na tela
function exibirMensagem(mensagem, tipo) {
    const mensagemDiv = document.getElementById('mensagem');
    mensagemDiv.textContent = mensagem;

    if (tipo === 'erro') {
        mensagemDiv.style.color = 'red';
    } else if (tipo === 'sucesso') {
        mensagemDiv.style.color = 'green';
    }
}
