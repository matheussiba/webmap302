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
        if (isset($_POST['spatial'])) {
            if ($_POST['spatial']=="NO") {
                $spatial="NO";
            } else {
                $spatial="YES";
            }
        } else {
            $spatial="YES";
        }

        try {
            if ($spatial=="NO") {
                $result = $pdo->query("SELECT {$fields} FROM {$table}{$where}{$order}");
                $features=[];
                foreach($result AS $row) {
                    $feature=["properties"=>$row];
                    array_push($features, $feature);
                }
                echo json_encode($features);
            } else {
                if (isset($_POST['distance'])) {
                    $result = $pdo->query("SELECT {$fields}, ST_AsGeoJSON(ST_Transform(ST_Buffer(ST_Transform(geom, 26913), {$_POST['distance']}), 4326), 5) AS geojson FROM {$table}{$where}{$order}");
                } else {
                    $result = $pdo->query("SELECT {$fields}, ST_AsGeoJSON(geom, 5) AS geojson FROM {$table}{$where}{$order}");
                }
                $features=[];
                foreach($result AS $row) {
                    unset($row['geom']);
                    $geometry=$row['geojson']=json_decode($row['geojson']);
                    unset($row['geojson']);
                    $feature=["type"=>"Feature", "geometry"=>$geometry, "properties"=>$row];
                    array_push($features, $feature);
                }
                $featureCollection=["type"=>"FeatureCollection", "features"=>$features];
                echo json_encode($featureCollection);
            }
        } catch(PDOException $e) {
            echo "ERROR: ".$e->getMessage();
        }
    } else {
        echo "ERROR: No table parameter incuded with request";
    }

?>