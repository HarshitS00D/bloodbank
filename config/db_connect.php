<?php
try {
    //remote database connection
    $host = 'remotemysql.com';
    $user = 'ysO8lvYgTt';
    $password = 'obgztEn68s';
    $dbname = 'ysO8lvYgTt';

    // local database connection
    // $host = 'localhost';
    // $user = 'root';
    // $password = '';
    // $dbname = 'bloodbank';

    // set dsn
    $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname;
    // connect to the database
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
