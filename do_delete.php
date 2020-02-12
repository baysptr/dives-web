<?php
    include "connect.php";
    $id = $_GET['id'];
    $sql = mysqli_query($conn, "delete from nopol where id = '$id'");
    if($sql){
        echo 1;
    }else{
        echo 0;
    }