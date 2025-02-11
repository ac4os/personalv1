<?php
require_once "config/database.php";

$nome = "Administrador";
$email = "admin@email.com";
$senha = "admin123";
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);
$tipo = "admin";

$stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)");
$stmt->execute(["nome" => $nome, "email" => $email, "senha" => $senha_hash, "tipo" => $tipo]);

echo "Usuário administrador criado com sucesso!";
?>
