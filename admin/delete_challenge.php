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

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: challenges.php");
    exit();
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM challenges WHERE id = ?");
$stmt->execute([$id]);

header("Location: challenges.php");
exit();
?>