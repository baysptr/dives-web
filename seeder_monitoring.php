<?php
    include "connect.php";
    function dateRange() {
        $f = strtotime('2020-02-01');
        $l = strtotime('2020-02-13');
        $int= rand($f,$l);
        $string = date("Y-m-d",$int);
        return $string;
    }
    for($i=0;$i<20;$i++){
        $randDate = dateRange();
        $randValue = rand(5,50);
        mysqli_query($conn, "insert into monitoring values ('', 'L 6125 QZ', '$randValue','$randDate')");
    }
    for($i=0;$i<20;$i++){
        $randDate = dateRange();
        $randValue = rand(5,50);
        mysqli_query($conn, "insert into monitoring values ('', 'W 3266 DS', '$randValue','$randDate')");
    }
for($i=0;$i<20;$i++){
    $randDate = dateRange();
    $randValue = rand(5,50);
    mysqli_query($conn, "insert into monitoring values ('', 'B 145 AA', '$randValue','$randDate')");
}
for($i=0;$i<20;$i++){
    $randDate = dateRange();
    $randValue = rand(5,50);
    mysqli_query($conn, "insert into monitoring values ('', 'S 1974 WR', '$randValue','$randDate')");
}