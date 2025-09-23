<?php
require_once("conn.php");
$conn = mysqli_connect("localhost", "root", "", "emix_inventory_it");
date_default_timezone_set('Asia/Jakarta');


if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
//                                                                              <==FORM DI BARANG MASTER==>
if (isset($_POST['addnewbarang'])) {
    $kodebarang = $_POST['kodebarang'];
    $jenisbarang = $_POST['jenisbarang'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $satuan = $_POST['satuan'];
    $stok_awal = $_POST['stok_awal'];
    $maker = $_POST['maker'];
    $errors = [];

    $targetDir = "../admin/upload/";

    foreach ($kodebarang as $i => $kode) {
        $kode = strtoupper(mysqli_real_escape_string($conn, $kode));
        $jenis = strtoupper(mysqli_real_escape_string($conn, $jenisbarang[$i]));
        $nama = strtoupper(mysqli_real_escape_string($conn, $namabarang[$i]));
        $des = strtoupper(mysqli_real_escape_string($conn, $deskripsi[$i]));
        $satuanValue = strtoupper(mysqli_real_escape_string($conn, $satuan[$i]));
        $stokAwalValue = (int) $stok_awal[$i];
        $makerName = strtoupper(mysqli_real_escape_string($conn, $maker[$i]));
        $fotoDB = null;

        // cek upload foto (optional)
        if (isset($_FILES['foto']['name'][$i]) && $_FILES['foto']['name'][$i] != '') {
            $foto = $_FILES['foto']['name'][$i];
            $fotoTmp = $_FILES['foto']['tmp_name'][$i];
            $fileExt = pathinfo($foto, PATHINFO_EXTENSION);
            $newFileName = uniqid('barang_', true) . '.' . $fileExt;
            $uploadPath = $targetDir . $newFileName;

            if (move_uploaded_file($fotoTmp, $uploadPath)) {
                $fotoDB = $newFileName;
            }
        }

        // cek kode barang sudah ada belum
        $checkQuery = "SELECT 1 FROM master_barang WHERE kode_barang = '$kode'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            $errors[] = "Kode barang '$kode' sudah ada!";
        } else {
            $query = "INSERT INTO master_barang 
                        (kode_barang, jenis_barang, nama_barang, deskripsi, satuan, maker, foto, stok_awal, jumlah) 
                      VALUES 
                        ('$kode', '$jenis', '$nama', '$des', '$satuanValue', '$makerName', " .
                ($fotoDB ? "'$fotoDB'" : "NULL") . ", '$stokAwalValue', '$stokAwalValue')";

            if (!mysqli_query($conn, $query)) {
                $errors[] = "Error: " . mysqli_error($conn);
            }
        }
    }

    if (!empty($errors)) {
        session_start();
        $_SESSION['errors'] = $errors;
        echo "<script>alert('Terjadi kesalahan.'); window.location.href='../admin/databarang';</script>";
        exit();
    }

    echo "<script>alert('Barang berhasil ditambahkan!'); window.location.href='../admin/databarang';</script>";
    exit();
}




//                                                                              <== FORM BARANG MASTER SELESAI==>


//                                                                              <==FORM DI BARANG MASUK.PHP==>
if (isset($_POST['addnewbarangmasuk'])) {
    // Ambil data dari form
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $supplier = strtoupper(mysqli_real_escape_string($conn, $_POST['supplier']));
    $note = strtoupper($_POST['note']);
    $kodebarang = $_POST['kodebarang'];
    $jumlah = $_POST['jumlah'];
    $transaksi = "EMIX" . date("YmdHis");


    // Loop untuk menyimpan setiap item ke database
    for ($i = 0; $i < count($kodebarang); $i++) {
        $kode = mysqli_real_escape_string($conn, $kodebarang[$i]);
        $qty = mysqli_real_escape_string($conn, $jumlah[$i]);

        // Perbarui query untuk menyimpan data barang masuk dengan transaksi ID
        $queryMasuk = "INSERT INTO barang_masuk (tanggal_masuk, supplier_id, kode_barang, jumlah_masuk, note, transaksi) 
        VALUES ('$tanggal', '$supplier', '$kode', '$qty', '$note', '$transaksi')";


        // Eksekusi query untuk barang masuk
        if (mysqli_query($conn, $queryMasuk)) {
            // Update jumlah di tabel master_barang
            $updateQuery = "UPDATE master_barang SET jumlah = jumlah + $qty WHERE kode_barang = '$kode'";
            if (!mysqli_query($conn, $updateQuery)) {
                // Handle error for update query
                echo "Error updating master_barang: " . mysqli_error($conn);
            }
        } else {
            // Handle error for insert query
            echo "Error: " . mysqli_error($conn);
        }
    }

    // Redirect to appropriate page after form submission
    session_start(); // Ensure session is started
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
        echo "<script>alert('Barang berhasil ditambahkan!'); window.location.href='../admin/masuk';</script>";
    } else {
        echo "<script>alert('Barang berhasil ditambahkan!'); window.location.href='../user/masuk';</script>";
    }
    exit();
}

//                                                                                  <==Form Barang masuk Selesai==>

// UPDATE BARANG MASTER START
if (isset($_POST['update_barang'])) {
    $kode_barang_old = $_POST['kode_barang_old'];
    $kode_barang = $_POST['kode_barang'];
    $jenis_barang = $_POST['jenis_barang'];
    $nama_barang = $_POST['nama_barang'];
    $deskripsi = $_POST['deskripsi'];
    $maker = $_POST['maker'];
    $jumlah = $_POST['jumlah'];

    // Mendapatkan foto lama dari hidden input
    $foto_lama = $_POST['foto_lama'];

    // Get current timestamp for updated_at
    $current_timestamp = date('Y-m-d H:i:s');

    // Cek jika foto baru diupload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        // Jika ada foto baru
        $foto = $_FILES['foto']['name'];
        $fotoTmp = $_FILES['foto']['tmp_name'];
        $fotoSize = $_FILES['foto']['size'];
        $fotoError = $_FILES['foto']['error'];
        $targetDir = "../admin/upload/";

        // Cek apakah file adalah gambar
        $fileType = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
        $newFileName = uniqid('barang_', true) . '.' . $fileType;
        $uploadPath = $targetDir . $newFileName;

        // Validasi jenis dan ukuran gambar
        if (getimagesize($fotoTmp) === false) {
            echo "<script>alert('File is not an image.'); window.location.href='../admin/databarang';</script>";
            exit();
        }

        if ($fotoSize > 5000000) {
            echo "<script>alert('File is too large.'); window.location.href='../admin/databarang';</script>";
            exit();
        }

        // Upload file
        if (move_uploaded_file($fotoTmp, $uploadPath)) {
            // Jika upload berhasil, simpan foto baru
            $fotoDB = $newFileName;
        } else {
            echo "<script>alert('Error uploading file.'); window.location.href='../admin/databarang';</script>";
            exit();
        }
    } else {
        // Jika tidak ada foto yang diupload, gunakan foto lama
        $fotoDB = $foto_lama;
    }

    // Update query
    $query = "UPDATE master_barang 
    SET kode_barang = '$kode_barang',
    jenis_barang = '$jenis_barang',
    nama_barang = '$nama_barang',
    deskripsi = '$deskripsi',
    maker = '$maker',
    jumlah = '$jumlah',
    foto = '$fotoDB', 
    updated_at = '$current_timestamp'
    WHERE kode_barang = '$kode_barang_old'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Barang berhasil di edit!'); window.location.href='../admin/databarang';</script>";
    } else {
        echo "<script>alert('Gagal mengedit barang.'); window.location.href='../admin/databarang';</script>";
    }
    exit();
}


// UPDATE BARANG MASTER END

// HAPUS BARANG MASTER START
if (isset($_POST['delete_barang'])) {
    $kode_barang = $_POST['kode_barang'];

    $query = "DELETE FROM master_barang WHERE kode_barang = '$kode_barang'";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Barang berhasil dihapus!'); window.location.href='../admin/databarang';</script>";
    } else {
        echo "<script>
      alert('Gagal hapus barang');
    </script>";
    }
    exit();
}
// HAPUS BARANG KELUAR END



//                                                                                  <==FORM DI KELUAR.PHP==>
// FORM DI KELUAR.PHP
if (isset($_POST['addbarangkeluar'])) {
    session_start();
    $tanggal = $_POST['tanggal'];
    $user1 = strtoupper($_POST['user1']);
    $kodebarang = $_POST['kodebarang'];
    $jumlah = $_POST['jumlah'];
    $note = strtoupper($_POST['note']); // Mengambil nilai catatan dari form

    $transaksiId = "EMIX-" . date("YmdHis");

    for ($i = 0; $i < count($kodebarang); $i++) {
        $kode = mysqli_real_escape_string($conn, $kodebarang[$i]);
        $qty = mysqli_real_escape_string($conn, $jumlah[$i]);

        // Simpan barang keluar dengan transaksi yang sudah dibuat
        $queryKeluar = "INSERT INTO barang_keluar 
                        (tanggal_keluar, user1, kode_barang, jumlah_keluar, note, status_approve, transaksi) 
                        VALUES 
                        ('$tanggal', '$user1    ', '$kode', '$qty', '$note', 'pending', '$transaksiId')";

        if (!mysqli_query($conn, $queryKeluar)) {
            echo "Error: " . mysqli_error($conn);
        }
    }

    // Redirect setelah berhasil menyimpan
    if ($_SESSION['role'] == 'admin') {
        echo "<script>alert('Barang keluar ditambahkan!'); window.location.href='../admin/keluar';</script>";
    } else {
        echo "<script>alert('Barang keluar ditambahkan!'); window.location.href='../user/keluar';</script>";
    }
    exit();
}



// Fungsi Untuk APPROVED BY 
if (isset($_POST['approveBarangKeluar'])) {
    $id_keluar = $_POST['id_keluar'];
    $kode_barang = $_POST['kode_barang'];
    $jumlah = $_POST['jumlah'];

    // Update status approve di tabel barang_keluar
    $approveQuery = "UPDATE barang_keluar SET status_approve = 'approved' WHERE id = '$id_keluar'";
    if (mysqli_query($conn, $approveQuery)) {
        // Kurangi stok barang di tabel master_barang
        $updateQuery = "UPDATE master_barang SET jumlah = jumlah - $jumlah WHERE kode_barang = '$kode_barang'";
        mysqli_query($conn, $updateQuery);

        // Redirect kembali ke halaman persetujuan
        header("Location: ../admin/keluar");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}


//                                                                                 <==FORM BARANG KELUAR SELESAI==>
if (isset($_POST['update_user'])) {
    $original_username = mysqli_real_escape_string($conn, $_POST['original_username']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $departemen = mysqli_real_escape_string($conn, $_POST['departemen']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $original_password = $_POST['original_password'];
    $current_timestamp = date('Y-m-d H:i:s');

    if (empty($original_username) || empty($username) || empty($nama_lengkap) || empty($departemen)) {
        echo "<script>alert('Semua field harus diisi!'); window.history.back();</script>";
        exit();
    }

    // Check if the new username already exists
    $checkQuery = "SELECT * FROM user WHERE username = '$username' AND username != '$original_username'";
    $checkResult = mysqli_query($conn, $checkQuery);
    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('Username sudah ada. Silakan gunakan username yang berbeda.'); window.history.back();</script>";
        exit();
    }

    if (md5($password) !== $original_password) {
        $hashed_password = md5($password);
        $password_query = ", password='$hashed_password'";
    } else {
        $password_query = "";
    }

    $query = "UPDATE user SET username='$username', nama_lengkap='$nama_lengkap', updated_at='$current_timestamp', departemen='$departemen' $password_query WHERE username='$original_username'";

    error_log("Query: " . $query);

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('User berhasil diupdate!'); window.location.href='../admin/user';</script>";
    } else {
        error_log("MySQL Error: " . mysqli_error($conn));
        echo "<script>alert('Terjadi kesalahan saat mengupdate user.'); window.history.back();</script>";
    }
    exit();
}



// SUPLIER.PHP
if (isset($_POST['addnewsupplier'])) {
    // Ambil data dari form
    $names = $_POST['name'];
    $emails = $_POST['email'];
    $no_telps = $_POST['no_telp'];
    $alamats = $_POST['alamat'];

    // Validasi dan iterasi input array
    foreach ($names as $index => $name) {
        // Escape setiap input
        $name = mysqli_real_escape_string($conn, $name);
        $email = mysqli_real_escape_string($conn, $emails[$index]);
        $no_telp = mysqli_real_escape_string($conn, $no_telps[$index]);
        $alamat = mysqli_real_escape_string($conn, $alamats[$index]);

        // Periksa apakah nama supplier sudah ada
        $check_query = "SELECT * FROM supplier WHERE nama_supplier='$name'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            echo "<script>alert('Nama supplier $name sudah ada, gunakan nama yang berbeda!');</script>";
            exit();
        }

        // Query untuk menambahkan supplier
        $query = "INSERT INTO supplier (nama_supplier, email, no_telp, alamat) VALUES ('$name', '$email', '$no_telp', '$alamat')";

        // Eksekusi query
        if (!mysqli_query($conn, $query)) {
            echo "<script>alert('Terjadi kesalahan saat menambahkan supplier $name: " . mysqli_error($conn) . "');</script>";
            exit();
        }
    }

    echo "<script>alert('Semua supplier berhasil ditambahkan!'); window.location.href='../admin/supplier';</script>";
    exit();
}
