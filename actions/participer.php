<?php
include("../includes/header.php");


if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$challenge_id = $_GET['id'] ?? null;

if (!$challenge_id) {
    die("Challenge invalide");
}

$check = $pdo->prepare("SELECT id FROM participations WHERE user_id = ? AND challenge_id = ?");
$check->execute([$user_id, $challenge_id]);

if ($check->fetch()) {
    header("Location: ../pages/challenge_details.php?id=$challenge_id&msg=already");
    exit;
}

$stmt = $pdo->prepare("INSERT INTO participations (user_id, challenge_id) VALUES (?, ?)");
$stmt->execute([$user_id, $challenge_id]);

header("Location: ../pages/challenge_details.php?id=$challenge_id&msg=success");
exit;