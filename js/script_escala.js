function adicionarInputsResponsaveis() {
    const numUsinasInput = document.getElementById('numUsinas');
    const numUsinas = parseInt(numUsinasInput.value);

    if (isNaN(numUsinas) || numUsinas <= 0) {
        alert("Por favor, insira um número válido de usinas.");
        return;
    }

    const containerInputs = document.getElementById('inputsResponsaveis');
    containerInputs.innerHTML = '';

    for (let i = 1; i <= numUsinas; i++) {
        const divUsina = document.createElement('div');
        divUsina.classList.add('form-group');
        divUsina.innerHTML = `
            <label for="usina${i}">Usina ${i}:</label>
            <input id="usina${i}" type="text" class="form-control mb-2" placeholder="Nome do Responsável da Usina ${i}" required>
        `;
        containerInputs.appendChild(divUsina);
    }
}

function exibirEscalaSobreaviso() {
    const numUsinasInput = document.getElementById('numUsinas');
    const numUsinas = parseInt(numUsinasInput.value);

    if (isNaN(numUsinas) || numUsinas <= 0) {
        alert("Por favor, insira um número válido de usinas.");
        return;
    }

    const responsaveis = [];
    for (let i = 1; i <= numUsinas; i++) {
        const responsavelInput = document.getElementById(`usina${i}`);
        const responsavelNome = responsavelInput.value.trim();
        if (responsavelNome !== "") {
            responsaveis.push(responsavelNome);
        }
    }

    if (responsaveis.length === 0) {
        alert("Por favor, insira os nomes dos responsáveis.");
        return;
    }

    const tabela = document.createElement('table');
    tabela.classList.add('table');
    const thead = document.createElement('thead');
    const tbody = document.createElement('tbody');

    const trHead = document.createElement('tr');
    const thUsina = document.createElement('th');
    thUsina.textContent = 'Usina';
    const thResponsavel = document.createElement('th');
    thResponsavel.textContent = 'Responsável';
    const thPrimeiroDia = document.createElement('th');
    thPrimeiroDia.textContent = 'Início';
    const thUltimoDia = document.createElement('th');
    thUltimoDia.textContent = 'Fim';

    trHead.appendChild(thUsina);
    trHead.appendChild(thResponsavel);
    trHead.appendChild(thPrimeiroDia);
    trHead.appendChild(thUltimoDia);
    thead.appendChild(trHead);
    tabela.appendChild(thead);

    const dataAtual = new Date();
    let proximaSegunda = new Date(dataAtual);
    proximaSegunda.setDate(proximaSegunda.getDate() + (1 + 7 - proximaSegunda.getDay()) % 7);
    proximaSegunda.setHours(17, 0, 0, 0);

    for (let i = 0; i < responsaveis.length; i++) {
        const responsavel = responsaveis[i];

        const tr = document.createElement('tr');
        const tdUsina = document.createElement('td');
        const tdResponsavel = document.createElement('td');
        const tdPrimeiroDia = document.createElement('td');
        const tdUltimoDia = document.createElement('td');

        tdUsina.textContent = `Usina ${i + 1}`;
        tdResponsavel.textContent = responsavel;
        tdPrimeiroDia.textContent = formatarDataHora(proximaSegunda);

        const fimSemana = new Date(proximaSegunda);
        fimSemana.setDate(fimSemana.getDate() + 7);
        tdUltimoDia.textContent = formatarDataHora(fimSemana);

        tr.appendChild(tdUsina);
        tr.appendChild(tdResponsavel);
        tr.appendChild(tdPrimeiroDia);
        tr.appendChild(tdUltimoDia);

        tbody.appendChild(tr);

        proximaSegunda.setDate(proximaSegunda.getDate() + 7);
    }

    tabela.appendChild(tbody);

    const containerEscala = document.getElementById('escalasobreaviso');
    containerEscala.innerHTML = '';
    containerEscala.appendChild(tabela);
}

function formatarDataHora(data) {
    const dia = data.getDate();
    const mes = data.getMonth() + 1;
    const ano = data.getFullYear();
    const horas = data.getHours().toString().padStart(2, '0');
    const minutos = data.getMinutes().toString().padStart(2, '0');
    return `${dia}/${mes}/${ano} ${horas}:${minutos}`;
}

$(document).ready(function () {
    // Dados das usinas em formato JSON
    var usinasData = {
        "usinas": [
            {"id": 1, "nome": "Usina 1"},
            {"id": 2, "nome": "Usina 2"},
            {"id": 3, "nome": "Usina 3"}
        ]
    };

    // Carregar opções de usinas a partir dos dados
    var options = "";
    $.each(usinasData.usinas, function (key, value) {
        options += `<option value="${value.id}">${value.nome}</option>`;
    });
    $("#usinaSelect").append(options);
});
