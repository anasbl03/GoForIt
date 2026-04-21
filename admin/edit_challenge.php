<?php
include("../includes/header.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit("ID manquant");
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM challenges WHERE id = ?");
$stmt->execute([$id]);
$challenge = $stmt->fetch();

if (!$challenge) {
    exit("Challenge introuvable");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $imageName = $challenge['image']; 

    if (!empty($_FILES['image']['name'])) {
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['image']['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, $allowed)) {
            exit("Format d'image non autorisé (JPEG, PNG, WEBP seulement)");
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = time() . "_" . uniqid() . "." . $ext;

        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $imageName);
    }

    $stmt = $pdo->prepare("
        UPDATE challenges 
        SET title = ?, category = ?, description = ?, rules = ?, 
            start_date = ?, end_date = ?, level = ?, duration = ?, image = ?
        WHERE id = ?
    ");
    $stmt->execute([
        $_POST['title'],
        $_POST['category'],
        $_POST['description'],
        $_POST['rules'],
        $_POST['start_date'],
        $_POST['end_date'],
        $_POST['level'],
        $_POST['duration'],
        $imageName,
        $id
    ]);

    header("Location: challenges.php");
    exit();
}
?>

<div class="container mt-5">
    <h2>Modifier le challenge</h2>

    <form method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label>Titre</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($challenge['title']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Catégorie</label>
            <select name="category" class="form-control" required>
                <option value="Course" <?= $challenge['category'] == 'Course' ? 'selected' : '' ?>>Course</option>
                <option value="Force" <?= $challenge['category'] == 'Force' ? 'selected' : '' ?>>Force</option>
                <option value="Velo" <?= $challenge['category'] == 'Velo' ? 'selected' : '' ?>>Vélo</option>
                <option value="Bien-être" <?= $challenge['category'] == 'Bien-être' ? 'selected' : '' ?>>Bien-être</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Niveau</label>
            <select name="level" class="form-control" required>
                <option value="debutant" <?= $challenge['level'] == 'debutant' ? 'selected' : '' ?>>Débutant</option>
                <option value="intermediaire" <?= $challenge['level'] == 'intermediaire' ? 'selected' : '' ?>>Intermédiaire</option>
                <option value="avance" <?= $challenge['level'] == 'avance' ? 'selected' : '' ?>>Avancé</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Durée</label>
            <select name="duration" class="form-control" required>
                <option value="short" <?= $challenge['duration'] == 'short' ? 'selected' : '' ?>>Court</option>
                <option value="medium" <?= $challenge['duration'] == 'medium' ? 'selected' : '' ?>>Moyen</option>
                <option value="long" <?= $challenge['duration'] == 'long' ? 'selected' : '' ?>>Long</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($challenge['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label>Règles</label>
            <textarea name="rules" class="form-control" rows="3"><?= htmlspecialchars($challenge['rules']) ?></textarea>
        </div>

        <div class="mb-3">
            <label>Date de début</label>
            <input type="date" name="start_date" class="form-control" value="<?= $challenge['start_date'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Date de fin</label>
            <input type="date" name="end_date" class="form-control" value="<?= $challenge['end_date'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Image (laisser vide pour conserver l’ancienne)</label>
            <input type="file" name="image" class="form-control">
            <?php if ($challenge['image']): ?>
                <img src="../uploads/<?= $challenge['image'] ?>" class="mt-2" style="height:100px">
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="challenges.php" class="btn btn-secondary">Annuler</a>

    </form>
</div>

<?php include("../includes/footer.php"); ?>