<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: adminlogin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $acao = $_POST['acao'];

    $status = ($acao === 'aceitar') ? 'aprovada' : 'negada';
    $query = $conn->prepare("UPDATE noticias SET status = ? WHERE id = ?");
    $query->bind_param("si", $status, $id);
    $query->execute();
}

$noticias = $conn->query("SELECT * FROM noticias WHERE status = 'pendente'");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Aprovar Notícias</h1>
        <div class="row">
            <?php while ($noticia = $noticias->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $noticia['titulo']; ?></h5>
                            <p class="card-text"><?php echo substr($noticia['texto'], 0, 100); ?>...</p>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?php echo $noticia['id']; ?>">
                                <button name="acao" value="aceitar" class="btn btn-success">Aceitar</button>
                                <button name="acao" value="negar" class="btn btn-danger">Negar</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
