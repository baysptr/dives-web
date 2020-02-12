<?php
    date_default_timezone_set("Asia/Jakarta");
    include "connect.php";
    $nopol = strtoupper($_POST['key']);
    $tgl = date("Y-m-d");
    $chk = mysqli_query($conn, "select * from nopol where key_secret = '$nopol'");
    if(mysqli_num_rows($chk) > 0){
        $ftch = mysqli_fetch_assoc($chk);
        $npl = $ftch['nopol'];
        $check = mysqli_query($conn, "select * from monitoring where nopol = '$npl' and tgl_scan = '$tgl'");
        if (mysqli_num_rows($check) > 0){
            $hh = mysqli_fetch_assoc($check);
            $q = $hh['quantity'] + 1;
            mysqli_query($conn, "update monitoring set tgl_scan = '$tgl', quantity = '$q' where nopol = '$npl'");
            echo "SUCCESS UPDATE";
        }else{
            mysqli_query($conn, "insert into monitoring values ('', '$npl', '1','$tgl')");
            echo "SUCCESS INSERT";
        }
    }else{
        echo "key tidak diketahui";
    }