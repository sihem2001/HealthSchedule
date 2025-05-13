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
    <title>Document</title>
</head>
<body>
    <h1> MY AGENDA </h1>
    <button><a href="../dashboard.php"> _Dashboard</a></button> <br>
    <form action="index.php" method="post">
        <input type="text" name="title" placeholder="title of new agenda" required><br>
        <button type="submit">ADD</button>
    </form>


    <ul>
    <?php foreach ($agendas as $a): ?>
        <li>
        <?= htmlspecialchars($a['title']) ?>
        [<button><a href="edit.php?id=<?= $a['id'] ?>">éditer</a></button>]
        [<button><a href="delete.php?id=<?= $a['id'] ?>"
            onclick="return confirm('Supprimer cet agenda ?')">supprimer</a></button>]
        [<button><a href="events.php?agenda_id=<?= $a['id'] ?>">événements</a></button>]
        </li>
    <?php endforeach; ?>
    </ul>
</body>
</html>