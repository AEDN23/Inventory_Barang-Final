<?php
session_start();
$session_timeout = 3600; // 1 jam
// Periksa apakah waktu terakhir aktivitas disimpan di sesi
if (isset($_SESSION['last_activity'])) {
    // Hitung selisih waktu antara sekarang dan waktu terakhir aktivitas
    $elapsed_time = time() - $_SESSION['last_activity'];

    // Jika selisih waktu melebihi batas, hapus sesi dan redirect ke halaman login
    if ($elapsed_time > $session_timeout) {
        session_unset(); // Hapus semua variabel sesi
        session_destroy(); // Hancurkan sesi
        header("Location: ../index?timeout=true");
        exit();
    }
}
$_SESSION['last_activity'] = time();

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'user') {
    header("Location: ../index.php");
    exit();
}
