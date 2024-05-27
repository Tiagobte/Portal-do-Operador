document.addEventListener('DOMContentLoaded', function() {
    // Seu código aqui
});

// Função para calcular os responsáveis pelos próximos 7 dias da escala de sobreaviso
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
    const diasSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
    const proximosDias = [];

    // Encontra o próximo dia da semana "Segunda-feira"
    let proximaSegunda = new Date(dataAtual);
    proximaSegunda.setDate(proximaSegunda.getDate() + (1 + 7 - proximaSegunda.getDay()) % 7);
    proximaSegunda.setHours(17, 0, 0, 0); // Define a hora para 17:00

    // Loop para os próximos 7 dias
    for (let i = 0; i < 7; i++) {
        const dataDia = new Date(proximaSegunda);
        dataDia.setDate(proximaSegunda.getDate() + i);
        const diaSemana = diasSemana[dataDia.getDay()];
        const responsavelIndex = Math.floor(i / 7) % responsaveis.length;
        const responsavel = responsaveis[responsavelIndex];
        proximosDias.push({
            data: dataDia.toLocaleDateString(),
            diaSemana: diaSemana,
            responsavel: responsavel
        });
    }

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

    // Mapeia o início e o fim da escala para cada responsável
    let escalasPorResponsavel = new Map();
    escaladeSobreaviso.forEach((dia) => {
        if (!escalasPorResponsavel.has(dia.responsavel)) {
            escalasPorResponsavel.set(dia.responsavel, { inicio: dia, fim: dia });
        } else {
            escalasPorResponsavel.get(dia.responsavel).fim = dia;
        }
    });

    // Exibir os resultados
    escalasPorResponsavel.forEach((escala, responsavel) => {
        const diaDiv = document.createElement('div');
        diaDiv.textContent = `Responsável: ${responsavel} - Início: ${escala.inicio.diaSemana}, ${escala.inicio.data} - Fim: ${escala.fim.diaSemana}, ${escala.fim.data}`;
        escalasobreavisoDiv.appendChild(diaDiv);
    });

    exibirMensagem('Escala de sobreaviso gerada com sucesso!', 'sucesso');
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
