<?php
session_start();
require_once "../../config/database.php";

// Verifica se é um personal trainer
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_tipo"] != "personal") {
    header("Location: ../../public/login.php");
    exit();
}

// Recebe os dados do formulário
$aluno_id = $_POST["aluno_id"];
$nome_treino = $_POST["nome_treino"];
$exercicios = $_POST["exercicios"];
$series = $_POST["series"];
$repeticoes = $_POST["repeticoes"];
$carga = $_POST["carga"];
$personal_id = $_SESSION["usuario_id"];

// Cria o treino e associa ao aluno e ao personal
$stmt = $pdo->prepare("INSERT INTO treinos (aluno_id, personal_id, nome_treino) VALUES (:aluno_id, :personal_id, :nome_treino)");
$stmt->execute([
    "aluno_id" => $aluno_id,
    "personal_id" => $personal_id,  // Esse campo vai agora funcionar após adicionar a coluna no banco
    "nome_treino" => $nome_treino
]);

$treino_id = $pdo->lastInsertId(); // Obtém o ID do treino recém-criado

// Salva os exercícios associados ao treino
$stmt = $pdo->prepare("INSERT INTO exercicios (treino_id, nome_exercicio, series, repeticoes, carga) VALUES (:treino_id, :nome_exercicio, :series, :repeticoes, :carga)");

for ($i = 0; $i < count($exercicios); $i++) {
    $stmt->execute([
        "treino_id" => $treino_id,
        "nome_exercicio" => $exercicios[$i],  // Agora, após a alteração no banco, esse campo vai funcionar
        "series" => $series[$i],
        "repeticoes" => $repeticoes[$i],
        "carga" => $carga[$i]
    ]);
}


// Redireciona para o painel do personal com mensagem de sucesso
header("Location: ../Views/personal_dashboard.php?success=Treino cadastrado!");
exit();
?>
