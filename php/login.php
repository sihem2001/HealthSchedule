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
    <link rel="stylesheet" href="/css/login.css">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/footer.css">
     <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>LOGIN-PAGE</title>
</head>
<body>
    <!-- nav bar -->
    <header class="loginHeader">
        <nav><h1>HealthScheduale <i class="fas fa-notes-medical"></i></h1></nav>
    </header>
    <!-- login part -->
    <div class="login">
         <h2 class="text-header">Easily Manage Your Medical Agendas, Schedule Appointments, And Track your Health Events All In One Place</h2>
        <fieldset class="fieldset">
    <legend>  <i class="fas fa-sign-in-alt"></i> LOGIN </legend>

    <?php if ($error) : ?>
    <p class="error-message "><?= htmlspecialchars($error) ?></p>
    <?php endif ?>

    <form method="post" class="login-form">
        <div class="email">
    <label>Email :</label>
     <input type="email" name="email" required><br>
     </div>
     <div class="password">
    <label>Password:</label>
     <input type="password" name="password" required><br>
     </div>
    
    <button type="submit">LOGIN</button>
    </fieldset>
    </form>
    <!-- create new count link -->
    <p class="newAccount">Don't have account ? <a href="register.php">Sign up and get started!</a></p>
    </div>
    <!-- feedback part  -->
        <section class="feedback">
          <div class="opinion-title"><h3>Satisfied Clients</h3></div>
      <div class="opinions">
        <!-- opinion1 -->
        <div class="opinion1">
            <p>
            The interface is clean and easy to use. In just a few clicks,
            I was able to add my medical appointments and organize them by agenda. 
            Very helpful to stay on track! <br />
            <span>Mimi Bristow</span>
          </p>
        </div>
        <!-- opinion2 -->
        <div class="opinion2">
          <p>
            Since I started using this app to manage my schedule and consultations, 
            I've saved a lot of time. The shared agenda feature with my colleagues is also a real bonus. <br />
            <span>Gina Laing </span>
          </p>
        </div>
        <!-- opinion3 -->
        <div class="opinion3">
          <p>
           It would be even better if the app could send automatic email or SMS reminders before each appointment.
            Otherwise, it's a great tool!<br />
            <span>Jonah Granger</span>
          </p>
        </div>
      </div>
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