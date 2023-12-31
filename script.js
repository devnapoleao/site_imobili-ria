function buscarImoveis(event) {
    event.preventDefault(); // Evita o recarregamento da página

    var cidade = document.getElementById('cidade').value;
    var bairro = document.getElementById('bairro').value;
    var valorAluguel = document.getElementById('valor_aluguel').value;
    var valorVenda = document.getElementById('valor_venda').value;

    // Preparando os dados para envio
    var dados = new FormData();
    dados.append('cidade', cidade);
    dados.append('bairro', bairro);
    dados.append('valor_aluguel', valorAluguel);
    dados.append('valor_venda', valorVenda);

    // Enviar os dados para buscarImoveis.php
    fetch('back/buscarImovel.php', {
        method: 'POST',
        body: dados
    })
    .then(response => response.json())
    .then(imoveis => {
        exibirImoveis(imoveis);
    })
    .catch(error => console.error('Erro ao buscar imóveis:', error));
}

function exibirImoveis(imoveis) {
    var resultadoDiv = document.getElementById('resultado');
    resultadoDiv.innerHTML = ''; // Limpa resultados anteriores

    imoveis.forEach(imovel => {
        var imovelDiv = document.createElement('div');
        imovelDiv.innerHTML = `
            <img src="${imovel.foto}" alt="Imagem do Imóvel">
            <p>Metros quadrados: ${imovel.metros}, Frente: ${imovel.frente}, Lado: ${imovel.lado}, Banheiros: ${imovel.banheiros}, Cômodos: ${imovel.comodos}, Quartos: ${imovel.quartos}</p>
            <p>Imóvel localizado na cidade de ${imovel.cidade}, bairro ${imovel.bairro}, com valor de aluguel ${imovel.valor_aluguel} e valor de venda ${imovel.valor_venda}</p>
        `;
        resultadoDiv.appendChild(imovelDiv);
    });
}

function buscarImoveis(event) {
    event.preventDefault();
    console.log("Função buscarImoveis chamada"); // Adicione esta linha
    // Restante do código...
}


function autocompletar() {
    var inputCidade = document.getElementById('cidade').value;
    var sugestoesDiv = document.getElementById('sugestoesCidade');
    sugestoesDiv.innerHTML = '';

    // Lista de cidades para sugestão
    var cidades = ["Bom Jardim", "Santa Inês", "Santa Luzia", "Imperatriz", "Açailândia", "Buriticupu", "Bom Jesus das Selvas"];
    
    // Filtrar cidades com base no input
    var filtradas = cidades.filter(function(cidade) {
        return cidade.toLowerCase().startsWith(inputCidade.toLowerCase());
    });

    // Criar sugestões
    filtradas.forEach(function(cidade) {
        var div = document.createElement('div');
        div.innerHTML = cidade;
        div.onclick = function() {
            document.getElementById('cidade').value = cidade;
            sugestoesDiv.innerHTML = '';
        };
        sugestoesDiv.appendChild(div);
    });

    // Esconder a lista se o campo estiver vazio
    if (inputCidade === '') {
        sugestoesDiv.innerHTML = '';
    }
}

// Esconder sugestões quando clicar fora
document.addEventListener('click', function(e) {
    if (e.target.id !== 'cidade') {
        document.getElementById('sugestoesCidade').innerHTML = '';
    }
});





function preencherEEnviar(cidade) {
    document.getElementById('cidade').value = cidade;
    document.getElementById('filtroForm').submit();
}


        function copiarEEnviar(imovelId) {
    var textoImovel = document.getElementById('info-imovel-' + imovelId).innerText;
    navigator.clipboard.writeText(textoImovel).then(() => {
        var whatsappUrl = "https://wa.me/98900000000?text=" + encodeURIComponent("Vim pelo site e estou interessado no imóvel: " + textoImovel);
        window.open(whatsappUrl, '_blank');
    }).catch(err => {
        console.error('Não foi possível copiar as informações: ', err);
    });
}


        function plusSlides(n, imovelIndex) {
            var slides = document.querySelectorAll('.imovel:nth-child(' + (imovelIndex + 1) + ') .mySlides');
            var slideIndex = Array.from(slides).findIndex(slide => slide.classList.contains('active-slide'));
            
            slides[slideIndex].classList.remove('active-slide');
            slideIndex += n;
            if (slideIndex >= slides.length) { slideIndex = 0; }
            if (slideIndex < 0) { slideIndex = slides.length - 1; }
            slides[slideIndex].classList.add('active-slide');
        }

        function copiarEEnviar(imovelId, whatsappNumber) {
    var textoImovel = document.getElementById('info-imovel-' + imovelId).innerText;
    navigator.clipboard.writeText(textoImovel).then(() => {
        var whatsappUrl = "https://wa.me/" + whatsappNumber + "?text=" + encodeURIComponent("Vim pelo site e estou interessado no imóvel: " + textoImovel);
        window.open(whatsappUrl, '_blank');
    }).catch(err => {
        console.error('Não foi possível copiar as informações: ', err);
    });
}

