<?php

$host = 'localhost';
$db = 'gaec';
$username = 'gaec';
$password = '1zXONtlCaR1uD234';
$charset = 'utf8mb4';
$port = '3306';


$dsn = "mysql:host=$host;dbname=$db;port=$port;charset=$charset";

$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false

];

$pdo = new PDO($dsn, $username, $password, $opt);