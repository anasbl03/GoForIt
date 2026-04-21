<?php
include("../includes/header.php");
require("../includes/db.php");

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
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (empty($_FILES['image']['name'])) {
        exit("Aucun fichier");
    }

    $file = $_FILES['image'];

    if ($file['error'] !== 0) {
        exit("Erreur upload");
    }

    $allowedTypes = ['image/jpeg', 'image/png'];

    if (!in_array($file['type'], $allowedTypes)) {
        exit("Type de fichier non autorisé");
    }

    $filename = basename($file['name']);
    $name = time() . "_" . $filename;

    move_uploaded_file($file['tmp_name'], "../uploads/" . $name);

    $stmt = $pdo->prepare("INSERT INTO images (filename) VALUES (:f)");
    $stmt->execute([
        "f" => $name
    ]);

    echo "Image ajoutée";
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="image" class="form-control" required>
    <button class="btn btn-primary mt-3">Uploader</button>
</form>

<?php include("../includes/footer.php"); ?>