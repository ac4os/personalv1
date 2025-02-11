<?php
session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../../public/login.php");
    exit();
}

$aluno_id = $_SESSION["usuario_id"];

$stmt = $pdo->prepare("SELECT * FROM evolucao_alunos WHERE aluno_id = :aluno_id ORDER BY data_registro ASC");
$stmt->execute(["aluno_id" => $aluno_id]);
$evolucao = $stmt->fetchAll();

// Preparar os dados para o gráfico
$datas = [];
$peso = [];
$gordura = [];
$braco = [];
$perna = [];
$cintura = [];

foreach ($evolucao as $registro) {
    $datas[] = date("d/m", strtotime($registro["data_registro"]));
    $peso[] = $registro["peso"];
    $gordura[] = $registro["percentual_gordura"];
    $braco[] = $registro["braco"];
    $perna[] = $registro["perna"];
    $cintura[] = $registro["cintura"];
}

// Converter os dados para JSON para uso no JavaScript
$datas_json = json_encode($datas);
$peso_json = json_encode($peso);
$gordura_json = json_encode($gordura);
$braco_json = json_encode($braco);
$perna_json = json_encode($perna);
$cintura_json = json_encode($cintura);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minha Evolução</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div style="display: flex; justify-content: space-between; padding: 10px;">
        <a href="personal_dashboard.php" style="background: #007BFF; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">🏠 Voltar ao Menu</a>
        <a href="../../public/logout.php" style="background: red; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">🚪 Sair</a>
    </div>

    <h2>Minha Evolução</h2>

    <canvas id="graficoEvolucao"></canvas>

    <script>
        const ctx = document.getElementById('graficoEvolucao').getContext('2d');
        const graficoEvolucao = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= $datas_json ?>,
                datasets: [
                    {
                        label: 'Peso (kg)',
                        data: <?= $peso_json ?>,
                        borderColor: 'blue',
                        backgroundColor: 'rgba(0, 0, 255, 0.2)',
                        fill: true
                    },
                    {
                        label: '% Gordura',
                        data: <?= $gordura_json ?>,
                        borderColor: 'red',
                        backgroundColor: 'rgba(255, 0, 0, 0.2)',
                        fill: true
                    },
                    {
                        label: 'Braço (cm)',
                        data: <?= $braco_json ?>,
                        borderColor: 'green',
                        backgroundColor: 'rgba(0, 255, 0, 0.2)',
                        fill: true
                    },
                    {
                        label: 'Perna (cm)',
                        data: <?= $perna_json ?>,
                        borderColor: 'purple',
                        backgroundColor: 'rgba(128, 0, 128, 0.2)',
                        fill: true
                    },
                    {
                        label: 'Cintura (cm)',
                        data: <?= $cintura_json ?>,
                        borderColor: 'orange',
                        backgroundColor: 'rgba(255, 165, 0, 0.2)',
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: { title: { display: true, text: 'Data' } },
                    y: { title: { display: true, text: 'Valores' } }
                }
            }
        });
    </script>

</body>
</html>
