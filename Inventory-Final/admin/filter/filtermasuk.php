<?php
require "../../backend/function.php";

// Ambil parameter filter dari URL
$start_date = isset($_GET['start']) ? $_GET['start'] : null;
$end_date = isset($_GET['end']) ? $_GET['end'] : null;
$supplier = isset($_GET['supplier']) ? $_GET['supplier'] : null;

// Query untuk mengambil data sesuai dengan filter
$query = "
    SELECT bm.transaksi, bm.kode_barang, bm.tanggal_masuk, s.nama_supplier, mb.jenis_barang, mb.nama_barang, mb.maker, mb.satuan, bm.jumlah_masuk, bm.note
    FROM barang_masuk bm
    LEFT JOIN master_barang mb ON bm.kode_barang = mb.kode_barang
    LEFT JOIN supplier s ON bm.supplier_id = s.id
";

// Filter kondisi berdasarkan parameter
$conditions = [];
if ($start_date) {
    $conditions[] = "bm.tanggal_masuk >= '$start_date'";
}
if ($end_date) {
    $conditions[] = "bm.tanggal_masuk <= '$end_date'";
}
if ($supplier) {
    $conditions[] = "bm.supplier_id = '$supplier'";
}

// Gabungkan kondisi jika ada
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

// Tambahkan urutan berdasarkan tanggal
$query .= " ORDER BY bm.tanggal_masuk DESC";

// Jalankan query
$result = mysqli_query($conn, $query);
?>

<html>

<head>
    <title>Export barang masuk</title>
    <link rel="stylesheet" href="../../../css/bootstrap.min.css">
    <script src="../../../js/jquery.min.js"></script>
    <script src="../../../js/popper.min.js"></script>
    <script src="../../../js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../../css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../../css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../../css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="../../../js/jquery.dataTables.js"></script>
</head>

<body>
    <div class="container">
        <h2 class="text-center">Data Barang</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="../index">Halaman awal</a></li>
            <li class="breadcrumb-item active">Import Dokumen</li>
        </ol>
        <p>Data barang masuk dari tanggal <b><?= date('d F Y', strtotime($start_date)); ?></b> sampai <b><?= date('d F Y', strtotime($end_date)); ?></b>.</p>

        <div class="data-tables datatable-dark">

            <table class="table table-hover table-striped table-bordered " id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr class="text-center">
                        <th>TRANSAKSI</th>
                        <th>NAMA BARANG</th>
                        <th>TANGGAL</th>
                        <th>SUPPLIER</th>
                        <th>JENIS BARANG</th>
                        <th>DESKRIPSI</th>
                        <th>MAKER</th>
                        <th>SATUAN</th>
                        <th>JUMLAH</th>
                        <th>NOTE</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($data = mysqli_fetch_array($result)) {
                        $transaksi = $data['transaksi'];
                        $kodebarang = $data['kode_barang'];
                        $tanggal = $data['tanggal_masuk'];
                        $supplier = $data['nama_supplier'];
                        $jenisbarang = $data['jenis_barang'];
                        $namabarang = $data['nama_barang'];
                        $maker = $data['maker'];
                        $satuan = $data['satuan'];
                        $qty = $data['jumlah_masuk'];
                        $note = $data['note'];
                    ?>
                        <tr class="text-center">
                            <td><?= $transaksi; ?></td>
                            <td><?= $kodebarang; ?></td>
                            <td><?= $tanggal; ?></td>
                            <td><?= $supplier; ?></td>
                            <td><?= $jenisbarang; ?></td>
                            <td><?= $namabarang; ?></td>
                            <td><?= $maker; ?></td>
                            <td><?= $satuan; ?></td>
                            <td><?= $qty; ?></td>
                            <td><?= $note; ?></td>
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
                        title: 'Laporan barang masuk'
                    },
                    {
                        extend: 'pdf',
                        title: 'Laporan barang masuk',
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
                            doc.content[1].margin = [0, 0, 0, 0];
                            doc.content[1].alignment = 'center';
                        }
                    },
                    'print'
                ]
            });
        });
    </script>


    <script src="../../../js/jquery-3.5.1.js"></script>
    <script src="../../../js/jquery.dataTables.min.js"></script>
    <script src="../../../js/dataTables.buttons.min.js"></script>
    <script src="../../../js/buttons.flash.min.js"></script>
    <script src="../../../js/jszip.min.js"></script>
    <script src="../../../js/pdfmake.min.js"></script>
    <script src="../../../js/vfs_fonts.js"></script>
    <script src="../../../js/buttons.html5.min.js"></script>
    <script src="../../../js/buttons.print.min.js"></script>


</body>

</html>