<?php
    include "../../includes/init.php";
    $username=$_SESSION['username'];
    if (isset($_POST['tbl'])) {
        $table = $_POST['tbl'];
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            unset($_POST['tbl']);
            unset($_POST['id']);

            try {
                $sets="";
                foreach($_POST as $key=>$val) {
                    $sets.="{$key}=:{$key}, ";
                }
                $sqlQuery="UPDATE {$table} SET {$sets}modified=current_date, modifiedby='{$username}' WHERE id={$id}";
                $result = $pdo->prepare($sqlQuery);
                $result->execute($_POST);
                echo $sqlQuery;
            } catch(PDOException $e) {
                echo "ERROR: ".$e->getMessage();
            }
        } else {
            echo "ERROR: ID not included in delete request";
        }
    } else {
        echo "ERROR: No table parameter incuded with request";
    }

?>