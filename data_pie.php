<?php
    header("Content-type: text/json");
    include "connect.php";
    $sql = mysqli_query($conn, "SELECT nopol, SUM(quantity) as q FROM monitoring GROUP BY nopol");
    $datas = array();
    while ($r = mysqli_fetch_array($sql)){
        $row['name'] = $r['nopol'];
        $row['y'] = (int)$r['q'];
        array_push($datas, $row);
    }
    echo json_encode($datas);