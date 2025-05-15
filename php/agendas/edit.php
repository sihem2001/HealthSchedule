<?php
// php/agendas/edit.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../db.php';

// 1) Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
$userId = $_SESSION['user_id'];

// 2) Récupérer l'ID passé en GET
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

// 3) Charger l'agenda depuis la base
$stmt = $pdo->prepare("
    SELECT title
      FROM agendas
     WHERE id = ?
       AND user_id = ?
");
$stmt->execute([$id, $userId]);
$agenda = $stmt->fetch();

// 4) Si l'agenda n'existe pas ou n'appartient pas à cet utilisateur
if (!$agenda) {
    exit('Agenda non trouvé ou accès refusé.');
}

// 5) Si le formulaire est soumis, traiter la mise à jour
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $newTitle = trim($_POST['title'] ?? '');
    if ($newTitle !== '') {
        $u = $pdo->prepare("
            UPDATE agendas
               SET title = ?
             WHERE id = ?
               AND user_id = ?
        ");
        $u->execute([$newTitle, $id, $userId]);
    }
    header('Location: index.php');
    exit;
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>UPDATE AGENDA</title>

</head>
<body>
    <!-- nav bar -->
    <header class="loginHeader">
    <nav >
       <h1 style="color:#f5f5f5">HealthScheduale <i class="fas fa-notes-medical"></i></h1>
      
    </nav>
    </header>
  <!-- update part -->
  <h1>Update agenda</h1>
  <form method="post" action="edit.php?id=<?= htmlspecialchars($id) ?>">
    <label>
      Ttile :
      <input
        type="text"
        name="title"
        value="<?= htmlspecialchars($agenda['title']) ?>"
        required
      >
    </label><br><br>
    <button type="submit">SAVE</button>
  </form>
  
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
</body>
</html>
