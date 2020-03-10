<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Manage Data Vehicle</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://getbootstrap.com/docs/4.4/examples/navbar-fixed/navbar-top-fixed.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet">
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
            <li class="nav-item">
                <a class="nav-link" href="manage_data.php">Manage Data</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="info.php">Summary</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="#">Data</a>
            </li>
        </ul>
    </div>
</nav>

<main role="main" class="container">
    <div class="jumbotron">
        <h1>Manage Data Vehicles (DiVes)</h1>
        <div class="lead">
            <table class="table table-striped table-bordered" id="myData" style="background-color: white">
                <thead>
                    <tr>
                        <td><strong>NAMA</strong></td>
                        <td><strong>NOPOL</strong></td>
                        <td><strong>QUANTITY</strong></td>
                        <td><strong>LAST SEEN</strong></td>
                    </tr>
                </thead>
                <tbody>
                <?php
                include "connect.php";
                $sql = mysqli_query($conn, "SELECT m.nopol, m.quantity, m.tgl_scan, n.nama FROM monitoring as m join nopol as n on n.nopol=m.nopol ORDER BY m.tgl_scan DESC");
                while($data = mysqli_fetch_array($sql)){
                ?>
                    <tr>
                        <td><?= $data['nama'] ?></td>
                        <td><?= $data['nopol'] ?></td>
                        <td><?= $data['quantity'] ?></td>
                        <td><?= $data['tgl_scan'] ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#myData').DataTable({
            "order": [[3, "desc"]]
        });
    } );
</script>
</body>
</html>
