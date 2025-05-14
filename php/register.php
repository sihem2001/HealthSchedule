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
            $stmt = $pdo -> prepare('INSERT INTO users (email, password) values (? , ?)');
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/register.css">
    <title>Register</title>
</head>
<body>
    <!-- nav bar -->
    <header class="loginHeader">
        <nav><h1>HealthScheduale <i class="fas fa-notes-medical"></i></h1></nav>
    </header>
    <section class="generativePicture">
    </section>
    <!-- register page -->
     <section class="registerPage">
      <fieldset class="fieldset">
      <legend> <i class="fas fa-user-plus"></i> Welcome  Register page </legend>
    <!-- affiche l'erreur -->
    <?php if ($error): ?>
          <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif ?>
    <form method="post" action="register.php">
        <label>Email : </label>
        <input type="email" name="email" placeholder="ex:myadress@healthschadule.com" required><br>
        <label>Password:</label>
        <input type="password" name="password" placeholder="write a password" required><br>
        <label>Confirme:</label>
        <input type="password" name="confirm_password" placeholder="rewrite to confirme your password" required><br>
        <button type="submit">Register Now</button>
    </form>
    </fieldset>
<!-- case where user already existe -->
  <p class="newAccount">already Register ? <a href="login.php">LOGING</a></p>
</section>
<!-- footer part -->
    <footer class="footer-section">
    <div class="footer-login">
        <h3>For more Informations, Call us .</h3>
        <ul class="socialMedia">
          <li>
            <a href="#"><i class="fab fa-facebook-f"></i></a>
          </li>
          <li>
            <a href="#"><i class="fab fa-twitter"></i></a>
          </li>
          <li>
            <a href="#"><i class="fab fa-google"></i></a>
          </li>
          <li>
            <a href="#"><i class="fab fa-youtube"></i></i></a>
          </li>
          <li>
            <a href="#"><i class="fab fa-linkedin"></i></a>
          </li>
          <li>
            <a href="#"><i class="fas fa-phone"></i></a>
          </li>
        </ul>
        <p><strong>Adress :</strong>65, Boulevard Mohamed V, Algiers, Algiers 16002 ·</p>
        <p>
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d25569.236890908564!2d3.0415312918002813!3d36.766858224380115!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x128fb2f877796ffd%3A0x62ac3794efff79ca!2sAlger%20Ctre!5e0!3m2!1sfr!2sdz!4v1696774205475!5m2!1sfr!2sdz"
            width="500"
            height="200"
            style="border: 0"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
          ></iframe>
        </p>
    </div>
    <div class="footer-bottom">
            <p>copyright &copy;2025 codeOpacity. designed by <span>nethunt</span></p> <br>
    </div>
</footer>
</body>
</html>