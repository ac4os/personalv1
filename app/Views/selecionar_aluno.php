<?php
session_start();
require_once "../../config/database.php";

// Verifica se o usuário é um personal
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_tipo"] != "personal") {
    header("Location: ../../public/login.php");
    exit();
}

// Buscar alunos no banco de dados
$alunos = $pdo->query("SELECT id, nome FROM usuarios WHERE tipo = 'aluno'")->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Selecionar Aluno</title>
</head>
<body>

    <div style="display: flex; justify-content: space-between; padding: 10px;">
        <a href="personal_dashboard.php" style="background: #007BFF; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">🏠 Voltar ao Menu</a>
        <a href="../../public/logout.php" style="background: red; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">🚪 Sair</a>
    </div>
    <h2>Selecione um Aluno para Visualizar a Evolução</h2>
    <form action="ver_evolucao_aluno.php" method="GET">
        <label>Escolha um aluno:</label>
        <select name="aluno_id" required>
            <?php foreach ($alunos as $aluno) { ?>
                <option value="<?= $aluno['id'] ?>"><?= $aluno['nome'] ?></option>
            <?php } ?>
        </select>
        <button type="submit">Visualizar Evolução</button>
    </form>
</body>
</html>
