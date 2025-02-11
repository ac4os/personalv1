<?php
session_start();
require_once "../../config/database.php";

// Verifica se o personal está logado
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_tipo"] != "personal") {
    header("Location: ../../public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aluno_id = $_POST["aluno_id"];
    $peso = $_POST["peso"];
    $percentual_gordura = $_POST["percentual_gordura"];
    $braco = $_POST["braco"];
    $perna = $_POST["perna"];
    $cintura = $_POST["cintura"];

    $stmt = $pdo->prepare("INSERT INTO evolucao_alunos (aluno_id, peso, percentual_gordura, braco, perna, cintura) 
                           VALUES (:aluno_id, :peso, :percentual_gordura, :braco, :perna, :cintura)");
    $stmt->execute([
        "aluno_id" => $aluno_id,
        "peso" => $peso,
        "percentual_gordura" => $percentual_gordura,
        "braco" => $braco,
        "perna" => $perna,
        "cintura" => $cintura
    ]);

    echo "<p style='color: green;'>Evolução registrada com sucesso!</p>";
}

// Busca os alunos para preencher a lista
$alunos = $pdo->query("SELECT id, nome FROM usuarios WHERE tipo = 'aluno'")->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registrar Evolução</title>
</head>
<body>

    <div style="display: flex; justify-content: space-between; padding: 10px;">
        <a href="personal_dashboard.php" style="background: #007BFF; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">🏠 Voltar ao Menu</a>
        <a href="../../public/logout.php" style="background: red; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">🚪 Sair</a>
    </div>

    <h2>Registrar Evolução do Aluno</h2>
    <form method="POST">
        <label>Aluno:</label>
        <select name="aluno_id" required>
            <?php foreach ($alunos as $aluno) { ?>
                <option value="<?= $aluno['id'] ?>"><?= $aluno['nome'] ?></option>
            <?php } ?>
        </select><br>

        <label>Peso (kg):</label>
        <input type="number" step="0.1" name="peso" required><br>

        <label>% Gordura:</label>
        <input type="number" step="0.1" name="percentual_gordura" required><br>

        <label>Braço (cm):</label>
        <input type="number" step="0.1" name="braco" required><br>

        <label>Perna (cm):</label>
        <input type="number" step="0.1" name="perna" required><br>

        <label>Cintura (cm):</label>
        <input type="number" step="0.1" name="cintura" required><br>

        <button type="submit">Registrar</button>
    </form>
</body>
</html>
