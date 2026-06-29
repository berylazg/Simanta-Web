-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2026 at 04:53 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simanta`
--

-- --------------------------------------------------------

--
-- Table structure for table `aktivitas_logs`
--

CREATE TABLE `aktivitas_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `aksi` varchar(100) NOT NULL,
  `model_tipe` varchar(100) DEFAULT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `aktivitas_logs`
--

INSERT INTO `aktivitas_logs` (`id`, `user_id`, `aksi`, `model_tipe`, `model_id`, `keterangan`, `created_at`) VALUES
(1, 1, 'Mencatat Pembayaran', 'tagihans', 15, 'Invoice INV-2026-015 telah dicatat pembayarannya', '2026-06-24 02:00:00'),
(2, 1, 'Kirim Reminder Email', 'tagihans', 6, 'Reminder email terkirim untuk INV-2026-006', '2026-06-23 01:00:00'),
(3, 1, 'Tambah Tagihan', 'tagihans', 18, 'Invoice baru INV-2026-018 berhasil ditambahkan', '2026-06-22 03:00:00'),
(4, 1, 'Tagihan Overdue', 'tagihans', 13, 'INV-2026-013 telah melewati tanggal jatuh tempo', '2026-06-20 01:00:00'),
(5, 1, 'Mencatat Pembayaran', 'tagihans', 17, 'Invoice INV-2026-017 telah dicatat pembayarannya', '2026-06-19 07:00:00'),
(6, 1, 'Kirim Reminder Email', 'tagihans', 12, 'Reminder email dijadwalkan untuk INV-2026-012', '2026-06-18 01:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori_tagihans`
--

CREATE TABLE `kategori_tagihans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_tagihans`
--

INSERT INTO `kategori_tagihans` (`id`, `nama_kategori`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Sewa Kendaraan', 'Biaya sewa kendaraan operasional kantor', '2026-06-25 02:12:37', '2026-06-25 02:12:37'),
(2, 'Sewa Rumah', 'Biaya sewa rumah dinas atau kantor', '2026-06-25 02:12:37', '2026-06-25 02:12:37'),
(3, 'Sewa Laptop / Alat IT', 'Sewa perangkat laptop, komputer, dan alat IT', '2026-06-25 02:12:37', '2026-06-25 02:12:37'),
(4, 'Tagihan Internet', 'Tagihan layanan internet kantor', '2026-06-25 02:12:37', '2026-06-25 02:12:37');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_06_22_082020_create_sessions_table', 1),
(2, '2026_06_22_082347_create_cache_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `pembayarans`
--

CREATE TABLE `pembayarans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tagihan_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_bayar` date NOT NULL,
  `jumlah_bayar` decimal(15,2) NOT NULL,
  `metode_bayar` enum('transfer_bank','tunai','cek','giro','lainnya') NOT NULL DEFAULT 'transfer_bank',
  `nomor_referensi` varchar(100) DEFAULT NULL,
  `file_bukti` varchar(255) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayarans`
--

INSERT INTO `pembayarans` (`id`, `tagihan_id`, `user_id`, `tanggal_bayar`, `jumlah_bayar`, `metode_bayar`, `nomor_referensi`, `file_bukti`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-01-28', 5000000.00, 'transfer_bank', 'TRF/2026/001', NULL, 'Pembayaran sewa kendaraan BG 1588 XA Januari', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(2, 2, 1, '2026-01-28', 5000000.00, 'transfer_bank', 'TRF/2026/002', NULL, 'Pembayaran sewa kendaraan BG 1444 XA Januari', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(3, 3, 1, '2026-03-28', 5300000.00, 'transfer_bank', 'TRF/2026/003', NULL, 'Pembayaran sewa kendaraan BG 1870 ZL Maret', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(4, 11, 1, '2026-03-28', 34725000.00, 'transfer_bank', 'TRF/2026/004', NULL, 'Pembayaran sewa rumah dinas Q1 2026', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(5, 14, 1, '2026-03-25', 7010000.00, 'transfer_bank', 'TRF/2026/005', NULL, 'Pembayaran internet CBN Maret', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(6, 15, 1, '2026-04-25', 7010000.00, 'transfer_bank', 'TRF/2026/006', NULL, 'Pembayaran internet CBN April', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(7, 17, 1, '2026-03-28', 7096200.00, 'transfer_bank', 'TRF/2026/007', NULL, 'Pembayaran sewa mesin fotocopy Maret', '2026-06-25 02:19:21', '2026-06-25 02:19:21');

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tagihan_id` bigint(20) UNSIGNED NOT NULL,
  `waktu_kirim` datetime NOT NULL,
  `status_kirim` enum('terkirim','gagal','dijadwalkan') NOT NULL DEFAULT 'dijadwalkan',
  `email_tujuan` varchar(150) NOT NULL,
  `pesan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reminders`
--

INSERT INTO `reminders` (`id`, `tagihan_id`, `waktu_kirim`, `status_kirim`, `email_tujuan`, `pesan`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-01-24 08:00:00', 'terkirim', 'admin@surveyorindonesia.co.id', 'Reminder: INV-2026-001 jatuh tempo 31 Jan 2026', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(2, 2, '2026-01-24 08:00:00', 'terkirim', 'admin@surveyorindonesia.co.id', 'Reminder: INV-2026-002 jatuh tempo 31 Jan 2026', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(3, 4, '2026-03-20 08:00:00', 'terkirim', 'admin@surveyorindonesia.co.id', 'Reminder: INV-2026-004 jatuh tempo 27 Mar 2026', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(4, 5, '2026-03-20 08:00:00', 'terkirim', 'admin@surveyorindonesia.co.id', 'Reminder: INV-2026-005 jatuh tempo 27 Mar 2026', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(5, 14, '2026-03-20 08:00:00', 'terkirim', 'admin@surveyorindonesia.co.id', 'Reminder: INV-2026-014 jatuh tempo 27 Mar 2026', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(6, 6, '2026-04-23 08:00:00', 'dijadwalkan', 'admin@surveyorindonesia.co.id', 'Reminder: INV-2026-006 jatuh tempo 30 Apr 2026', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(7, 12, '2026-06-23 08:00:00', 'dijadwalkan', 'admin@surveyorindonesia.co.id', 'Reminder: INV-2026-012 jatuh tempo 30 Jun 2026', '2026-06-25 02:19:21', '2026-06-25 02:19:21');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('an4V4au15O1dkxVse56pUiCMykf6OvXhPyaLjQCw', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoieVpISWVFeTV0ems1QUF6Mmt3N0lhb2JMVlk5YmhwS1JaTWVRSldiWSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjk6ImRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1782700396);

-- --------------------------------------------------------

--
-- Table structure for table `tagihans`
--

CREATE TABLE `tagihans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `kategori_id` bigint(20) UNSIGNED NOT NULL,
  `nomor_invoice` varchar(50) NOT NULL,
  `nama_tagihan` varchar(200) NOT NULL,
  `nomor_kontrak` varchar(100) DEFAULT NULL,
  `nominal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tanggal_invoice` date NOT NULL,
  `tanggal_jatuh_tempo` date NOT NULL,
  `tanggal_reminder` date DEFAULT NULL,
  `status` enum('draft','upcoming','overdue','paid') NOT NULL DEFAULT 'draft',
  `deskripsi` text DEFAULT NULL,
  `file_invoice` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tagihans`
--

INSERT INTO `tagihans` (`id`, `user_id`, `vendor_id`, `kategori_id`, `nomor_invoice`, `nama_tagihan`, `nomor_kontrak`, `nominal`, `tanggal_invoice`, `tanggal_jatuh_tempo`, `tanggal_reminder`, `status`, `deskripsi`, `file_invoice`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'INV-2026-001', 'Sewa Kendaraan BG 1588 XA — Januari 2026', 'KTR/2026/ASR/001', 5000000.00, '2026-01-02', '2026-01-31', '2026-01-24', 'paid', 'Sewa kendaraan operasional SIPAL', NULL, '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(2, 1, 1, 1, 'INV-2026-002', 'Sewa Kendaraan BG 1444 XA — Januari 2026', 'KTR/2026/ASR/002', 5000000.00, '2026-01-02', '2026-01-31', '2026-01-24', 'paid', 'Sewa kendaraan operasional SIPAL', NULL, '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(3, 1, 3, 1, 'INV-2026-003', 'Sewa Kendaraan BG 1870 ZL — Maret 2026', 'KTR/2026/WCS/001', 5300000.00, '2026-03-01', '2026-03-31', '2026-03-24', 'paid', 'Perpanjangan sewa kendaraan', NULL, '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(4, 1, 5, 1, 'INV-2026-004', 'Sewa Kendaraan BG 8119 OO — Maret 2026', 'KTR/2026/KTA/001', 21000000.00, '2026-03-01', '2026-03-27', '2026-03-20', 'overdue', 'Perpanjangan sewa kendaraan', NULL, '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(5, 1, 6, 1, 'INV-2026-005', 'Sewa Kendaraan BG 8965 CI — Maret 2026', 'KTR/2026/BIM/001', 21000000.00, '2026-03-01', '2026-03-27', '2026-03-20', 'overdue', 'Perpanjangan sewa kendaraan', NULL, '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(6, 1, 4, 1, 'INV-2026-006', 'Sewa Kendaraan BG 1505 AAU — April 2026', 'KTR/2026/JSB/001', 24000000.00, '2026-04-01', '2026-04-30', '2026-04-23', 'upcoming', 'Perpanjangan sewa kendaraan', NULL, '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(7, 1, 6, 1, 'INV-2026-007', 'Sewa Kendaraan BG 1401 CU — April 2026', 'KTR/2026/BIM/002', 23000000.00, '2026-04-01', '2026-04-30', '2026-04-23', 'upcoming', 'Perpanjangan sewa kendaraan', NULL, '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(8, 1, 2, 1, 'INV-2026-008', 'Sewa Kendaraan BG 1512 LR — Mei 2026', 'KTR/2026/ASA/001', 4400000.00, '2026-05-01', '2026-05-31', '2026-05-24', 'upcoming', 'Perpanjangan sewa kendaraan', NULL, '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(9, 1, 9, 1, 'INV-2026-009', 'Sewa Kendaraan BK 1887 ACG — April 2026', 'KTR/2026/MRZ/001', 4500000.00, '2026-04-01', '2026-04-30', '2026-04-23', 'draft', 'Perpanjangan sewa kendaraan', NULL, '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(10, 1, 7, 1, 'INV-2026-010', 'Sewa Kendaraan B 2774 PZM — April 2026', 'KTR/2026/AGS/001', 12900000.00, '2026-04-01', '2026-05-15', '2026-05-08', 'draft', 'Perpanjangan sewa kendaraan', NULL, '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(11, 1, 12, 2, 'INV-2026-011', 'Sewa Rumah Dinas — Q1 2026', 'KTR/2026/HRN/001', 34725000.00, '2026-01-01', '2026-03-31', '2026-03-24', 'paid', 'Sewa rumah dinas kantor Palembang', NULL, '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(12, 1, 12, 2, 'INV-2026-012', 'Sewa Rumah Dinas — Q2 2026', 'KTR/2026/HRN/002', 34725000.00, '2026-04-01', '2026-06-30', '2026-06-23', 'upcoming', 'Sewa rumah dinas kantor Palembang', NULL, '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(13, 1, 13, 2, 'INV-2026-013', 'Sewa Rumah Operasional — Maret 2026', 'KTR/2026/FBP/001', 20000000.00, '2026-03-01', '2026-03-20', '2026-03-13', 'overdue', 'Sewa rumah operasional lapangan', NULL, '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(14, 1, 10, 4, 'INV-2026-014', 'Tagihan Internet CBN — Maret 2026', 'KTR/2026/CBN/001', 7010000.00, '2026-03-01', '2026-03-27', '2026-03-20', 'paid', 'Tagihan internet kantor CBN', NULL, '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(15, 1, 10, 4, 'INV-2026-015', 'Tagihan Internet CBN — April 2026', 'KTR/2026/CBN/001', 7010000.00, '2026-04-01', '2026-04-27', '2026-04-20', 'paid', 'Tagihan internet kantor CBN', NULL, '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(16, 1, 10, 4, 'INV-2026-016', 'Tagihan Internet CBN — Mei 2026', 'KTR/2026/CBN/001', 7010000.00, '2026-05-01', '2026-05-27', '2026-05-20', 'upcoming', 'Tagihan internet kantor CBN', NULL, '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(17, 1, 11, 3, 'INV-2026-017', 'Sewa Mesin Fotocopy — Maret 2026', 'KTR/2026/AGR/001', 7096200.00, '2026-03-01', '2026-03-31', '2026-03-24', 'paid', 'Sewa mesin fotocopy kantor', NULL, '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(18, 1, 11, 3, 'INV-2026-018', 'Sewa Mesin Fotocopy — April 2026', 'KTR/2026/AGR/002', 7182400.00, '2026-04-01', '2026-04-30', '2026-04-23', 'upcoming', 'Sewa mesin fotocopy kantor', NULL, '2026-06-25 02:19:21', '2026-06-25 02:19:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','superadmin') NOT NULL DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin Palembang', 'admin@surveyorindonesia.co.id', '$2y$12$Lo8ZafYwiqqlAI8J.dsdye/sk3LTtKwpbfe8gzQXbTnkKOhocLk96', 'admin', '2026-06-22 03:33:41', '2026-06-22 01:25:32'),
(2, 'Beryl Gumay', 'berylazg@gmail.com', '$2y$12$C9Oye.JP9JIA.fOudkNiQO8O1SzEDpo6nuFYtqCNOgxh2B20bXWjK', 'admin', '2026-06-22 01:21:09', '2026-06-22 01:21:09');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_vendor` varchar(150) NOT NULL,
  `kontak_person` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `nama_vendor`, `kontak_person`, `email`, `telepon`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'Assa Rent', 'Customer Service', 'cs@assarent.co.id', '021-50200800', 'Jakarta', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(2, 'ADI SARANA ARMADA', 'Admin ASA', 'admin@adisarana.com', '021-12345678', 'Jakarta', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(3, 'WAHANA CIPTA SRIWIJAYA', 'Admin WCS', 'admin@wahanacs.com', '0711-123456', 'Palembang', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(4, 'JELI SUKSES BERSAMA', 'Admin JSB', 'admin@jelisukses.com', '0711-234567', 'Palembang', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(5, 'KOTA TUA ABADI', 'Admin KTA', 'admin@kotatuaabadi.com', '0711-345678', 'Palembang', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(6, 'BIMA', 'Bima', 'bima@gmail.com', '0812-3456789', 'Palembang', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(7, 'AGUNG SOLUSI TRANS', 'Admin AST', 'admin@agungsolusi.com', '0711-456789', 'Palembang', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(8, 'SERASI AUTO RAYA', 'Admin SAR', 'admin@serasiauto.com', '021-87654321', 'Jakarta', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(9, 'M. Rizki', 'M. Rizki', 'mrizki@gmail.com', '0813-4567890', 'Palembang', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(10, 'CYBERINDO ADITAMA', 'Admin CBN', 'admin@cbn.net.id', '021-23456789', 'Jakarta', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(11, 'Astra Graphia TBK', 'Admin Astra', 'admin@astragraphia.co.id', '021-34567890', 'Jakarta', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(12, 'Hernawaty', 'Hernawaty', 'hernawaty@gmail.com', '0812-5678901', 'Palembang', '2026-06-25 02:19:21', '2026-06-25 02:19:21'),
(13, 'FARELIS BAROKAH PROFERTY', 'Admin FBP', 'admin@farelis.com', '0711-567890', 'Palembang', '2026-06-25 02:19:21', '2026-06-25 02:19:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aktivitas_logs`
--
ALTER TABLE `aktivitas_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `kategori_tagihans`
--
ALTER TABLE `kategori_tagihans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tagihan_id` (`tagihan_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tagihan_id` (`tagihan_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tagihans`
--
ALTER TABLE `tagihans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_invoice` (`nomor_invoice`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aktivitas_logs`
--
ALTER TABLE `aktivitas_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kategori_tagihans`
--
ALTER TABLE `kategori_tagihans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pembayarans`
--
ALTER TABLE `pembayarans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tagihans`
--
ALTER TABLE `tagihans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aktivitas_logs`
--
ALTER TABLE `aktivitas_logs`
  ADD CONSTRAINT `aktivitas_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD CONSTRAINT `pembayarans_ibfk_1` FOREIGN KEY (`tagihan_id`) REFERENCES `tagihans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayarans_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `reminders`
--
ALTER TABLE `reminders`
  ADD CONSTRAINT `reminders_ibfk_1` FOREIGN KEY (`tagihan_id`) REFERENCES `tagihans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tagihans`
--
ALTER TABLE `tagihans`
  ADD CONSTRAINT `tagihans_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tagihans_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`),
  ADD CONSTRAINT `tagihans_ibfk_3` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_tagihans` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
