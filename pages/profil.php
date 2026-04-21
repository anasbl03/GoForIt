<?php
include("../includes/header.php");

if (!isset($_SESSION['user_id'])) {
    exit("Vous devez être connecté pour voir votre profil.");
}

$sql = "SELECT name, email, role, created_at FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    exit("Utilisateur introuvable.");
}


$stmt = $pdo->prepare("SELECT COUNT(*) FROM participations WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$challenges_done = $stmt->fetchColumn();


$stmt = $pdo->prepare("
    SELECT c.title, p.score, p.created_at 
    FROM participations p
    JOIN challenges c ON p.challenge_id = c.id
    WHERE p.user_id = ?
    ORDER BY p.created_at DESC
    LIMIT 1
");
$stmt->execute([$_SESSION['user_id']]);
$last_challenge = $stmt->fetch();


$stmt = $pdo->prepare("
    SELECT c.id, c.title, c.category, f.created_at
    FROM favorites f
    JOIN challenges c ON f.challenge_id = c.id
    WHERE f.user_id = ?
    ORDER BY f.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$favorites = $stmt->fetchAll();


$stmt = $pdo->prepare("
    SELECT c.title, p.status, p.score
    FROM participations p
    JOIN challenges c ON p.challenge_id = c.id
    WHERE p.user_id = ?
    ORDER BY p.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$myChallenges = $stmt->fetchAll();
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-9">

            
            <div class="card shadow-lg p-4 mb-4">
                <div class="card-body text-center">
                    <h2 class="mb-4">Mon Profil</h2>
                    <p><strong>Nom :</strong> <?= htmlspecialchars($user['name']) ?></p>
                    <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
                    <p><strong>Rôle :</strong> <?= htmlspecialchars($user['role']) ?></p>
                    <p><strong>Inscription :</strong> <?= date("d/m/Y", strtotime($user['created_at'])) ?></p>
                    <p><strong>Défis complétés :</strong> <?= $challenges_done ?></p>
                    <p><strong>Dernier défi :</strong>
                        <?php if ($last_challenge): ?>
                            <?= htmlspecialchars($last_challenge['title']) ?> - <?= $last_challenge['score'] ?> pts
                        <?php else: ?>
                            Aucun défi
                        <?php endif; ?>
                    </p>
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="../admin/admin_espace.php" class="btn btn-primary mt-3">Admin</a>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="card shadow-sm p-4 mb-4">
                <h4>❤️ Mes favoris</h4>
                <?php if ($favorites): ?>
                    <ul class="list-group mt-3">
                        <?php foreach ($favorites as $fav): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= htmlspecialchars($fav['title']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($fav['category']) ?></small>
                                </div>
                                <a href="challenge_details.php?id=<?= $fav['id'] ?>" class="btn btn-sm btn-outline-primary">Voir</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted mt-2">Aucun favori ❤️</p>
                <?php endif; ?>
            </div>

            
            <div class="card shadow-sm p-4 mb-4">
                <h4>🏃 Mes défis</h4>
                <div class="list-group mt-3">
                    <?php if ($myChallenges): ?>
                        <?php foreach ($myChallenges as $mc): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <strong><?= htmlspecialchars($mc['title']) ?></strong>
                                <span class="badge <?= $mc['status'] == 'validated' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                    <?= $mc['status'] == 'validated' ? 'Terminé' : 'En cours' ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Aucun défi rejoint pour le moment.</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>