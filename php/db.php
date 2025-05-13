<?
// la connexion
$host     = 'localhost';
$db       = 'agenda';   
$user     = 'root';         
$pass     = '';              
$charset  = 'utf8mb4';   

// DSN 
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
    // objet PDO
    $pdo = new PDO($dsn, $user, $pass, $options);

}catch (PDOException $e){
     exit('Échec de la connexion à la base de données : ' . $e->getMessage());
}

?>