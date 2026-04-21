<?php
include("../includes/header.php");
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT role FROM users WHERE id = :id");
$stmt->execute(["id" => $_SESSION['user_id']]);

$user = $stmt->fetch();

if (!$user || $user['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
} 
?>

<div class="container my-5">
    <h1 class="text-center mb-5">Espace Admin</h1>
    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm text-center h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title mb-3">Ajouter un défi</h5>
                    <p class="card-text text-muted">Crée un nouveau défi sportif pour les utilisateurs.</p>
                    <a href="add_challenge.php" class="btn btn-primary mt-auto">Ajouter</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm text-center h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title mb-3">Gérer les défis</h5>
                    <p class="card-text text-muted">
                        Modifier ou supprimer les défis existants.
                    </p>
                    <a href="challenges.php" class="btn btn-warning mt-auto">
                        Gérer
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm text-center h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title mb-3">Page d'accueil</h5>
                    <p class="card-text text-muted">
                        Modifier le contenu affiché sur la page d'accueil.
                    </p>
                    <a href="edit_homepage.php" class="btn btn-success mt-auto">
                        Modifier
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm text-center h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title mb-3">Mon profil</h5>
                    <p class="card-text text-muted">
                        Retourner à ton espace utilisateur.
                    </p>
                    <a href="../pages/profil.php" class="btn btn-secondary mt-auto">
                        Retour
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include("../includes/footer.php"); ?>