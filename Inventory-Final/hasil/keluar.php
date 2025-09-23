<?php
require "../backend/function.php";
session_start();

// Cek apakah ada parameter 'transaksi' di URL
if (!isset($_GET['transaksi'])) {
    echo "Transaksi tidak ditemukan!";
    exit();
}

$transaksi = mysqli_real_escape_string($conn, $_GET['transaksi']);

// Ambil data barang berdasarkan transaksi yang diterima


$query = "
    SELECT bk.*, mb.jenis_barang, mb.nama_barang, mb.maker, mb.satuan, u.nama_lengkap 
    FROM barang_keluar bk
    JOIN master_barang mb ON bk.kode_barang = mb.kode_barang 
    JOIN user u ON bk.user_id = u.id
    WHERE bk.transaksi = '$transaksi'
";

// Eksekusi query dan cek errornya
$result = mysqli_query($conn, $query);

// Cek apakah query berhasil dieksekusi
if (!$result) {
    die('Query error: ' . mysqli_error($conn));
}

?>
<html>

<head>
    <title>Detail Barang keluar</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="../../js/jquery.dataTables.js"></script>
    <style>
        @media print {

            @page {
                margin: 0;
                /* Atur margin menjadi nol */
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center">Data Barang</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="../admin/keluar">Halaman awal</a></li>
            <li class="breadcrumb-item active">Import Dokumen</li>
        </ol>

        <div class="data-tables datatable-dark">
            <table class="table table-hover table-striped table-bordered " id="dataTable" width="100%" cellspacing="0">
                <thead>

                    <tr class="text-center">
                        <th>NAMA BARANG</th>
                        <th>TANGGAL</th>
                        <th>PENGGUNA</th>
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
                    // Cek jika ada hasil
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                <td>{$row['kode_barang']}</td>
                                <td>{$row['tanggal_keluar']}</td>
                                <td>{$row['nama_lengkap']}</td>
                                <td>{$row['jenis_barang']}</td>
                                <td>{$row['nama_barang']}</td>
                                <td>{$row['maker']}</td>
                                <td>{$row['jumlah_keluar']}</td>
                                <td>{$row['satuan']}</td>
                                <td>{$row['note']}</td>
                                <td>{$row['status_approve']}</td>
                              </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>Tidak ada data untuk transaksi ini</td></tr>";
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
                        title: 'Barang keluar'
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