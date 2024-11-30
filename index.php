<?php
include 'db_connect.php';
$noticias = $conn->query("SELECT * FROM noticias WHERE status = 'aprovada'");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notícias de RAP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">Notícias de RAP</h1>
        <div class="row">
            <?php while ($noticia = $noticias->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <img src="uploads/<?php echo $noticia['imagem']; ?>" class="card-img-top" alt="Imagem">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $noticia['titulo']; ?></h5>
                            <p class="card-text"><?php echo substr($noticia['texto'], 0, 100); ?>...</p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
