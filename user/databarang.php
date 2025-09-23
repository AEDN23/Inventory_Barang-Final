<?php
$title = "Data Barang | Elastomix";
require "layout/header.php";
// require "../BackEnd/function.php";
// require_once("../BackEnd/check_role_user.php");

?>
<main>
    <div class="container-fluid">
        <h1 class="mt-4">Data Master</h1>

        <a href="../export/export" style="color: white; text-decoration: none;"> <button style="border-radius: 10px;" class="btn btn-success">
                Ekspor Dokumen
            </button></a>
        <!-- tabel hasil data -->
        <div class="card mb-4 mt-2">

            <div class="card-header ">
                <i class="fas fa-table me-1"></i>TABEL MASTER DATA
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
                                <th>MAKER</th>
                                <th>JUMLAH</th>
                                <th>SATUAN</th>
                                <th>TEMPAT PENYIMPANAN</th>
                                <th>GARANSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ambilsemuastock = mysqli_query($conn, "SELECT * FROM  master_barang");
                            $i = 1;
                            while ($data = mysqli_fetch_array($ambilsemuastock)) {
                                $jenisBarang = $data['jenis_barang'];
                                $kbarang = $data['kode_barang'];
                                $namabarang = $data['nama_barang'];
                                $deskripsi = $data['deskripsi'];
                                $satuan = $data['satuan'];
                                $maker = $data['maker'];
                                $qty = $data['jumlah'];
                                $foto = $data['foto'];
                                $garansi = $data['garansi'];
                            ?>
                                <tr class="text-center">
                                    <td><?= $i++; ?></td>
                                    <td><?= $kbarang; ?></td>
                                    <td><?= $jenisBarang; ?></td>
                                    <td><?= $namabarang; ?></td>
                                    <td><?= $maker; ?></td>
                                    <td><?= $qty; ?></td>
                                    <td><?= $satuan; ?></td>
                                    <td><?= $deskripsi; ?></td>
                                    <td><?= $garansi; ?></td>
                                    <!-- <td>
                                        <?php if ($foto): ?>
                                            <img src="upload/<?= $foto; ?>" alt="<?= $namabarang; ?>" width="100" height="100">
                                        <?php else: ?>
                                            No Image
                                        <?php endif; ?>
                                    </td> -->

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
</main>


<?php include "../footer.php"; ?>