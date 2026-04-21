<?php
include("../includes/header.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM homepage LIMIT 1");
$home = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = $_POST['hero_title'];
    $text = $_POST['hero_text'];

    if ($home) {
        $stmt = $pdo->prepare("
            UPDATE homepage 
            SET hero_title = :title, hero_text = :text 
            WHERE id = :id
        ");
        $stmt->execute([
            "title" => $title,
            "text" => $text,
            "id" => $home['id']
        ]);
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO homepage (hero_title, hero_text) 
            VALUES (:title, :text)
        ");
        $stmt->execute([
            "title" => $title,
            "text" => $text
        ]);
    }

    header("Location: edit_homepage.php");
    exit();
}
?>

<form method="POST">
    <label>Titre HERO</label>
    <input type="text" name="hero_title" class="form-control"
           value="<?= $home['hero_title'] ?? '' ?>">

    <label class="mt-3">Texte HERO</label>
    <textarea name="hero_text" id="editor">
        <?= $home['hero_text'] ?? '' ?>
    </textarea>

    <button type="submit" class="btn btn-primary mt-3">Enregistrer</button>
</form>

<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor');
</script>

<?php include("../includes/footer.php"); ?>