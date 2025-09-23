<?php
$title = "Data keluar | Elastomix";
require "layout/header.php";
// require_once("../BackEnd/check_role_user.php");
// require "../BackEnd/function.php";
?>
<main>
    <div class="container-fluid">
        <h1 class="mt-4">Data Keluar</h1>
        <!-- Button trigger modal -->
        <button type="button" style="border-radius: 10px; background-color:red; color:white" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
            TAMBAH DATA KELUAR BARANG
        </button>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl"> <!-- Menggunakan kelas modal-xl di sini -->
                <div class="modal-content">
                    <!-- HEADER -->
                    <div class="modal-header bg-danger">
                        <h1 class="modal-title fs-6 text-white" id="exampleModalLabel">Form Keluar Barang</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form id="dynamicForm" action="../BackEnd/function.php" method="POST">
                            <!-- Input Tanggal dan  dalam satu baris -->
                            <div class="form-group mb-3 d-flex">
                                <input type="date" name="tanggal" placeholder="Tanggal Barang Masuk" class="form-control me-2" onclick="this.showPicker()" value="<?= date('Y-m-d'); ?>">

                                <select name="user" class="form-control me-2" required="true">
                                    <option value="" hidden>User atau pengguna</option>
                                    <?php
                                    // Mengambil data dari tabel user
                                    $queryUser = "SELECT id, nama_lengkap, departemen FROM user";
                                    $resultUser = mysqli_query($conn, $queryUser);
                                    while ($u = mysqli_fetch_assoc($resultUser)) {
                                        echo '<option value="' . $u['id'] . '">' . $u['nama_lengkap'] . ' | ' . $u['departemen'] . '</option>';
                                    }
                                    ?>
                                </select>
                                <!-- <input type="text" name="user" placeholder="User atau Pengguna" class="form-control me-2"> -->
                                <input type="text" name="note" placeholder="Note" class="form-control me-2">
                            </div>
                            <h2 class="fs-6">Detail:</h2>
                            <!-- Input yang dapat ditambah -->
                            <div id="formInputs">
                                <?php
                                // Mengambil data dari tabel master_barang
                                $query = "SELECT * FROM master_barang";
                                $result = mysqli_query($conn, $query);
                                $masterBarang = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                ?>

                                <div class="form-group mb-3 d-flex">



                                    <select name="kodebarang[]" class="form-control me-2 select2" onchange="fetchBarangData(this)">
                                        <option value="" hidden>Nama barang</option>
                                        <?php foreach ($masterBarang as $barang): ?>
                                            <option value="<?= $barang['kode_barang'] ?>"><?= $barang['kode_barang'] ?></option>
                                        <?php endforeach; ?>
                                    </select>





                                    <select name="jenisbarang[]" class="form-control me-2 jenisbarang">
                                        <option value="">Jenis Barang</option>
                                    </select>

                                    <select name="namabarang[]" class="form-control me-2 namabarang">
                                        <option value="">Deskripsi</option>
                                    </select>

                                    <select name="satuan[]" class="form-control me-2 satuan">
                                        <option value="">Satuan Barang</option>
                                    </select>

                                    <select name="maker[]" class="form-control me-2 maker">
                                        <option value="">Maker</option>
                                    </select>


                                    <input type="number" name="jumlah[]" placeholder="Jumlah barang" class="form-control me-2">


                                </div>
                            </div>

                            <script>
                                function fetchBarangData(selectElement) {
                                    var kodeBarang = selectElement.value;

                                    // Check jika ada kode barang yang dipilih
                                    if (kodeBarang) {
                                        // AJAX request untuk mengambil data dari get_barang.php
                                        var xhr = new XMLHttpRequest();
                                        xhr.open("POST", "../BackEnd/get_barang.php", true);
                                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                        xhr.onreadystatechange = function() {
                                            if (xhr.readyState === 4 && xhr.status === 200) {
                                                var response = JSON.parse(xhr.responseText);

                                                // Mendapatkan elemen parent untuk input jenisbarang, namabarang, dan maker
                                                var parent = selectElement.closest('.form-group');

                                                // Update input jenisbarang, namabarang, dan maker
                                                parent.querySelector('.jenisbarang').innerHTML = '<option value="' + response.jenis_barang + '">' + response.jenis_barang + '</option>';
                                                parent.querySelector('.namabarang').innerHTML = '<option value="' + response.nama_barang + '">' + response.nama_barang + '</option>';
                                                parent.querySelector('.satuan').innerHTML = '<option value="' + response.satuan + '">' + response.satuan + '</option>';
                                                parent.querySelector('.maker').innerHTML = '<option value="' + response.maker + '">' + response.maker + '</option>';
                                            }
                                        };
                                        xhr.send("kode_barang=" + kodeBarang);
                                    }
                                }
                            </script>

                            <!-- Tombol Tambah dan Hapus Item -->
                            <button type="button" class="btn btn-success" id="addButton">Tambah Item</button>
                            <button type="button" class="btn btn-danger" id="removeButton">Hapus Item</button>

                            <!-- Tombol Submit -->
                            <button type="submit" class="btn btn-primary" name="addbarangkeluar">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <script>
            document.getElementById('dynamicForm').addEventListener('submit', function(event) {
                let isValid = true;

                // Validasi untuk Tanggal dan User
                const tanggalInput = document.querySelector('input[name="tanggal"]');
                const userInput = document.querySelector('input[name="user"]');

                if (tanggalInput.value.trim() === '') {
                    isValid = false;
                    tanggalInput.classList.add('is-invalid'); // Tambahkan class error
                } else {
                    tanggalInput.classList.remove('is-invalid');
                }

                if (userInput.value.trim() === '') {
                    isValid = false;
                    userInput.classList.add('is-invalid'); // Tambahkan class error
                } else {
                    userInput.classList.remove('is-invalid');
                }

                // Validasi untuk input dan select dalam #formInputs
                const inputs = document.querySelectorAll('#formInputs input, #formInputs select');

                inputs.forEach(input => {
                    if (input.value.trim() === '') {
                        isValid = false;
                        input.classList.add('is-invalid'); // Tambahkan class untuk menandai error
                    } else {
                        input.classList.remove('is-invalid'); // Hapus class jika sudah benar
                    }
                });

                if (!isValid) {
                    event.preventDefault();
                    alert('Semua field harus diisi.');
                }
            });


            // Tambah Barang
            document.getElementById('addButton').addEventListener('click', function() {
                const formInputs = document.getElementById('formInputs');
                const newInputGroup = document.createElement('div');
                newInputGroup.className = 'form-group mb-3 d-flex';
                newInputGroup.innerHTML = `

        <select name="kodebarang[]" class="form-control me-2" onchange="fetchBarangData(this)">
            <option value="" hidden>Nama barang</option>
            <?php foreach ($masterBarang as $barang): ?>
                <option value="<?= $barang['kode_barang'] ?>"><?= $barang['kode_barang'] ?></option>
            <?php endforeach; ?>
        </select>

        <select name="jenisbarang[]" class="form-control me-2 jenisbarang">
            <option value="">Jenis Barang</option>
        </select>

        <select name="namabarang[]" class="form-control me-2 namabarang">
            <option value="">Deskripsi</option>
       </select>

        <select name="satuan[]" class="form-control me-2 satuan">
            <option value="">Satuan Barang</option>
        </select>

        <select name="maker[]" class="form-control me-2 maker">
            <option value="">Maker</option>
        </select>

        <input type="number" name="jumlah[]" placeholder="Jumlah barang" class="form-control me-2">`;
                formInputs.appendChild(newInputGroup);
            });

            // Hapus Item
            document.querySelector('.btn-danger').addEventListener('click', function() {
                const formInputs = document.getElementById('formInputs');
                const inputGroups = formInputs.getElementsByClassName('form-group mb-3');

                // Hapus grup input terakhir jika ada
                if (inputGroups.length > 1) {
                    formInputs.removeChild(inputGroups[inputGroups.length - 1]);
                } else {
                    alert("Tidak ada item untuk dihapus!");
                }
            });
        </script>

        <a href="../export/exportkeluar" style="color: white; text-decoration: none;"> <button style="border-radius: 10px;" class="btn btn-success">
                Ekspor Dokumen
            </button></a>




        <!-- tabel hasil data -->
        <div class="card mb-4 mt-2">
            <div class="card-header ">
                <i class="fas fa-table me-1"></i>Tabel Item Keluar
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered " id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th>TRANSAKSI</th>
                                <th>NAMA BARANG</th>
                                <th>TANGGAL</th>
                                <th>USER</th>
                                <th>JENIS BARANG</th>
                                <th>Deskripsi</th>
                                <th>MAKER</th>
                                <th>SATUAN</th>
                                <th>JUMLAH</th>
                                <th>NOTE</th>
                                <th>STATUS</th>
                                <th>DETAIL</th>
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
                            while ($data = mysqli_fetch_array($ambilsemuadatakeluar)) {
                                $transaksi = $data['transaksi'];
                                $kode_barang = $data['kode_barang'];
                                $tanggal_keluar = $data['tanggal_keluar'];
                                $username = $data['nama_lengkap'];
                                $jenis_barang = $data['jenis_barang'];
                                $nama_barang = $data['nama_barang'];
                                $maker = $data['maker'];
                                $satuan = $data['satuan'];
                                $jumlah_keluar = $data['jumlah_keluar'];
                                $note = $data['note'];
                                $status_approve = $data['status_approve'];
                                $alasan = $data['alasan'];

                                // Tentukan status approve yang ditampilkan dengan background warna
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
                                    <td><?= $satuan; ?></td>
                                    <td><?= $jumlah_keluar; ?></td>
                                    <td><?= $note; ?></td>
                                    <td><?= $approve_status; ?> <br>
                                        <small><?= $alasan; ?></small>
                                    </td>
                                    <td><a href="../hasil/keluar.php?transaksi=<?= $transaksi; ?>"><button class="btn btn-primary">DETAIL</button></a></td>

                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>




                    </table>
                </div>
            </div>
        </div>
        <!-- tabel hasil data End -->
    </div>
</main>

<?php include "../footer.php"; ?>