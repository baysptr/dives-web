<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Manage Data Vehicle</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://getbootstrap.com/docs/4.4/examples/navbar-fixed/navbar-top-fixed.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="#">DiVes</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="#">Manage Data</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="info.php">Information</a>
            </li>
        </ul>
    </div>
</nav>

<main role="main" class="container">
    <div class="jumbotron">
        <h1>Manage Data Vehicles (DiVes)</h1>
        <div class="lead">
            <table class="table table-bordered table-hover">
                <thead class="table-active">
                    <tr>
                        <td><strong>NO</strong></td>
                        <td><strong>NIK</strong></td>
                        <td><strong>NAMA</strong></td>
                        <td><strong>NOPOL</strong></td>
                        <td colspan="3"><button onclick="opModal()" class="btn btn-sm btn-block btn-primary"><span class="fa fa-plus-square"></span> Add Data</button></td>
                    </tr>
                </thead>
                <tbody style="background-color: lightblue">
                <?php
                include "connect.php";
                $no = 1;
                $sql = mysqli_query($conn, "select * from nopol order by tgl_post desc");
                while($data = mysqli_fetch_array($sql)){
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $data['nik'] ?></td>
                        <td><?= $data['nama'] ?></td>
                        <td><?= $data['nopol'] ?></td>
                        <td><a href="javascript:;" onclick="viewKey(<?= $data['id'] ?>)" class="btn btn-sm btn-block btn-info"><span class="fa fa-eye"></span> Key</a></td>
                        <td><a href="javascript:;" onclick="updtData(<?= $data['id'] ?>)" class="btn btn-sm btn-block btn-warning"><span class="fa fa-edit"></span></a></td>
                        <td><a href="javascript:;" onclick="delData(<?= $data['id'] ?>)" class="btn btn-sm btn-block btn-danger"><span class="fa fa-trash"></span></a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="manageModal" tabindex="-1" role="dialog" aria-labelledby="manageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageModalLabel"></h5>
                <button type="button" class="close" onclick="clModal()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="frManage" action="do_manage.php" method="post">
                <input type="hidden" id="id" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label><strong>NIK</strong></label>
                        <input type="text" name="nik" id="nik" class="form-control">
                    </div>
                    <div class="form-group">
                        <label><strong>NAMA</strong></label>
                        <input type="text" name="nama" id="nama" class="form-control">
                    </div>
                    <div class="form-group">
                        <label><strong>NOPOL</strong></label>
                        <input type="text" name="nopol" id="nopol" class="form-control">
                    </div>
                    <div class="form-group">
                        <label><strong>NOMOR STNK</strong></label>
                        <input type="text" name="nomor_stnk" id="nomor_stnk" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="clModal()">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnAction"></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/js/all.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js" crossorigin="anonymous"></script>
<script>
    function viewKey(id) {
        $.get("do_where.php?id="+id, function (data) {
            var obj = JSON.parse(data);
            Swal.fire(
                'Warning!',
                'Key: <strong>'+obj.key_secret+'</strong>',
                'warning'
            )
        });
    }

    function delData(id) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Apakah anda yakin akan hapus data ini, jika data dihapus maka sistem tidak bisa mendeteksi NOPOL anda!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.get("do_delete.php?id="+id, function () {
                    location.reload();
                });
            }
        })
    }

    function opModal() {
        $("#manageModalLabel").html("Tambah Data");
        $("#btnAction").html("Simpan Data");
        $("#manageModal").modal("show");
    }

    function clModal() {
        document.getElementById("frManage").reset();
        $("#manageModal").modal("hide");
    }

    function updtData(id) {
        $.get("do_where.php?id="+id, function (data) {
            var obj = JSON.parse(data);
            // console.log(obj);
            $("#id").val(obj.id);
            $("#nik").val(obj.nik);
            $("#nama").val(obj.nama);
            $("#nopol").val(obj.nopol);
            $("#nomor_stnk").val(obj.nomor_stnk);
            $("#manageModalLabel").html("Form Ubah Data");
            $("#btnAction").html("Ubah Data");
            $("#manageModal").modal("show");
        })
    }
</script>
</body>
</html>
