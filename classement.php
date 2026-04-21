<?php
include("includes/header.php");

$ranking = [];
$stmt = $pdo->query("
    SELECT u.name, c.title, p.score
    FROM participations p
    JOIN users u ON p.user_id = u.id
    JOIN challenges c ON p.challenge_id = c.id
    WHERE p.status = 'validated'
    ORDER BY p.score DESC
    LIMIT 10
");
$ranking = $stmt->fetchAll();

?>

<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold display-6">🏆 Classement général</h2>
        <p class="text-muted">Les meilleurs performeurs de la plateforme</p>
    </div>

    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>#</th><th>Utilisateur</th><th>Défi</th><th class="text-end">Score</th></tr>
                    </thead>
                    <tbody>
                    <?php if ($ranking): ?>
                        <?php $i = 1; foreach ($ranking as $row): ?>
                            <tr>
                                <td>
                                    <?php if ($i == 1): ?>🥇
                                    <?php elseif ($i == 2): ?>🥈
                                    <?php elseif ($i == 3): ?>🥉
                                    <?php else: ?><?= $i ?>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-semibold"><?= htmlspecialchars($row['name']) ?></td>
                                <td><span class="badge bg-primary"><?= htmlspecialchars($row['title']) ?></span></td>
                                <td class="text-end"><span class="badge bg-success px-3 py-2"><?= $row['score'] ?> pts</span></td>
                            </tr>
                        <?php $i++; endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">Aucun classement pour le moment</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="defis.php" class="btn btn-primary px-4">🔥 Participer à un défi</a>
    </div>
</div>

<?php include("includes/footer.php"); ?>