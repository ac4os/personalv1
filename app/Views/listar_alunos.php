<?php
session_start();
require_once "../../config/database.php";

// Verifica se o usuário está logado e se é personal trainer
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_tipo"] != "personal") {
    header("Location: ../../public/login.php");
    exit();
}

// Buscar o nome do personal no banco de dados
$personal_id = $_SESSION["usuario_id"];
$stmt = $pdo->prepare("SELECT nome FROM usuarios WHERE id = :id");
$stmt->execute(["id" => $personal_id]);
$personal = $stmt->fetch();

// Buscar os alunos associados ao personal trainer
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE tipo = 'aluno' AND personal_id = :personal_id ORDER BY nome ASC");
$stmt->execute(["personal_id" => $personal_id]);
$alunos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Alunos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        .menu {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .menu a {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }
        .menu a:hover {
            background-color: #0056b3;
        }
        .logout {
            background-color: red;
        }
        .logout:hover {
            background-color: darkred;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        .actions a {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            display: inline-block;
        }
        .edit {
            background-color: #28a745;
            color: white;
        }
        .edit:hover {
            background-color: #218838;
        }
        .delete {
            background-color: red;
            color: white;
        }
        .delete:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

    <div class="menu">
        <a href="personal_dashboard.php">🏠 Voltar ao Menu</a>
        <a href="../../public/logout.php" class="logout">🚪 Sair</a>
    </div>

    <div class="container">
        <h2>Alunos de <?= htmlspecialchars($personal["nome"]) ?></h2>

        <?php if (count($alunos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alunos as $aluno): ?>
                        <tr>
                            <td><?= htmlspecialchars($aluno["nome"]) ?></td>
                            <td><?= htmlspecialchars($aluno["email"]) ?></td>
                            <td class="actions">
                                <a href="editar_aluno.php?id=<?= $aluno['id'] ?>" class="edit">✏️ Editar</a>
                                <a href="excluir_aluno.php?id=<?= $aluno['id'] ?>" class="delete" onclick="return confirm('Tem certeza que deseja excluir?')">🗑️ Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Você ainda não cadastrou nenhum aluno.</p>
        <?php endif; ?>
    </div>

</body>
</html>
