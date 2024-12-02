<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db_connect.php';

// Verifica se o admin está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: adminlogin.php");
    exit;
}

// Processa aprovações ou recusas
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    if (isset($_POST['aceitar'])) {
        $query = $conn->prepare("UPDATE noticias SET aprovado = 1 WHERE id = ?");
        $query->bind_param("i", $id);
        if ($query->execute()) {
            $success = "Notícia aprovada com sucesso!";
        } else {
            $error = "Erro ao aprovar a notícia: " . $conn->error;
        }
    } elseif (isset($_POST['negar'])) {
        $query = $conn->prepare("DELETE FROM noticias WHERE id = ?");
        $query->bind_param("i", $id);
        if ($query->execute()) {
            $success = "Notícia negada e removida!";
        } else {
            $error = "Erro ao negar a notícia: " . $conn->error;
        }
    }
}

// Busca notícias pendentes
$query = $conn->query("SELECT id, titulo, texto FROM noticias WHERE aprovado = 0");

if (!$query) {
    die("Erro na consulta SQL: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Aprovar Notícias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Aprovar Notícias</h1>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php while ($noticia = $query->fetch_assoc()): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($noticia['titulo']); ?></h5>
                    <p class="card-text">
                        <?php echo htmlspecialchars(mb_strimwidth($noticia['texto'], 0, 150, '...')); ?>
                    </p>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="id" value="<?php echo $noticia['id']; ?>">
                        <button type="submit" name="aceitar" class="btn btn-success">Aceitar</button>
                    </form>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="id" value="<?php echo $noticia['id']; ?>">
                        <button type="submit" name="negar" class="btn btn-danger">Negar</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
