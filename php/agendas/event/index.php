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
  <link rel="stylesheet" href="/../css/header.css">
  <link rel="stylesheet" href="/../css/footer.css">
  <link rel="stylesheet" href="/../css/event.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <!-- nav bar -->
    <header class="loginHeader">
    <nav >
       <h1 style="color:#f5f5f5">HealthScheduale <i class="fas fa-notes-medical"></i></h1>
       <button><a href="../index.php">← My Agendas</a></button>
    </nav>
    </header>
  <!-- start the event page -->
  <div class="container">
  <fieldset>
  <legend>Appointment#<?= htmlspecialchars($agendaId) ?></legend>
  
  <form id="event-form" method="post" action="index.php?agenda_id=<?= $agendaId ?>">
    <label>
      Ttile : <input type="text" name="title" required>
    </label><br>
    <label>
      Start : <input type="datetime-local" name="start" required>
    </label><br>
    <label>
      End :   <input type="datetime-local" name="end" required>
    </label><br>
    <button type="submit">Add</button>
  </form>
  </fieldset>
  <!-- event items -->
<section class="event-items">
  <ul>
    <?php foreach ($events as $e): ?>
      <li class="items">
        <?= htmlspecialchars($e['title']) ?>  <br>
        
         <?= $e['start'] ?> → <?= $e['end'] ?> 
        <button class="update"><a  href="edit.php?agenda_id=<?= $agendaId ?>&id=<?= $e['id'] ?>">Update</a></button>
        <button class="delete"><a  href="delete.php?agenda_id=<?= $agendaId ?>&id=<?= $e['id'] ?>" onclick="return confirm('Delete this appointment?')">Delete</a></button>
      </li>
    <?php endforeach; ?>
  </ul>
</section>
</div>
<!-- footer  -->
        
    <footer class="footer-section">
    <div class="footer-login">
        <h3 style="color:#f5f5f5" >For more Informations, Call us .</h3>
        <ul class="socialMedia">
          <li>
            <a href="#"><i  class="fab fa-facebook-f"></i></a>
          </li>
          <li>
            <a href="#"><i  class="fab fa-twitter"></i></a>
          </li>
          <li>
            <a href="#"><i  class="fab fa-google"></i></a>
          </li>
          <li>
            <a href="#"><i  class="fab fa-youtube"></i></i></a>
          </li>
          <li>
            <a href="#"><i  class="fab fa-linkedin"></i></a>
          </li>
          <li>
            <a href="#"><i  class="fas fa-phone"></i></a>
          </li>
        </ul>
        <p style="color:#f5f5f5" ><strong style="color:#f5f5f5">Adress :</strong>65, Boulevard Mohamed V, Algiers, Algiers 16002 ·</p>
    </div>
    <div class="footer-bottom">
            <p style="color:#f5f5f5">copyright &copy;2025 codeOpacity. designed by <span>nethunt</span></p> <br>
    </div>
</footer>

<!-- javascript part -->
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