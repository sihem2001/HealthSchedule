<?
session_start();


// check the session
if (!isset($_SESSION['user_id'])){
    header('Location: ../login.php');
    exit;
}
$userId = $_SESSION['user_id'];

// get the id 
$id = $_GET['id'] ?? null;
if(!$id){
    header('Location: index.php');
    exit;
}

// charger les donnees existantes
$stmt = $pdo->prepare("SELECT title FROM agendas WHERE id = ? AND user_id = ?");
$stmt->execute([$id,$userId]);
$agenda = $stmt->fetch();
if(!$agenda){
    exit('Agenda not found or refuse access');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Edite Agenda</h1>
    <form action="edit.php?id=<?=$id?>" method="post">
    <label>
      Titre :
      <input name="title" value="<?= htmlspecialchars($agenda['title']) ?>" required>
    </label>
    <button type="submit">DONE</button>
    </form>
    <button><a href="index.php">← Return</a></button>
</body>
</html>