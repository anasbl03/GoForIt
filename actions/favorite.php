<?php
include("../includes/header.php");
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$challenge_id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND challenge_id = ?");
$stmt->execute([$user_id, $challenge_id]);

if ($stmt->fetch()) {
    $del = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND challenge_id = ?");
    $del->execute([$user_id, $challenge_id]);
} else {
    $ins = $pdo->prepare("INSERT INTO favorites (user_id, challenge_id) VALUES (?, ?)");
    $ins->execute([$user_id, $challenge_id]);
}

header("Location: ../pages/challenge_details.php?id=$challenge_id");
exit;