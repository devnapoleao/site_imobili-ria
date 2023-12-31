<?php
// Conexão com o banco de dados
$db = new mysqli('host', 'usuario', 'senha', 'nome_do_banco');
if($db->connect_error) {
    die("Conexão falhou: " . $db->connect_error);
}

// Verifica se o ID foi passado
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $db->real_escape_string($_GET['id']);

    // Consulta para buscar o imóvel pelo ID
    $query = "SELECT * FROM imoveis WHERE id = $id";
    $resultado = $db->query($query);

    if($resultado->num_rows > 0) {
        // Obter dados do imóvel
        $imovel = $resultado->fetch_assoc();
    } else {
        echo "Imóvel não encontrado.";
        exit;
    }
} else {
    echo "ID inválido.";
    exit;
}

// Armazenando os dados em um array
$dadosImovel = [
    'titulo' => $imovel['titulo'],
    'cidade' => $imovel['cidade'],
    'bairro' => $imovel['bairro'],
    'valor_aluguel' => $imovel['valor_aluguel'],
    'valor_venda' => $imovel['valor_venda'],
    'descricao' => $imovel['descricao'],
    'fotos' => explode(',', $imovel['fotos']) // Supondo que as fotos sejam separadas por vírgulas
];

// Fechar conexão
$db->close();

// Encaminhar os dados para o arquivo HTML
include('detalheImovel.html');
?>
