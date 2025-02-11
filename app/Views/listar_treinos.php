<?php
session_start();
require_once "../../config/database.php";

// Verifica se o usuário está logado e é um personal
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_tipo"] != "personal") {
    header("Location: ../../public/login.php");
    exit();
}

// Buscar todos os treinos do personal
$stmt = $pdo->prepare("SELECT * FROM treinos WHERE personal_id = :personal_id");
$stmt->execute(["personal_id" => $_SESSION["usuario_id"]]);
$treinos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Treinos</title>
</head>
<body>
    <h2>Treinos Cadastrados</h2>

    <?php if (count($treinos) > 0): ?>
        <ul>
            <?php foreach ($treinos as $treino): ?>
                <li>
                    <strong><?= htmlspecialchars($treino["nome_treino"]) ?></strong>
                    - Criado em: <?= $treino["data_criacao"] ?>
                    <a href="visualizar_treino.php?id=<?= $treino["id"] ?>">Ver Treino</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nenhum treino cadastrado.</p>
    <?php endif; ?>

    <br>
    <a href="personal_dashboard.php">🏠 Voltar ao Menu</a>
</body>
</html>
