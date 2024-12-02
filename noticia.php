<?php
include 'db_connect.php';

if (!isset($_GET['id'])) {
    echo "Notícia não encontrada!";
    exit;
}

$id = intval($_GET['id']); // Converte o ID para um número inteiro por segurança

// Busca os detalhes da notícia pelo ID
$query = $conn->prepare("SELECT * FROM noticias WHERE id = ? AND aprovado = 1");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $noticia = $result->fetch_assoc();
} else {
    echo "Notícia não encontrada ou ainda não aprovada!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($noticia['titulo']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1><?php echo htmlspecialchars($noticia['titulo']); ?></h1>
        <p class="text-muted">Por: <?php echo htmlspecialchars($noticia['autor']); ?></p>
        <img src="uploads/<?php echo htmlspecialchars($noticia['imagem']); ?>" class="img-fluid mb-3" alt="Imagem da notícia">
        <p><?php echo nl2br(htmlspecialchars($noticia['texto'])); ?></p>
        <a href="index.php" class="btn btn-primary mt-3">Voltar para a Página Inicial</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
