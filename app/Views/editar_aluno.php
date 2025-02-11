<?php
session_start();
require_once "../../config/database.php";

// Verifica se o usuário está logado e se é personal trainer
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_tipo"] != "personal") {
    header("Location: ../../public/login.php");
    exit();
}

// Verifica se o ID do aluno foi passado pela URL
$aluno_id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
if ($aluno_id <= 0) {
    die("ID do aluno inválido.");
}

$personal_id = $_SESSION["usuario_id"];

// Buscar os dados do aluno
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id AND personal_id = :personal_id");
$stmt->execute(["id" => $aluno_id, "personal_id" => $personal_id]);
$aluno = $stmt->fetch();

if (!$aluno) {
    die("Aluno não encontrado ou não pertence a este personal.");
}

// Buscar os treinos do aluno
$stmt = $pdo->prepare("SELECT * FROM treinos WHERE aluno_id = :aluno_id");
$stmt->execute(["aluno_id" => $aluno_id]);
$treinos = $stmt->fetchAll();

// Buscar exercícios de cada treino
$exercicios_por_treino = [];
foreach ($treinos as $treino) {
    $stmt = $pdo->prepare("SELECT * FROM exercicios WHERE treino_id = :treino_id");
    $stmt->execute(["treino_id" => $treino["id"]]);
    $exercicios_por_treino[$treino["id"]] = $stmt->fetchAll();
}

// Processar edição do aluno
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"] ?? "";
    $email = $_POST["email"] ?? "";

    if (!empty($nome) && !empty($email)) {
        $update_stmt = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id AND personal_id = :personal_id");
        $update_stmt->execute([
            "nome" => $nome,
            "email" => $email,
            "id" => $aluno_id,
            "personal_id" => $personal_id
        ]);

        header("Location: listar_alunos.php");
        exit();
    } else {
        echo "<p style='color: red;'>Todos os campos são obrigatórios.</p>";
    }
}

// Função para excluir treino
if (isset($_GET["excluir_treino"])) {
    $treino_id = intval($_GET["excluir_treino"]);
    $stmt = $pdo->prepare("DELETE FROM treinos WHERE id = :treino_id AND aluno_id = :aluno_id");
    $stmt->execute(["treino_id" => $treino_id, "aluno_id" => $aluno_id]);

    header("Location: editar_aluno.php?id=" . $aluno_id);
    exit();
}

// Função para excluir exercício
if (isset($_GET["excluir_exercicio"])) {
    $exercicio_id = intval($_GET["excluir_exercicio"]);
    $stmt = $pdo->prepare("DELETE FROM exercicios WHERE id = :exercicio_id");
    $stmt->execute(["exercicio_id" => $exercicio_id]);

    header("Location: editar_aluno.php?id=" . $aluno_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Aluno</title>
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
        .delete-link {
            color: #dc3545;
            text-decoration: none;
            margin-left: 10px;
        }
        .delete-link:hover {
            text-decoration: underline;
        }
        .exercicio {
            margin-top: 10px;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Editar Aluno</h2>
        <form method="POST" class="mb-5">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($aluno['nome'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($aluno['email'] ?? '') ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </form>

        <h3 class="mb-4">Treinos do Aluno</h3>
        <div class="treinos">
            <?php if (empty($treinos)): ?>
                <p class="text-muted">Este aluno ainda não possui treinos cadastrados.</p>
            <?php else: ?>
                <?php foreach ($treinos as $treino): ?>
                    <div class="treino-box">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong><?= htmlspecialchars($treino["nome_treino"]) ?></strong>
                            <a href="?id=<?= $aluno_id ?>&excluir_treino=<?= $treino["id"] ?>" class="delete-link" onclick="return confirm('Excluir este treino?')">Excluir</a>
                        </div>
                        <?php if (!empty($exercicios_por_treino[$treino["id"]])): ?>
                            <div class="mt-3">
                                <p><strong>Exercícios:</strong></p>
                                <?php foreach ($exercicios_por_treino[$treino["id"]] as $exercicio): ?>
                                    <div class="exercicio d-flex justify-content-between align-items-center">
                                        <div>
                                            <?= htmlspecialchars($exercicio["nome_exercicio"]) ?>
                                            <small class="text-muted">(Séries: <?= htmlspecialchars($exercicio["series"]) ?>, Repetições: <?= htmlspecialchars($exercicio["repeticoes"]) ?>)</small>
                                        </div>
                                        <a href="?id=<?= $aluno_id ?>&excluir_exercicio=<?= $exercicio["id"] ?>" class="delete-link" onclick="return confirm('Excluir este exercício?')">Excluir</a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">Sem exercícios cadastrados.</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <a href="listar_alunos.php" class="btn btn-secondary mt-4">Voltar</a>
    </div>

    <!-- Bootstrap JS (opcional, se precisar de funcionalidades JS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>