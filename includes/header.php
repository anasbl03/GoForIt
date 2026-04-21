<?php
session_start();
require(__DIR__ . "/db.php");
require(__DIR__ . "/config.php");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Défis Sportifs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg custom-navbar" data-bs-theme="light">
    <div class="container">

        <a class="navbar-brand">🏆 Défis Sportifs</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">

    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>index.php">Accueil</a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>defis.php">Défis</a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>classement.php">Classement</a>
    </li>

    <?php if (!empty($_SESSION['user_id'])): ?>

        <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>pages/profil.php">Mon profil</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/ProjetWeb2526/actions/logout.php">Déconnexion</a>
        </li>

    <?php else: ?>

        <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>login.php">Connexion</a>
        </li>

    <?php endif; ?>

</ul>
        </div>
    </div>
</nav>