<?php
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
        $dsn = "pgsql:host=localhost;dbname=webmap302;port=5432";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ];
        $pdo = new PDO($dsn, 'postgres', '123456', $opt);

        $result = $pdo->query("SELECT {$fields}, ST_AsGeoJSON(geom, 5) AS geojson FROM {$table}{$where}{$order}");
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
    } else {
        echo "ERROR: No table parameter incuded with request";
    }

?>