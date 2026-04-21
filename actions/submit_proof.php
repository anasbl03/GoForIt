<?php
include("../includes/header.php");


if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$challenge_id = $_POST['challenge_id'] ?? null;
$description = $_POST['description'] ?? '';

if (!$challenge_id) {
    die("Challenge invalide");
}

$stmt = $pdo->prepare("SELECT id FROM participations WHERE user_id = ? AND challenge_id = ?");
$stmt->execute([$user_id, $challenge_id]);
$participation = $stmt->fetch();

if (!$participation) {
    die("Vous n'êtes pas inscrit à ce challenge.");
}
$proofName = null;
if (!empty($_FILES['proof']['name'])) {
    $ext = pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION);
    $proofName = uniqid() . "." . $ext;
    move_uploaded_file($_FILES['proof']['tmp_name'], "../uploads/" . $proofName);
}
$stmt = $pdo->prepare("
    UPDATE participations 
    SET proof = ?, status = 'pending'
    WHERE user_id = ? AND challenge_id = ?
");
$stmt->execute([$proofName, $user_id, $challenge_id]);

header("Location: ../pages/challenge_details.php?id=$challenge_id&msg=proof_sent");
exit;