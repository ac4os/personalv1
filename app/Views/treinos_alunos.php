<?php
session_start();
require_once "../../config/database.php";

// Verifica se o usuário está logado e é um aluno
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_tipo"] != "aluno") {
    header("Location: ../../public/login.php");
    exit();
}

$aluno_id = $_SESSION["usuario_id"];

// Busca os treinos do aluno
$stmt = $pdo->prepare("SELECT * FROM treinos WHERE aluno_id = :aluno_id");
$stmt->execute(["aluno_id" => $aluno_id]);
$treinos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Treinos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .treino-box {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .exercicio {
            margin-top: 10px;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 4px;
        }
        .back-link {
            text-decoration: none;
            color: #6c757d;
        }
        .back-link:hover {
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Meus Treinos</h2>

        <?php if (count($treinos) > 0): ?>
            <?php foreach ($treinos as $treino): ?>
                <div class="treino-box">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3><?= htmlspecialchars($treino["nome_treino"]) ?></h3>
                        <small class="text-muted">Criado em: <?= date("d/m/Y", strtotime($treino["data_criacao"])) ?></small>//
                    </div>

                    <?php
                    // Busca os exercícios do treino
                    $stmt_exercicios = $pdo->prepare("SELECT * FROM exercicios WHERE treino_id = :treino_id");
                    $stmt_exercicios->execute(["treino_id" => $treino["id"]]);
                    $exercicios = $stmt_exercicios->fetchAll();
                    ?>

                    <?php if (count($exercicios) > 0): ?>
                        <div class="mt-3">
                            <p><strong>Exercícios:</strong></p>
                            <?php foreach ($exercicios as $exercicio): ?>
                                <div class="exercicio">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= htmlspecialchars($exercicio["nome_exercicio"]) ?></strong>
                                            <small class="text-muted">
                                                (Séries: <?= $exercicio["series"] ?>, Repetições: <?= $exercicio["repeticoes"] ?>, Carga: <?= htmlspecialchars($exercicio["carga"]) ?>)
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Nenhum exercício cadastrado neste treino.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                Nenhum treino cadastrado ainda.
            </div>
        <?php endif; ?>

        <br>
        <a href="dashboard_aluno.php" class="back-link">🏠 Voltar ao Menu</a>
    </div>

    <!-- Bootstrap JS (opcional, se precisar de funcionalidades JS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>