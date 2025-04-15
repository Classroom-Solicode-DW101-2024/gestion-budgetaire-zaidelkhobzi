<?php
    $dsn = "mysql:host=localhost;dbname=gestion_budget";
    $user = "root";
    $password = "";
    $options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    );

    try {
        $pdo = new PDO($dsn, $user, $password, $options);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        die("Failed to connect to database, " + $e->getMessage());
    }