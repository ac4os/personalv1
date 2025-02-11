<?php
session_start();
require_once "../../config/database.php";

// Verifica se o usuário está logado e se é personal trainer
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_tipo"] != "personal") {
    header("Location: ../../public/login.php");
    exit();
}

// Buscar alunos no banco
$stmt = $pdo->query("SELECT id, nome FROM usuarios WHERE tipo = 'aluno'");
$alunos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Prescrição de Treinos</title>
</head>
<body>
    <h2>Prescrever Novo Treino</h2>
    
    <!-- Formulário para criar o treino -->
    <form action="../Actions/salvar_treino.php" method="post">


        <label>Selecione o Aluno:</label>
        <select name="aluno_id" required>
            <?php foreach ($alunos as $aluno): ?>
                <option value="<?= $aluno['id'] ?>"><?= htmlspecialchars($aluno['nome']) ?></option>
            <?php endforeach; ?>
        </select>
        
        <label>Nome do Treino:</label>
        <input type="text" name="nome_treino" required>
        
        <h3>Adicionar Exercícios</h3>
        <div id="exercicios">
            <div>
                <input type="text" name="exercicios[]" placeholder="Nome do Exercício" required>
                <input type="number" name="series[]" placeholder="Séries" required>
                <input type="number" name="repeticoes[]" placeholder="Repetições" required>
                <input type="text" name="carga[]" placeholder="Carga (opcional)">
            </div>
        </div>
        
        <button type="button" onclick="adicionarExercicio()">+ Adicionar Exercício</button>
        <button type="submit">Salvar Treino</button>
    </form>

    <script>
        function adicionarExercicio() {
            let div = document.createElement("div");
            div.innerHTML = `<input type="text" name="exercicios[]" placeholder="Nome do Exercício" required>
                             <input type="number" name="series[]" placeholder="Séries" required>
                             <input type="number" name="repeticoes[]" placeholder="Repetições" required>
                             <input type="text" name="carga[]" placeholder="Carga (opcional)">
                             <button type="button" onclick="this.parentElement.remove()">❌</button>`;
            document.getElementById("exercicios").appendChild(div);
        }
    </script>

    <br>
    <a href="personal_dashboard.php">🏠 Voltar ao Menu</a>
</body>
</html>
