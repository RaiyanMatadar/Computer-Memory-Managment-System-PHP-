<?php

$dsn = "mysql:host=localhost;dbname=computer_memory_managment";
$dbUsername = "root";
$dbPassword = "";

try {
    $pdo = new PDO($dsn,$dbUsername,$dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $error) {
    echo "connection failed";
}