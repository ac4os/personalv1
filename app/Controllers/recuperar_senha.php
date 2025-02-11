<?php
require_once "../../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Verificar se o email existe no banco
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->execute(["email" => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Gerar um token único para redefinição de senha
        $token = bin2hex(random_bytes(20));

        // Salvar o token no banco de dados
        $stmt = $pdo->prepare("UPDATE usuarios SET token_recuperacao = :token WHERE email = :email");
        $stmt->execute(["token" => $token, "email" => $email]);

        // Criar o link de redefinição
        $link = "http://localhost/Gym/app/Views/redefinir_senha.php?token=" . $token;

        // Mensagem inicial para perguntar o WhatsApp do usuário
        $mensagem_wpp = "Olá! Para recuperar sua senha, por favor, me informe o seu número de WhatsApp.";

        // Criar o link do WhatsApp Web para perguntar o número
        $whatsapp_link = "https://api.whatsapp.com/send?phone=+5527999089882&text=" . urlencode($mensagem_wpp);

        // Responder com o link para o WhatsApp
        echo json_encode(["success" => true, "link" => $whatsapp_link]);
    } else {
        echo json_encode(["success" => false, "message" => "E-mail não encontrado!"]);
    }
}
