<?php
$link = $_GET['link'] ?? '';
$email = $_GET['email'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $whatsapp = $_POST["whatsapp"];

    // Verificar se o link e o e-mail foram passados corretamente
    if (empty($link) || empty($email)) {
        die("Link ou e-mail ausente. Tente novamente.");
    }

    // Validar e formatar o número de WhatsApp para o padrão internacional
    if (empty($whatsapp) || !preg_match("/^\+?[1-9]\d{1,14}$/", $whatsapp)) {
        die("Número de WhatsApp inválido. Insira um número válido no formato internacional.");
    }

    // Criar a mensagem com o link de redefinição de senha
    $mensagem = "Olá! Você solicitou a redefinição de senha. Clique no link para redefinir: " . $link;

    // Criar o link do WhatsApp
    $whatsapp_url = "https://api.whatsapp.com/send?phone=" . $whatsapp . "&text=" . urlencode($mensagem);

    // Redirecionar para o WhatsApp
    header("Location: " . $whatsapp_url);
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recuperação via WhatsApp</title>
</head>
<body>
    <h2>Informe seu número de WhatsApp</h2>
    <form method="POST">
        <label>Digite seu número do WhatsApp:</label>
        <input type="text" name="whatsapp" placeholder="Ex: +5511998765432" required>
        <button type="submit">Enviar Link pelo WhatsApp</button>
    </form>
</body>
</html>
