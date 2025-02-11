<?php
session_start();
require_once "../../config/database.php";

// Verifica se o personal está logado
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_tipo"] != "personal") {
    header("Location: ../../public/login.php");
    exit();
}

// Recebe o id do aluno
$aluno_id = $_GET['id'];

// Inicia a transação
$pdo->beginTransaction();

try {
    // Exclui os treinos associados ao aluno
    $stmt = $pdo->prepare("DELETE FROM exercicios WHERE treino_id IN (SELECT id FROM treinos WHERE aluno_id = :aluno_id)");
    $stmt->execute(['aluno_id' => $aluno_id]);

    $stmt = $pdo->prepare("DELETE FROM treinos WHERE aluno_id = :aluno_id");
    $stmt->execute(['aluno_id' => $aluno_id]);

    // Exclui os registros de progresso e evolução
    $stmt = $pdo->prepare("DELETE FROM progresso WHERE aluno_id = :aluno_id");
    $stmt->execute(['aluno_id' => $aluno_id]);

    $stmt = $pdo->prepare("DELETE FROM evolucao_alunos WHERE aluno_id = :aluno_id");
    $stmt->execute(['aluno_id' => $aluno_id]);

    // Exclui o aluno
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
    $stmt->execute(['id' => $aluno_id]);

    // Confirma a transação
    $pdo->commit();

    header("Location: personal_dashboard.php?success=Aluno excluído com sucesso!");
    exit();
} catch (Exception $e) {
    // Se houver um erro, desfaz a transação
    $pdo->rollBack();
    echo "Erro ao excluir aluno: " . $e->getMessage();
    exit();
}
?>
