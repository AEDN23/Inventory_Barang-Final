<?php
$title = "User | Elastomix";
include "layout/header.php";

if (isset($_POST['addnewuser'])) {
    $usernames = $_POST['username'];
    $nama_lengkaps = $_POST['nama_lengkap'];
    $departemens = $_POST['departemen'];
    $passwords = $_POST['password'];
    $role_id = 2;

    foreach ($usernames as $index => $username) {
        $username = mysqli_real_escape_string($conn, $username);
        $nama_lengkap = mysqli_real_escape_string($conn, $nama_lengkaps[$index]);
        $departemen = mysqli_real_escape_string($conn, $departemens[$index]);
        $password = mysqli_real_escape_string($conn, $passwords[$index]);

        $check_query = "SELECT * FROM user WHERE username = '$username'";
        $checkResult = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($checkResult) > 0) {
            echo "<script>alert('Username $username sudah digunakan! Silahkan gunakan username yang lain.'); window.history.back();</script>";
            exit();
        }

        $hashed_password = md5($password);
        $query = "INSERT INTO user (username, nama_lengkap, departemen, password, role_id) VALUES ('$username', '$nama_lengkap', '$departemen', '$hashed_password', '$role_id')";

        if (!mysqli_query($conn, $query)) {
            echo "<script>alert('Terjadi kesalahan saat menambahkan user $username: " . mysqli_error($conn) . "');</script>";
            exit();
        }
    }

    echo "<script>alert('Semua user berhasil ditambahkan!'); window.location.href='user';</script>";
    exit();
}


?>

<main>
    <div class="container-fluid">
        <h1 class="mt-4">User</h1>
        <button type="button" style="border-radius: 10px;" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah user</button>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h1 class="modal-title fs-6 text-white" id="exampleModalLabel">Form User</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="dynamicForm" action="" method="POST">
                            <div id="formInputs">
                                <div class="form-group mb-3 d-flex">
                                    <input type="text" name="username[]" placeholder="Username" class="form-control" required>
                                    <input type="text" name="nama_lengkap[]" placeholder="Nama Lengkap" class="form-control ms-2" required>
                                    <select name="departemen[]" class="form-control ms-2" required>
                                        <option value=" " hidden>Departemen</option>
                                        <option value="PGA">PGA</option>
                                        <option value="IT">IT</option>
                                        <option value="Purchasing">Purchasing</option>
                                        <option value="Warehouse">Warehouse</option>
                                        <option value="QC/QA">QC/QA</option>
                                        <option value="Sales">Sales</option>
                                        <option value="Produksi">Produksi</option>
                                    </select>
                                    <input type="password" name="password[]" placeholder="Password" class="form-control ms-2" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success" id="addButton">Tambah Item</button>
                            <button type="button" class="btn btn-danger" id="removeButton">Hapus Item</button>
                            <button type="submit" class="btn btn-primary" name="addnewuser">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('dynamicForm').addEventListener('submit', function(event) {
                let isValid = true;
                const inputs = document.querySelectorAll('#formInputs input');

                inputs.forEach(input => {
                    if (input.value.trim() === '') {
                        isValid = false;
                        input.classList.add('is-invalid');
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    event.preventDefault();
                    alert('Semua field harus diisi.');
                }
            });

            document.getElementById('addButton').addEventListener('click', function() {
                const formInputs = document.getElementById('formInputs');
                const newInputGroup = document.createElement('div');
                newInputGroup.className = 'form-group mb-3 d-flex';
                newInputGroup.innerHTML = `
                    <input type="text" name="username[]" placeholder="Username" class="form-control" required>
                    <input type="text" name="nama_lengkap[]" placeholder="Nama Lengkap" class="form-control ms-2" required>
                    <select name="departemen[]" class="form-control ms-2" required>
                        <option value="" hidden>Departemen</option>
                        <option value="PGA">PGA</option>
                        <option value="IT">IT</option>
                        <option value="Purchasing">Purchasing</option>
                        <option value="Warehouse">Warehouse</option>
                        <option value="QC/QA">QC/QA</option>
                        <option value="Sales">Sales</option>
                    </select>
                    <input type="password" name="password[]" placeholder="Password" class="form-control ms-2" required>`;
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

        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered " id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th>NO</th>
                                <th>USERNAME</th>
                                <th>NAMA LENGKAP</th>
                                <th>DEPARTEMEN</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM user WHERE role_id != 1";
                            $result = mysqli_query($conn, $query);
                            $i = 1;

                            while ($data = mysqli_fetch_array($result)) {
                                $username = $data['username'];
                                $created_at = $data['created_at'];
                                $nama_lengkap = $data['nama_lengkap'];
                                $departemen = $data['departemen'];
                            ?>
                                <tr class="text-center">
                                    <td><?= $i++; ?></td>
                                    <td><?= $username; ?></td>
                                    <td><?= $nama_lengkap; ?></td>
                                    <td><?= $departemen ?></td>
                                    <td>
                                        <button
                                            class="btn btn-warning btn-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal"
                                            data-username="<?= $username; ?>"
                                            data-nama_lengkap="<?= $nama_lengkap; ?>"
                                            data-departemen="<?= $departemen; ?>"
                                            data-password="<?= $data['password']; ?>">
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
                <h1 class="modal-title fs-6 text-white" id="editModalLabel">Edit User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" action="../BackEnd/function.php" method="POST">
                    <input type="hidden" name="original_username" id="editOriginalUsername">
                    <div class="form-group mb-3">
                        <label for="editUsername">Username</label>
                        <input type="text" name="username" id="editUsername" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="editNamaLengkap">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="editNamaLengkap" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="editDepartemen">Departemen</label>
                        <select name="departemen" id="editDepartemen" class="form-control" required>
                            <option value="PGA">PGA</option>
                            <option value="IT">IT</option>
                            <option value="Purchasing">Purchasing</option>
                            <option value="Warehouse">Warehouse</option>
                            <option value="QC/QA">QC/QA</option>
                            <option value="Sales">Sales</option>
                            <option value="Produksi">Produksi</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="editPassword">Password</label>
                        <input type="password" name="password" id="editPassword" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="update_user">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            const username = button.getAttribute('data-username');
            const namaLengkap = button.getAttribute('data-nama_lengkap');
            const departemen = button.getAttribute('data-departemen');
            const password = button.getAttribute('data-password');

            document.getElementById('editOriginalUsername').value = username;
            document.getElementById('editUsername').value = username;
            document.getElementById('editNamaLengkap').value = namaLengkap;
            document.getElementById('editDepartemen').value = departemen;
            document.getElementById('editPassword').value = password;
        });
    });
</script>

<?php include "../footer.php"; ?>