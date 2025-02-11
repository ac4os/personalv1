<?php
session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../../public/login.php");
    exit();
}

$treino_id = $_GET["id"];

$stmt = $pdo->prepare("SELECT treinos.*, usuarios.nome AS aluno_nome 
                       FROM treinos 
                       JOIN usuarios ON treinos.aluno_id = usuarios.id
                       WHERE treinos.id = :id");
$stmt->execute(["id" => $treino_id]);
$treino = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$treino) {
    echo "Treino não encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Treino de <?= $treino['aluno_nome'] ?></title>
</head>
<body>

    <div style="display: flex; justify-content: space-between; padding: 10px;">
        <a href="personal_dashboard.php" style="background: #007BFF; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">🏠 Voltar ao Menu</a>
        <a href="../../public/logout.php" style="background: red; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">🚪 Sair</a>
    </div>

    <h2>Treino de <?= $treino['aluno_nome'] ?></h2>
    <p><strong>Nome do Treino:</strong> <?= $treino['nome_treino'] ?></p>
    <p><strong>Descrição:</strong> <?= $treino['descricao'] ?></p>
    <a href="listar_treinos.php">Voltar</a>
</body>
</html>
