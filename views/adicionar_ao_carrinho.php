<!-- adicionar_ao_carrinho.php -->
<?php
session_start();
require_once('../config/conexao.php');// Inclua o arquivo de configuração do banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['user_id'];
$produto_id = $_POST['produto_id'];
$quantidade = (int)$_POST['quantidade'];

// Verifica se o produto já está no carrinho do usuário
$stmt = $pdo->prepare("SELECT * FROM carrinho WHERE usuario_id = :usuario_id AND produto_id = :produto_id");
$stmt->execute(['usuario_id' => $usuario_id, 'produto_id' => $produto_id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($item) {
    // Atualiza a quantidade se o produto já estiver no carrinho
    $nova_quantidade = $item['quantidade'] + $quantidade;
    $stmt = $pdo->prepare("UPDATE carrinho SET quantidade = :quantidade WHERE id = :id");
    $stmt->execute(['quantidade' => $nova_quantidade, 'id' => $item['id']]);
} else {
    // Adiciona o novo produto ao carrinho
    $stmt = $pdo->prepare("INSERT INTO carrinho (usuario_id, produto_id, quantidade) VALUES (:usuario_id, :produto_id, :quantidade)");
    $stmt->execute(['usuario_id' => $usuario_id, 'produto_id' => $produto_id, 'quantidade' => $quantidade]);
}

// Redireciona de volta para a página de produtos
header('Location: produtos.php');
exit();
?>
