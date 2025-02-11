<?php
session_start();
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_tipo"] != "admin") {
    header("Location: ../../public/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Administrador</title>
</head>
<body>
    <div style="display: flex; justify-content: space-between; padding: 10px;">
        <a href="personal_dashboard.php" style="background: #007BFF; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">🏠 Voltar ao Menu</a>
        <a href="../../public/logout.php" style="background: red; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">🚪 Sair</a>
    </div>

    <h2>Bem-vindo, <?= $_SESSION["usuario_nome"]; ?>!</h2>
    <p>Você está no painel do administrador.</p>
    <a href="cadastrar_admin.php">Criar Novo Administrador</a><br>
    <a href="../../public/logout.php">Sair</a>
</body>
</html>
