<?php
    include "connect.php";
    $id = $_GET['id'];
    $sql = mysqli_query($conn, "select * from nopol where id = '$id'");
    $fetch = mysqli_fetch_assoc($sql);
    echo json_encode($fetch);