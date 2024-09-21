<!-- produtos.php -->
<?php
session_start();
require_once('../config/conexao.php'); // Inclua o arquivo de configuração do banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Busca os produtos no banco de dados
$stmt = $pdo->query("SELECT * FROM produtos");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
</head>
<body>
    <h1>Produtos Disponíveis</h1>
    <a href="carrinho.php">Ver Carrinho</a>
    <ul>
        <?php foreach ($produtos as $produto): ?>
            <li>
                <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
                <p><?php echo htmlspecialchars($produto['descricao']); ?></p>
                <p>Preço: R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                <form action="adicionar_ao_carrinho.php" method="POST">
                    <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                    <label for="quantidade">Quantidade:</label>
                    <input type="number" name="quantidade" id="quantidade" value="1" min="1">
                    <button type="submit">Adicionar ao Carrinho</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
