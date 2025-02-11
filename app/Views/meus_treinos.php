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
    <title>Ficha de Treinos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
            max-width: 800px;
        }
        .ficha-treino {
            background-color: #fff;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .ficha-treino h3 {
            color: #0d6efd;
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .ficha-treino small {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .exercicio {
            margin-bottom: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        .exercicio strong {
            color: #212529;
            font-size: 1.1rem;
        }
        .exercicio .detalhes {
            color: #6c757d;
            font-size: 0.95rem;
            margin-top: 5px;
        }
        .btn-voltar {
            background-color: #0d6efd;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .btn-voltar:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Ficha de Treinos</h1>

        <?php if (count($treinos) > 0): ?>
            <?php foreach ($treinos as $treino): ?>
                <div class="ficha-treino">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3><?= htmlspecialchars($treino["nome_treino"]) ?></h3>
                        <small>Criado em: <?= date("d/m/Y", strtotime($treino["data_criacao"])) ?></small>
                    </div>

                    <?php
                    // Busca os exercícios do treino
                    $stmt_exercicios = $pdo->prepare("SELECT * FROM exercicios WHERE treino_id = :treino_id");
                    $stmt_exercicios->execute(["treino_id" => $treino["id"]]);
                    $exercicios = $stmt_exercicios->fetchAll();
                    ?>

                    <?php if (count($exercicios) > 0): ?>
                        <?php foreach ($exercicios as $exercicio): ?>
                            <div class="exercicio">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= htmlspecialchars($exercicio["nome_exercicio"]) ?></strong>
                                        <div class="detalhes">
                                            Séries: <?= $exercicio["series"] ?> | Repetições: <?= $exercicio["repeticoes"] ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-warning" role="alert">
                            Nenhum exercício cadastrado neste treino.
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                Nenhum treino cadastrado ainda.
            </div>
        <?php endif; ?>

        <a href="aluno_dashboard.php" class="btn-voltar">🏠 Voltar ao Menu</a>
    </div>

    <!-- Bootstrap JS (opcional, se precisar de funcionalidades JS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>