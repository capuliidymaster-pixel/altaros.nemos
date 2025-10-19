<?php
$host = "localhost";
$dbname = "user_db";   // your database name
$username = "root";    // default XAMPP username
$password = "";        // default XAMPP password is empty

    // syntax para makapag connect sa database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
?>
