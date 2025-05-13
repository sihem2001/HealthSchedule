<?php
session_start();
require_once __DIR__ . '/../db.php';

// check session
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
$userId = $_SESSION['user_id'];

//get the id sand delete
$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM agendas WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $userId]);
}

// redirection
header('Location: index.php');
exit;
