<?php
session_start();

if ( !isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}
?>
<!-- html part -->
 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
 </head>
 <body>
    <h1> HELLO IN HEALTH-AGENDA</h1>
    <P>YOU ARE CONNECTE ; YOUR ID <?= htmlspecialchars($_SESSION['user_id']) ?></P><br>
    <button><a href="./agendas/index.php">My Agendas</a></button><br>
      <button><a href="logout.php">LOGOUT</a></button>
 </body>
 </html>