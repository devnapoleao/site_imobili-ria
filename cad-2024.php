<?php
include('back/config.php');

function canRegisterProperty($email, $db) {
    $stmt = $db->prepare("SELECT id_cliente, status, imovel_cadastrado FROM clientes WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows === 1) {
        $cliente = $result->fetch_assoc();
        if ($cliente['status'] === 'pago' && !$cliente['imovel_cadastrado']) {
            return $cliente['id_cliente']; // Retorna o ID do cliente se ele pode registrar um imóvel
        }
    }
    return false; // Retorna false se ele não pode registrar
}

function markPropertyAsRegistered($idCliente, $db) {
    $stmt = $db->prepare("UPDATE clientes SET imovel_cadastrado = 1 WHERE id_cliente = ?");
    $stmt->bind_param("i", $idCliente);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = connectDB();

    $email = $db->real_escape_string($_POST['email']);
    $idCliente = canRegisterProperty($email, $db);

    if ($idCliente) {
        $whatsapp = $db->real_escape_string($_POST['whatsapp']);
        $titulo = $db->real_escape_string($_POST['titulo']);
        $cidade = $db->real_escape_string($_POST['cidade']);
        $bairro = $db->real_escape_string($_POST['bairro']);
        $valorAluguel = $db->real_escape_string($_POST['valor_aluguel']);
        $valorVenda = $db->real_escape_string($_POST['valor_venda']);

        $query = "INSERT INTO imoveis (email, whatsapp, titulo, cidade, bairro, valor_aluguel, valor_venda) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sssssss", $email, $whatsapp, $titulo, $cidade, $bairro, $valorAluguel, $valorVenda);
        
        if ($stmt->execute()) {
            $idImovel = $db->insert_id;
            $diretorioImovel = "fotosImoveis/" . $idImovel . "/";
            if (!file_exists($diretorioImovel)) {
                mkdir($diretorioImovel, 0777, true);
            }

            $fotos = array();
            $contador = 1;
            foreach ($_FILES['fotos']['tmp_name'] as $i => $file) {
                if ($_FILES['fotos']['error'][$i] == 0 && move_uploaded_file($file, $diretorioImovel . $contador . '.jpg')) {
                    $fotos[] = $diretorioImovel . $contador . '.jpg';
                    $contador++;
                }
            }

            $fotosSerializadas = serialize($fotos);
            $updateQuery = "UPDATE imoveis SET fotos = ? WHERE id = ?";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bind_param("si", $fotosSerializadas, $idImovel);
            $updateStmt->execute();

            echo "<p>Imóvel cadastrado com sucesso!</p>";
            
            markPropertyAsRegistered($idCliente, $db);
        } else {
            echo "<p>Erro: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Você não tem permissão para cadastrar um imóvel ou já cadastrou um imóvel.</p>";
    }

    $db->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Imóvel</title>
    <meta charset="UTF-8">

    <style>/* Reset CSS Básico */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Estilos Gerais */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    line-height: 1.6;
}

.container {
    width: 90%;
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    margin-bottom: 20px;
}

input[type="text"], input[type="file"], input[type="submit"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
}

input[type="submit"] {
    background-color: #333;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: #555;
}

/* Estilos para o botão de upload */
.upload-btn-wrapper {
    position: relative;
    overflow: hidden;
    display: block;
}

.btn {
    border: 2px solid gray;
    color: gray;
    background-color: white;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 1em; /* Adapta-se ao tamanho da fonte do dispositivo */
    width: 100%;
    text-align: center;
}

.upload-btn-wrapper input[type=file] {
    font-size: 16px; /* Grande o suficiente para ser acessível */
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
    width: 100%;
    height: 100%;
}

/* Estilos Responsivos */
@media screen and (max-width: 768px) {
    .btn, input[type="text"], input[type="file"], input[type="submit"] {
        font-size: 0.9em;
    }
}

@media screen and (max-width: 480px) {
    h1 {
        font-size: 1.2em;
    }

    .container {
        width: 95%;
    }

    .btn, input[type="text"], input[type="file"], input[type="submit"] {
        font-size: 0.8em;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Cadastro de Imóvel</h1>
        <form action="cad-2024.php" method="post" enctype="multipart/form-data">
            <input type="text" name="email" placeholder="E-mail do proprietário" required><br>
            <input type="text" name="whatsapp" placeholder="Whatsapp do Proprietário com DDD" required><br>
            <input type="text" name="cidade" placeholder="Cidade" required><br>
            <input type="text" name="bairro" placeholder="Bairro" required><br>
            <input type="text" name="valor_aluguel" placeholder="Valor de Aluguel" required><br>
            <input type="text" name="valor_venda" placeholder="Valor de Venda" required><br>
            <div class="upload-btn-wrapper">
                <button class="btn">Enviar Fotos do Imóvel</button>
                <input type="file" name="fotos[]" multiple required>
            </div><br>
            <input type="submit" value="Cadastrar">
        </form>
    </div>
</body>
</html>
