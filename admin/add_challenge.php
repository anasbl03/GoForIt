<?php
include("../includes/header.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || $user['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $imageName = null;

    if (!empty($_FILES['image']['name'])) {
        $file = $_FILES['image'];
        $allowedTypes = ['image/jpeg', 'image/png'];

        if (!in_array($file['type'], $allowedTypes)) {
            exit("Fichier non autorisé");
        }

        $imageName = time() . "_" . $file['name'];

        move_uploaded_file(
            $file['tmp_name'],
            "../uploads/" . $imageName
        );
    }

    $stmt = $pdo->prepare("
        INSERT INTO challenges 
        (title, category, description, rules, start_date, end_date, level, duration, image) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
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
        $imageName
    ]);
    header("Location: challenges.php");
    exit();
}
?>

<div class="container mt-5 flex-grow-1">

    <h2 class="mb-4">Ajouter un challenge</h2>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Titre" class="form-control mb-3" required>

        <select name="category" class="form-control mb-3" required>
            <option value="">-- Choisir une catégorie --</option>
            <option value="Course">Course</option>
            <option value="Force">Force</option>
            <option value="Velo">Vélo</option>
            <option value="Bien-être">Bien-être</option>
        </select>

        <select name="level" class="form-control mb-3" required>
            <option value="">-- Niveau --</option>
            <option value="debutant">Débutant</option>
            <option value="intermediaire">Intermédiaire</option>
            <option value="avance">Avancé</option>
        </select>

        <select name="duration" class="form-control mb-3" required>
            <option value="">-- Durée --</option>
            <option value="short">Court</option>
            <option value="medium">Moyen</option>
            <option value="long">Long</option>
        </select>

        <textarea name="description" placeholder="Description" class="form-control mb-3"></textarea>
        <textarea name="rules" placeholder="Règles" class="form-control mb-3"></textarea>

        <label class="mt-2">Date de début</label>
        <input type="date" name="start_date" class="form-control mb-3">
        <label>Date de fin</label>
        <input type="date" name="end_date" class="form-control mb-3">

        <input type="file" name="image" class="form-control mb-4">
        <button type="submit" class="btn btn-success">
            Ajouter
        </button>

    </form>

    <a href="admin_espace.php" class="btn btn-secondary mt-3">
        ← Retour
    </a>

</div>

<?php include("../includes/footer.php"); ?>