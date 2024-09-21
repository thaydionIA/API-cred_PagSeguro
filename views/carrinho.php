<!-- carrinho.php -->
<?php
session_start();
require_once('../config/conexao.php'); // Inclua o arquivo de configuração do banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['user_id'];

// Busca os itens do carrinho do usuário
$stmt = $pdo->prepare("
    SELECT c.id as carrinho_id, p.nome, p.preco, c.quantidade 
    FROM carrinho c
    JOIN produtos p ON c.produto_id = p.id
    WHERE c.usuario_id = :usuario_id
");
$stmt->execute(['usuario_id' => $usuario_id]);
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seu Carrinho</title>
</head>
<body>
    <h1>Seu Carrinho</h1>
    <a href="produtos.php">Continuar Comprando</a>
    <ul>
        <?php if (empty($itens)): ?>
            <p>Seu carrinho está vazio.</p>
        <?php else: ?>
            <?php foreach ($itens as $item): ?>
                <li>
                    <h3><?php echo htmlspecialchars($item['nome']); ?></h3>
                    <p>Quantidade: <?php echo $item['quantidade']; ?></p>
                    <p>Preço Unitário: R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></p>
                    <p>Total: R$ <?php echo number_format($item['preco'] * $item['quantidade'], 2, ',', '.'); ?></p>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <!-- Botão para finalizar compra -->
    <?php if (!empty($itens)): ?>
        <form action="index.php" method="POST">
            <input type="hidden" name="finalizar_compra" value="1">
            <button type="submit" class="btn btn-primary">Finalizar Compra</button>
        </form>
    <?php endif; ?>
</body>
</html>
