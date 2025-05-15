<?php

session_start();
require_once __DIR__ . '/../db.php';

// check the session
if ( !isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}
$userId = $_SESSION['user_id'];

//create new agnda
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'){
    $title = trim($_POST['title'] ?? '');
    if($title !==''){
        $stmt = $pdo -> prepare('INSERT INTO agendas (user_id, title) VALUES (?,?)');
        $stmt -> execute([$userId, $title]);
    }
    header('Location: index.php');
    exit;
}
// get list of agendas
$stmt = $pdo->prepare(
    "SELECT * 
     FROM agendas 
     WHERE user_id = ? 
     ORDER BY id DESC"
);
$stmt->execute([$userId]);
$agendas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
    <link rel="stylesheet" href="/./css/header.css">
    <link rel="stylesheet" href="/./css/footer.css">
    <link rel="stylesheet" href="/./css/agenda.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <!-- nav bar -->
    <header class="loginHeader">
    <nav><h1 style="color:#f5f5f5">HealthScheduale <i class="fas fa-notes-medical"></i></h1></nav>
    </header>
    <div class="affichage">
    <!-- agenda part -->
    <section class="agenda">
    <fieldset class="fieldset">
    <legend> <i class="fas fa-calendar-check"></i> MY AGENDA </legend><br>
    
    <button class="button "><a class="link" href="../dashboard.php"> ← Dashboard</a></button> <br>

    <form class="fieldset" method="post">
        <input type="text" name="title" placeholder="title of new agenda" required><br> <br>
        <button class="button" type="submit">ADD</button>
    </form>
    </fieldset>
    </section>

    <section class="agenda-items">
    <ul>
    <?php foreach ($agendas as $a): ?>
        <li >
        <?= htmlspecialchars($a['title']) ?>
        <button class="update"> <a class="link" href="edit.php?id=<?= $a['id'] ?>">Update</a></button>
        <button class="delete">
           <a class="link" href="delete.php?id=<?= $a['id'] ?>" onclick="return confirm('Supprimer cet agenda ?')">Delete</a></button>
        <button class="event"><a class="link" href="event/index.php?agenda_id=<?= $a['id'] ?>">Appointment</a></button>
        </li>
    <?php endforeach; ?>
    </ul>
</section>
</div>
    <!-- footer  -->
         <!-- footer part -->
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