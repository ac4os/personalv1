<?php
require_once "../../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST["token"];
    $nova_senha = password_hash($_POST["nova_senha"], PASSWORD_DEFAULT);

    // Verificar se o token existe
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE token_recuperacao = :token");
    $stmt->execute(["token" => $token]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Atualizar a senha e remover o token
        $stmt = $pdo->prepare("UPDATE usuarios SET senha = :senha, token_recuperacao = NULL WHERE token_recuperacao = :token");
        $stmt->execute(["senha" => $nova_senha, "token" => $token]);

        echo "Senha redefinida com sucesso!";
    } else {
        echo "Token inválido!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
</head>
<body>
    <h2>Redefinir Senha</h2>
    <form method="POST">
        <input type="hidden" name="token" value="<?= $_GET['token'] ?? '' ?>">
        <label>Nova Senha:</label>
        <input type="password" name="nova_senha" required>
        <button type="submit">Redefinir Senha</button>
    </form>
</body>
</html>
