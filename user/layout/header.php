<?php
require_once("../BackEnd/check_role_user.php");
require "../BackEnd/function.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="../../assets/img/images.jpg" rel="icon">
    <link href="../../css/bootstrap@5.3.3.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../../css/styles.css" rel="stylesheet" />
    <link href="../../css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script src="../../js/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="../../js/jquery-1.12.4.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>

    <script src="../../js/bundle.js"></script>
    <script src="../../js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>

<body class="sb-nav-fixed">

    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="index"><img src="../../assets/img/images.jpg" alt="" style="height: 25px; border-radius:5px;"> Inventory</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <div class="input-group"></div>
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="../logout.php">Logout</a></li>

                    <?php
                    // Check connection

                    // Query to get items with stock less than 2
                    $sql = "SELECT kode_barang, jumlah FROM master_barang WHERE jumlah < 2";
                    $result = $conn->query($sql);

                    // Check if there are any items with low stock
                    if ($result->num_rows > 0) {
                        // echo '<li><a class="dropdown-item" href="#">Low Stock Notifications</a></li>';
                        while ($row = $result->fetch_assoc()) {
                            echo '<li><a class="dropdown-item" href="#">' . $row["kode_barang"] . ' sisa stok ' . $row["jumlah"] . ' </a></li>';
                        }
                    } else {
                        echo '<li><a class="dropdown-item" href="#">No low stock items</a></li>';
                    }

                    ?>
                </ul>
            </li>
        </ul>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading" style="margin-top: -0px; ">Stats</div>
                        <a class="nav-link" href="<?= site_url(); ?>/user/index" style="margin-top:-10px; margin-top:-10px; ">
                            <div class="sb-nav-link-icon"><i class="bi bi-bar-chart"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading" style="margin-top: -10px; ">Master Data</div>
                        <!-- <a class="nav-link" href="<?= site_url(); ?>/user/user" style="margin-top:-10px; ">
                            <div class="sb-nav-link-icon"><i class="bi bi-people"></i></div>
                            User
                        </a> -->
                        <!-- <a class="nav-link" href="<?= site_url(); ?>/user/supplier" style="margin-top:-10px; ">
                            <div class="sb-nav-link-icon"><i class="bi bi-card-checklist"></i></div>
                            Supplier
                        </a> -->
                        <a class="nav-link" href="<?= site_url(); ?>/user/databarang" style="margin-top:-10px; ">
                            <div class="sb-nav-link-icon"><i class="bi bi-clipboard-data"></i></div>
                            Data Barang
                        </a>

                        <div class="sb-sidenav-menu-heading">MANAGEMEN BARANG</div>
                        <a class="nav-link" href="<?= site_url(); ?>/user/masuk" style="margin-top:-10px; ">
                            <div class="sb-nav-link-icon"><i class="bi bi-box-arrow-in-up-right"></i></div>
                            Item Masuk
                        </a>
                        <a class="nav-link" href="<?= site_url(); ?>/user/keluar" style="margin-top:-10px; ">
                            <div class="sb-nav-link-icon"><i class="bi bi-box-arrow-up-left"></i></div>
                            Item Keluar
                        </a>

                        <div class="sb-sidenav-menu-heading">LAPORAN BARANG</div>
                        <a class="nav-link" href="laporanmasuk" style="margin-top:-10px; ">
                            <div class="sb-nav-link-icon"><i class="bi bi-graph-up"></i> </div>
                            Laporan Item Masuk
                        </a>
                        <a class="nav-link" href="laporankeluar" style="margin-top:-10px; ">
                            <div class="sb-nav-link-icon"><i class="bi bi-graph-down"></i></div>
                            Laporan Item Keluar
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <a class="nav-link" href="../logout" style="margin-top:-10px;">
                        <div class="sb-nav-link-icon"></div><i class='bx bx-log-out bx-spin'></i>
                        Logout
                    </a>
                </div>
            </nav>
            </nav>
        </div>
        <div id="layoutSidenav_content">