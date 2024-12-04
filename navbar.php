<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/Noticias/index.php"> RAP NEWS </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/Site_de_Noticias/Noticias/escritor.php">Criar Notícia</a>
                        </li>
                    <?php endif; ?>
                    <?php if ($_SESSION['tipo'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/Site_de_Noticias/Noticias/admin.php">Admin</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/Site_de_Noticias/Noticias/logout.php"> Logout </a>
                    </li>
                
         
            </ul>
        </div>
    </div>
</nav>
