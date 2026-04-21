<?php
include("includes/header.php");

$selectedCategory = $_GET['category'] ?? '';
$selectedLevel = $_GET['level'] ?? '';
$selectedDuration = $_GET['duration'] ?? '';
$conditions = [];
$params = [];

$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;


if (!empty($_GET['category'])) {
    $conditions[] = "category = :category";
    $params['category'] = $_GET['category'];
}
if (!empty($_GET['level'])) {
    $conditions[] = "level = :level";
    $params['level'] = $_GET['level'];
}
if (!empty($_GET['duration'])) {
    $conditions[] = "duration = :duration";
    $params['duration'] = $_GET['duration'];
}

$sql = "SELECT * FROM challenges";
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
$sql .= " ORDER BY start_date ASC LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue(":$key", $value);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$challenges = $stmt->fetchAll();


$userParticipations = [];
if (isset($_SESSION['user_id'])) {
    $stmt2 = $pdo->prepare("SELECT challenge_id FROM participations WHERE user_id = ?");
    $stmt2->execute([$_SESSION['user_id']]);
    $userParticipations = $stmt2->fetchAll(PDO::FETCH_COLUMN);
}

$countSql = "SELECT COUNT(*) FROM challenges";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute();
$totalChallenges = $countStmt->fetchColumn();
$totalPages = ceil($totalChallenges / $limit);

?>

<div class="container my-5">

    <div class="text-center mb-4">
        <h2>Tous les défis</h2>
        <p class="text-muted">
            Découvrez tous nos défis et participez pour tester vos compétences et gagner des récompenses !
        </p>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2 justify-content-center">
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Toutes catégories</option>
                        <option value="Course" <?= $selectedCategory == 'Course' ? 'selected' : '' ?>>Course</option>
                        <option value="Force" <?= $selectedCategory == 'Force' ? 'selected' : '' ?>>Force</option>
                        <option value="Velo" <?= $selectedCategory == 'Velo' ? 'selected' : '' ?>>Vélo</option>
                        <option value="Bien-être" <?= $selectedCategory == 'Bien-être' ? 'selected' : '' ?>>Bien-être</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="level" class="form-select">
                        <option value="">Tous niveaux</option>
                        <option value="debutant" <?= $selectedLevel == 'debutant' ? 'selected' : '' ?>>Débutant</option>
                        <option value="intermediaire" <?= $selectedLevel == 'intermediaire' ? 'selected' : '' ?>>Intermédiaire</option>
                        <option value="avance" <?= $selectedLevel == 'avance' ? 'selected' : '' ?>>Avancé</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="duration" class="form-select">
                        <option value="">Toutes durées</option>
                        <option value="short" <?= $selectedDuration == 'short' ? 'selected' : '' ?>>Court</option>
                        <option value="medium" <?= $selectedDuration == 'medium' ? 'selected' : '' ?>>Moyen</option>
                        <option value="long" <?= $selectedDuration == 'long' ? 'selected' : '' ?>>Long</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($challenges as $challenge): ?>
            <?php $isParticipating = in_array($challenge['id'], $userParticipations); ?>
            <div class="col">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-primary mb-2 align-self-start">
                            <?= htmlspecialchars($challenge['category']) ?>
                        </span>
                        <h5 class="fw-bold"><?= htmlspecialchars($challenge['title']) ?></h5>
                        <p class="text-muted flex-grow-1"><?= htmlspecialchars($challenge['description']) ?></p>
                        <p class="small text-secondary mb-2">
                            📅 <?= $challenge['start_date'] ?> → <?= $challenge['end_date'] ?>
                        </p>
                        <div class="mt-3 mt-md-0">
                            <?php if (!isset($_SESSION['user_id'])): ?>
                                <a href="login.php" class="btn btn-outline-secondary w-100">Se connecter</a>
                            <?php elseif ($isParticipating): ?>
                                <button class="btn btn-secondary w-100" disabled>Déjà inscrit</button>
                            <?php else: ?>
                                <a href="actions/participer.php?id=<?= $challenge['id'] ?>" class="btn btn-success w-100">Participer</a>
                            <?php endif; ?>
                            <a href="pages/challenge_details.php?id=<?= $challenge['id'] ?>" class="btn btn-outline-primary w-100 mt-2">Voir plus</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="d-flex justify-content-center mt-5">
        <nav>
            <ul class="pagination">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>&category=<?= $selectedCategory ?>&level=<?= $selectedLevel ?>&duration=<?= $selectedDuration ?>">Précédent</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&category=<?= $selectedCategory ?>&level=<?= $selectedLevel ?>&duration=<?= $selectedDuration ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&category=<?= $selectedCategory ?>&level=<?= $selectedLevel ?>&duration=<?= $selectedDuration ?>">Suivant</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<?php include("includes/footer.php"); ?>