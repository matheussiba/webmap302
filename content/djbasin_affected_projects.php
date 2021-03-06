<?php
    if (isset($_POST['tbl'])) {
        $table = $_POST['tbl'];
        if (isset($_POST['fld'])) {
            $fld = $_POST['fld'];
        } else {
            $fld = "nest_id";
        }
        if (isset($_POST['distance'])) {
            $distance = $_POST['distance'];
        } else {
            $distance = 300;
        }
        if (isset($_POST['id'])) {
            $id=$_POST['id'];
        } else {
            $id=1;
        }
        $dsn = "pgsql:host=localhost;dbname=webmap302;port=5432";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ];
        $pdo = new PDO($dsn, 'postgres', 'admin', $opt);

        try {
            $strQuery = 'SELECT Round(ST_Distance(b.geom::geography, l.geom::geography)) as "Distance", l.project as "Project ID", l.type as "Type", Round(ST_Length(l.geom::geography)) as "Length (m)" FROM '.$table.' b JOIN dj_linear l ON ST_DWithin(b.geom::geography, l.geom::geography, '.$distance.') WHERE '.$fld.'='.$id.' ORDER BY "Distance"';

            $result = $pdo->query($strQuery);
            $returnTable="<hr /><h4 class='text-center' style='margin-top:0px;padding-top:0px;'>Affected Projects:</h4><table class='table table-hover '>";
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
            else {
              $returnTable.="<th class='text-center' style='background:gold'>...No affected projects...</th>";
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
