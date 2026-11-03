document.addEventListener("DOMContentLoaded", function () {
    const listaCarrinho = document.getElementById("lista-carrinho");
    const subtotalElemento = document.getElementById("subtotal");
    const totalElemento = document.getElementById("total");
    const botaoFinalizar = document.getElementById("finalizar-compra");

    let carrinho = JSON.parse(localStorage.getItem("avaliaCarrinho")) || [];

    function salvarCarrinho() {
        localStorage.setItem("avaliaCarrinho", JSON.stringify(carrinho));
    }

    function formatarPreco(valor) {
        return valor.toLocaleString("pt-BR", {
            style: "currency",
            currency: "BRL"
        });
    }

    function atualizarCarrinho() {
        listaCarrinho.innerHTML = "";

        if (carrinho.length === 0) {
            listaCarrinho.innerHTML = "<p>Seu carrinho está vazio.</p>";
        }

        let subtotal = 0;

        carrinho.forEach(function (produto, index) {
            const quantidade = Number(produto.quantidade) || 1;
            const preco = Number(produto.preco) || 0;

            subtotal += preco * quantidade;

            const item = document.createElement("div");
            item.classList.add("item-carrinho");

            item.innerHTML = `
                <h3>${produto.nome}</h3>

                <p>Preço: ${formatarPreco(preco)}</p>

                <div class="controle-quantidade">
                    <button class="diminuir" data-index="${index}">−</button>

                    <span>${quantidade}</span>

                    <button class="aumentar" data-index="${index}">+</button>
                </div>

                <p>Subtotal: ${formatarPreco(preco * quantidade)}</p>

                <button class="remover" data-index="${index}">
                    Remover
                </button>
            `;

            listaCarrinho.appendChild(item);
        });

        subtotalElemento.textContent = formatarPreco(subtotal);
        totalElemento.textContent = formatarPreco(subtotal);

        salvarCarrinho();
    }

    listaCarrinho.addEventListener("click", function (evento) {
        const index = Number(evento.target.dataset.index);

        if (evento.target.classList.contains("aumentar")) {
            carrinho[index].quantidade++;
        }

        if (evento.target.classList.contains("diminuir")) {
            if (carrinho[index].quantidade > 1) {
                carrinho[index].quantidade--;
            }
        }

        if (evento.target.classList.contains("remover")) {
            carrinho.splice(index, 1);
        }

        atualizarCarrinho();
    });

    botaoFinalizar.addEventListener("click", function () {
        if (carrinho.length === 0) {
            alert("Seu carrinho está vazio!");
            return;
        }

        alert("Pedido preparado! A finalização real ainda será implementada.");
    });

    atualizarCarrinho();
});