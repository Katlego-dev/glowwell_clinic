<?php
//Database configuration for AMPPS local environment

$host   = 'localhost';
$db     = 'glowwell_db';
$user   = 'root';
$pass   = 'mysql';
$charset = 'utf8mb4';

//Set DSN (Data source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

//Set PDO options for secure execution and error handling
$options = [
    PDO::ATTR_ERRMODE           =>PDO::ERRMODE_EXCEPTION, //Throws exception on SQL errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     //Returns associative arrays by default
    PDO::ATTR_EMULATE_PREPARES  =>false,                 //Disable emulation for real prepared statements
];

try {
    //Create a new PDO instance
    $pdo = new PDO($dsn, $user, $pass, $options);


} catch (\PDOException $e){
    //Stop execution and show error message if connection fails
    exit("Database Connection Failed: " . $e->getMessage());
}
?>