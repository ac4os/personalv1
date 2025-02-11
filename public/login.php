<?php
session_start();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    // Verificar se o email existe no banco
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->execute(["email" => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario["senha"])) {
        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["usuario_nome"] = $usuario["nome"];
        $_SESSION["usuario_tipo"] = $usuario["tipo"];

        // Redirecionamento baseado no tipo de usuário
        if ($usuario["tipo"] == "admin") {
            header("Location: ../app/Views/admin_dashboard.php");
            exit();
        } elseif ($usuario["tipo"] == "personal") {
            header("Location: ../app/Views/personal_dashboard.php");
            exit();
        } else {
            header("Location: ../app/Views/aluno_dashboard.php");
            exit();
        }
    } else {
        $erro = "Email ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema do Personal Trainer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f2f2f2;
        }
        .login-container {
            width: 400px;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .error-message {
            color: #ff4d4d;
            margin-bottom: 20px;
        }
        .footer-links a {
            color: #555;
            text-decoration: none;
            margin: 0 10px;
        }
        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if (isset($erro)): ?>
            <div class="error-message"> <?= $erro ?> </div>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit" class="btn btn-dark w-100 mt-3">Entrar</button>
        </form>
        <div class="footer-links mt-3">
            <a href="#" data-bs-toggle="modal" data-bs-target="#esqueciSenhaModal">Esqueci minha senha</a>
        </div>
    </div>

    <!-- Modal de Recuperação de Senha -->
    <div class="modal fade" id="esqueciSenhaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Recuperar Senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Digite seu e-mail para receber o link de redefinição de senha.</p>
                    <form id="recuperarSenhaForm">
                        <input type="email" id="emailRecuperacao" class="form-control" required>
                        <button type="submit" class="btn btn-primary mt-3">Enviar</button>
                    </form>
                    <p id="mensagemRecuperacao" class="mt-2"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById("recuperarSenhaForm").addEventListener("submit", function(event) {
        event.preventDefault();
        var email = document.getElementById("emailRecuperacao").value;
        
        fetch("../app/Controllers/recuperar_senha.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "email=" + encodeURIComponent(email)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.link; // Redireciona para o WhatsApp
            } else {
                document.getElementById("mensagemRecuperacao").innerText = data.message;
            }
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
