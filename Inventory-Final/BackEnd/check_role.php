<?php
session_start();
// sesi waktu

// $session_timeout = 3600; // 1 jam
// Periksa apakah waktu terakhir aktivitas disimpan di sesi
// if (isset($_SESSION['last_activity'])) {
//     // Hitung selisih waktu antara sekarang dan waktu terakhir aktivitas
//     $elapsed_time = time() - $_SESSION['last_activity'];

//     // Jika selisih waktu melebihi batas, hapus sesi dan redirect ke halaman login
//     if ($elapsed_time > $session_timeout) {
//         session_unset(); // Hapus semua variabel sesi
//         session_destroy(); // Hancurkan sesi
//         header("Location: ../index?timeout=true");
//         exit();
//     }
// }
// $_SESSION['last_activity'] = time();

// sesi waktu end

if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
    header("Location: ../index");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../user/index");
    exit();
}

// Tambahkan ini jika `username` belum diambil
if (!isset($_SESSION['username'])) {
    $query = "SELECT username FROM user WHERE id = " . intval($_SESSION['id']);
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $user['username'];
    }
}
