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

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Personal Trainer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        h2 {
            color: #333;
        }
        .menu {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .menu a {
            display: block;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .menu a:hover {
            background-color: #0056b3;
        }
        .logout {
            margin-top: 20px;
            background-color: red;
        }
        .logout:hover {
            background-color: darkred;
        }
        .success-message {
            background-color: #28a745;
            color: white;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        /* Estilos para o pop-up */
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .popup-content {
            background: white;
            padding: 20px;
            border-radius: 5px;
            width: 300px;
            text-align: center;
        }
        .popup input {
            margin: 10px 0;
            padding: 10px;
            width: 80%;
        }
        .popup button {
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .popup button:hover {
            background-color: #0056b3;
        }
        .close-btn {
            background-color: red;
        }
        .close-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <div style="display: flex; justify-content: space-between; padding: 10px;">
        <a href="personal_dashboard.php" style="background: #007BFF; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">🏠 Voltar ao Menu</a>
        <a href="../../public/logout.php" style="background: red; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">🚪 Sair</a>
    </div>
    <div class="container">
        <h2>Bem-vindo, <?= htmlspecialchars($personal["nome"]) ?>!</h2>
        <p>O que deseja fazer hoje?</p>

        <!-- Exibindo a mensagem de sucesso -->
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                <?= htmlspecialchars($_GET['success']) ?>
            </div>
        <?php endif; ?>

        <div class="menu">
            <a href="javascript:void(0);" onclick="openPopup()">Cadastrar Novo Aluno</a>
            <a href="listar_alunos.php">Gerenciar Alunos</a>
            <a href="selecionar_aluno.php">Visualizar Evolução dos Alunos</a>
            <a href="prescricao_treinos.php">Prescrever Treinos</a>
            <a href="relatorios.php">Relatórios e Estatísticas</a>
            <a href="../../public/logout.php" class="logout">Sair</a>
        </div>
    </div>

    <!-- Pop-up para cadastrar aluno -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <h3>Cadastrar Aluno</h3>
            <form id="formCadastrarAluno" action="cadastrar_aluno.php" method="POST">
                <input type="text" id="nome" name="nome" placeholder="Nome" required><br>
                <input type="email" id="email" name="email" placeholder="Email" required><br>
                <input type="text" id="senha" name="senha" placeholder="Senha" readonly><br>
                <button type="button" onclick="gerarSenha()">Gerar Senha Aleatória</button><br><br>
                <!-- Envia o ID do personal junto -->
                <input type="hidden" name="personal_id" value="<?= $personal_id ?>">
                <button type="submit">Cadastrar</button>
            </form>
            <button class="close-btn" onclick="closePopup()">Fechar</button>
        </div>
    </div>

    <script>
        // Função para abrir o pop-up
        function openPopup() {
            document.getElementById("popup").style.display = "flex";
        }

        // Função para fechar o pop-up
        function closePopup() {
            document.getElementById("popup").style.display = "none";
        }

        // Função para gerar senha aleatória de 4 dígitos
        function gerarSenha() {
            const senha = Math.floor(1000 + Math.random() * 9000); // Gera um número aleatório de 4 dígitos
            document.getElementById("senha").value = senha;
        }
    </script>

</body>
</html>
