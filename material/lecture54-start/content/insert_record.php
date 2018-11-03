<?php
    session_start();
    $username=$_SESSION['username'];
    if (isset($_POST['tbl'])) {
        $table = $_POST['tbl'];
        unset($_POST['tbl']);
        $dsn = "pgsql:host=localhost;dbname=webmap302;port=5432";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ];
        $pdo = new PDO($dsn, 'postgres', '123456', $opt);

        try {
            $keys="";
            $vals="";
            foreach($_POST as $key=>$val) {
                $keys.="{$key}, ";
                $vals.=":{$key}, ";
            }
            $sqlQuery="INSERT INTO {$table} ({$keys}created, createdby, modified, modifiedby) VALUES ({$vals}current_date, '{$username}', current_date, '{$username}')";
            $result = $pdo->prepare($sqlQuery);
            $result->execute($_POST);
            echo $sqlQuery;
        } catch(PDOException $e) {
            echo "ERROR: ".$e->getMessage();
        }
    } else {
        echo "ERROR: No table parameter incuded with request";
    }

?>