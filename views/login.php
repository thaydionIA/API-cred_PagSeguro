<!-- login.php -->
<?php
session_start(); // Inicia a sessão para armazenar o user_id

// Verifica se o formulário de login foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('../config/conexao.php');
 // Inclua a configuração do banco de dados

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Busca o usuário no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o usuário foi encontrado e a senha está correta
    if ($user && password_verify($senha, $user['senha'])) {
        // Armazena o user_id na sessão
        $_SESSION['user_id'] = $user['id'];
        // Redireciona para a página de pagamento ou outra página principal
        header('Location: produtos.php');
        exit();
    } else {
        $erro = 'E-mail ou senha incorretos.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
    <form action="login.php" method="POST">
        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required>
        <br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
