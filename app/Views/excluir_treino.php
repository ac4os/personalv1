<?php
session_start();
require_once "../../config/database.php";

// Verifica se o usuário está logado e se é personal trainer
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_tipo"] != "personal") {
    header("Location: ../../public/login.php");
    exit();
}

// Pega o ID do treino a ser excluído
$treino_id = $_GET['id'] ?? null;
$aluno_id = $_GET['aluno_id'] ?? null;

if (!$treino_id || !$aluno_id) {
    header("Location: listar_alunos.php");
    exit();
}

// Excluir o treino
$stmt = $pdo->prepare("DELETE FROM treinos WHERE id = :id AND aluno_id = :aluno_id");
$stmt->execute(["id" => $treino_id, "aluno_id" => $aluno_id]);

header("Location: editar_aluno.php?id=$aluno_id");
exit();
?>
