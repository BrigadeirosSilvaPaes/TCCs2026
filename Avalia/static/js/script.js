// ======================================
// CONTADOR DE CARACTERES
// ======================================

document.addEventListener("DOMContentLoaded", function () {

    const campo = document.getElementById("problema");
    const contador = document.getElementById("contador");

    if (campo && contador) {

        contador.innerText = campo.value.length;

        campo.addEventListener("input", function () {

            contador.innerText = campo.value.length;

        });

    }

});


// ======================================
// BOTÕES DE EXEMPLO
// ======================================

function preencher(texto) {

    const campo = document.getElementById("problema");
    const contador = document.getElementById("contador");

    if (campo) {

        campo.value = texto;

        if (contador) {

            contador.innerText = texto.length;

        }

    }

}


// ======================================
// IA DO DIAGNÓSTICO
// ======================================

async function analisarProblema() {

    const campo = document.getElementById("problema");

    if (!campo) return;

    const texto = campo.value.trim();

    if (texto === "") {

        alert("Descreva o problema do veículo.");

        return;

    }

    const resultado = document.getElementById("resultado");

    resultado.innerHTML = `

        <h2>Resultado da IA</h2>

        <p>Analisando problema...</p>

    `;

    try {

        const resposta = await fetch("/analisar", {

            method: "POST",

            headers: {

                "Content-Type": "application/json"

            },

            body: JSON.stringify({

                problema: texto

            })

        });

        const dados = await resposta.json();

        resultado.innerHTML = `

            <h2>Resultado do Diagnóstico</h2>

            <div class="caixa-resultado">

                <h3>${dados.problema}</h3>

                <p><strong>Possível causa:</strong><br>${dados.causa}</p>

                <p><strong>Gravidade:</strong> ${dados.gravidade}</p>

                <p><strong>Confiança:</strong> ${dados.confianca}</p>

                <p><strong>Recomendação:</strong><br>${dados.recomendacao}</p>

            </div>

        `;

    }

    catch (erro) {

        console.error(erro);

        resultado.innerHTML = `

            <div class="caixa-resultado">

                <h3>Erro</h3>

                <p>Não foi possível realizar o diagnóstico.</p>

            </div>

        `;

    }

}


// ======================================
// LOCALIZAÇÃO DOS MECÂNICOS
// ======================================

function obterLocalizacao() {

    if (!navigator.geolocation) {

        alert("Seu navegador não suporta geolocalização.");

        return;

    }

    navigator.geolocation.getCurrentPosition(

        function (posicao) {

            const latitude = posicao.coords.latitude;

            const longitude = posicao.coords.longitude;

            alert(

                "Localização encontrada!\n\n" +

                "Latitude: " + latitude +

                "\nLongitude: " + longitude

            );

        },

        function () {

            alert("Não foi possível obter sua localização.");

        }

    );

}


// ======================================
// PESQUISA DE PEÇAS
// ======================================

function pesquisar() {

    const campo = document.getElementById("campoPesquisa");

    if (!campo) return;

    const texto = campo.value.toLowerCase();

    const pecas = document.querySelectorAll(".peca");

    pecas.forEach(function (peca) {

        const nome = peca.dataset.nome.toLowerCase();

        if (nome.includes(texto)) {

            peca.style.display = "flex";

        }

        else {

            peca.style.display = "none";

        }

    });

}


// ======================================
// ADICIONAR AO PEDIDO
// ======================================

let quantidade = 0;

function adicionar() {

    quantidade++;

    const texto = document.getElementById("quantidade");

    if (texto) {

        texto.innerText = quantidade + " item(ns) no pedido";

    }

}


// ======================================
// PESQUISA DE TUTORIAIS
// ======================================

function pesquisarTutorial() {

    const campo = document.getElementById("pesquisa");

    if (!campo) return;

    const texto = campo.value.toLowerCase();

    const tutoriais = document.querySelectorAll(".tutorial");

    tutoriais.forEach(function (tutorial) {

        const nome = tutorial.dataset.nome.toLowerCase();

        if (nome.includes(texto)) {

            tutorial.style.display = "flex";

        }

        else {

            tutorial.style.display = "none";

        }

    });

}