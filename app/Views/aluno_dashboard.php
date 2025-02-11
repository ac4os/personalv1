<?php
session_start();
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_tipo"] != "aluno") {
    header("Location: ../../public/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Área do Aluno</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #007BFF;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-size: 1rem;
        }
        .navbar a:hover {
            opacity: 0.8;
        }
        .container {
            margin-top: 50px;
            text-align: center;
        }
        .welcome-message {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
        }
        .btn-custom {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
            transition: background-color 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
            transition: background-color 0.3s ease;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>


    <!-- Conteúdo principal -->
    <div class="container">
        <h1 class="welcome-message">Bem-vindo, <?php echo htmlspecialchars($_SESSION["usuario_nome"]); ?>!</h1>
        <p class="lead">Você está na área do aluno.</p>

        <div class="mt-4">
            <a href="../Views/meus_treinos.php" class="btn-custom">📋 Meus Treinos</a>
            <a href="../../public/logout.php" class="btn-danger">🚪 Sair</a>
        </div>
    </div>

    <!-- Bootstrap JS (opcional, se precisar de funcionalidades JS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>