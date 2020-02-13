<?php
    date_default_timezone_set('Asia/Jakarta');
    include "connect.php";
    function dateRange() {
        $f = strtotime('2020-02-01');
        $l = strtotime('2020-02-13');
        $int= rand($f,$l);
        $string = date("Y-m-d",$int);
        return $string;
    }
    function seeder_mon($nopol, $tgl, $sort, $quan){
        include "connect.php";
        $cek = mysqli_query($conn, "select * from monitoring where nopol = '$nopol' and tgl_scan = '$tgl'");
        if(mysqli_num_rows($cek) > 0){
            //ada
            $ambil = mysqli_fetch_assoc($cek);
            $id = $ambil['id'];
            $q = (int)$ambil['quantity'] + (int)$quan;
            mysqli_query($conn, "update monitoring set quantity = '$q' where id = '$id'");
            echo "SUCCESS UPDATE :".$sort.": <br/>";
        }else{
            //tidak ada
            mysqli_query($conn, "insert into monitoring values ('', '$nopol', '$quan', '$tgl')");
            echo "SUCCESS INSERT :".$sort.": <br/>";
        }
    }
    $tgl = date("Y-m-d H:i:s");
    mysqli_query($conn, "insert into nopol values ('', '122333444455555', 'SUPRIANTO', 'L 6125 QZ', '555554444333221', 'KEY_SECRET1', '$tgl')");
    mysqli_query($conn, "insert into nopol values ('', '908070605040302', 'CHAYANTO', 'W 3266 DS', '555554444333221', 'KEY_SECRET2', '$tgl')");
    mysqli_query($conn, "insert into nopol values ('', '593857462811574', 'BAGYO', 'B 145 AA', '214576896534231', 'KEY_SECRET3', '$tgl')");
    mysqli_query($conn, "insert into nopol values ('', '236749586739058', 'SANTO', 'S 1974 WR', '234576984543675', 'KEY_SECRET4', '$tgl')");
    for($i=0;$i<20;$i++){
        $randDate = dateRange();
        $randValue = rand(5,50);
        seeder_mon("L 6125 QZ", $randDate, $i, $randValue);
//        mysqli_query($conn, "insert into monitoring values ('', 'L 6125 QZ', '$randValue','$randDate')");
    }
    for($i=0;$i<20;$i++){
        $randDate = dateRange();
        $randValue = rand(5,50);
        seeder_mon("W 3266 DS", $randDate, $i, $randValue);
    }
    for($i=0;$i<20;$i++){
        $randDate = dateRange();
        $randValue = rand(5,50);
        seeder_mon("B 145 AA", $randDate, $i, $randValue);
    }
    for($i=0;$i<20;$i++){
        $randDate = dateRange();
        $randValue = rand(5,50);
        seeder_mon("S 1974 WR", $randDate, $i, $randValue);
    }