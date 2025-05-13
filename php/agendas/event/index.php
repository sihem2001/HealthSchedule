<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../../db.php';


// check the login authentification
if (!isset($_SESSION['user_id'])) {
   header('Location: ../../login.php');
    exit;
}
$userId   = $_SESSION['user_id'];
$agendaId = $_GET['agenda_id'] ?? null;
if (!$agendaId) {
    header('Location: ../agendas/index.php');
    exit;
}

// here we check for the agenda
$stmt = $pdo->prepare("SELECT id FROM agendas WHERE id = ? AND user_id = ?");
$stmt->execute([$agendaId, $userId]);
if (!$stmt->fetch()) {
    exit('Accès refusé ou agenda introuvable.');
}

// the creation of event
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $start = $_POST['start'] ?? '';
    $end   = $_POST['end']   ?? '';
    if ($title && $end > $start) {
        $i = $pdo->prepare(
            "INSERT INTO events (agenda_id, title, start, end) VALUES (?, ?, ?, ?)"
        );
        $i->execute([$agendaId, $title, $start, $end]);
    }
    header("Location: index.php?agenda_id=$agendaId");
    exit;
}

// get the event
$stmt = $pdo->prepare("SELECT * FROM events WHERE agenda_id = ? ORDER BY start");
$stmt->execute([$agendaId]);
$events = $stmt->fetchAll();
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Events</title>
</head>
<body>
  <h1>Event Agendas #<?= htmlspecialchars($agendaId) ?></h1>
  <p><a href="../index.php">← My Agendas</a></p>

  <form id="event-form" method="post" action="index.php?agenda_id=<?= $agendaId ?>">
    <label>
      Ttile : <input name="title" required>
    </label><br>
    <label>
      Start : <input type="datetime-local" name="start" required>
    </label><br>
    <label>
      End :   <input type="datetime-local" name="end" required>
    </label><br>
    <button type="submit">Add</button>
  </form>

  <ul>
    <?php foreach ($events as $e): ?>
      <li>
        <?= htmlspecialchars($e['title']) ?> (<?= $e['start'] ?> → <?= $e['end'] ?>)
        [<a href="edit.php?agenda_id=<?= $agendaId ?>&id=<?= $e['id'] ?>">Update</a>]
        [<a href="delete.php?agenda_id=<?= $agendaId ?>&id=<?= $e['id'] ?>" onclick="return confirm('Supprimer cet événement ?')">Delete</a>]
      </li>
    <?php endforeach; ?>
  </ul>

  <script>
  document.getElementById('event-form').addEventListener('submit', function(e) {
    const start = new Date(this.start.value);
    const end   = new Date(this.end.value);
    if (end <= start) {
      e.preventDefault();
      alert('La date de fin doit être après la date de début.');
    }
  });
  </script>
</body>
</html>