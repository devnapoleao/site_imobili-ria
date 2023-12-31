<?php
// Inclui o arquivo de configuração para a conexão com o banco de dados
require_once 'config.php';

// Estabelece a conexão com o banco de dados
$db = connectDB();

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebendo e limpando os dados do formulário
    $titulo = $db->real_escape_string($_POST['titulo']);
    $cidade = $db->real_escape_string($_POST['cidade']);
    $bairro = $db->real_escape_string($_POST['bairro']);
    $valorAluguel = $db->real_escape_string($_POST['valor_aluguel']);
    $valorVenda = $db->real_escape_string($_POST['valor_venda']);
    $descricao = $db->real_escape_string($_POST['descricao']);

    // Tratamento das fotos (aqui seria implementada a lógica de upload e armazenamento das fotos)

    // Montagem e execução da consulta SQL para inserir os dados
    $sql = "INSERT INTO imoveis (titulo, cidade, bairro, valor_aluguel, valor_venda, descricao) VALUES ('$titulo', '$cidade', '$bairro', '$valorAluguel', '$valorVenda', '$descricao')";
    
    if($db->query($sql) === TRUE) {
        echo "Imóvel cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar o imóvel: " . $db->error;
    }
}

// Fechar conexão
$db->close();
?>
