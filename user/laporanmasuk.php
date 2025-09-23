<?php
$title = "Laporan Masuk";
include 'layout/header.php';

// Ambil parameter filter dari URL
$start_date = isset($_GET['start']) ? $_GET['start'] : null;
$end_date = isset($_GET['end']) ? $_GET['end'] : null;
$supplier = isset($_GET['supplier']) ? $_GET['supplier'] : null;

// Default query
$query = "SELECT bm.transaksi, bm.kode_barang, bm.tanggal_masuk, s.nama_supplier, mb.jenis_barang, mb.nama_barang, mb.maker, mb.satuan, bm.jumlah_masuk, bm.note 
          FROM barang_masuk bm
          LEFT JOIN master_barang mb ON bm.kode_barang = mb.kode_barang
          LEFT JOIN supplier s ON bm.supplier_id = s.id";

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


<main>


    <div class="container-fluid">
        <h1 class="mt-4">LAPORAN ITEM MASUK</h1>
        <br>
        <br>
        <?php if (!isset($_GET['hideformfilter'])) { ?>
            <h3>Quick Filter</h3>
            <a href="filter/filtermasuk.php?start=<?= date('Y-m-d'); ?>&end=<?= date('Y-m-d'); ?>" class="btn btn-dark">Today</a>
            <a href="filter/filtermasuk.php?start=<?= date('Y-m-d', strtotime('-1 day')); ?>&end=<?= date('Y-m-d', strtotime('-1 day')); ?>" class="btn btn-dark">Yesterday</a>
            <a href="filter/filtermasuk.php?start=<?= date('Y-m-d', strtotime('-7 days')); ?>&end=<?= date('Y-m-d'); ?>" class="btn btn-dark">Last Week</a>
            <a href="filter/filtermasuk.php?start=<?= date('Y-m-d', strtotime('-1 month')); ?>&end=<?= date('Y-m-d'); ?>" class="btn btn-dark">Last Month</a>
            <a href="filter/filtermasuk.php?start=<?= date('Y-m-d', strtotime('-6 months')); ?>&end=<?= date('Y-m-d'); ?>" class="btn btn-dark">Last 6 Month</a>
            <a href="filter/filtermasuk.php?start=<?= date('Y-m-d', strtotime('-1 year')); ?>&end=<?= date('Y-m-d'); ?>" class="btn btn-dark">Last Year</a>
            <a href="<?= site_url(); ?>/export/exportmasuk.php" class="btn btn-danger">Show All Without Filter</a>

            <br><br>
            <h3>Advance Filter</h3>
            <form method="GET" action="filter/filtermasuk.php">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="">Start date:</label>
                        <input type="date" name="start" class="form-control" onclick="this.showPicker()" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">End date:</label>
                        <input type="date" name="end" class="form-control" onclick="this.showPicker()" value="<?= date('Y-m-d'); ?>" required>
                    </div>
                    <input type="hidden" name="hideformfilter" value="true">
                    <div class="col-md-3 form-group">
                        <label for="">Supplier:</label>
                        <select name="supplier" class="form-control me-2">
                            <option value="" hidden>Supplier</option>
                            <?php
                            // Mengambil data dari tabel supplier
                            $querySupplier = "SELECT id, nama_supplier FROM supplier";
                            $resultSupplier = mysqli_query($conn, $querySupplier);
                            while ($u = mysqli_fetch_assoc($resultSupplier)) {
                                echo '<option value="' . $u['id'] . '">' . $u['nama_supplier'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <input type="submit" name="filter" class="btn btn-primary" value="Submit">

            </form>
        <?php } ?>


        <div class="data-tables datatable-dark">

        </div>
    </div>
</main>





<?php include "../footer.php"; ?>