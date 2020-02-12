<?php
    header("Content-type: text/json");
    include "connect.php";
    $sql = mysqli_query($conn, "select distinct nopol from monitoring order by nopol asc");
    $data = array();
    while($r = mysqli_fetch_array($sql)){
        $row['name'] = $r['nopol'];
        $lo = mysqli_query($conn, "select quantity, tgl_scan from monitoring where nopol = '$r[nopol]'order by tgl_scan asc, id asc");
        $dt = array();
        while($t = mysqli_fetch_array($lo)){
            $kl = array($t['tgl_scan'], (int)$t['quantity']);
            array_push($dt, $kl);
        }
        $row['data'] = $dt;
        array_push($data, $row);
    }
    echo json_encode($data);
//    $pretty_respon = array();
