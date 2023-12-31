<?php
include('back/config.php'); // Inclui o arquivo de configuração

function getFotosImovel($diretorio) {
    $fotos = [];
    if (file_exists($diretorio) && is_dir($diretorio)) {
        $arquivos = scandir($diretorio);
        foreach ($arquivos as $arquivo) {
            if ($arquivo !== '.' && $arquivo !== '..') {
                $fotos[] = $diretorio . $arquivo;
            }
        }
    }
    sort($fotos); // Ordena as fotos em ordem crescente
    return $fotos;
}

function buscarImoveis($filtros) {
    $db = connectDB(); // Esta função é definida em config.php
    $condicoes = [];

    foreach ($filtros as $campo => $valor) {
        if (!empty($valor)) {
            $valor = $db->real_escape_string($valor);
            if (in_array($campo, ['cidade', 'bairro'])) {
                $condicoes[] = "$campo LIKE '%$valor%'";
            } else if ($campo == 'valor_aluguel' || $campo == 'valor_venda') {
                $condicoes[] = "$campo <= '$valor'";
            }
        }
    }

    $query = "SELECT * FROM imoveis";
    if (!empty($condicoes)) {
        $query .= " WHERE " . join(" AND ", $condicoes);
    }

    $imoveis = [];
    if ($resultado = $db->query($query)) {
        while($imovel = $resultado->fetch_assoc()) {
            $imoveis[] = $imovel;
        }
    } else {
        echo "Erro na consulta: " . $db->error;
    }

    $db->close();
    return $imoveis;
}


?>
