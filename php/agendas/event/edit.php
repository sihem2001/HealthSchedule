<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../../db.php';


// login 
if (!isset($_SESSION['user_id'])){
    header('Location: ../../login.php');
    exit;
}
$userId = $_SESSION['user_id'];
$agendaId = $_GET['agenda_id'] ?? null;
$id = $_GET['id'] ?? null;
if (!$agendaId || !$id) {
    header("Location: index.php?agenda_id=$agendaId");
    exit;
}

// check the agenda
$stmt = $pdo->prepare('SELECT id FROM agendas WHERE id = ? AND user_id = ?');
$stmt->execute([$agendaId, $userId]);
if (!$stmt->fetch()) {
    exit('Accès refusé ou agenda introuvable.');
}

// treatement of post method from form
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'){
    $title = trim($_POST['title'] ?? '');
    $start = $_POST['start'] ?? '';
    $end   = $_POST['end']   ?? '';
    if ($title && $end > $start) {
        $u = $pdo->prepare(
            "UPDATE events SET title = ?, start = ?, end = ? WHERE id = ? AND agenda_id = ?"
        );
        $u->execute([$title, $start, $end, $id, $agendaId]); 
    }
        header("Location: index.php?agenda_id=$agendaId"); 
        exit;
}
// get the event 

$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ? AND agenda_id = ?");
$stmt->execute([$id, $agendaId]);
$event = $stmt->fetch();
if (!$event) exit('Événement introuvable.');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Modifier l’événement</h1>
  <form id="event-form" method="post" action="edit.php?agenda_id=<?= $agendaId ?>&id=<?= $id ?>">
    <label>Title : <input name="title" value="<?= htmlspecialchars($event['title']) ?>" required></label><br>
    <label>Start : <input type="datetime-local" name="start" value="<?= str_replace(' ', 'T', $event['start']) ?>" required></label><br>
    <label>End :   <input type="datetime-local" name="end"   value="<?= str_replace(' ', 'T', $event['end']) ?>" required></label><br>
    <button type="submit">Save</button>
  </form>
  <p><a href="index.php?agenda_id=<?= $agendaId ?>">← Return</a></p>
  <script>
  document.getElementById('event-form').addEventListener('submit', function(e) {
    const start = new Date(this.start.value);
    const end   = new Date(this.end.value);
    if (end <= start) {
      e.preventDefault(); alert('La date de fin doit être après le début.');
    }
  });
  </script>
</body>
</html>