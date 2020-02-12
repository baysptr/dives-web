<?php
    date_default_timezone_set("Asia/Jakarta");
    include "connect.php";
    $id = $_POST['id'];
    $nama = strtoupper($_POST['nama']);
    $nik = $_POST['nik'];
    $nopol = strtoupper($_POST['nopol']);
    $nomor_stnk = $_POST['nomor_stnk'];
    $tgl = date("Y-m-d H:i:s");
    $key_secret = uniqid("KEYSECRET_");

    if(checkNopol($nopol) == true){
        echo "<script>alert('Maaf nopol yang anda masukan sudah ada!');window.location = 'manage_data.php';</script>";
    }else{
        if($id){
            //UPDATE
            $sql = mysqli_query($conn, "update nopol set nik = '$nik', nama = '$nama', nopol = '$nopol', nomor_stnk = '$nomor_stnk', tgl_post = '$nomor_stnk' where id = '$id'");
            if($sql){
                echo "<script>window.location = 'manage_data.php';</script>";
            }else{
                echo "<script>alert('Maaf terjadi kesalahan sistem, mohon hubungi admin');window.location = 'manage_data.php';</script>";
            }
        }else{
            //INSERT
            $sql = mysqli_query($conn, "insert into nopol (nik, nama, nopol, nomor_stnk, key_secret, tgl_post) values ('$nik', '$nama', '$nopol', '$nomor_stnk', '$key_secret', '$tgl')");
            if($sql){
                echo "<script>window.location = 'manage_data.php';</script>";
            }else{
                echo "<script>alert('Maaf terjadi kesalahan sistem, mohon hubungi admin');window.location = 'manage_data.php';</script>";
            }
        }
    }

    function checkNopol($ks){
        $sql = mysqli_query($conn, "select * from nopol where nopol = '$ks'");
        $check = mysqli_num_rows($sql);
        if($check > 0){
            return true;
        }else{
            return false;
        }
    }