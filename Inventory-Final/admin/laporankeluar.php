<?php
$title = "Laporan keluar";
include 'layout/header.php';

// Ambil parameter filter dari URL
$start_date = isset($_GET['start']) ? $_GET['start'] : null;
$end_date = isset($_GET['end']) ? $_GET['end'] : null;
$user = isset($_GET['user']) ? $_GET['user'] : null;

// Default query
$query = "SELECT bk.transaksi, bk.kode_barang, bk.tanggal_keluar, u.nama_lengkap AS nama_user, mb.jenis_barang, mb.nama_barang, mb.maker, mb.satuan, bk.jumlah_keluar, bk.note 
          FROM barang_keluar bk
          LEFT JOIN master_barang mb ON bk.kode_barang = mb.kode_barang
          LEFT JOIN user u ON bk.user_id = u.id";

// Filter kondisi berdasarkan parameter
$conditions = [];
if ($start_date) {
    $conditions[] = "bk.tanggal_keluar >= '$start_date'";
}
if ($end_date) {
    $conditions[] = "bk.tanggal_keluar <= '$end_date'";
}
if ($user) {
    $conditions[] = "bk.user_id = '$user'";
}

// Gabungkan kondisi jika ada
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

// Tambahkan urutan berdasarkan tanggal
$query .= " ORDER BY bk.tanggal_keluar DESC";

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
            <a href="filter/filterkeluar.php?start=<?= date('Y-m-d'); ?>&end=<?= date('Y-m-d'); ?>" class="btn btn-dark">Today</a>
            <a href="filter/filterkeluar.php?start=<?= date('Y-m-d', strtotime('-1 day')); ?>&end=<?= date('Y-m-d', strtotime('-1 day')); ?>" class="btn btn-dark">Yesterday</a>
            <a href="filter/filterkeluar.php?start=<?= date('Y-m-d', strtotime('-7 days')); ?>&end=<?= date('Y-m-d'); ?>" class="btn btn-dark">Last Week</a>
            <a href="filter/filterkeluar.php?start=<?= date('Y-m-d', strtotime('-1 month')); ?>&end=<?= date('Y-m-d'); ?>" class="btn btn-dark">Last Month</a>
            <a href="filter/filterkeluar.php?start=<?= date('Y-m-d', strtotime('-6 months')); ?>&end=<?= date('Y-m-d'); ?>" class="btn btn-dark">Last 6 Month</a>
            <a href="filter/filterkeluar.php?start=<?= date('Y-m-d', strtotime('-1 year')); ?>&end=<?= date('Y-m-d'); ?>" class="btn btn-dark">Last Year</a>
            <a href="<?= site_url(); ?>/export/exportkeluar.php" class="btn btn-danger">Show All Without Filter</a>

            <br><br>
            <h3>Advance Filter</h3>
            <form method="GET" action="filter/filterkeluar.php">
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
                        <label for="">User | Pengguna:</label>
                        <select name="user" class="form-control me-2">
                            <option value="" hidden>~ Select User atau Pengguna</option>
                            <?php
                            // Mengambil data dari tabel user
                            $queryUser = "SELECT id, nama_lengkap FROM user";
                            $resultUser = mysqli_query($conn, $queryUser);
                            while ($u = mysqli_fetch_assoc($resultUser)) {
                                echo '<option value="' . $u['id'] . '">' . $u['nama_lengkap'] . '</option>';
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