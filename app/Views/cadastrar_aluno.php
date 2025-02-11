<?php
session_start();
require_once "../../config/database.php";

// Verifica se o usuário está logado e se é personal trainer
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_tipo"] != "personal") {
    header("Location: ../../public/login.php");
    exit();
}

// Verifica se os dados foram enviados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta os dados do formulário
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $personal_id = $_POST["personal_id"];

    // Verifica se o email já existe no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->execute(["email" => $email]);
    if ($stmt->rowCount() > 0) {
        // Se o email já existir, exibe uma mensagem de erro
        header("Location: personal_dashboard.php?error=Email já cadastrado!");
        exit();
    }

    // Insere o aluno no banco de dados
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo, personal_id) VALUES (:nome, :email, :senha, 'aluno', :personal_id)");
    $stmt->execute([
        "nome" => $nome,
        "email" => $email,
        "senha" => password_hash($senha, PASSWORD_DEFAULT), // Criptografa a senha
        "personal_id" => $personal_id
    ]);

    // Redireciona para o painel com uma mensagem de sucesso
    header("Location: personal_dashboard.php?success=Aluno cadastrado com sucesso!");
    exit();
}
?>
