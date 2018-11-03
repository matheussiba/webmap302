<?php
    ob_start();
    session_start();
    //  *************** For PostgreSQL
        $dsn = "pgsql:host=localhost;dbname=webmap302;port=5432";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,//If occur some error fom the DB, it is displayed
            // PDO::ATTR_ERRMODE            => PDO::ERRMODE_SILENT, //If occur some error fom the DB, it's not displayed
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ];
        $pdo = new PDO($dsn, 'postgres', 'admin', $opt);
    //  *************** For MySQL
    //    $dsn = "mysql:host=localhost;dbname=login_course;port=3306;charset=utf8";
    //    $opt = [
    //        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    //        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    //        PDO::ATTR_EMULATE_PREPARES   => false
    //    ];
    //    $pdo = new PDO($dsn, $user, $pass, $opt);

    $root_directory = "courses/webmap302/material/lecture56-start";
    $from_email = "admin@imgenv.com";
    $reply_email = "admin@imgenv.com";
    include "php_functions.php";
?>
