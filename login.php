<?php
session_start();


$servername = "localhost";
$username = "username";
$password = "password";

// Create connection, donne acces a la base de donnée
$connection = new mysqli($servername, $username, $password);
// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

createDatabase($connection, 'laboratoire2');
if (login($connection, $_POST['user_mail'], $_POST['user_pass'])) {
    $_SESSION['email'] = '$_POST[user_mail]';

    echo ' Bienvenue ! Félicitations " ' . $_POST['user_mail'] . '" vous êtes connecté';

} else {
    echo 'Mot de passe ou courriel invalide';
}

$connection->close();

function createDatabase($connection, $dbName)
{
    // Create database
    $createDatabase = "CREATE DATABASE $dbName";
    $createTable = "CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL
)";

    $insertUser = "INSERT INTO Users (email,password)
        VALUES ('username@gmail.com',SHA1('password'))";

    $connection->query($createDatabase);
    $connection->select_db($dbName);
    $connection->query($createTable);
    $connection->query($insertUser);
}

function login($connection, $username, $password)
{
    try {
        $statement = $connection->prepare("SELECT `email`, `password` FROM users WHERE ? = email  AND SHA1(?) = password");
        $statement->bind_param("ss", $username, $password);
        $statement->execute();
        return $statement->get_result()->num_rows !== 0;
    } finally {
        $statement->close();
    }

}

?>


<a href="logout.php"><button>DECONNEXION</button></a>