<?php
require "../backend/function.php";
session_start();

// Cek apakah pengguna sudah login dan memiliki role 'admin' atau 'user'
if (!isset($_SESSION['id']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'user')) {
    header("Location: ../index.php");
    exit();
}

// Date collections for filters
$current_month_start = date('Y-m-01');
$current_month_end = date('Y-m-t');

$k_start = isset($_GET['start']) ? $_GET['start'] : $current_month_start;
$k_end = isset($_GET['end']) ? $_GET['end'] : $current_month_end;

$ambilsemuadatastock = mysqli_query($conn, "
    SELECT bm.*, mb.jenis_barang, mb.satuan, mb.nama_barang, mb.maker 
    FROM barang_masuk bm
    LEFT JOIN master_barang mb ON bm.kode_barang = mb.kode_barang
    WHERE DATE(bm.tanggal_masuk) BETWEEN '$k_start' AND '$k_end'");
?>

<html>

<head>
    <title>Export Barang Masuk</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../css/buttons.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="../../js/jquery.dataTables.js"></script>
</head>

<body>
    <div class="container">
        <h2 class="text-center">Data Barang Masuk</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="../admin/masuk">Halaman awal</a></li>
            <li class="breadcrumb-item active">Export Barang Masuk</li>
        </ol>

        <a href="?start=<?= $current_month_start; ?>&end=<?= $current_month_end; ?>" class="btn btn-default">Bulan Ini</a>

        <h3>Filter Harian</h3>
        <form method="GET">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="">Start Date:</label>
                    <input type="date" name="start" class="form-control" onclick="this.showPicker()"   required>
                </div>
                <div class="col-md-3 form-group">
                    <label for="">End Date:</label>
                    <input type="date" name="end" class="form-control" onclick="this.showPicker()" value="<?= date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-3 form-group">
                    <input type="submit" name="filter" class="btn btn-primary" value="Submit">
                </div>
            </div>
        </form>

        <p>Data barang masuk dari tanggal <b><?= date('d F Y', strtotime($k_start)); ?></b> sampai <b><?= date('d F Y', strtotime($k_end)); ?></b>.</p>

        <div class="data-tables datatable-dark">
            <table class="table table-hover table-striped table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA BARANG</th>
                        <th>TANGGAL</th>
                        <th>SUPPLIER</th>
                        <th>JENIS BARANG</th>
                        <th>DESKRIPSI</th>
                        <th>MAKER</th>
                        <th>JUMLAH</th>
                        <th>SATUAN</th>
                        <th>NOTE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    while ($data = mysqli_fetch_array($ambilsemuadatastock)) {
                    ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $data['kode_barang']; ?></td>
                            <td><?= $data['tanggal_masuk']; ?></td>
                            <td><?= $data['supplier']; ?></td>
                            <td><?= $data['jenis_barang']; ?></td>
                            <td><?= $data['nama_barang']; ?></td>
                            <td><?= $data['maker']; ?></td>
                            <td><?= $data['jumlah_masuk']; ?></td>
                            <td><?= $data['satuan']; ?></td>
                            <td><?= $data['note']; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        title: 'Barang Masuk'
                    },
                    {
                        extend: 'pdf',
                        title: 'Barang Masuk'
                    },
                    'print'
                ]
            });
        });
    </script>

    <script src="../../js/jquery-3.5.1.js"></script>
    <script src="../../js/jquery.dataTables.min.js"></script>
    <script src="../../js/dataTables.buttons.min.js"></script>
    <script src="../../js/buttons.flash.min.js"></script>
    <script src="../../js/jszip.min.js"></script>
    <script src="../../js/pdfmake.min.js"></script>
    <script src="../../js/vfs_fonts.js"></script>
    <script src="../../js/buttons.html5.min.js"></script>
    <script src="../../js/buttons.print.min.js"></script>
</body>

</html>