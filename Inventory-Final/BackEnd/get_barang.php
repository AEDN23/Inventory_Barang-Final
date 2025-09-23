<?php
include 'function.php';

if (isset($_POST['search'])) {
    $search = $_POST['search'];

    // Query untuk mencari data berdasarkan pencarian
    $query = "SELECT kode_barang, jenis_barang, nama_barang, maker, satuan 
              FROM master_barang 
              WHERE kode_barang LIKE '%$search%' OR nama_barang LIKE '%$search%' 
              LIMIT 10";
    $result = mysqli_query($conn, $query);

    $data = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = [
                'id' => $row['kode_barang'],
                'text' => $row['kode_barang'] . ' - ' . $row['nama_barang'],
                'jenis_barang' => $row['jenis_barang'],
                'nama_barang' => $row['nama_barang'],
                'maker' => $row['maker'],
                'satuan' => $row['satuan'] 
            ];
        }
    }

    echo json_encode($data);
} elseif (isset($_POST['kode_barang'])) {
    $kode_barang = $_POST['kode_barang'];

    // Query untuk mengambil data detail berdasarkan kode_barang
    $query = "SELECT jenis_barang, nama_barang, maker, satuan 
              FROM master_barang 
              WHERE kode_barang = '$kode_barang'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        echo json_encode($data);
    } else {
        echo json_encode([]);
    }
}
