<?php
$title = "LIST BARANG | Elastomix";
require "layout/header.php";
// require "../BackEnd/function.php";
// require_once("../BackEnd/check_role_user.php");
?>

<main>
    <div class="container-fluid">

        <h1 class="mt-4 text-center mb-5">List Data Item</h1>

        <div>
            <!-- Chart Item  semua form-->
            <?php

            $total_barang_masuk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah_masuk) AS total FROM barang_masuk"))['total'] ?? 0;
            $total_barang_keluar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah_keluar) AS total FROM barang_keluar WHERE status_approve = 'approved'"))['total'] ?? 0;
            $total_stok = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM master_barang"))['total'] ?? 0;
            $total_jenis_barang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM master_barang"))['total'] ?? 0;
            ?>
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4" style="border-radius: 20px;">
                        <div class="card-body">
                            <h5>Total Barang Masuk</h5>
                            <h2><?= $total_barang_masuk; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4" style="border-radius: 20px;">
                        <div class="card-body">
                            <h5>Total Barang Keluar</h5>
                            <h2><?= $total_barang_keluar; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4" style="border-radius: 20px;">
                        <div class="card-body">
                            <h5>Total Stok</h5>
                            <h2><?= $total_stok; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-info text-white mb-4" style="border-radius: 20px;">
                        <div class="card-body">
                            <h5>Jenis Barang</h5>
                            <h2><?= $total_jenis_barang; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--  Chart Item  semua form  END-->



        <!-- Tabel Dashboard Start -->
        <div class="card mb-4">
            <div class="card-header ">
                <i class="fas fa-table me-1"></i>TABEL LIST BARANG <a href="../export/exportlist" style="color: white; text-decoration: none;" class='ms-2'> <button style="border-radius: 10px;" class="btn btn-success">
                        Ekspor Dokumen
                    </button></a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered " id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th>NO</th>
                                <th>NAMA BARANG</th>
                                <th>JENIS BARANG</th>
                                <th>DESKRIPSI</th>
                                <th>GARANSI</th>
                                <th>IN ITEM</th>
                                <th>OUT ITEM</th>
                                <th>STOK</th>
                                <th>SATUAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ambilsemuastock = mysqli_query($conn, "
                                SELECT 
                                master_barang.jumlah, garansi,
                                    master_barang.kode_barang, 
                                    master_barang.nama_barang, 
                                    master_barang.jenis_barang, 
                                    master_barang.satuan, 
                                    COALESCE((SELECT SUM(jumlah_masuk) FROM barang_masuk WHERE barang_masuk.kode_barang = master_barang.kode_barang), 0) AS jumlah_masuk, 
                                    COALESCE((SELECT SUM(jumlah_keluar) FROM barang_keluar WHERE barang_keluar.kode_barang = master_barang.kode_barang AND barang_keluar.status_approve = 'approved'), 0) AS jumlah_keluar, 
                                    (COALESCE((SELECT SUM(jumlah_masuk) FROM barang_masuk WHERE barang_masuk.kode_barang = master_barang.kode_barang), 0) - 
                                    COALESCE((SELECT SUM(jumlah_keluar) FROM barang_keluar WHERE barang_keluar.kode_barang = master_barang.kode_barang AND barang_keluar.status_approve = 'approved'), 0)) AS stok 
                                FROM master_barang
                            ");
                            $i = 1;
                            while ($data = mysqli_fetch_array($ambilsemuastock)) {
                                $kbarang = $data['kode_barang'];
                                $namabarang = $data['nama_barang'];
                                $jenisBarang = $data['jenis_barang'];
                                $garansi = $data['garansi'];
                                $jumlah_masuk = $data['jumlah_masuk'];
                                $jumlah_keluar = $data['jumlah_keluar'];
                                $jumlah = $data['jumlah'];
                                $satuan = $data['satuan'];
                                $stok = $data['stok'];
                            ?>
                                <tr class="text-center" style="color: <?= $stok < 2 ? 'red' : 'black'; ?>">
                                    <td><?= $i++; ?></td>
                                    <td><?= $kbarang; ?></td>
                                    <td><?= $jenisBarang; ?></td>
                                    <td><?= $namabarang; ?></td>
                                    <td><?= $garansi; ?></td>
                                    <td><?= $jumlah_masuk; ?></td>
                                    <td><?= $jumlah_keluar; ?></td>
                                    <td><?= $stok; ?></td> <!-- Menggunakan stok yang sudah dihitung -->
                                    <td><?= $satuan; ?></td>
                                    
                                </tr>

                            <?php
                            };
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Tabel Dashboard END -->

    <br>
    <br>
    <br>

    <!-- chart 1 START -->
    <div>
        <?php
        // Ambil tahun yang dipilih dari URL (GET parameter), atau jika tidak ada pilih tahun saat ini
        $tahun_sekarang = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

        // Query untuk mengambil barang masuk per bulan berdasarkan tahun yang dipilih
        $barang_masuk_per_bulan = [];
        $barang_keluar_per_bulan = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            // Ambil data barang masuk per bulan
            $query_masuk = mysqli_query($conn, "
            SELECT SUM(jumlah_masuk) AS total 
            FROM barang_masuk 
            WHERE MONTH(tanggal_masuk) = $bulan AND YEAR(tanggal_masuk) = '$tahun_sekarang'
        ");
            $result_masuk = mysqli_fetch_assoc($query_masuk);
            $barang_masuk_per_bulan[] = $result_masuk['total'] ?? 0;

            // Ambil data barang keluar per bulan
            $query_keluar = mysqli_query($conn, "
            SELECT SUM(jumlah_keluar) AS total 
            FROM barang_keluar 
            WHERE MONTH(tanggal_keluar) = $bulan AND YEAR(tanggal_keluar) = '$tahun_sekarang' AND status_approve = 'approved'
        ");
            $result_keluar = mysqli_fetch_assoc($query_keluar);
            $barang_keluar_per_bulan[] = $result_keluar['total'] ?? 0;
        }

        // Menampilkan label bulan
        $labels_bulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
        ?>

        <!-- Dropdown untuk memilih tahun -->
        <div class="ms-5 me-5">
            <form method="GET" action="">
                <label for="tahun">Pilih Tahun:</label>
                <select name="tahun" id="tahun" class="form-select">
                    <?php
                    // Menampilkan pilihan tahun dari database
                    $queryTahun = mysqli_query($conn, "SELECT DISTINCT YEAR(tanggal_masuk) AS tahun FROM barang_masuk ORDER BY tahun DESC");
                    while ($tahun = mysqli_fetch_assoc($queryTahun)) {
                        // Jika tahun saat ini dipilih, beri atribut selected
                        echo "<option value='" . $tahun['tahun'] . "' " . ($tahun['tahun'] == $tahun_sekarang ? 'selected' : '') . ">" . $tahun['tahun'] . "</option>";
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-primary mt-2 ">Tampilkan</button>
            </form>

            <!-- Chart untuk perbandingan barang masuk dan barang keluar -->
            <canvas id="chartPerbandingan" width="100%" height="40"></canvas>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                var ctx = document.getElementById('chartPerbandingan').getContext('2d');
                var chartPerbandingan = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode($labels_bulan); ?>, // Label bulan
                        datasets: [{
                                label: 'Barang Masuk per Bulan Tahun <?= $tahun_sekarang; ?>',
                                data: <?= json_encode($barang_masuk_per_bulan); ?>, // Data barang masuk
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Barang Keluar per Bulan Tahun <?= $tahun_sekarang; ?>',
                                data: <?= json_encode($barang_keluar_per_bulan); ?>, // Data barang keluar
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true
                            },
                            title: {
                                display: true,
                                text: 'Perbandingan Barang Masuk dan Barang Keluar per Bulan Tahun <?= $tahun_sekarang; ?>'
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>

    <!-- chart 1 END -->

    <br>
    <br>
    <br>

    <!-- PIE CHART START -->
    <?php
    // Hitung total keseluruhan (barang masuk + barang keluar + stok)
    $total_keseluruhan = $total_barang_masuk + $total_barang_keluar + $total_stok;

    // Hitung persentase masing-masing
    $persentase_barang_masuk = $total_keseluruhan > 0 ? ($total_barang_masuk / $total_keseluruhan) * 100 : 0;
    $persentase_barang_keluar = $total_keseluruhan > 0 ? ($total_barang_keluar / $total_keseluruhan) * 100 : 0;
    $persentase_stok = $total_keseluruhan > 0 ? ($total_stok / $total_keseluruhan) * 100 : 0;
    ?>
    <div class="ms-5 me-5 mt-4 text-center">
        <h4>Persentase Barang</h4>
        <div style="display: flex; justify-content: center;">
            <canvas id="pieChartBarang" style="max-width: 500px; max-height: 500px;"></canvas>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
            <script>
                var ctx = document.getElementById('pieChartBarang').getContext('2d');
                var pieChartBarang = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Barang Masuk', 'Barang Keluar', 'Stok Barang'],
                        datasets: [{
                            data: [
                                <?= $persentase_barang_masuk; ?>,
                                <?= $persentase_barang_keluar; ?>,
                                <?= $persentase_stok; ?>
                            ],
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.7)', // Barang Masuk
                                'rgba(255, 99, 132, 0.7)', // Barang Keluar
                                'rgba(75, 192, 192, 0.7)' // Stok Barang
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(75, 192, 192, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            title: {
                                display: true,
                                text: 'Persentase Barang'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let value = context.raw; // Ambil nilai persentase
                                        return context.label + ': ' + value.toFixed(2) + '%'; // Tambahkan tanda %
                                    }
                                }
                            },
                            datalabels: {
                                color: '#000',
                                formatter: function(value) {
                                    return value.toFixed(2) + '%'; // Format dengan tanda %
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels] // Aktifkan plugin datalabels
                });
            </script>
        </div>
    </div>

    <!-- PIE CHART END -->


    <br>
    <br>
    <br>

    <div>
        <!-- Line Chart Start -->
        <?php
        // Ambil tahun yang dipilih dari URL (GET parameter), atau gunakan tahun saat ini jika tidak ada parameter
        $tahun_sekarang = isset($_GET['tahun_line']) ? $_GET['tahun_line'] : date('Y');

        // Query untuk mengambil data line chart per bulan berdasarkan tahun yang dipilih
        $data_per_bulan = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $query = mysqli_query($conn, "
        SELECT 
            COALESCE(SUM(jumlah_masuk), 0) AS barang_masuk,
            COALESCE(SUM(jumlah_keluar), 0) AS barang_keluar
        FROM master_barang
        LEFT JOIN barang_masuk ON MONTH(barang_masuk.tanggal_masuk) = $bulan AND YEAR(barang_masuk.tanggal_masuk) = '$tahun_sekarang'
        LEFT JOIN barang_keluar ON MONTH(barang_keluar.tanggal_keluar) = $bulan AND YEAR(barang_keluar.tanggal_keluar) = '$tahun_sekarang' AND barang_keluar.status_approve = 'approved'
        ");
            $result = mysqli_fetch_assoc($query);
            $data_per_bulan[] = [
                'barang_masuk' => $result['barang_masuk'],
                'barang_keluar' => $result['barang_keluar']
            ];
        }
        ?>
        <div class="ms-5 me-5 mt-4">
            <!-- Form Filter Tahun -->
            <form method="GET" action="">
                <label for="tahun_line">Pilih Tahun:</label>
                <select name="tahun_line" id="tahun_line" class="form-select">
                    <?php
                    // Menampilkan pilihan tahun dari database
                    $queryTahun = mysqli_query($conn, "SELECT DISTINCT YEAR(tanggal_masuk) AS tahun FROM barang_masuk UNION SELECT DISTINCT YEAR(tanggal_keluar) AS tahun FROM barang_keluar ORDER BY tahun DESC");
                    while ($tahun = mysqli_fetch_assoc($queryTahun)) {
                        echo "<option value='" . $tahun['tahun'] . "' " . ($tahun['tahun'] == $tahun_sekarang ? 'selected' : '') . ">" . $tahun['tahun'] . "</option>";
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-primary mt-2">Tampilkan</button>
            </form>

            <!-- Line Chart -->
            <canvas id="lineChartBarang" width="100%" height="40"></canvas>

            <script src="../js/chart/"></script>
            <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
            <script>
                var ctx = document.getElementById('lineChartBarang').getContext('2d');
                var lineChartBarang = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: <?= json_encode($labels_bulan); ?>, // Label bulan
                        datasets: [{
                                label: 'Barang Masuk',
                                data: <?= json_encode(array_column($data_per_bulan, 'barang_masuk')); ?>,
                                borderColor: 'rgba(54, 162, 235, 1)',
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Barang Keluar',
                                data: <?= json_encode(array_column($data_per_bulan, 'barang_keluar')); ?>,
                                borderColor: 'rgba(255, 99, 132, 1)',
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                tension: 0.4,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            title: {
                                display: true,
                                text: 'Barang Masuk dan Keluar per Bulan Tahun <?= $tahun_sekarang; ?>'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>
    <!-- LINE CHART START -->
</main>

<?php
require "../footer.php"
?>