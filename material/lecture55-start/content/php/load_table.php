<?php
    include "../../includes/init.php";
    if (isset($_POST['tbl'])) {
        $table = $_POST['tbl'];
        if (isset($_POST['flds'])) {
            $fields = $_POST['flds'];
        } else {
            $fields = "*";
        }
        if (isset($_POST['where'])) {
            $where = " WHERE ".$_POST['where'];
        } else {
            $where = "";
        }
        if (isset($_POST['order'])) {
            $order=" ORDER BY ".$_POST['order'];
        } else {
            $order="";
        }

        try {
            $result = $pdo->query("SELECT {$fields} FROM {$table}{$where}{$order}");
            if (isset($_POST['title'])) {
                $returnTable="<h2 class='text-center'>{$_POST['title']}</h2>";
            }else {
                $returnTable="";
            }
            $returnTable.="<table class='table table-hover'>";
            $row=$result->fetch();
            if ($row) {
                $returnTable.="<tr class='tblHeader'>";
                foreach($row AS $key=>$val) {
                    $returnTable.="<th>{$key}</th>";
                }
                $returnTable.="</tr>";
                $returnTable.="<tr>";
                foreach($row AS $key=>$val) {
                    $returnTable.="<td>{$val}</td>";
                }
                $returnTable.="</tr>";
            }
            foreach($result AS $row) {
                $returnTable.="<tr>";
                foreach($row AS $key=>$val) {
                    $returnTable.="<td>{$val}</td>";
                }
                $returnTable.="</tr>";
            }
            $returnTable.="</table>";
            echo $returnTable;
        } catch(PDOException $e) {
            echo "ERROR: ".$e->getMessage();
        }
    } else {
        echo "ERROR: No table parameter incuded with request";
    }

?>