<?php
include 'db_connect.php';

// Consulta apenas notícias aprovadas
$query = $conn->query("SELECT id, titulo, texto, imagem FROM noticias WHERE aprovado = 1");

if (!$query) {
    die("Erro na consulta SQL: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notícias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Notícias</h1>
        <div class="row">
            <?php while ($noticia = $query->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="uploads/<?php echo htmlspecialchars($noticia['imagem']); ?>" class="card-img-top" alt="Imagem da notícia">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($noticia['titulo']); ?></h5>
                            <p class="card-text">
                                <?php echo htmlspecialchars(mb_strimwidth($noticia['texto'], 0, 100, '...')); ?>
                            </p>
                            <a href="noticia.php?id=<?php echo $noticia['id']; ?>" class="btn btn-primary">Leia Mais</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
