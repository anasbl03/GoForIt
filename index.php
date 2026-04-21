<?php
require("includes/db.php");
include("includes/header.php");


$sql = "SELECT * FROM challenges ORDER BY start_date DESC LIMIT 3";
$stmt = $pdo->query($sql);
$challenges = $stmt->fetchAll();


$sqlComments = "
    SELECT comments.content, users.name 
    FROM comments
    JOIN users ON comments.user_id = users.id
    ORDER BY comments.created_at DESC
    LIMIT 3
";
$stmtComments = $pdo->query($sqlComments);
$comments = $stmtComments->fetchAll();


$stmt = $pdo->query("SELECT * FROM homepage LIMIT 1");
$home = $stmt->fetch();

?>

<header class="bg-primary text-white text-center py-5 mb-5">
    <div class="container">
        <h1 class="fw-bold">
            <?= $home['hero_title'] ?? 'Rejoins des défis sportifs 🔥' ?>
        </h1>

        <p class="lead">
            <?= $home['hero_text'] ?? 'Teste tes limites, progresse et compare-toi aux autres !' ?>
        </p>

        <a href="defis.php" class="btn btn-light btn-lg mt-3 me-2">
            Découvrir les défis
        </a>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="register.php" class="btn btn-outline-light btn-lg mt-3">
                S'inscrire
            </a>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin'): ?>
            <div class="mt-3">
                <a href="admin/edit_homepage.php" class="btn btn-warning">Modifier le HERO</a>
            </div>
        <?php endif; ?>
    </div>
</header>

<div class="container mb-5">
    <h2 class="text-center mb-4">🔥 Défis populaires</h2>

    <?php if (count($challenges) > 0): ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($challenges as $challenge): ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <?php if (!empty($challenge['image'])): ?>
                            <img src="uploads/<?= $challenge['image'] ?>" 
                                class="card-img-top" 
                                style="height:200px; object-fit:cover;">
                        <?php endif; ?>

                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-primary mb-2 align-self-start">
                                <?= htmlspecialchars($challenge['category']) ?>
                            </span>
                            <h5 class="card-title fw-bold">
                                <?= htmlspecialchars($challenge['title']) ?>
                            </h5>
                            <p class="card-text text-muted flex-grow-1">
                                <?= htmlspecialchars($challenge['description']) ?>
                            </p>
                            <a href="pages/challenge_details.php?id=<?= $challenge['id'] ?>" 
                               class="btn btn-outline-primary mt-auto w-100">
                                Voir le défi
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center text-muted">
            Pas de défis disponibles
        </div>
    <?php endif; ?>
</div>

<div class="container py-5">
    <h2 class="text-center mb-5">⚙️ Comment ça marche ?</h2>
    <div class="row text-center g-4">
        <div class="col-md-3">
            <div class="p-4 border rounded shadow-sm h-100">
                <h4>📝</h4>
                <h5>Inscris-toi</h5>
                <p class="text-muted">Crée ton compte gratuitement.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-4 border rounded shadow-sm h-100">
                <h4>🎯</h4>
                <h5>Choisis un défi</h5>
                <p class="text-muted">Trouve un challenge adapté à ton niveau.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-4 border rounded shadow-sm h-100">
                <h4>💪</h4>
                <h5>Participe</h5>
                <p class="text-muted">Enregistre tes performances.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-4 border rounded shadow-sm h-100">
                <h4>🏆</h4>
                <h5>Classement</h5>
                <p class="text-muted">Compare-toi aux autres utilisateurs.</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-4">⭐ Avis des utilisateurs</h2>
        <div class="row g-4">
            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body text-center">
                                <p class="card-text fst-italic">
                                    “<?= htmlspecialchars($comment['content']) ?>”
                                </p>
                                <h6 class="mt-3 fw-bold">
                                    <?= htmlspecialchars($comment['name']) ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Aucun avis pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>