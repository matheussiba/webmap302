<?php
    $dsn = "pgsql:host=localhost;dbname=webmap302;port=5432";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false
    ];
    $pdo = new PDO($dsn, 'postgres', '123456', $opt);

    $result = $pdo->query("SELECT *, ST_AsGeoJSON(geom, 5) AS geojson FROM dj_eagle");
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
    
?>