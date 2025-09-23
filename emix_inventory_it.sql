-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 13 Feb 2025 pada 17.18
-- Versi server: 8.0.30
-- Versi PHP: 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `emix_inventory_it`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang_keluar`
--

CREATE TABLE `barang_keluar` (
  `id` int NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `user_id` int NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `jumlah_keluar` int NOT NULL,
  `note` text,
  `status_approve` enum('pending','approved','rejected') NOT NULL,
  `alasan` varchar(255) DEFAULT NULL,
  `transaksi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `barang_keluar`
--

INSERT INTO `barang_keluar` (`id`, `tanggal_keluar`, `user_id`, `kode_barang`, `jumlah_keluar`, `note`, `status_approve`, `alasan`, `transaksi`) VALUES
(36, '2025-01-24', 1, 'KEYBOARD WIRELES', 2, 'NOTE', 'rejected', 'Barang ditolak oleh admin', 'EMIX-20250124131525'),
(37, '2025-01-24', 1, 'MOUSE  WIRELLES', 3, 'NOTE', 'approved', 'Disetujui oleh admin', 'EMIX-20250124131525'),
(38, '2025-01-24', 1, 'KEYBOARD LOGITECH', 1, 'TIDAK ADA', 'pending', NULL, 'EMIX-20250124140818'),
(39, '2025-02-04', 17, 'KEYBOARD WIRELES', 4, 'RUANG SERVER', 'pending', NULL, 'EMIX-20250204121629'),
(40, '2025-02-04', 17, 'KEYBOARD LOGITECH', 1, 'RUANG SERVER', 'pending', NULL, 'EMIX-20250204121629');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `id_masuk` int NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `supplier_id` int NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `jumlah_masuk` int NOT NULL,
  `note` text,
  `transaksi` varchar(50) NOT NULL,
  `garansi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `barang_masuk`
--

INSERT INTO `barang_masuk` (`id_masuk`, `tanggal_masuk`, `supplier_id`, `kode_barang`, `jumlah_masuk`, `note`, `transaksi`, `garansi`) VALUES
(33, '2025-01-24', 17, 'KEYBOARD WIRELES', 2, 'DI BELI PADA TANGGAL 25 JANUARI', 'EMIX20250124131452', NULL),
(34, '2025-01-24', 17, 'PC AIO', 3, 'DI BELI PADA TANGGAL 25 JANUARI', 'EMIX20250124131452', NULL),
(35, '2025-01-24', 17, 'KEYBOARD WIRELES', 2, '', 'EMIX20250124131504', NULL),
(36, '2025-01-24', 17, 'KEYBOARD LOGITECH', 2, '-', 'EMIX20250124140736', NULL),
(37, '2025-02-04', 17, 'KEYBOARD LOGITECH', 5, 'BUAT RUANG SERVE', 'EMIX20250204121530', NULL),
(38, '2025-02-04', 17, 'KEYBOARD WIRELES', 3, 'BUAT RUANG SERVE', 'EMIX20250204121530', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_barang`
--

CREATE TABLE `master_barang` (
  `kode_barang` varchar(50) NOT NULL,
  `jenis_barang` varchar(100) DEFAULT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `deskripsi` text,
  `satuan` varchar(25) NOT NULL DEFAULT 'unit',
  `garansi` varchar(255) DEFAULT NULL,
  `maker` varchar(100) DEFAULT NULL,
  `jumlah` int DEFAULT '0',
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `master_barang`
--

INSERT INTO `master_barang` (`kode_barang`, `jenis_barang`, `nama_barang`, `deskripsi`, `satuan`, `garansi`, `maker`, `jumlah`, `foto`, `created_at`, `updated_at`) VALUES
('KEYBOARD LOGITECH', 'ELEKTRONIK', '-', 'RUANG SERVER', 'UNIT', 'TIDAK ADA', 'LENOVO', 6, '', '2025-01-24 14:06:36', '2025-02-04 12:15:30'),
('KEYBOARD WIRELES', 'ELEKTRONIK', '-', 'RUANG SERVER', 'UNIT', 'TIDAK ADA', 'LENOVO', 4, '', '2025-01-24 13:11:58', '2025-02-04 12:15:30'),
('MOUSE  WIRELLES', 'ELEKTRONIK', '-', 'LEMARI', 'UNIT', 'TIDAK ADA', 'FANTECH', 2, '', '2025-01-24 13:11:58', '2025-01-30 00:53:43'),
('PC AIO', 'ELEKTRONIK', 'DI PAKE DI WEIGHJING', 'RUANG TRAINING', 'UNIT', '10 JANUARI 2030', 'HP', 3, '', '2025-01-24 13:11:58', '2025-01-24 13:14:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role`
--

CREATE TABLE `role` (
  `id` int NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `role`
--

INSERT INTO `role` (`id`, `role_name`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `id` int NOT NULL,
  `nama_supplier` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `alamat` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `supplier`
--

INSERT INTO `supplier` (`id`, `nama_supplier`, `email`, `no_telp`, `alamat`, `created_at`, `updated_at`) VALUES
(17, 'INDOCOOM', '', '', '', '2025-01-24 13:14:26', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `departemen` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `username`, `nama_lengkap`, `departemen`, `password`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'super admin', 'admin', '202cb962ac59075b964b07152d234b70', 1, '2025-01-16 10:27:02', NULL),
(14, 'Riza', 'Muhammad riza dwi prasetia', 'IT', '202cb962ac59075b964b07152d234b70', 2, '2025-01-16 10:27:38', NULL),
(17, 'Rama', 'Rama dan putra', 'PGA', '202cb962ac59075b964b07152d234b70', 2, '2025-01-16 10:28:21', NULL),
(18, 'Rizaa', 'Muhammad riza dwi prasetiaa', 'IT', '202cb962ac59075b964b07152d234b70', 2, '2025-01-20 16:16:33', NULL),
(19, 'rIZAAA', 'Muhammad riza dwi prasetiaa', 'PGA', 'b2ef9c7b10eb0985365f913420ccb84a', 2, '2025-01-20 16:16:59', NULL),
(20, 'rIZZ', 'MM', 'IT', '202cb962ac59075b964b07152d234b70', 2, '2025-01-20 16:16:59', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `kode_barang` (`kode_barang`);

--
-- Indeks untuk tabel `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id_masuk`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `kode_barang` (`kode_barang`);

--
-- Indeks untuk tabel `master_barang`
--
ALTER TABLE `master_barang`
  ADD PRIMARY KEY (`kode_barang`);

--
-- Indeks untuk tabel `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `user_ibfk_2` (`departemen`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang_keluar`
--
ALTER TABLE `barang_keluar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT untuk tabel `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `id_masuk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT untuk tabel `role`
--
ALTER TABLE `role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD CONSTRAINT `barang_keluar_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `barang_keluar_ibfk_2` FOREIGN KEY (`kode_barang`) REFERENCES `master_barang` (`kode_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD CONSTRAINT `barang_masuk_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `barang_masuk_ibfk_2` FOREIGN KEY (`kode_barang`) REFERENCES `master_barang` (`kode_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
