<?php

session_start();
require_once __DIR__ . '/db.php'; // obligation de connexion a la base de donnee
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (!$email || !$password || !$confirm ){
        $error = 'all information are required';

    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = 'Email invailable';
    }elseif($password !== $confirm){
       $error = 'Incorrect password';
    }else {
        // check if email already exisite
        $stmt = $pdo -> prepare('SELECT id FROM users WHERE email = ?');
        $stmt -> execute([$email]);
        if ($stmt -> fetch()){
            $error = 'this email already existe.';
        }else{
            // add new user 
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo -> prepare('INSERT INTO users (email,password) values (? , ?)');
            $stmt -> execute([$email , $hash]);

            // connexion
            $_SESSION['user_id'] = $pdo -> lastInsertId();
            header('Location : dashboard.php');
            exit;
        }
    }
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
  
    <h1>Welcome <strong> Register page</strong></h1>
    <!-- affiche l'erreur -->
    <?php if ($error): ?>
          <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif ?>
    <form method="post" action="register.php">
        <label>Email : </label>
        <input type="email" name="email" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <label>Confirme:</label>
        <input type="password" name="confirm_password" required><br>
        <button type="submit">Register Now</button>
    </form>
<!-- case where user already existe -->
  <p>already Register ? <a href="login.php">LOGING</a></p>

</body>
</html>