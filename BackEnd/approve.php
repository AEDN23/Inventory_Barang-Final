<?php
require_once("function.php");
session_start();

$id = $_POST['id'];
$action = $_POST['action'];
$admin_name = $_SESSION['username'];

$response = ["success" => false];

if ($action === 'approve') {
    $approveQuery = "UPDATE barang_keluar 
                     SET status_approve = 'approved', alasan = CONCAT('Disetujui oleh ', '$admin_name') 
                     WHERE id = '$id'";
    $ok = mysqli_query($conn, $approveQuery);

    if ($ok) {
        $barangQuery = mysqli_query($conn, "SELECT kode_barang, jumlah_keluar FROM barang_keluar WHERE id = '$id'");
        $barang = mysqli_fetch_assoc($barangQuery);
        //  $response = [
        //     "success" => true,
        //     "status" => "approved",
        //     "alasan" => "Disetujui oleh $admin_name",
        //     "data" => $barang['jumlah_keluar']
        // ];
        $jumlah_keluar = (int)$barang['jumlah_keluar'];
        $kode_barang = $barang['kode_barang'];

        $updateStok = "UPDATE master_barang 
                       SET jumlah = jumlah - $jumlah_keluar
                       WHERE kode_barang = '$kode_barang'";
        mysqli_query($conn, $updateStok);

        $response = [
            "success" => true,
            "status" => "approved",
            "alasan" => "Disetujui oleh $admin_name"
        ];
    }
} elseif ($action === 'reject') {
    $rejectQuery = "UPDATE barang_keluar 
                    SET status_approve = 'rejected', alasan = CONCAT('Barang ditolak oleh ', '$admin_name') 
                    WHERE id = '$id'";
    $ok = mysqli_query($conn, $rejectQuery);

    if ($ok) {
        $response = [
            "success" => true,
            "status" => "rejected",
            "alasan" => "Barang ditolak oleh $admin_name"
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
