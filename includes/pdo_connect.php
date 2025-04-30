<?php
$host = 'localhost';
$dbname = 'acy3465'; 
$username = 'acy3465'; 
$password = 'SeaBeachSand15!'; 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>