<?php
$title = "Supplier | Elastomix";
include "layout/header.php";

?>

<main>
    <div class="container-fluid">
        <h1 class="mt-4">Supplier</h1>
        <!-- Button trigger modal -->
        <button type="button" style="border-radius: 10px;" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah Supplier</button>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <!-- HEADER -->
                    <div class="modal-header bg-success">
                        <h1 class="modal-title fs-6 text-white" id="exampleModalLabel">Form Supplier</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- HEADER END -->

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form id="dynamicForm" action="../BackEnd/function.php" method="POST">
                            <div id="formInputs">
                                <div class="form-group mb-3 d-flex">
                                    <input type="text" name="name[]" placeholder="Nama Supplier" class="form-control" required>
                                    <input type="email" name="email[]" placeholder="Email (opsional)" class="form-control ms-2">
                                    <input type="text" name="no_telp[]" placeholder="No Telepon (opsional)" class="form-control ms-2">
                                    <input type="text" name="alamat[]" placeholder="Alamat (opsional)" class="form-control ms-2">
                                </div>
                            </div>
                        <button type="button" class="btn btn-success" id="addButton">Tambah Item</button>
                            <button type="button" class="btn btn-danger" id="removeButton">Hapus Item</button>
                            <button type="submit" class="btn btn-primary" name="addnewsupplier">Submit</button>
                        </form>
                    </div>
                    <!-- Modal End -->
                </div>
            </div>
        </div>

        <script>
            document.getElementById('addButton').addEventListener('click', function() {
                const formInputs = document.getElementById('formInputs');
                const newInputGroup = document.createElement('div');
                newInputGroup.className = 'form-group mb-3 d-flex';
                newInputGroup.innerHTML = `
                    <input type="text" name="name[]" placeholder="Nama Supplier" class="form-control" required>
                    <input type="email" name="email[]" placeholder="Email (opsional)" class="form-control ms-2">
                    <input type="text" name="no_telp[]" placeholder="No Telepon (opsional)" class="form-control ms-2">
                    <input type="text" name="alamat[]" placeholder="Alamat (opsional)" class="form-control ms-2">`;
                formInputs.appendChild(newInputGroup);
            });

            document.getElementById('removeButton').addEventListener('click', function() {
                const formInputs = document.getElementById('formInputs');
                const inputGroups = formInputs.getElementsByClassName('form-group mb-3');

                if (inputGroups.length > 1) {
                    formInputs.removeChild(inputGroups[inputGroups.length - 1]);
                } else {
                    alert("Tidak ada item untuk dihapus!");
                }
            });
        </script>

        <!-- tabel hasil data -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th>NO</th>
                                <th>NAMA SUPPLIER</th>
                                <th>EMAIL</th>
                                <th>NO TELEPON</th>
                                <th>ALAMAT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM supplier";
                            $result = mysqli_query($conn, $query);
                            $i = 1;

                            while ($data = mysqli_fetch_array($result)) {
                                $nama_supplier = $data['nama_supplier'];
                                $created_at = $data['created_at'];
                                $email = $data['email'];
                                $no_telp = !empty($data['no_telp']) ? $data['no_telp'] : '-';
                                $alamat = !empty($data['alamat']) ? $data['alamat'] : '-';
                            ?>
                                <tr class="text-center">
                                    <td><?= $i++; ?></td>
                                    <td><?= $nama_supplier; ?></td>
                                    <td><?= $email; ?></td>
                                    <td><?= $no_telp; ?></td>
                                    <td><?= $alamat ?></td>
                                    <td>
                                        <button
                                            class="btn btn-warning btn-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal"
                                            data-nama_supplier="<?= $nama_supplier; ?>"
                                            data-email="<?= $email; ?>"
                                            data-no_telp="<?= $no_telp; ?>"
                                            data-alamat="<?= $alamat; ?>">
                                            Edit
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
</main>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h1 class="modal-title fs-6 text-white" id="editModalLabel">Edit Supplier</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" action="" method="POST">
                    <input type="hidden" name="original_nama_supplier" id="editOriginalNamaSupplier">
                    <div class="form-group mb-3">
                        <label for="edit_nama_supplier">Nama Supplier</label>
                        <input type="text" name="nama_supplier" id="edit_nama_supplier" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_email">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_no_telp">No Telepon</label>
                        <input type="text" name="no_telp" id="edit_no_telp" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_alamat">Alamat</label>
                        <input type="text" name="alamat" id="edit_alamat" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary" name="update_supplier">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            const nama_supplier = button.getAttribute('data-nama_supplier');
            const email = button.getAttribute('data-email');
            const no_telp = button.getAttribute('data-no_telp');
            const alamat = button.getAttribute('data-alamat');

            document.getElementById('editOriginalNamaSupplier').value = nama_supplier;
            document.getElementById('edit_nama_supplier').value = nama_supplier;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_no_telp').value = no_telp;
            document.getElementById('edit_alamat').value = alamat;
        });
    });
</script>
<?php
if (isset($_POST['update_supplier'])) {
    $original_nama_supplier = mysqli_real_escape_string($conn, $_POST['original_nama_supplier']);
    $nama_supplier = mysqli_real_escape_string($conn, $_POST['nama_supplier']);
    $email = !empty($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : NULL;
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    // Periksa apakah nama supplier sudah ada
    $check_query = "SELECT * FROM supplier WHERE nama_supplier='$nama_supplier' AND nama_supplier != '$original_nama_supplier'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Nama supplier sudah ada, gunakan nama yang berbeda!');</script>";
    } else {
        // Periksa apakah email kosong atau tidak
        if ($email === NULL) {
            // Jika email kosong, ubah query supaya tidak memasukkan email
            $query = "UPDATE supplier SET nama_supplier='$nama_supplier', no_telp='$no_telp', alamat='$alamat' WHERE nama_supplier='$original_nama_supplier'";
        } else {
            // Jika email ada, masukkan email dalam query
            $query = "UPDATE supplier SET nama_supplier='$nama_supplier', email='$email', no_telp='$no_telp', alamat='$alamat' WHERE nama_supplier='$original_nama_supplier'";
        }

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Supplier berhasil diupdate!'); window.location.href='supplier';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat mengupdate supplier: " . mysqli_error($conn) . "');</script>";
        }
    }
}

?>

<?php include "../footer.php"; ?>