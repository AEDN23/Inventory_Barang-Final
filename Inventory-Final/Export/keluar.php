<?php
require "../backend/function.php";
session_start();

// Cek apakah pengguna sudah login dan memiliki role 'admin' atau 'user'
if (!isset($_SESSION['id']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'user')) {
    // Jika belum login atau bukan admin atau user, arahkan ke halaman login
    header("Location: ../index.php");
    exit();
}

// Proses selanjutnya jika sudah login

// Date collections for filters
$current_month_start = date('Y-m-01');
$current_month_end = date('Y-m-t');



$k_start = isset($_GET['start']) ? $_GET['start'] : $current_month_start;
$k_end = isset($_GET['end']) ? $_GET['end'] : $current_month_end;

$ambilsemuadatakeluar = mysqli_query($conn, "
    SELECT bk.*, mb.jenis_barang, mb.satuan, mb.nama_barang, mb.maker 
    FROM barang_keluar bk
    LEFT JOIN master_barang mb ON bk.kode_barang = mb.kode_barang
    WHERE DATE(bk.tanggal_keluar) BETWEEN '$k_start' AND '$k_end'");
?>
<html>

<head>
    <title>Export Barang Keluar</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="../../js/jquery.dataTables.js"></script>
</head>

<body>
    <div class="container">
        <h2 class="text-center">Data Barang</h2>
        <!-- <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="../admin/keluar">Halaman awal</a></li>
            <li class="breadcrumb-item active">Import Dokumen</li>
        </ol> -->

        <ol class="breadcrumb mb-4">
            <?php if ($_SESSION['role'] == 'admin') { ?>
                <li class="breadcrumb-item"><a href="../admin/keluar">Halaman awal</a></li>
            <?php } else if ($_SESSION['role'] == 'user') { ?>
                <li class="breadcrumb-item"><a href="../user/keluar">Halaman awal</a></li>
            <?php } ?>
            <li class="breadcrumb-item active">Import Dokumen</li>
        </ol>

        <a href="?start=<?= $current_month_start; ?>&end=<?= $current_month_end; ?>" class="btn btn-default">Bulan Ini</a>

        <h3>Filter Harian</h3>
        <form method="GET">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="">Start Date:</label>
                    <input type="date" name="start" class="form-control" onclick="this.showPicker()" required>
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

            <table class="table table-hover table-striped table-bordered " id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA BARANG</th>
                        <th>TANGGAL</th>
                        <th>USER</th>
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
                    while ($data = mysqli_fetch_array($ambilsemuadatakeluar)) {
                        $kode_barang = $data['kode_barang'];
                        $tanggal_keluar = $data['tanggal_keluar'];
                        $user = $data['user_id'];
                        $jenis_barang = $data['jenis_barang'];
                        $nama_barang = $data['nama_barang'];
                        $maker = $data['maker'];
                        $jumlah_keluar = $data['jumlah_keluar'];
                        $satuan = $data['satuan'];
                        $note = $data['note'];
                    ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $kode_barang; ?></td>
                            <td><?= date('d-m-Y', strtotime($tanggal_keluar)); ?></td>
                            <td><?= $user; ?></td>
                            <td><?= $jenis_barang; ?></td>
                            <td><?= $nama_barang; ?></td>
                            <td><?= $maker; ?></td>
                            <td><?= $jumlah_keluar; ?></td>
                            <td><?= $satuan ?></td>
                            <td><?= $note; ?></td>
                        </tr>
                    <?php
                    };
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
                        title: 'Barang Keluar'
                    },
                    {
                        extend: 'pdf',
                        title: 'Barang Keluar',
                        customize: function(doc) {
                            doc.content[1].table.body.forEach(function(row) {
                                row.forEach(function(cell) {
                                    cell.alignment = 'center';
                                });
                            });
                            doc.pageOrientation = 'landscape';
                            doc.content[1].table.widths = ['*', '*', '*', '*', '*', '*', '*', '*', '*'];
                            doc.content[1].layout = {
                                hLineWidth: function(i) {
                                    return 0.5;
                                },
                                vLineWidth: function(i) {
                                    return 0.5;
                                },
                                hLineColor: function(i) {
                                    return '#000';
                                },
                                vLineColor: function(i) {
                                    return '#000';
                                },
                                fillColor: function(i) {
                                    return (i === 0 || i === doc.content[1].table.body.length) ? '#ccc' : null;
                                }
                            };
                            // Tambahkan pengaturan agar tabel berada di tengah halaman
                            doc.content[1].margin = [0, 0, 0, 0]; // Hapus margin bawaan tabel
                            doc.content[1].alignment = 'center'; // Rata tengah seluruh tabel
                        }
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