<?php
include("../includes/header.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM challenges");
$stmt->execute();
$challenges = $stmt->fetchAll();
?>

<div class="container mt-5">
    <h2>Gestion des Challenges</h2>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($challenges as $challenge): ?>
    <tr>
        <td><?= $challenge['id'] ?></td>
        <td><?= htmlspecialchars($challenge['title']) ?></td>
        <td><?= htmlspecialchars($challenge['category']) ?></td>
        <td>
    <a href="edit_challenge.php?id=<?= $challenge['id'] ?>" class="btn btn-warning btn-sm">
        Modifier
    </a>

    <a href="delete_challenge.php?id=<?= $challenge['id'] ?>" 
       class="btn btn-danger btn-sm"
       onclick="return confirm('Supprimer ce challenge ?')">
        Supprimer
    </a>
</td>
    </tr>
<?php endforeach; ?>
        </tbody>

    </table>
</div>
<?php include("../includes/footer.php"); ?>