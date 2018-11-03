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
        if (isset($_POST['limit'])) {
            $limit=" LIMIT ".$_POST['limit'];
        } else {
            $limit="";
        }
        $dsn = "pgsql:host=localhost;dbname=webmap302;port=5432";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ];
        $pdo = new PDO($dsn, 'postgres', 'admin', $opt);
        //try...catch block in order to handle error better (if any)
        try{
          if(isset($_POST['distance'])){
            //If isset calculate buffer: Transform the coordinate to a UTM, calculate the buffer based on the distance (or field) and transform it back to the SRID of 4326.
            $result = $pdo->query("SELECT {$fields}, ST_AsGeoJSON(ST_Transform(ST_Buffer(ST_Transform(geom,26913),{$_POST['distance']}),4326), 5) AS geojson FROM {$table}{$where}{$order}{$limit}");
          }else{
            $result = $pdo->query("SELECT {$fields}, ST_AsGeoJSON(geom, 5) AS geojson FROM {$table}{$where}{$order}{$limit}");
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
        }catch(PDOException $e){
          //This 'ERROR' will be handled in the AJAX call. by checking if this word exist. (see call)
          echo "ERROR: ".$e->getMessage();
        }

    } else {
       //This 'ERROR' will be handled in the AJAX call. by checking if this word exist. (see call)
        echo "ERROR: No table parameter incuded with request";
    }

?>
