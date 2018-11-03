<?php
        if (isset($_POST['id'])) {
            $id=$_POST['id'];
        } else {
            $id=1;
        }
        if ($id=="geojson") {
            $geojson=$_POST['geojson'];
        }
        $dsn = "pgsql:host=localhost;dbname=webmap302;port=5432";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ];
        $pdo = new PDO($dsn, 'postgres', '123456', $opt);

        try {
            if ($id=="geojson") {
                $geojson="ST_SetSRID(ST_GeomFromGeoJSON('{$geojson}'), 4326)::geography";
                $strQuery = 'SELECT Round(ST_Distance(b.geom::geography, '.$geojson.')) as dist, b.habitat_id as id, b.recentstatus as status, Round(ST_Area(b.geom::geography)/1000)/10 as hectares FROM dj_buowl b WHERE ST_DWithin(b.geom::geography, '.$geojson.', 300) AND ST_Area(b.geom)>0.000000001 ORDER BY dist';
            } else {
                $strQuery = 'SELECT Round(ST_Distance(b.geom::geography, l.geom::geography)) as dist, b.habitat_id as id, b.recentstatus as status, Round(ST_Area(b.geom::geography)/1000)/10 as hectares FROM dj_buowl b JOIN dj_linear l ON ST_DWithin(b.geom::geography, l.geom::geography, 300) WHERE l.project='.$id.' AND ST_Area(b.geom)>0.000000001 ORDER BY dist';
            }
            
//            echo $strQuery."<br><br>";
            $result = $pdo->query($strQuery);
            $returnTable="<table class='table table-hover'>";
            $returnTable.="<tr><th>Constraint</th><th>ID</th><th>Distance</th><th>Status</th><th>Hectares</th></tr>";
            foreach($result AS $row) {
                $returnTable.="<tr><td>BUOWL</td><td>{$row['id']}</td><td>{$row['dist']}</td><td>{$row['status']}</td><td>{$row['hectares']}</td></tr>";
            }
            
            if ($id=="geojson") {
                $strQuery = 'SELECT Round(ST_Distance(b.geom::geography, '.$geojson.')) as dist, b.nest_id as id, b.status as status FROM dj_eagle b WHERE ST_DWithin(b.geom::geography, '.$geojson.', 804.5) ORDER BY dist';
            } else {
                $strQuery = 'SELECT Round(ST_Distance(b.geom::geography, l.geom::geography)) as dist, b.nest_id as id, b.status as status FROM dj_eagle b JOIN dj_linear l ON ST_DWithin(b.geom::geography, l.geom::geography, 804.5) WHERE l.project='.$id.' ORDER BY dist';
            }
            
//            echo $strQuery."<br><br>";
            $result = $pdo->query($strQuery);
            foreach($result AS $row) {
                $returnTable.="<tr><td>Eagle</td><td>{$row['id']}</td><td>{$row['dist']}</td><td>{$row['status']}</td><td>NA</td></tr>";
            }
            $case="CASE WHEN b.recentspecies='Swainsons Hawk' THEN 402 WHEN b.recentspecies='Red-tail Hawk' THEN 533 ELSE 1600 END";
            
            if ($id=="geojson") {
                $strQuery = 'SELECT Round(ST_Distance(b.geom::geography, '.$geojson.')) as dist, b.nest_id as id, b.recentstatus as status FROM dj_raptor b WHERE ST_DWithin(b.geom::geography, '.$geojson.', '.$case.') ORDER BY dist';
            } else {
                $strQuery = 'SELECT Round(ST_Distance(b.geom::geography, l.geom::geography)) as dist, b.nest_id as id, b.recentstatus as status FROM dj_raptor b JOIN dj_linear l ON ST_DWithin(b.geom::geography, l.geom::geography, '.$case.') WHERE l.project='.$id.' ORDER BY dist';
            }
            
//            echo $strQuery."<br><br>";
            $result = $pdo->query($strQuery);
            foreach($result AS $row) {
                $returnTable.="<tr><td>Raptor</td><td>{$row['id']}</td><td>{$row['dist']}</td><td>{$row['status']}</td><td>NA</td></tr>";
            }
            echo $returnTable;
        } catch(PDOException $e) {
            echo "ERROR: ".$e->getMessage();
        }
?>