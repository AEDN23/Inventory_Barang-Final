<!-- di keluar.php -->
<table class="table table-hover table-striped table-bordered " id="dataTable" width="100%" cellspacing="0">
    <thead>
        <!-- <tr class="text-center">
                                <th>TRANSAKSI</th>
                                <th>KODE BARANG </th>
                                <th>TANGGAL</th>
                                <th>USER</th>
                                <th>JENIS BARANG</th>
                                <th>NAMA BARANG </th>
                                <th>MAKER</th>
                                <th>JUMLAH</th>
                                <th>SATUAN</th>
                                <th>NOTE</th>
                                <th>STATUS</th>
                                <th>DETAIL</th>

                            </tr> -->
    </thead>
    <!-- <tbody>
                            <?php
                            $ambilsemuadatakeluar = mysqli_query($conn, "
                           SELECT bk.id, bk.kode_barang, bk.tanggal_keluar, bk.user, 
                                  bk.jumlah_keluar, bk.note, bk.status_approve, bk.alasan,
                                  mb.jenis_barang, mb.nama_barang, mb.maker, mb.satuan, bk.transaksi
                           FROM barang_keluar bk
                           LEFT JOIN master_barang mb ON bk.kode_barang = mb.kode_barang
                           ORDER BY bk.tanggal_keluar DESC
                       ");




                            $i = 1;
                            while ($data = mysqli_fetch_array($ambilsemuadatakeluar)) {
                                $kode_barang = $data['kode_barang'];
                                $tanggal_keluar = $data['tanggal_keluar'];
                                $user = $data['user'];
                                $jenis_barang = $data['jenis_barang'];
                                $nama_barang = $data['nama_barang'];
                                $maker = $data['maker'];
                                $jumlah_keluar = $data['jumlah_keluar'];
                                $satuan = $data['satuan'];
                                $note = $data['note'];
                                $transaksi = $data['transaksi'];
                            ?>
                                <tr class="text-center">
                                    <td><?= $transaksi; ?></td>
                                    <td><?= $kode_barang; ?></td>
                                    <td><?= date('d-m-Y', strtotime($tanggal_keluar)); ?></td>
                                    <td><?= $user; ?></td>
                                    <td><?= $jenis_barang; ?></td>
                                    <td><?= $nama_barang; ?></td>
                                    <td><?= $maker; ?></td>
                                    <td><?= $jumlah_keluar; ?></td>
                                    <td><?= $satuan; ?></td>
                                    <td><?= $note; ?></td>
                                    <td>
                                        <?php if ($data['status_approve'] == 'pending') { ?>
                                            <form method="POST" action="../BackEnd/approve.php" style="display: inline;">
                                                <input type="hidden" name="id_keluar" value="<?= $data['id']; ?>">
                                                <input type="hidden" name="user" value="<?= $user; ?>">
                                                <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">✔️Approve</button>
                                            </form>
                                            <form method="POST" action="../BackEnd/approve.php" style="display: inline;">
                                                <input type="hidden" name="id_keluar" value="<?= $data['id']; ?>">
                                                <input type="hidden" name="user" value="<?= $user; ?>">
                                                <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">❌Reject</button>
                                            </form>
                                        <?php } else { ?>
                                            <span class="badge <?= $data['status_approve'] == 'approved' ? 'bg-success' : 'bg-danger'; ?>">
                                                <?= ucfirst($data['status_approve']); ?>
                                            </span>
                                            <br>
                                            <small><?= $data['alasan']; ?></small> <!-- Tampilkan alasan -->
<?php } ?>
</td>

<td><a href="../hasil/keluar.php?transaksi=<?= $transaksi; ?>"><button class="btn btn-primary">DETAIL</button></a></td>
</tr>
<?php
                            }
?>
</tbody> -->


</table>

<!-- DI MASUK.PHP -->
<table class="table table-hover table-striped table-bordered " id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr class="text-center">
            <th>TRANSAKSI</th>
            <th>KODE BARANG</th>
            <th>TANGGAL</th>
            <th>SUPPLIER</th>
            <th>JENIS BARANG</th>
            <th>NAMA BARANG</th>
            <th>MAKER</th>
            <th>SATUAN</th>
            <th>JUMLAH</th>
            <th>NOTE</th>
            <th>DETAIL</th>

        </tr>
    </thead>
    <tbody>
        <?php
        $ambilsemuadatastock = mysqli_query($conn, "
                                    SELECT bm.transaksi, bm.kode_barang, bm.tanggal_masuk, bm.supplier, mb.jenis_barang, mb.nama_barang, mb.maker, mb.satuan, bm.jumlah_masuk, bm.note 
                                    FROM barang_masuk bm
                                    LEFT JOIN master_barang mb ON bm.kode_barang = mb.kode_barang
                                    ORDER BY bm.tanggal_masuk DESC
                                    ");

        $i = 1;
        while ($data = mysqli_fetch_array($ambilsemuadatastock)) {
            $transaksi = $data['transaksi'];
            $kodebarang = $data['kode_barang'];
            $tanggal = $data['tanggal_masuk'];
            $supplier = $data['supplier'];
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

                <td><a href="../hasil/masuk.php?transaksi=<?= $transaksi; ?>"><button class="btn btn-primary">DETAIL</button></a></td>




            </tr>
        <?php
        }
        ?>
    </tbody>


</table>





<!-- lpaoranmasuk.php -->
<main>
    <div class="container">
        <div class="page-header print-header">
            <h1>Laporan</h1>
        </div>

        <?php if (!isset($_GET['hideformfilter'])) { ?>
            <h3>Quick Filter</h3>
            <a href="<?= site_url(); ?>/export/masuk.php?start=<?= $today; ?>&end=<?= $today; ?>&status=ALL&hideformfilter=true&filter=Submit" class="btn btn-default">Today</a>
            <a href="<?= site_url(); ?>/export/masuk.php?start=<?= $yesterday; ?>&end=<?= $today; ?>&status=ALL&hideformfilter=true&filter=Submit" class="btn btn-default">Yesterday</a>
            <a href="<?= site_url(); ?>/export/masuk.php?start=<?= $last_week; ?>&end=<?= $today; ?>&status=ALL&hideformfilter=true&filter=Submit" class="btn btn-default">Last Week</a>
            <a href="<?= site_url(); ?>/export/masuk.php?start=<?= $last_month; ?>&end=<?= $today; ?>&status=ALL&hideformfilter=true&filter=Submit" class="btn btn-default">Last Month</a>
            <a href="<?= site_url(); ?>/export/masuk.php?start=<?= $last_6month; ?>&end=<?= $today; ?>&status=ALL&hideformfilter=true&filter=Submit" class="btn btn-default">Last 6 Month</a>
            <a href="<?= site_url(); ?>/export/masuk.php?start=<?= $last_year; ?>&end=<?= $today; ?>&status=ALL&hideformfilter=true&filter=Submit" class="btn btn-default">Last Year</a>
            <a href="<?= site_url(); ?>/export/exportmasuk.php" class="btn btn-danger">Show All Without Filter</a>

            <br><br>
            <h3>Advance Filter</h3>
            <form method="GET">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="">Start date:</label>
                        <input type="date" name="start" class="form-control" onclick="this.showPicker()" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">End date:</label>
                        <input type="date" name="end" class="form-control" onclick="this.showPicker()" value="<?= date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Status:</label>
                        <select name="status" class="form-control">
                            <option value="ALL">- select status -</option>
                            <option value="NEW">NEW</option>
                            <option value="PROCESS">PROCESS</option>
                            <option value="PENDING">PENDING</option>
                            <option value="CANCEL">CANCEL</option>
                            <option value="DONE">DONE</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Service:</label>
                        <select name="service" class="form-control">
                            <option value="ALL">- select service -</option>
                            <?php
                            $services = mysqli_query($conn, "SELECT * FROM tbl_service");
                            while ($service = mysqli_fetch_assoc($services)) {
                                $selected = isset($_GET['service']) && $_GET['service'] == $service['ts_id'] ? "selected" : "";
                                echo "<option value='" . $service['ts_id'] . "' $selected>" . $service['ts_name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <input type="hidden" name="hideformfilter" value="true">
                </div>
                <input type="submit" name="filter" class="btn btn-primary" value="Submit">
            </form>
        <?php } ?>

        <?php
        if (isset($_GET['filter'])) {
            $q_status = "";
            if (isset($_GET['status']) && $_GET['status'] !== "ALL") {
                $k_status = mysqli_real_escape_string($conn, $_GET['status']);
                $q_status = "AND tt_status='$k_status'";
            }

            $k_start = mysqli_real_escape_string($conn, $_GET['start']);
            $k_end = mysqli_real_escape_string($conn, $_GET['end']);

            $sql = "
            SELECT bm.transaksi, bm.kode_barang, bm.tanggal_masuk, bm.supplier_id, mb.jenis_barang, mb.nama_barang, mb.maker, mb.satuan, bm.jumlah_masuk, bm.note 
            FROM barang_masuk bm
            LEFT JOIN master_barang mb ON bm.kode_barang = mb.kode_barang
            WHERE (DATE_FORMAT(bm.tanggal_masuk, '%Y-%m-%d') BETWEEN '$k_start' AND '$k_end')
            $q_status
            ORDER BY bm.tanggal_masuk DESC
        ";

            $data = mysqli_query($conn, $sql);
            $data_count = mysqli_num_rows($data);

            $re_start = date("d F Y", strtotime($_GET['start']));
            $re_end = date("d F Y", strtotime($_GET['end']));

            echo '<div class="print-container">';

            if ($_GET['start'] == $last_1century) {
                echo '<p>Terdapat <span class="label label-default">' . $data_count . ' data</span></p>';
            } else {
                echo '<p>Berikut ini adalah daftar laporan tiket periode <b>' . $re_start . '</b> sampai <b>' . $re_end . '</b> terdapat <span class="label label-default">' . $data_count . ' data</span></p>';
            }

            echo '<table class="table table-hover table-striped table-bordered " id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th>TRANSAKSI</th>
                                <th>KODE BARANG</th>
                                <th>TANGGAL</th>
                                <th>SUPPLIER</th>
                                <th>JENIS BARANG</th>
                                <th>NAMA BARANG</th>
                                <th>MAKER</th>
                                <th>SATUAN</th>
                                <th>JUMLAH</th>
                                <th>NOTE</th>
                                <th>DETAIL</th>
                            </tr>
                        </thead>
                        <tbody>';

            if ($data_count !== 0) {
                while ($row = mysqli_fetch_assoc($data)) {
                    echo "<tr class='text-center'>
                        <td>{$row['transaksi']}</td>
                        <td>{$row['kode_barang']}</td>
                        <td>{$row['tanggal_masuk']}</td>
                        <td>{$row['supplier_id']}</td>
                        <td>{$row['jenis_barang']}</td>
                        <td>{$row['nama_barang']}</td>
                        <td>{$row['maker']}</td>
                        <td>{$row['satuan']}</td>
                        <td>{$row['jumlah_masuk']}</td>
                        <td>{$row['note']}</td>
                        <td><a href='../hasil/masuk.php?transaksi={$row['transaksi']}'><button class='btn btn-primary'>DETAIL</button></a></td>
                    </tr>";
                }
            } else {
                echo "<tr align='center'>
                    <td colspan='11'>No data!</td>
                </tr>";
            }
            echo '</tbody></table>';
            echo '</div>';
            echo '<button onclick="window.print()" style="color: #222;" class="btn btn-primary print-hide"><img src="images/printer-fill.svg" alt=""> Print Now</button> ';

            // BUTTON UNTUK EXPORT EXCEL
            echo '<a href="' . site_url() . '/laporanmasuk.php?export=excel&start=' . $k_start . '&end=' . $k_end . '&status=' . $_GET['status'] .  '" style="color: #222;" class="btn  btn-success print-hide"><img src="images/icons8-excel.svg" alt="">  Export to Excel</a>';
            // BUTTON UNTUK EXPORT EXCEL
            echo '<a href="' . site_url() . '/laporanmasuk.php" style="margin-left:4px;" class="btn btn-default ms-3 print-hide"> Clear Filter</a> ';
        }
        ?>
    </div>
</main>