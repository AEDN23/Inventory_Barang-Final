<?php
require "../backend/function.php";
session_start();

// Cek apakah pengguna sudah login dan memiliki role 'admin'
// if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
//     // Jika belum login atau bukan admin, arahkan ke halaman login
//     header("Location: ../index.php");
//     exit();
// }

// Proses selanjutnya jika sudah login


// Cek apakah pengguna sudah login dan memiliki role 'admin' atau 'user'
if (!isset($_SESSION['id']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'user')) {
    // Jika belum login atau bukan admin atau user, arahkan ke halaman login
    header("Location: ../index.php");
    exit();
}

// Proses selanjutnya jika sudah login
?>
<html>

<head>
    <title>Export List Barang</title>
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
        <ol class="breadcrumb mb-4">
            <?php if ($_SESSION['role'] == 'admin') { ?>
                <li class="breadcrumb-item"><a href="../admin/keluar">Halaman awal</a></li>
            <?php } else if ($_SESSION['role'] == 'user') { ?>
                <li class="breadcrumb-item"><a href="../user/keluar">Halaman awal</a></li>
            <?php } ?>
            <li class="breadcrumb-item active">Import Dokumen</li>
        </ol>

        <div class="data-tables datatable-dark">

            <table class="table table-hover table-striped table-bordered " id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr class="text-center">
                        <th>TRANSAKSI</th>
                        <th>NAMA BARANG</th>
                        <th>TANGGAL</th>
                        <th>USER</th>
                        <th>JENIS BARANG</th>
                        <th>DESKRIPSI</th>
                        <th>MAKER</th>
                        <th>JUMLAH</th>
                        <th>SATUAN</th>
                        <th>NOTE</th>
                        <th>STATUS</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $ambilsemuadatakeluar = mysqli_query($conn, "
                           SELECT bk.id, bk.kode_barang, bk.tanggal_keluar, bk.user_id,
                                  bk.jumlah_keluar, bk.note, bk.status_approve, bk.alasan,
                                  mb.jenis_barang, mb.nama_barang, mb.maker, mb.satuan, bk.transaksi,
                                  u.nama_lengkap
                           FROM barang_keluar bk
                           LEFT JOIN master_barang mb ON bk.kode_barang = mb.kode_barang
                           LEFT JOIN user u ON bk.user_id = u.id
                           ORDER BY bk.tanggal_keluar DESC
                       ");

                    $i = 1;
                    while ($data = mysqli_fetch_array($ambilsemuadatakeluar)) {
                        $kode_barang = $data['kode_barang'];
                        $tanggal_keluar = $data['tanggal_keluar'];
                        $username = $data['nama_lengkap'];
                        $jenis_barang = $data['jenis_barang'];
                        $nama_barang = $data['nama_barang'];
                        $maker = $data['maker'];
                        $jumlah_keluar = $data['jumlah_keluar'];
                        $satuan = $data['satuan'];
                        $note = $data['note'];
                        $transaksi = $data['transaksi'];
                        $status_approve = $data['status_approve'];
                        $approve_status = ''; // Inisialisasi variabel
                        if ($status_approve == 'pending') {
                            $approve_status = '<span class="badge bg-warning">Pending</span>';
                        } elseif ($status_approve == 'approved') {
                            $approve_status = '<span class="badge bg-success">Approved</span>';
                        } elseif ($status_approve == 'rejected') {
                            $approve_status = '<span class="badge bg-danger">Rejected</span>';
                        }
                    ?>
                        <tr class="text-center">
                            <td><?= $transaksi; ?></td>
                            <td><?= $kode_barang; ?></td>
                            <td><?= date('d-m-Y', strtotime($tanggal_keluar)); ?></td>
                            <td><?= $username; ?></td>
                            <td><?= $jenis_barang; ?></td>
                            <td><?= $nama_barang; ?></td>
                            <td><?= $maker; ?></td>
                            <td><?= $jumlah_keluar; ?></td>
                            <td><?= $satuan; ?></td>
                            <td><?= $note; ?></td>
                            <td><?= $approve_status; ?>

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
                        title: 'Laporan Barang Keluar',
                    },
                    {
                        extend: 'pdf',
                        title: 'Laporan Barang Keluar',
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