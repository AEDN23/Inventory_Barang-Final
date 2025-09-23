<?php
require_once("function.php");

$username = $_POST['username'];
$password = md5($_POST['password']);

// Bagian untuk login
$query = mysqli_query($conn, "
    SELECT user.id, user.username, role.role_name 
    FROM user 
    JOIN role ON user.role_id = role.id 
    WHERE user.username='$username' AND user.password='$password'
");

if (mysqli_num_rows($query) != 0) {
    $row = mysqli_fetch_assoc($query);

    session_start();
    $_SESSION['id'] = $row['id'];
    $_SESSION['role'] = $row['role_name'];

    // Pastikan redirect berdasarkan role
    if ($row['role_name'] == 'admin') {
        header('Location: '   . 'admin/index.php');
    } elseif ($row['role_name'] == 'user') {
        header('Location: ' . 'user/index.php');
    }
    exit();
} else {
    // Jika login gagal
    header('Location: ' . '../');
    exit();
}
