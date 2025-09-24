<?php
$title = "Data Barang | Elastomix";
include "layout/header.php";
?>
<main>
    <div class="container-fluid">
        <h1 class="mt-4">Data Master</h1>
        <!-- Button trigger modal -->
        <button type="button" style="border-radius: 10px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-folder-plus" viewBox="0 0 16 16">
                <path d="m.5 3 .04.87a2 2 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2m5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19q-.362.002-.683.12L1.5 2.98a1 1 0 0 1 1-.98z" />
                <path d="M13.5 9a.5.5 0 0 1 .5.5V11h1.5a.5.5 0 1 1 0 1H14v1.5a.5.5 0 1 1-1 0V12h-1.5a.5.5 0 0 1 0-1H13V9.5a.5.5 0 0 1 .5-.5" />
            </svg>
            Tambah Data
        </button>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl"> <!-- Menggunakan kelas modal-xl di sini -->
                <div class="modal-content  ">
                    <!-- HEADER -->
                    <div class="modal-header bg-primary">
                        <h1 class="modal-title fs-6 text-white" id="exampleModalLabel">Form Tambah Master Barang</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form id="dynamicForm" action="../BackEnd/function.php" method="POST" enctype="multipart/form-data">
                            <div id="formInputs">
                                <div class="form-group mb-3 d-flex">
                                    <input type="text" name="kodebarang[]" placeholder="Nama barang" class="form-control me-2" required>
                                    <input type="text" name="jenisbarang[]" placeholder="Jenis Barang" class="form-control me-2" required>
                                    <input type="text" name="namabarang[]" placeholder="Deskripsi" class="form-control me-2" required>
                                    <input type="text" name="deskripsi[]" placeholder="Tempat penyimpanan" class="form-control me-2" required>
                                    <input type="text" name="satuan[]" placeholder="Satuan Barang" class="form-control me-2" required>
                                    <input type="number" name="stok_awal[]" placeholder="Stok Awal" class="form-control me-2" required>
                                    <input type="text" name="maker[]" placeholder="Maker" class="form-control me-2" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success" id="addButton">+ Tambah Item</button>
                            <button type="button" class="btn btn-danger">Hapus Item</button>
                            <button type="submit" class="btn btn-primary" name="addnewbarang">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <script>
            document.getElementById('addButton').addEventListener('click', function() {
                const formInputs = document.getElementById('formInputs');
                const newInputGroup = document.createElement('div');
                newInputGroup.className = 'form-group mb-3 d-flex';
                newInputGroup.innerHTML = `
        <input type="text" name="kodebarang[]" placeholder="Nama Barang" class="form-control me-2" required>
        <input type="text" name="jenisbarang[]" placeholder="Jenis Barang" class="form-control me-2" required>
        <input type="text" name="namabarang[]" placeholder="Deskripsi" class="form-control me-2" required>
        <input type="text" name="deskripsi[]" placeholder="Tempat Penyimpanan" class="form-control me-2" required>
        <input type="text" name="satuan[]" placeholder="Sat uan Barang" class="form-control me-2" required>
        <input type="number" name="stok_awal[]" placeholder="Stok Awal" class="form-control me-2" required>
        <input type="text" name="maker[]" placeholder="Maker" class="form-control me-2" required>
    `;
                formInputs.appendChild(newInputGroup);
            });

            // Hapus item terakhir
            document.querySelector('.btn-danger').addEventListener('click', function() {
                const formInputs = document.getElementById('formInputs');
                const inputGroups = formInputs.getElementsByClassName('form-group');
                if (inputGroups.length > 1) {
                    formInputs.removeChild(inputGroups[inputGroups.length - 1]);
                } else {
                    alert("Tidak ada item untuk dihapus!");
                }
            });
        </script>

        <a href="../export/export" style="color: white; text-decoration: none;"> <button style="border-radius: 10px;" class="btn btn-success">
                Ekspor Dokumen
            </button></a>

        <!-- tabel hasil data -->
        <div class="card mb-4 mt-2">
            <div class="card-header ">
                <i class="fas fa-table me-1"></i> Tabel Master Data
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
                                <th>SATUAN</th>
                                <th>TEMPAT PENYIMPANAN</th>
                                <th>STOK AWAL</th>
                                <th>JUMLAH</th>
                                <th>AKSI</th>
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
                                $stokAwal = $data['stok_awal'];
                            ?>
                                <tr class="text-center">
                                    <td><?= $i++; ?></td>
                                    <td><?= $kbarang; ?></td>
                                    <td><?= $jenisBarang; ?></td>
                                    <td><?= $namabarang; ?></td>
                                    <td><?= $maker; ?></td>
                                    <td><?= $satuan; ?></td>
                                    <td><?= $deskripsi; ?></td>
                                    <td><?= $stokAwal; ?></td>
                                    <td><?= $qty; ?></td>
                                    <!-- <td>
                                        <?php if ($foto): ?>
                                            <img src="upload/<?= $foto; ?>" alt="<?= $namabarang; ?>" width="100" height="100">
                                        <?php else: ?>
                                            No Image
                                        <?php endif; ?>
                                    </td> -->

                                    <td>
                                        <button
                                            class="btn btn-warning btn-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal"
                                            data-id="<?= $kbarang; ?>"
                                            data-jenis="<?= $jenisBarang; ?>"
                                            data-nama="<?= $namabarang; ?>"
                                            data-deskripsi="<?= $deskripsi; ?>"
                                            data-maker="<?= $maker; ?>"
                                            data-qty="<?= $qty; ?>">
                                            Edit
                                        </button>
                                        <button
                                            class="btn btn-danger btn-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-id="<?= $kbarang; ?>">
                                            Hapus
                                        </button>
                                    </td>
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

    <!-- MODAL EDIT START -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white" id="editModalLabel">
                        Edit Master Barang
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../BackEnd/function.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="editKodeBarangHidden" name="kode_barang_old" value="<?= $kode_barang; ?>" />
                        <!-- Untuk kode lama -->
                        <div class="form-group mb-3">
                            <label for="editKodeBarang">Kode Barang</label>
                            <input type="text" id="editKodeBarang" name="kode_barang" class="form-control" value="<?= $kode_barang; ?>" required />
                        </div>
                        <div class="form-group mb-3">
                            <label for="editJenisBarang">Jenis Barang</label>
                            <input type="text" id="editJenisBarang" name="jenis_barang" class="form-control" value="<?= $jenis_barang; ?>" required />
                        </div>
                        <div class="form-group mb-3">
                            <label for="editNamaBarang">Nama Barang</label>
                            <input type="text" id="editNamaBarang" name="nama_barang" class="form-control" value="<?= $nama_barang; ?>" required />
                        </div>
                        <div class="form-group mb-3">
                            <label for="editDeskripsi">Tempat Penyimpanan</label>
                            <input type="text" id="editDeskripsi" name="deskripsi" class="form-control" value="<?= $deskripsi; ?>" required />
                        </div>
                        <div class="form-group mb-3">
                            <label for="editMaker">Maker</label>
                            <input type="text" id="editMaker" name="maker" class="form-control" value="<?= $maker; ?>" required />
                        </div>
                        <!-- <div class="form-group mb-3">
                            <label for="editFoto">Foto Barang</label>
                            <input type="file" id="editFoto" name="foto" class="form-control" />
                        </div> -->

                        <!-- Menampilkan foto lama jika ada -->
                        <!-- <?php if ($foto): ?>
                            <div>
                                <img src="upload/<?= $foto; ?>" alt="<?= $nama_barang; ?>" width="100" height="100">
                                <p>Foto lama: <?= $foto; ?></p>
                            </div>
                        <?php else: ?>
                            <p>No image available</p>
                        <?php endif; ?> -->

                        <!-- Hidden input untuk foto lama -->
                        <input type="hidden" name="foto_lama" value="<?= $foto; ?>" />

                        <div class="form-group mb-3">
                            <input type="hidden" id="editJumlah" name="jumlah" class="form-control" required />
                        </div>
                        <button type="submit" class="btn btn-primary" name="update_barang">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- MODAL EDIT END -->

    <!-- MODAL EDIT END -->

    <!-- MODAL HAPUS START -->
    <div
        class="modal fade"
        id="deleteModal"
        tabindex="-1"
        aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="deleteModalLabel">
                        Konfirmasi Hapus
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus barang ini?</p>
                    <p>
                        <strong>Kode Barang: <span id="deleteKodeBarang"></span></strong>
                    </p>
                </div>
                <div class="modal-footer">
                    <form action="../BackEnd/function.php" method="POST">
                        <input type="hidden" id="deleteKodeBarangInput" name="kode_barang" />
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-danger" name="delete_barang">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL HAPUS START -->

    <!-- SCRIPT MODAL START-->
    <script>
        // SCRIPT EDIT START
        document.querySelectorAll(".btn-edit").forEach((button) => {
            button.addEventListener("click", function() {
                const kodeBarang = this.getAttribute("data-id");
                const jenisBarang = this.getAttribute("data-jenis");
                const namaBarang = this.getAttribute("data-nama");
                const deskripsi = this.getAttribute("data-deskripsi");
                const maker = this.getAttribute("data-maker");
                const qty = this.getAttribute("data-qty");

                document.getElementById("editKodeBarang").value = kodeBarang;
                document.getElementById("editKodeBarangHidden").value = kodeBarang;
                document.getElementById("editJenisBarang").value = jenisBarang;
                document.getElementById("editNamaBarang").value = namaBarang;
                document.getElementById("editDeskripsi").value = deskripsi;
                document.getElementById("editMaker").value = maker;
                document.getElementById("editJumlah").value = qty;
            });
        });
        // SCRIPT EDIT END

        // SCRIPT HAPUS START

        document.querySelectorAll(".btn-delete").forEach((button) => {
            button.addEventListener("click", function() {
                const kodeBarang = this.getAttribute("data-id");
                document.getElementById("deleteKodeBarang").textContent = kodeBarang;
                document.getElementById("deleteKodeBarangInput").value = kodeBarang;
            });
        });
        // SCRIPT HAPUS END
    </script>
    <!-- SCRIPT MODAL END -->
</main>

<?php include "../footer.php"; ?>