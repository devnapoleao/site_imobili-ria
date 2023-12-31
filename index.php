<?php include('logicaFiltro.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Imóveis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

    <div class="container">
        <h1>Busque seu imóvel usando os filtros abaixo</h1>
        <h2>Não precisa preencher todos os campos, certo?</h2>
        <div class="esquerda-topo">
        <form method="POST" action="">
        <input type="text" id="cidade" name="cidade" placeholder="Cidade desejada" onkeyup="autocompletar()" autocomplete="off">
<div id="sugestoesCidade" class="sugestoes-cidade"></div>

                <div id="sugestoesCidade" style="background-color: white; position: absolute;"></div>
                <input type="text" id="valor_aluguel" name="valor_aluguel" placeholder="Valor máximo do Aluguel"><br>
                <input type="text" id="valor_venda" name="valor_venda" placeholder="Valor máximo para Venda - Em caso de venda"><br>
                <input class="botao" type="submit" value="Buscar">
                <br><br>
            </form>
        </div>
        <div class="container-resultado">
        <!-- Cabeçalho e Formulário de Filtro -->
        <div id="resultado">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $filtros = [
                    'cidade' => $_POST['cidade'],

                    'valor_aluguel' => $_POST['valor_aluguel'],
                    'valor_venda' => $_POST['valor_venda']
                ];
                
                try {
                    $imoveis = buscarImoveis($filtros);
                    $imovelIndex = 0;

                    if (!empty($imoveis)) {
                        foreach ($imoveis as $imovel) {
                            echo "<div class='imovel slideshow-container-resultado'>";
                            $fotos = getFotosImovel("fotosImoveis/" . $imovel['id'] . "/");
                            $fotoIndex = 0;
                            foreach ($fotos as $foto) {
                                $activeClass = $fotoIndex === 0 ? 'active-slide' : '';
                                echo "<div class='mySlides $activeClass'>";
                                echo "<img src='" . htmlspecialchars($foto) . "' alt='Foto do Imóvel'>";
                                echo "</div>";
                                $fotoIndex++;
                            }
                            echo "<a class='prev' onclick='plusSlides(-1, $imovelIndex)'>&#10094;</a>";
                            echo "<a class='next' onclick='plusSlides(1, $imovelIndex)'>&#10095;</a>";
                            echo "</div>";
                            echo "<div class='imovel-info'>";
                            echo "<p id='info-imovel-" . $imovel['id'] . "'>Cidade: " . htmlspecialchars($imovel['cidade']) . ", " . htmlspecialchars($imovel['bairro']) . ", aluguel em " . htmlspecialchars($imovel['valor_aluguel']) . " e venda em " . htmlspecialchars($imovel['valor_venda']) . "</p>";
                            echo "<button onclick='copiarEEnviar(" . $imovel['id'] . ", \"" . $imovel['whatsapp'] . "\")' style='display: inline-block; background-color: black; color: white; text-decoration: none; border: none; border-radius: 8px; padding: 0.75em; cursor: pointer; font-size: 0.9em; margin-top: 10px;'>Entrar em contato com o proprietário</button>";
                            echo "</div><br><br>";
                            
                            
                            echo "</div>";
        
                            $imovelIndex++;
                        }
                    } else {
                        echo "<p>Nenhum imóvel encontrado.</p>";
                    }
                } catch (Exception $e) {
                    echo "<p>Erro: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
            }
            ?>
        </div>
    </div>

    <div class="container">




    <h1>Buscas mais populares</h1>




    <h2>Santa Inês</h2>
    <!-- Links para Santa Inês -->
    <a href="javascript:void(0)" onclick="preencherEEnviar('Santa Inês', 'Imóveis à venda')" class="linkCredito">Imóveis à venda em Santa Inês</a><br>
    <a href="javascript:void(0)" onclick="preencherEEnviar('Santa Inês', 'Terrenos à venda')" class="linkCredito">Terrenos à venda em Santa Inês</a><br>
    <a href="javascript:void(0)" onclick="preencherEEnviar('Santa Inês', 'Casa para alugar')" class="linkCredito">Casa para alugar em Santa Inês</a><br>
    <a href="javascript:void(0)" onclick="preencherEEnviar('Santa Inês', 'Aluguel de Apartamento')" class="linkCredito">Aluguel de Apartamento em Santa Inês</a><br>
    <a href="javascript:void(0)" onclick="preencherEEnviar('Santa Inês', 'KitNet para alugar')" class="linkCredito">KitNet para alugar em Santa Inês</a><br>
    <a href="javascript:void(0)" onclick="preencherEEnviar('Santa Inês', 'Casas à venda')" class="linkCredito">Casas à venda em Santa Inês</a><br>
    <a href="javascript:void(0)" onclick="preencherEEnviar('Santa Inês', 'Imóveis para alugar')" class="linkCredito">Imóveis para alugar em Santa Inês</a><br>
    <a href="javascript:void(0)" onclick="preencherEEnviar('Santa Inês', 'Imóveis novos à venda')" class="linkCredito">Imóveis novos à venda em Santa Inês</a><br>


    <h2>Bom Jardim</h2>
    <a href="javascript:void(0)" onclick="preencherEEnviar('Bom Jardim', 'Imóveis à venda')" class="linkCredito">Imóveis à venda em Bom Jardim</a><br>
    <a href="javascript:void(0)" onclick="preencherEEnviar('Bom Jardim', 'Terrenos à venda')" class="linkCredito">Terrenos à venda em Bom Jardim</a><br>
    <a href="javascript:void(0)" onclick="preencherEEnviar('Bom Jardim', 'Casa para alugar')" class="linkCredito">Casa para alugar em Bom Jardim</a><br>
    <a href="javascript:void(0)" onclick="preencherEEnviar('Bom Jardim', 'Aluguel de Apartamento')" class="linkCredito">Aluguel de Apartamento em Bom Jardim</a><br>
    <a href="javascript:void(0)" onclick="preencherEEnviar('Bom Jardim', 'KitNet para alugar')" class="linkCredito">KitNet para alugar em Bom Jardim</a><br>
    <a href="javascript:void(0)" onclick="preencherEEnviar('Bom Jardim', 'Casas à venda')" class="linkCredito">Casas à venda em Bom Jardim</a><br>
    <a href="javascript:void(0)" onclick="preencherEEnviar('Bom Jardim', 'Imóveis para alugar')" class="linkCredito">Imóveis para alugar em Bom Jardim</a><br>
    <a href="javascript:void(0)" onclick="preencherEEnviar('Bom Jardim', 'Imóveis novos à venda')" class="linkCredito">Imóveis novos à venda em Bom Jardim</a><br>

    

</div>
<script src="script.js"></script>
</body>
</html>
