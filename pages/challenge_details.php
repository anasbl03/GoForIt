<?php
include("../includes/header.php");
include("../includes/db.php");


if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de défi manquant.");
}
$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM challenges WHERE id = ?");
$stmt->execute([$id]);
$challenge = $stmt->fetch();

if (!$challenge) {
    die("Défi introuvable.");
}

$isFavorite = false;
if (isset($_SESSION['user_id'])) {
    $stmtFav = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND challenge_id = ?");
    $stmtFav->execute([$_SESSION['user_id'], $id]);
    $isFavorite = (bool) $stmtFav->fetch();
}

$participation = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM participations WHERE user_id = ? AND challenge_id = ?");
    $stmt->execute([$_SESSION['user_id'], $id]);
    $participation = $stmt->fetch();
}

$stmtParticipants = $pdo->prepare("
    SELECT u.name, p.score
    FROM participations p
    JOIN users u ON p.user_id = u.id
    WHERE p.challenge_id = ? AND p.status = 'validated'
    ORDER BY p.score DESC
");
$stmtParticipants->execute([$id]);
$participants = $stmtParticipants->fetchAll();


$showProofSentAlert = (isset($_GET['msg']) && $_GET['msg'] == 'proof_sent');


?>

<div class="container my-5">

<?php if ($showProofSentAlert): ?>
    <div class="alert alert-info">
        Preuve envoyée ✔️ En attente de validation
    </div>
<?php endif; ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">
            <?= htmlspecialchars($challenge['title']) ?>
        </h2>
        <?php if (!empty($challenge['image'])): ?>
            <div class="mb-3 text-center">
                <img src="../uploads/<?= $challenge['image'] ?>" 
                     class="img-fluid rounded shadow"
                     style="max-height:400px;">
            </div>
        <?php endif; ?>
        <span class="badge bg-primary">
            <?= htmlspecialchars($challenge['category']) ?>
        </span>
    </div>

    <div class="mt-3 mt-md-0">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="../login.php?redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn btn-outline-secondary">Se connecter pour participer au défi</a>
        <?php elseif (!$participation): ?>
            <a href="../actions/participer.php?id=<?= $challenge['id'] ?>" class="btn btn-success">Participer</a>
        <?php else: ?>
            <?php if ($participation['status'] == 'validated'): ?>
                <button class="btn btn-success" disabled>Déjà validé ✔</button>
            <?php elseif ($participation['status'] == 'pending'): ?>
                <?php if (!empty($participation['proof'])): ?>
                    <button class="btn btn-warning" disabled>Preuve en attente de validation ⏳</button>
                <?php else: ?>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#proofModal">
                        Envoyer une preuve
                    </button>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <small class="text-muted">Début</small>
                <p class="fw-bold mb-0"><?= date('d/m/Y', strtotime($challenge['start_date'])) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <small class="text-muted">Fin</small>
                <p class="fw-bold mb-0"><?= date('d/m/Y', strtotime($challenge['end_date'])) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <small class="text-muted">Durée</small>
                <p class="fw-bold mb-0">
                    <?= floor((strtotime($challenge['end_date']) - strtotime($challenge['start_date'])) / 86400) ?> jours
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Description</h5>
                <p class="text-muted">
                    <?= nl2br(htmlspecialchars($challenge['description'])) ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Règles</h5>
                <p class="text-muted">
                    <?= nl2br(htmlspecialchars($challenge['rules'])) ?>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center gap-2 mt-4">
    <a href="../actions/favorite.php?id=<?= $challenge['id'] ?>" 
       class="btn <?= $isFavorite ? 'btn-danger' : 'btn-outline-danger' ?>">
        <?= $isFavorite ? '❤️ Retirer' : '🤍 Favori' ?>
    </a>
</div>

<div class="mt-5">
    <h5 class="fw-bold mb-3">Participants</h5>
    <table class="table table-striped align-middle">
        <thead>
            <tr><th>Utilisateur</th><th>Score</th></tr>
        </thead>
        <tbody>
            <?php if (count($participants) > 0): ?>
                <?php foreach ($participants as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><span class="badge bg-success"><?= $p['score'] ?> pts</span></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="2" class="text-center">Aucun participant validé pour l'instant</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="text-center mt-4">
    <a href="../defis.php" class="btn btn-outline-secondary">
        ← Retour
    </a>
</div>

</div>

<div class="modal fade" id="proofModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="../actions/submit_proof.php" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
            <h5 class="modal-title">Envoyer une preuve</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="challenge_id" value="<?= $challenge['id'] ?>">
            <div class="mb-3">
                <label>Image preuve</label>
                <input type="file" name="proof" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button class="btn btn-success">Envoyer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include("../includes/footer.php"); ?>