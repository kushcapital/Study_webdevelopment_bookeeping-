<?php
// Database configuration
$host = 'babbage.se.edu';
$dbname = 'rkushwahaDB';
$username = 'rkushwaha'; // Default username for XAMPP/WAMP
$password = '12345';     // Default password for XAMPP/WAMP (empty)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>