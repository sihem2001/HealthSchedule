<?php
// la connexion
$host     = 'localhost';
$db       = 'agenda';   
$user     = 'root';         
$pass     = '';              
$charset  = 'utf8mb4';   

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];


// DSN 
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
    // objet PDO
    $pdo = new PDO($dsn, $user, $pass, $options);

}catch (PDOException $e){
     exit(' connection databse has been failed: ' . $e->getMessage());
}

?>