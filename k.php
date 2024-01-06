<?php
include 'back/config.php'; // Inclui o arquivo de configuração do banco de dados

$secretToken = 'b3btp7nasl3'; // Seu token secreto da Kiwify.

// Função para verificar a assinatura do webhook
function verifyWebhookSignature($payload, $signature, $secretToken) {
    return $signature === '' || hash_equals(hash_hmac('sha256', $payload, $secretToken), $signature);
}

// Função para salvar os dados do cliente no banco de dados
function saveClientData($name, $email, $status, $conn) {
    $stmt = $conn->prepare("INSERT INTO clientes (nome, email, status, timestamp) VALUES (?, ?, ?, CURRENT_TIMESTAMP)");
    $stmt->bind_param("sss", $name, $email, $status);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Lê o corpo da requisição
$payload = file_get_contents('php://input');
$signature = isset($_SERVER['HTTP_X_KIWIFY_SIGNATURE']) ? $_SERVER['HTTP_X_KIWIFY_SIGNATURE'] : '';

// Verifica a assinatura do webhook
if (!verifyWebhookSignature($payload, $signature, $secretToken)) {
    http_response_code(401); // Não autorizado
    exit('Assinatura inválida');
}

// Decodifica o payload JSON
$data = json_decode($payload, true);

// Checa se a compra foi aprovada
if (isset($data['order_status']) && $data['order_status'] === 'paid') {
    $conn = connectDB(); // Conecta-se ao banco de dados

    // Dados do cliente
    $clientName = $data['Customer']['full_name'];
    $clientEmail = $data['Customer']['email'];
    $clientStatus = 'pago';

    // Salva os dados do cliente no banco de dados
    if (saveClientData($clientName, $clientEmail, $clientStatus, $conn)) {
        // Preparar para enviar o e-mail para o administrador do site
        $toEmail = 'contato.napoleao2023@gmail.com';
        $subject = 'Novo Pagamento Aprovado';
        $message = "Um pagamento foi aprovado e os dados do cliente foram salvos no banco de dados.\nNome: $clientName\nE-mail: $clientEmail";
        $headers = 'From: no-reply@seusite.com' . "\r\n" .
                   'Reply-To: no-reply@seusite.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        // Enviar o e-mail
        mail($toEmail, $subject, $message, $headers);
    } else {
        // Handle error - não conseguiu salvar no banco de dados
        // Você pode querer registrar este erro em um arquivo de log ou tomar outras medidas
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
} else {
    http_response_code(400); // Requisição inválida
    exit('Evento não reconhecido ou falta de informações');
}
?>
