<?php
require_once "../config/database.php";

session_start();

// Verifica se o administrador já está logado
if (isset($_SESSION["admin_logado"])) {
    // Se já estiver logado, continua para o restante do código
} else {
    // Se não estiver logado, verifica as credenciais
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["admin_login"])) {
        $admin_usuario = $_POST["admin_usuario"];
        $admin_senha = $_POST["admin_senha"];

        // Busca o administrador no banco de dados
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND tipo = 'admin'");
        $stmt->execute(["email" => $admin_usuario]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($admin_senha, $admin["senha"])) {
            // Credenciais corretas, define a sessão como logado
            $_SESSION["admin_logado"] = true;
        } else {
            // Credenciais incorretas, exibe mensagem de erro
            echo "Credenciais de administrador incorretas. <a href='register.php'>Tentar novamente</a>";
            exit();
        }
    } else {
        // Exibe o formulário de login do administrador
        echo "
        <!DOCTYPE html>
        <html lang='pt-br'>
        <head>
            <meta charset='UTF-8'>
            <title>Login do Administrador</title>
        </head>
        <body>
            <h2>Login do Administrador</h2>
            <form method='POST'>
                <input type='hidden' name='admin_login' value='1'>
                <input type='text' name='admin_usuario' placeholder='Usuário do Admin' required><br>
                <input type='password' name='admin_senha' placeholder='Senha do Admin' required><br>
                <button type='submit'>Entrar</button>
            </form>
        </body>
        </html>
        ";
        exit();
    }
}

// Processa o formulário de registro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nome"])) {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
    $tipo = $_POST["tipo"];

    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)");
    $stmt->execute(["nome" => $nome, "email" => $email, "senha" => $senha, "tipo" => $tipo]);

    echo "Cadastro realizado com sucesso! <a href='login.php'>Fazer login</a>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>
    <h2>Cadastro</h2>
    <form method="POST">
        <input type="text" name="nome" placeholder="Nome" required><br>
        <input type="email" name="email" placeholder="E-mail" required><br>
        <input type="password" name="senha" placeholder="Senha" required><br>
        <select name="tipo">
            <option value="personal">Personal Trainer</option>
            <option value="aluno">Aluno</option>
        </select><br>
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>