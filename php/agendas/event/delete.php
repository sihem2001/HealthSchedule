<?php

session_start();
require_once __DIR__ . '/../../db.php';


if (!isset($_SESSION['user_id'])) {
  header('Location: ../../login.php');
}
$userId   = $_SESSION['user_id'];
$agendaId = $_GET['agenda_id'] ?? null;
$id        = $_GET['id']       ?? null;

if ($agendaId && $id) {
    $d = $pdo->prepare("DELETE FROM events WHERE id = ? AND agenda_id = ?");
    $d->execute([$id, $agendaId]);
}
header("Location: index.php?agenda_id=$agendaId");
exit;
