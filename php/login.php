<?php
session_start();
require_once __DIR__ . '/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    if (!$email || !$password ){
        $error = 'all information are required';
    }else{
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if($user && password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            header('Location: dashboard.php');
            exit;
        }else{
            $error = ' email or password are incorrecte';
        }
    }
}

?>
<!-- HTML PART -->
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1> LOGIN PAGE </h1>
    <?php if ($error) : ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif ?>
      <form method="post">
    <label>Email :</label>
     <input type="email" name="email" required><br>
    <label>Password:</label>
     <input type="password" name="password" required><br>
    
    <button type="submit">LOGIN</button>
  </form>
<p>Didn't registered befor ? <a href="register.php">Creat an account from here</a></p>
</body>
</html>