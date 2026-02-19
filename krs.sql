-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 19, 2026 at 08:02 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `krs`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_events`
--

CREATE TABLE `academic_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_type` enum('krs','krs_perubahan','perkuliahan','uts','uas','libur_akademik','lainnya') NOT NULL DEFAULT 'lainnya',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `semester_id` bigint(20) UNSIGNED DEFAULT NULL,
  `color` varchar(7) NOT NULL DEFAULT '#3788d8',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `activity` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `user_id`, `nip`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 1, '198501012010011001', '081234567890', 'Jl. Kampus STIH No. 1', '2026-02-10 04:54:57', '2026-02-10 04:54:57');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `actor_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(100) NOT NULL,
  `auditable_type` varchar(255) NOT NULL,
  `auditable_id` bigint(20) UNSIGNED NOT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `actor_id`, `action`, `auditable_type`, `auditable_id`, `meta`, `created_at`) VALUES
(1, 9, 'installment_request.approve', 'App\\Models\\InstallmentRequest', 2, '{\"approved_terms\":12,\"invoice_id\":2,\"notes\":null}', '2026-02-17 21:16:17'),
(2, 9, 'payment_proof.approve', 'App\\Models\\PaymentProof', 1, '{\"payment_id\":1,\"installment_id\":1,\"amount\":4166000,\"notes\":null}', '2026-02-17 21:26:40');

-- --------------------------------------------------------

--
-- Table structure for table `bobot_penilaian`
--

CREATE TABLE `bobot_penilaian` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED NOT NULL,
  `bobot_partisipatif` decimal(5,2) NOT NULL DEFAULT 25.00,
  `bobot_proyek` decimal(5,2) NOT NULL DEFAULT 25.00,
  `bobot_quiz` decimal(5,2) NOT NULL DEFAULT 5.00,
  `bobot_tugas` decimal(5,2) NOT NULL DEFAULT 5.00,
  `bobot_uts` decimal(5,2) NOT NULL DEFAULT 20.00,
  `bobot_uas` decimal(5,2) NOT NULL DEFAULT 20.00,
  `is_locked` tinyint(1) NOT NULL DEFAULT 0,
  `locked_at` timestamp NULL DEFAULT NULL,
  `locked_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bobot_penilaian`
--

INSERT INTO `bobot_penilaian` (`id`, `kelas_id`, `bobot_partisipatif`, `bobot_proyek`, `bobot_quiz`, `bobot_tugas`, `bobot_uts`, `bobot_uas`, `is_locked`, `locked_at`, `locked_by`, `created_at`, `updated_at`) VALUES
(1, 1, 25.00, 25.00, 5.00, 5.00, 20.00, 20.00, 1, '2026-02-10 06:51:03', 2, '2026-02-10 06:50:45', '2026-02-10 06:51:03'),
(2, 5, 25.00, 25.00, 5.00, 5.00, 20.00, 20.00, 0, NULL, NULL, '2026-02-11 21:09:01', '2026-02-11 21:09:01'),
(3, 2, 25.00, 25.00, 5.00, 5.00, 20.00, 20.00, 1, '2026-02-13 01:14:48', 2, '2026-02-11 21:26:55', '2026-02-13 01:14:48'),
(4, 3, 25.00, 25.00, 5.00, 5.00, 20.00, 20.00, 1, '2026-02-13 01:20:54', 2, '2026-02-12 21:53:23', '2026-02-13 01:20:54'),
(5, 4, 25.00, 25.00, 5.00, 5.00, 20.00, 20.00, 1, '2026-02-13 01:21:18', 2, '2026-02-13 01:21:16', '2026-02-13 01:21:18');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dokumen_kelas`
--

CREATE TABLE `dokumen_kelas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED NOT NULL,
  `tipe_dokumen` enum('silabus','rps') NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `path_file` varchar(255) NOT NULL,
  `uploaded_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dokumen_kelas`
--

INSERT INTO `dokumen_kelas` (`id`, `kelas_id`, `tipe_dokumen`, `nama_file`, `path_file`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'silabus', 'surat_1_1770871777.pdf', 'dokumen/silabus_1_1771479897.pdf', 2, '2026-02-10 21:41:06', '2026-02-18 22:44:57'),
(2, 1, 'rps', 'KRS_2024010001_9.pdf', 'dokumen/rps_1_1770784876.pdf', 2, '2026-02-10 21:41:16', '2026-02-10 21:41:16'),
(3, 2, 'silabus', '1. Proposal Penelitian Determinasi Faham Abolisionis dan Retensionis Terhadap Masa Tunggu Pidana Mati dalam Prespektif Pembaharuan Hukum Pidana di Indonesia.pdf', 'dokumen/silabus_2_1770868575.pdf', 2, '2026-02-11 00:39:13', '2026-02-11 20:56:15'),
(4, 3, 'silabus', 'JadwalMatkul_Genap2425_UrutNamaMatkul.pdf', 'dokumen/silabus_3_1770798305.pdf', 2, '2026-02-11 01:25:05', '2026-02-11 01:25:05'),
(5, 4, 'silabus', '1. Proposal Penelitian Determinasi Faham Abolisionis dan Retensionis Terhadap Masa Tunggu Pidana Mati dalam Prespektif Pembaharuan Hukum Pidana di Indonesia.pdf', 'dokumen/silabus_4_1770869766.pdf', 2, '2026-02-11 01:25:31', '2026-02-11 21:16:06'),
(6, 5, 'silabus', '1. Proposal Penelitian Determinasi Faham Abolisionis dan Retensionis Terhadap Masa Tunggu Pidana Mati dalam Prespektif Pembaharuan Hukum Pidana di Indonesia.pdf', 'dokumen/silabus_5_1770869131.pdf', 2, '2026-02-11 21:05:31', '2026-02-11 21:05:31'),
(7, 2, 'rps', '2. Form Penilaian Proposal Penelitian (Sandi Yudha Prayoga, S.H., M.H.).pdf', 'dokumen/rps_2_1770953590.pdf', 2, '2026-02-12 20:33:02', '2026-02-12 20:33:10');

-- --------------------------------------------------------

--
-- Table structure for table `dosens`
--

CREATE TABLE `dosens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nidn` varchar(255) NOT NULL,
  `pendidikan_terakhir` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Multiple education levels: S1, S2, S3' CHECK (json_valid(`pendidikan_terakhir`)),
  `universitas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Array of universities for each education level' CHECK (json_valid(`universitas`)),
  `dosen_tetap` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is permanent lecturer',
  `jabatan_fungsional` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Functional positions' CHECK (json_valid(`jabatan_fungsional`)),
  `pendidikan` varchar(255) DEFAULT NULL,
  `prodi` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `mata_kuliah_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`mata_kuliah_ids`)),
  `status` enum('aktif','non-aktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kuota` int(11) NOT NULL DEFAULT 6
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dosens`
--

INSERT INTO `dosens` (`id`, `user_id`, `nidn`, `pendidikan_terakhir`, `universitas`, `dosen_tetap`, `jabatan_fungsional`, `pendidikan`, `prodi`, `phone`, `address`, `mata_kuliah_ids`, `status`, `created_at`, `updated_at`, `kuota`) VALUES
(1, 2, '0101018501', '[\"S1\"]', '[\"Universitas Gunadarma\"]', 0, '[\"Lektor\"]', 'S1', '[\"HK01\"]', '081234567891', 'Jl. Dosen No. 1', '[\"2\",\"1\",\"3\",\"4\",\"7\",\"50\"]', 'aktif', '2026-02-10 04:54:57', '2026-02-18 22:06:53', 6),
(2, 3, '0102028601', '[\"S1\"]', '[\"STIH\"]', 0, '[\"Lektor Kepala\"]', 'S1', '[\"HK01\"]', '081234567892', 'Jl. Dosen No. 2', '[\"4\",\"49\"]', 'aktif', '2026-02-10 04:54:57', '2026-02-12 01:58:52', 6),
(3, 4, '0103038701', '[\"S1\"]', '[\"STIH\"]', 0, '[\"Lektor Kepala\"]', 'S1', '[\"HK01\"]', '081234567893', 'Jl. Dosen No. 3', '[\"4\",\"49\",\"61\"]', 'aktif', '2026-02-10 04:54:57', '2026-02-12 01:59:05', 6),
(4, 10, '0102028604', '[\"S1\"]', '[\"STIH\"]', 1, '[\"Lektor\"]', 'S1', '[\"HK02\"]', '081234567896', 'jl.test', '[\"11\"]', 'aktif', '2026-02-17 23:33:51', '2026-02-18 02:58:59', 6);

-- --------------------------------------------------------

--
-- Table structure for table `dosen_attendances`
--

CREATE TABLE `dosen_attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dosen_id` bigint(20) UNSIGNED NOT NULL,
  `kelas_mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `pertemuan_id` bigint(20) UNSIGNED NOT NULL,
  `metode_pengajaran` enum('offline','online','asynchronous') NOT NULL DEFAULT 'offline',
  `jam_kelas_mulai` time DEFAULT NULL COMMENT 'Scheduled class start time',
  `jam_kelas_selesai` time DEFAULT NULL COMMENT 'Scheduled class end time',
  `jam_absen_dosen` datetime NOT NULL COMMENT 'When dosen tapped activate QR',
  `lokasi_dosen` varchar(500) DEFAULT NULL COMMENT 'GPS coords or address',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dosen_attendances`
--

INSERT INTO `dosen_attendances` (`id`, `dosen_id`, `kelas_mata_kuliah_id`, `pertemuan_id`, `metode_pengajaran`, `jam_kelas_mulai`, `jam_kelas_selesai`, `jam_absen_dosen`, `lokasi_dosen`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 1, 11, 66, 'offline', '09:45:00', '11:15:00', '2026-02-19 05:47:36', NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-18 22:47:36', '2026-02-18 22:47:36'),
(2, 1, 11, 68, 'offline', '09:45:00', '11:15:00', '2026-02-19 05:53:14', NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-18 22:53:14', '2026-02-18 22:53:14'),
(3, 1, 11, 70, 'offline', '09:45:00', '11:15:00', '2026-02-19 05:53:27', NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-18 22:53:27', '2026-02-18 22:53:27'),
(4, 1, 12, 69, 'offline', '19:55:00', '21:25:00', '2026-02-19 06:12:39', NULL, '192.168.1.34', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Mobile Safari/537.36', '2026-02-18 23:12:39', '2026-02-18 23:12:39'),
(5, 1, 11, 71, 'offline', '09:45:00', '11:15:00', '2026-02-19 06:37:11', '-6.2379456,106.8542557', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-18 23:37:11', '2026-02-18 23:37:11');

-- --------------------------------------------------------

--
-- Table structure for table `dosen_availabilities`
--

CREATE TABLE `dosen_availabilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dosen_id` bigint(20) UNSIGNED NOT NULL,
  `semester_id` bigint(20) UNSIGNED NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL COMMENT 'Hari tersedia',
  `jam_perkuliahan_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('available','booked','blocked') NOT NULL DEFAULT 'available' COMMENT 'Status ketersediaan',
  `notes` text DEFAULT NULL COMMENT 'Catatan dari dosen',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dosen_availability_checks`
--

CREATE TABLE `dosen_availability_checks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dosen_id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `hari` varchar(255) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dosen_pa`
--

CREATE TABLE `dosen_pa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dosen_id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fakultas`
--

CREATE TABLE `fakultas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_fakultas` varchar(10) NOT NULL,
  `nama_fakultas` varchar(255) NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fakultas`
--

INSERT INTO `fakultas` (`id`, `kode_fakultas`, `nama_fakultas`, `status`, `created_at`, `updated_at`) VALUES
(1, 'FH', 'Fakultas Hukum', 'aktif', '2026-02-10 04:54:55', '2026-02-10 04:54:55');

-- --------------------------------------------------------

--
-- Table structure for table `import_logs`
--

CREATE TABLE `import_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `total_rows` int(11) NOT NULL DEFAULT 0,
  `success_count` int(11) NOT NULL DEFAULT 0,
  `failed_count` int(11) NOT NULL DEFAULT 0,
  `skipped_count` int(11) NOT NULL DEFAULT 0,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `imported_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `installments`
--

CREATE TABLE `installments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `installment_no` int(11) NOT NULL,
  `amount` bigint(20) UNSIGNED NOT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('UNPAID','WAITING_VERIFICATION','PAID','REJECTED_PAYMENT') NOT NULL DEFAULT 'UNPAID',
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `installments`
--

INSERT INTO `installments` (`id`, `invoice_id`, `installment_no`, `amount`, `due_date`, `status`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 4166000, '2026-02-18', 'PAID', '2026-02-18 04:26:40', '2026-02-17 21:16:17', '2026-02-17 21:26:40'),
(2, 2, 2, 4166000, '2026-03-18', 'UNPAID', NULL, '2026-02-17 21:16:17', '2026-02-17 21:16:17'),
(3, 2, 3, 4166000, '2026-04-18', 'UNPAID', NULL, '2026-02-17 21:16:17', '2026-02-17 21:16:17'),
(4, 2, 4, 4166000, '2026-05-18', 'UNPAID', NULL, '2026-02-17 21:16:17', '2026-02-17 21:16:17'),
(5, 2, 5, 4166000, '2026-06-18', 'UNPAID', NULL, '2026-02-17 21:16:17', '2026-02-17 21:16:17'),
(6, 2, 6, 4166000, '2026-07-18', 'UNPAID', NULL, '2026-02-17 21:16:17', '2026-02-17 21:16:17'),
(7, 2, 7, 4166000, '2026-08-18', 'UNPAID', NULL, '2026-02-17 21:16:17', '2026-02-17 21:16:17'),
(8, 2, 8, 4166000, '2026-09-18', 'UNPAID', NULL, '2026-02-17 21:16:17', '2026-02-17 21:16:17'),
(9, 2, 9, 4166000, '2026-10-18', 'UNPAID', NULL, '2026-02-17 21:16:17', '2026-02-17 21:16:17'),
(10, 2, 10, 4166000, '2026-11-18', 'UNPAID', NULL, '2026-02-17 21:16:17', '2026-02-17 21:16:17'),
(11, 2, 11, 4166000, '2026-12-18', 'UNPAID', NULL, '2026-02-17 21:16:17', '2026-02-17 21:16:17'),
(12, 2, 12, 4174000, '2027-01-18', 'UNPAID', NULL, '2026-02-17 21:16:17', '2026-02-17 21:16:17');

-- --------------------------------------------------------

--
-- Table structure for table `installment_requests`
--

CREATE TABLE `installment_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `requested_terms` int(11) NOT NULL,
  `approved_terms` int(11) DEFAULT NULL,
  `alasan` text DEFAULT NULL,
  `status` enum('SUBMITTED','APPROVED','REJECTED','CANCELLED') NOT NULL DEFAULT 'SUBMITTED',
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `installment_requests`
--

INSERT INTO `installment_requests` (`id`, `invoice_id`, `student_id`, `requested_terms`, `approved_terms`, `alasan`, `status`, `reviewed_by`, `reviewed_at`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(2, 2, 3, 12, 12, 'Sedang kesulitan keuangan', 'APPROVED', 9, '2026-02-18 04:16:17', NULL, '2026-02-17 21:04:41', '2026-02-17 21:16:17');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `semester` int(11) NOT NULL,
  `tahun_ajaran` varchar(20) NOT NULL,
  `sks_ambil` int(11) DEFAULT NULL,
  `paket_sks_bayar` int(11) DEFAULT NULL,
  `total_tagihan` bigint(20) UNSIGNED NOT NULL,
  `status` enum('DRAFT','PUBLISHED','IN_INSTALLMENT','LUNAS','CANCELLED') NOT NULL DEFAULT 'DRAFT',
  `allow_partial` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `student_id`, `semester`, `tahun_ajaran`, `sks_ambil`, `paket_sks_bayar`, `total_tagihan`, `status`, `allow_partial`, `notes`, `created_by`, `published_at`, `created_at`, `updated_at`) VALUES
(2, 3, 1, '2025/2026', 24, 18, 50000000, 'IN_INSTALLMENT', 1, NULL, 9, '2026-02-18 04:01:55', '2026-02-17 20:59:18', '2026-02-17 21:16:17');

-- --------------------------------------------------------

--
-- Table structure for table `jadwals`
--

CREATE TABLE `jadwals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruangan` varchar(100) DEFAULT NULL,
  `ruangan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('pending','approved','rejected','active') NOT NULL DEFAULT 'pending',
  `catatan_dosen` text DEFAULT NULL,
  `catatan_admin` text DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwals`
--

INSERT INTO `jadwals` (`id`, `kelas_id`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`, `ruangan_id`, `status`, `catatan_dosen`, `catatan_admin`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Jumat', '09:45:00', '11:15:00', 'R.202', NULL, 'active', NULL, NULL, 2, '2026-02-10 06:41:54', '2026-02-10 06:41:54', '2026-02-10 06:41:54'),
(2, 2, 'Kamis', '19:55:00', '21:25:00', 'LAB.01', NULL, 'active', NULL, NULL, 2, '2026-02-10 06:42:00', '2026-02-10 06:42:00', '2026-02-10 06:42:00'),
(3, 3, 'Senin', '13:00:00', '15:15:00', 'LAB.01', NULL, 'active', NULL, NULL, 2, '2026-02-10 06:42:13', '2026-02-10 06:42:13', '2026-02-10 06:42:13'),
(4, 4, 'Kamis', '19:15:00', '20:40:00', 'R.101', NULL, 'active', NULL, NULL, 2, '2026-02-10 06:42:43', '2026-02-10 06:42:43', '2026-02-10 06:42:43'),
(5, 5, 'Jumat', '16:55:00', '19:15:00', 'R.105', NULL, 'active', NULL, NULL, 2, '2026-02-11 21:04:25', '2026-02-11 21:04:25', '2026-02-11 21:04:25'),
(6, 6, 'Sabtu', '20:40:00', '22:10:00', 'AULA.01', NULL, 'active', NULL, NULL, 3, '2026-02-12 00:15:07', '2026-02-12 00:15:07', '2026-02-12 00:15:07'),
(7, 4, 'Jumat', '18:30:00', '19:55:00', 'R.102', NULL, 'active', NULL, NULL, 4, '2026-02-12 01:17:44', '2026-02-12 01:17:44', '2026-02-12 01:17:44'),
(8, 7, 'Rabu', '13:45:00', '15:15:00', 'R.203', NULL, 'active', NULL, NULL, 4, '2026-02-12 01:17:48', '2026-02-12 01:17:48', '2026-02-12 01:17:48'),
(9, 6, 'Senin', '17:45:00', '19:15:00', 'R.105', NULL, 'active', NULL, NULL, 2, '2026-02-12 01:35:04', '2026-02-12 01:35:04', '2026-02-12 01:35:04'),
(10, 4, 'Rabu', '20:40:00', '22:10:00', 'R.202', NULL, 'active', NULL, NULL, 3, '2026-02-12 01:59:23', '2026-02-12 01:59:23', '2026-02-12 01:59:23'),
(11, 7, 'Selasa', '16:55:00', '18:30:00', 'PRAK.01', NULL, 'active', NULL, NULL, 3, '2026-02-12 01:59:28', '2026-02-12 01:59:28', '2026-02-12 01:59:28'),
(12, 8, 'Kamis', '17:45:00', '19:15:00', 'R.202', NULL, 'active', NULL, NULL, 4, '2026-02-12 01:59:39', '2026-02-12 01:59:39', '2026-02-12 01:59:39'),
(13, 10, 'Selasa', '09:45:00', '10:30:00', 'LAB.01', NULL, 'active', NULL, NULL, NULL, NULL, '2026-02-12 19:38:48', '2026-02-12 19:38:48'),
(14, 11, 'Sabtu', '15:30:00', '16:55:00', 'AULA.01', NULL, 'active', NULL, NULL, 2, '2026-02-13 01:05:59', '2026-02-13 01:05:59', '2026-02-13 01:05:59'),
(15, 12, 'Senin', '09:45:00', '11:15:00', 'LAB.02', NULL, 'active', NULL, NULL, 2, '2026-02-13 01:06:02', '2026-02-13 01:06:02', '2026-02-13 01:06:02'),
(16, 13, 'Selasa', '16:55:00', '19:15:00', 'AULA.01', NULL, 'active', NULL, NULL, 2, '2026-02-13 01:06:16', '2026-02-13 01:06:16', '2026-02-13 01:06:16'),
(17, 14, 'Sabtu', '13:45:00', '15:15:00', 'AULA.01', NULL, 'active', NULL, NULL, 2, '2026-02-13 01:06:18', '2026-02-13 01:06:18', '2026-02-13 01:06:18'),
(18, 15, 'Selasa', '16:55:00', '19:15:00', 'LAB.02', NULL, 'active', NULL, NULL, 2, '2026-02-13 01:06:21', '2026-02-13 01:06:21', '2026-02-13 01:06:21'),
(19, 16, 'Rabu', '18:30:00', '19:55:00', 'LAB.01', NULL, 'active', NULL, NULL, 2, '2026-02-13 01:06:24', '2026-02-13 01:06:24', '2026-02-13 01:06:24');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_approvals`
--

CREATE TABLE `jadwal_approvals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jadwal_proposal_id` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED NOT NULL,
  `role` enum('dosen','admin') NOT NULL,
  `action` enum('approve','reject') NOT NULL,
  `alasan_penolakan` text DEFAULT NULL,
  `hari_pengganti` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') DEFAULT NULL,
  `jam_mulai_pengganti` time DEFAULT NULL,
  `jam_selesai_pengganti` time DEFAULT NULL,
  `ruangan_pengganti` varchar(100) DEFAULT NULL,
  `approved_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_approvals`
--

INSERT INTO `jadwal_approvals` (`id`, `jadwal_proposal_id`, `approved_by`, `role`, `action`, `alasan_penolakan`, `hari_pengganti`, `jam_mulai_pengganti`, `jam_selesai_pengganti`, `ruangan_pengganti`, `approved_at`, `created_at`, `updated_at`) VALUES
(7, 8, 4, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-02-12 01:17:44', '2026-02-12 01:17:44', '2026-02-12 01:17:44'),
(8, 9, 4, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-02-12 01:17:48', '2026-02-12 01:17:48', '2026-02-12 01:17:48'),
(9, 7, 2, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-02-12 01:35:04', '2026-02-12 01:35:04', '2026-02-12 01:35:04'),
(10, 10, 3, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-02-12 01:59:23', '2026-02-12 01:59:23', '2026-02-12 01:59:23'),
(11, 11, 3, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-02-12 01:59:28', '2026-02-12 01:59:28', '2026-02-12 01:59:28'),
(12, 12, 4, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-02-12 01:59:39', '2026-02-12 01:59:39', '2026-02-12 01:59:39'),
(13, 13, 2, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-02-13 01:05:59', '2026-02-13 01:05:59', '2026-02-13 01:05:59'),
(14, 14, 2, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-02-13 01:06:02', '2026-02-13 01:06:02', '2026-02-13 01:06:02'),
(15, 15, 2, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-02-13 01:06:16', '2026-02-13 01:06:16', '2026-02-13 01:06:16'),
(16, 16, 2, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-02-13 01:06:18', '2026-02-13 01:06:18', '2026-02-13 01:06:18'),
(17, 17, 2, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-02-13 01:06:21', '2026-02-13 01:06:21', '2026-02-13 01:06:21'),
(18, 18, 2, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-02-13 01:06:24', '2026-02-13 01:06:24', '2026-02-13 01:06:24');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_exceptions`
--

CREATE TABLE `jadwal_exceptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jadwal_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `hari` varchar(255) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruangan` varchar(255) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_generate_logs`
--

CREATE TABLE `jadwal_generate_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total_generated` int(11) NOT NULL DEFAULT 0,
  `total_failed` int(11) NOT NULL DEFAULT 0,
  `failed_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`failed_items`)),
  `status` varchar(255) NOT NULL DEFAULT 'completed',
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_generate_logs`
--

INSERT INTO `jadwal_generate_logs` (`id`, `user_id`, `total_generated`, `total_failed`, `failed_items`, `status`, `error_message`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 0, NULL, 'completed', NULL, '2026-02-10 06:35:58', '2026-02-10 06:35:58'),
(2, 1, 1, 4, '[\"Bahasa Indonesia Hukum - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Ilmu Agama - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Pancasila & Kewarganegaraan - A (3 SKS - slot berturut-turut tidak tersedia)\",\"Ekonomi Pembangunan - A (2 SKS - slot berturut-turut tidak tersedia)\"]', 'partial', NULL, '2026-02-11 21:04:10', '2026-02-11 21:04:10'),
(3, 1, 1, 5, '[\"Bahasa Indonesia Hukum - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Ilmu Agama - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Pancasila & Kewarganegaraan - A (3 SKS - slot berturut-turut tidak tersedia)\",\"Ekonomi Pembangunan - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Pengantar Hukum Indonesia - A (3 SKS - slot berturut-turut tidak tersedia)\"]', 'partial', NULL, '2026-02-12 00:10:00', '2026-02-12 00:10:00'),
(4, 1, 3, 6, '[\"Bahasa Indonesia Hukum - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Ilmu Agama - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Pancasila & Kewarganegaraan - A (3 SKS - slot berturut-turut tidak tersedia)\",\"Ekonomi Pembangunan - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Pengantar Hukum Indonesia - A (3 SKS - slot berturut-turut tidak tersedia)\",\"Filsafat Hukum - A (2 SKS - slot berturut-turut tidak tersedia)\"]', 'partial', NULL, '2026-02-12 01:17:26', '2026-02-12 01:17:26'),
(5, 1, 2, 8, '[\"Bahasa Indonesia Hukum - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Ilmu Agama - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Pancasila & Kewarganegaraan - A (3 SKS - slot berturut-turut tidak tersedia)\",\"Ekonomi Pembangunan - ADH30001-A (2 SKS - slot berturut-turut tidak tersedia)\",\"Pengantar Hukum Indonesia - A (3 SKS - slot berturut-turut tidak tersedia)\",\"Filsafat Hukum - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Ekonomi Pembangunan - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Etika, Tanggung Jawab & Profesi Hukum - A (2 SKS - slot berturut-turut tidak tersedia)\"]', 'partial', NULL, '2026-02-12 01:58:15', '2026-02-12 01:58:15'),
(6, 1, 0, 11, '[\"Bahasa Indonesia Hukum - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Ilmu Agama - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Pancasila & Kewarganegaraan - A (3 SKS - slot berturut-turut tidak tersedia)\",\"Ekonomi Pembangunan - ADH30001-A (2 SKS - slot berturut-turut tidak tersedia)\",\"Pengantar Hukum Indonesia - A (3 SKS - slot berturut-turut tidak tersedia)\",\"Filsafat Hukum - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Ekonomi Pembangunan - ADH30001-A (2 SKS - slot berturut-turut tidak tersedia)\",\"Etika, Tanggung Jawab & Profesi Hukum - ADH20037-A (2 SKS - slot berturut-turut tidak tersedia)\",\"Filsafat Hukum - ADH20038-A (2 SKS - slot berturut-turut tidak tersedia)\",\"Ekonomi Pembangunan - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Etika, Tanggung Jawab & Profesi Hukum - A (2 SKS - slot berturut-turut tidak tersedia)\"]', 'partial', NULL, '2026-02-12 01:58:40', '2026-02-12 01:58:40'),
(7, 1, 1, 10, '[\"Bahasa Indonesia Hukum - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Ilmu Agama - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Pancasila & Kewarganegaraan - A (3 SKS - slot berturut-turut tidak tersedia)\",\"Ekonomi Pembangunan - ADH30001-A (2 SKS - slot berturut-turut tidak tersedia)\",\"Pengantar Hukum Indonesia - A (3 SKS - slot berturut-turut tidak tersedia)\",\"Filsafat Hukum - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Ekonomi Pembangunan - ADH30001-A (2 SKS - slot berturut-turut tidak tersedia)\",\"Etika, Tanggung Jawab & Profesi Hukum - ADH20037-A (2 SKS - slot berturut-turut tidak tersedia)\",\"Ekonomi Pembangunan - A (2 SKS - slot berturut-turut tidak tersedia)\",\"Etika, Tanggung Jawab & Profesi Hukum - A (2 SKS - slot berturut-turut tidak tersedia)\"]', 'partial', NULL, '2026-02-12 01:59:10', '2026-02-12 01:59:10'),
(8, 1, 11, 0, NULL, 'completed', NULL, '2026-02-13 01:05:52', '2026-02-13 01:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_proposals`
--

CREATE TABLE `jadwal_proposals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED NOT NULL,
  `dosen_id` bigint(20) UNSIGNED NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruangan` varchar(100) DEFAULT NULL,
  `ruangan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('pending_dosen','approved_dosen','rejected_dosen','pending_admin','approved_admin','rejected_admin') NOT NULL DEFAULT 'pending_dosen',
  `catatan_generate` text DEFAULT NULL COMMENT 'Catatan dari sistem auto generate',
  `generated_by` bigint(20) UNSIGNED NOT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_proposals`
--

INSERT INTO `jadwal_proposals` (`id`, `mata_kuliah_id`, `kelas_id`, `dosen_id`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`, `ruangan_id`, `status`, `catatan_generate`, `generated_by`, `generated_at`, `created_at`, `updated_at`) VALUES
(7, 50, 6, 1, 'Senin', '17:45:00', '19:15:00', 'R.105', NULL, 'approved_admin', 'Auto generated oleh sistem (random)', 1, '2026-02-12 01:17:26', '2026-02-12 01:17:26', '2026-02-12 01:35:04'),
(8, 4, 4, 3, 'Jumat', '18:30:00', '19:55:00', 'R.102', NULL, 'approved_admin', 'Auto generated oleh sistem (random)', 1, '2026-02-12 01:17:26', '2026-02-12 01:17:26', '2026-02-12 01:17:44'),
(9, 49, 7, 3, 'Rabu', '13:45:00', '15:15:00', 'R.203', NULL, 'approved_admin', 'Auto generated oleh sistem (random)', 1, '2026-02-12 01:17:26', '2026-02-12 01:17:26', '2026-02-12 01:17:48'),
(10, 4, 4, 2, 'Rabu', '20:40:00', '22:10:00', 'R.202', NULL, 'approved_admin', 'Auto generated oleh sistem (random)', 1, '2026-02-12 01:58:15', '2026-02-12 01:58:15', '2026-02-12 01:59:23'),
(11, 49, 7, 2, 'Selasa', '16:55:00', '18:30:00', 'PRAK.01', NULL, 'approved_admin', 'Auto generated oleh sistem (random)', 1, '2026-02-12 01:58:15', '2026-02-12 01:58:15', '2026-02-12 01:59:28'),
(12, 61, 8, 3, 'Kamis', '17:45:00', '19:15:00', 'R.202', NULL, 'approved_admin', 'Auto generated oleh sistem (random)', 1, '2026-02-12 01:59:10', '2026-02-12 01:59:10', '2026-02-12 01:59:39'),
(13, 2, 11, 1, 'Sabtu', '15:30:00', '16:55:00', 'AULA.01', NULL, 'approved_admin', 'Auto generated oleh sistem (random)', 1, '2026-02-13 01:05:52', '2026-02-13 01:05:52', '2026-02-13 01:05:59'),
(14, 1, 12, 1, 'Senin', '09:45:00', '11:15:00', 'LAB.02', NULL, 'approved_admin', 'Auto generated oleh sistem (random)', 1, '2026-02-13 01:05:52', '2026-02-13 01:05:52', '2026-02-13 01:06:02'),
(15, 3, 13, 1, 'Selasa', '16:55:00', '19:15:00', 'AULA.01', NULL, 'approved_admin', 'Auto generated oleh sistem (random)', 1, '2026-02-13 01:05:52', '2026-02-13 01:05:52', '2026-02-13 01:06:16'),
(16, 4, 14, 1, 'Sabtu', '13:45:00', '15:15:00', 'AULA.01', NULL, 'approved_admin', 'Auto generated oleh sistem (random)', 1, '2026-02-13 01:05:52', '2026-02-13 01:05:52', '2026-02-13 01:06:18'),
(17, 7, 15, 1, 'Selasa', '16:55:00', '19:15:00', 'LAB.02', NULL, 'approved_admin', 'Auto generated oleh sistem (random)', 1, '2026-02-13 01:05:52', '2026-02-13 01:05:52', '2026-02-13 01:06:21'),
(18, 50, 16, 1, 'Rabu', '18:30:00', '19:55:00', 'LAB.01', NULL, 'approved_admin', 'Auto generated oleh sistem (random)', 1, '2026-02-13 01:05:52', '2026-02-13 01:05:52', '2026-02-13 01:06:24'),
(19, 4, 14, 2, 'Selasa', '19:55:00', '21:25:00', 'PRAK.02', NULL, 'pending_dosen', 'Auto generated oleh sistem (random)', 1, '2026-02-13 01:05:52', '2026-02-13 01:05:52', '2026-02-13 01:05:52'),
(20, 49, 17, 2, 'Rabu', '17:45:00', '19:15:00', 'R.104', NULL, 'pending_dosen', 'Auto generated oleh sistem (random)', 1, '2026-02-13 01:05:52', '2026-02-13 01:05:52', '2026-02-13 01:05:52'),
(21, 4, 14, 3, 'Senin', '09:00:00', '10:30:00', 'R.201', NULL, 'pending_dosen', 'Auto generated oleh sistem (random)', 1, '2026-02-13 01:05:52', '2026-02-13 01:05:52', '2026-02-13 01:05:52'),
(22, 49, 17, 3, 'Kamis', '10:30:00', '12:00:00', 'PRAK.02', NULL, 'pending_dosen', 'Auto generated oleh sistem (random)', 1, '2026-02-13 01:05:52', '2026-02-13 01:05:52', '2026-02-13 01:05:52'),
(23, 61, 18, 3, 'Jumat', '13:00:00', '14:30:00', 'LAB.01', NULL, 'pending_dosen', 'Auto generated oleh sistem (random)', 1, '2026-02-13 01:05:52', '2026-02-13 01:05:52', '2026-02-13 01:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_reschedules`
--

CREATE TABLE `jadwal_reschedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jadwal_id` bigint(20) UNSIGNED NOT NULL,
  `dosen_id` bigint(20) UNSIGNED NOT NULL,
  `old_hari` varchar(255) NOT NULL,
  `old_jam_mulai` time DEFAULT NULL,
  `old_jam_selesai` time DEFAULT NULL,
  `new_hari` varchar(255) NOT NULL,
  `new_jam_mulai` time NOT NULL,
  `new_jam_selesai` time NOT NULL,
  `catatan` text DEFAULT NULL,
  `apply_date` date DEFAULT NULL,
  `one_week_only` tinyint(1) NOT NULL DEFAULT 1,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jam_perkuliahan`
--

CREATE TABLE `jam_perkuliahan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jam_ke` int(11) NOT NULL COMMENT 'Jam ke berapa (1-14)',
  `jam_mulai` time NOT NULL COMMENT 'Waktu mulai',
  `jam_selesai` time NOT NULL COMMENT 'Waktu selesai',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Status aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jam_perkuliahan`
--

INSERT INTO `jam_perkuliahan` (`id`, `jam_ke`, `jam_mulai`, `jam_selesai`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 1, '09:00:00', '09:45:00', 1, NULL, NULL),
(3, 2, '09:45:00', '10:30:00', 1, NULL, NULL),
(4, 3, '10:30:00', '11:15:00', 1, NULL, NULL),
(5, 4, '11:15:00', '12:00:00', 1, NULL, NULL),
(6, 5, '13:00:00', '13:45:00', 1, NULL, NULL),
(7, 6, '13:45:00', '14:30:00', 1, NULL, NULL),
(8, 7, '14:30:00', '15:15:00', 1, NULL, NULL),
(9, 8, '15:30:00', '16:15:00', 1, NULL, NULL),
(10, 9, '16:15:00', '17:00:00', 1, NULL, '2026-02-18 18:52:04'),
(18, 10, '17:00:00', '17:45:00', 1, '2026-02-18 18:52:38', '2026-02-18 18:52:38'),
(19, 11, '17:45:00', '18:30:00', 1, '2026-02-18 18:52:41', '2026-02-18 18:52:41'),
(20, 12, '18:30:00', '19:15:00', 1, '2026-02-18 18:52:44', '2026-02-18 18:52:44'),
(21, 13, '19:15:00', '20:00:00', 1, '2026-02-18 18:52:47', '2026-02-18 18:52:47'),
(22, 14, '20:00:00', '20:45:00', 1, '2026-02-18 18:52:49', '2026-02-18 18:52:49'),
(23, 15, '20:45:00', '21:30:00', 1, '2026-02-18 18:52:52', '2026-02-18 18:52:52'),
(24, 16, '21:30:00', '22:15:00', 1, '2026-02-18 18:52:55', '2026-02-18 18:52:55'),
(25, 17, '22:15:00', '23:00:00', 1, '2026-02-18 18:53:00', '2026-02-18 18:53:00');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `dosen_id` bigint(20) UNSIGNED NOT NULL,
  `section` varchar(10) NOT NULL,
  `kapasitas` int(11) NOT NULL DEFAULT 40,
  `tahun_ajaran` varchar(20) NOT NULL,
  `semester_type` enum('Ganjil','Genap') NOT NULL DEFAULT 'Ganjil',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `mata_kuliah_id`, `dosen_id`, `section`, `kapasitas`, `tahun_ajaran`, `semester_type`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'A', 40, '2025/2026', 'Ganjil', '2026-02-10 06:35:58', '2026-02-18 00:30:27'),
(2, 1, 1, 'A', 40, '2025/2026', 'Ganjil', '2026-02-10 06:35:58', '2026-02-18 00:30:27'),
(3, 3, 1, 'A', 40, '2025/2026', 'Ganjil', '2026-02-10 06:35:58', '2026-02-18 00:30:27'),
(4, 4, 1, 'A', 40, '2025/2026', 'Ganjil', '2026-02-10 06:35:58', '2026-02-18 00:30:27'),
(5, 7, 1, 'A', 40, '2025/2026', 'Ganjil', '2026-02-11 21:04:10', '2026-02-18 00:30:27'),
(6, 50, 2, 'A', 40, '2026/2027', 'Ganjil', '2026-02-12 00:10:00', '2026-02-12 00:10:00'),
(7, 49, 3, 'A', 40, '2026/2027', 'Ganjil', '2026-02-12 01:17:26', '2026-02-12 01:17:26'),
(8, 61, 3, 'A', 40, '2026/2027', 'Ganjil', '2026-02-12 01:59:10', '2026-02-12 01:59:10'),
(10, 2, 2, 'Hukum', 30, '2026/2027', 'Ganjil', '2026-02-12 19:38:48', '2026-02-12 19:38:48'),
(11, 2, 1, 'A', 40, '2025/2026', 'Ganjil', '2026-02-13 01:05:52', '2026-02-13 01:05:52'),
(12, 1, 1, 'A', 40, '2025/2026', 'Ganjil', '2026-02-13 01:05:52', '2026-02-13 01:05:52'),
(13, 3, 1, 'A', 40, '2025/2026', 'Ganjil', '2026-02-13 01:05:52', '2026-02-13 01:05:52'),
(14, 4, 1, 'A', 40, '2025/2026', 'Ganjil', '2026-02-13 01:05:52', '2026-02-13 01:05:52'),
(15, 7, 1, 'A', 40, '2025/2026', 'Ganjil', '2026-02-13 01:05:52', '2026-02-13 01:05:52'),
(16, 50, 1, 'A', 40, '2025/2026', 'Ganjil', '2026-02-13 01:05:52', '2026-02-13 01:05:52'),
(17, 49, 2, 'A', 40, '2025/2026', 'Ganjil', '2026-02-13 01:05:52', '2026-02-13 01:05:52'),
(18, 61, 3, 'A', 40, '2025/2026', 'Ganjil', '2026-02-13 01:05:52', '2026-02-13 01:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `kelas_mata_kuliahs`
--

CREATE TABLE `kelas_mata_kuliahs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `dosen_id` bigint(20) UNSIGNED NOT NULL,
  `semester_id` bigint(20) UNSIGNED NOT NULL,
  `kode_kelas` varchar(255) NOT NULL,
  `kapasitas` int(11) NOT NULL,
  `ruang` varchar(255) NOT NULL,
  `ruangan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hari` varchar(255) DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `metode_pengajaran` enum('offline','online','asynchronous') DEFAULT NULL,
  `online_link` varchar(255) DEFAULT NULL,
  `asynchronous_tugas` text DEFAULT NULL,
  `asynchronous_file` varchar(255) DEFAULT NULL,
  `qr_token` varchar(255) DEFAULT NULL,
  `qr_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `qr_current_pertemuan` int(11) DEFAULT NULL,
  `qr_expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas_mata_kuliahs`
--

INSERT INTO `kelas_mata_kuliahs` (`id`, `mata_kuliah_id`, `dosen_id`, `semester_id`, `kode_kelas`, `kapasitas`, `ruang`, `ruangan_id`, `hari`, `jam_mulai`, `jam_selesai`, `metode_pengajaran`, `online_link`, `asynchronous_tugas`, `asynchronous_file`, `qr_token`, `qr_enabled`, `qr_current_pertemuan`, `qr_expires_at`, `created_at`, `updated_at`) VALUES
(11, 2, 1, 4, 'A', 40, 'AULA.01', NULL, 'Sabtu', '15:30:00', '16:55:00', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-02-13 01:05:59', '2026-02-13 01:05:59'),
(12, 1, 1, 4, 'A', 40, 'LAB.02', NULL, 'Senin', '09:45:00', '11:15:00', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-02-13 01:06:02', '2026-02-13 01:06:02'),
(13, 3, 1, 4, 'A', 40, 'AULA.01', NULL, 'Selasa', '16:55:00', '19:15:00', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-02-13 01:06:16', '2026-02-13 01:06:16'),
(14, 4, 1, 4, 'A', 40, 'AULA.01', NULL, 'Sabtu', '13:45:00', '15:15:00', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-02-13 01:06:18', '2026-02-13 01:06:18'),
(15, 7, 1, 4, 'A', 40, 'LAB.02', NULL, 'Selasa', '16:55:00', '19:15:00', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-02-13 01:06:21', '2026-02-13 01:06:21'),
(16, 50, 1, 4, 'A', 40, 'LAB.01', NULL, 'Rabu', '18:30:00', '19:55:00', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-02-13 01:06:24', '2026-02-13 01:06:24'),
(17, 11, 4, 5, 'A', 35, 'R.102', 3, 'Selasa', '13:45:00', '14:30:00', NULL, NULL, NULL, NULL, 'c40UYgVKKBaVxxyXt9jPdY7I04tPOnt6wxuQTanG', 0, NULL, NULL, '2026-02-18 22:43:01', '2026-02-18 22:43:01'),
(18, 11, 4, 5, 'A', 25, 'LAB.02', 11, 'Senin', '20:00:00', '20:45:00', NULL, NULL, NULL, NULL, 'yG6wFjsxRlOFNltYmenOR0ROGlU46Z6xky6bdZL5', 0, NULL, NULL, '2026-02-18 22:43:43', '2026-02-18 22:43:43');

-- --------------------------------------------------------

--
-- Table structure for table `kelas_reschedules`
--

CREATE TABLE `kelas_reschedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kelas_mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `dosen_id` bigint(20) UNSIGNED NOT NULL,
  `old_hari` varchar(255) NOT NULL,
  `old_jam_mulai` time DEFAULT NULL,
  `old_jam_selesai` time DEFAULT NULL,
  `new_hari` varchar(255) NOT NULL,
  `new_jam_mulai` time NOT NULL,
  `new_jam_selesai` time NOT NULL,
  `new_ruang` varchar(255) DEFAULT NULL,
  `new_kelas` varchar(50) DEFAULT NULL,
  `metode_pengajaran` varchar(255) DEFAULT NULL,
  `online_link` varchar(255) DEFAULT NULL,
  `asynchronous_tugas` text DEFAULT NULL,
  `asynchronous_file` varchar(255) DEFAULT NULL,
  `week_start` date NOT NULL,
  `week_end` date NOT NULL,
  `status` enum('pending','approved','room_assigned','rejected') NOT NULL DEFAULT 'pending',
  `catatan_dosen` text DEFAULT NULL,
  `catatan_admin` text DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas_reschedules`
--

INSERT INTO `kelas_reschedules` (`id`, `kelas_mata_kuliah_id`, `dosen_id`, `old_hari`, `old_jam_mulai`, `old_jam_selesai`, `new_hari`, `new_jam_mulai`, `new_jam_selesai`, `new_ruang`, `new_kelas`, `metode_pengajaran`, `online_link`, `asynchronous_tugas`, `asynchronous_file`, `week_start`, `week_end`, `status`, `catatan_dosen`, `catatan_admin`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(14, 16, 1, 'Rabu', '18:30:00', '19:55:00', 'Kamis', '09:00:00', '10:30:00', 'LAB.02', NULL, 'offline', NULL, NULL, NULL, '2026-03-09', '2026-03-14', 'approved', 'Reschedule langsung oleh dosen', NULL, 2, '2026-02-17 21:32:06', '2026-02-17 21:32:06', '2026-02-17 21:32:06');

-- --------------------------------------------------------

--
-- Table structure for table `krs`
--

CREATE TABLE `krs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kelas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('draft','diajukan','approved','rejected') NOT NULL DEFAULT 'draft',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ambil_mk` enum('ya','tidak') NOT NULL DEFAULT 'ya',
  `kelas_mata_kuliah_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `krs`
--

INSERT INTO `krs` (`id`, `mahasiswa_id`, `mata_kuliah_id`, `kelas_id`, `status`, `keterangan`, `created_at`, `updated_at`, `ambil_mk`, `kelas_mata_kuliah_id`) VALUES
(41, 3, 2, 1, 'approved', NULL, '2026-02-18 00:23:05', '2026-02-18 00:23:05', 'ya', 11),
(42, 3, 3, 3, 'approved', NULL, '2026-02-18 00:23:05', '2026-02-18 00:23:05', 'ya', 13),
(43, 3, 1, 2, 'approved', NULL, '2026-02-18 00:23:05', '2026-02-18 00:23:05', 'ya', 12),
(44, 3, 5, NULL, 'approved', NULL, '2026-02-18 00:23:05', '2026-02-18 00:23:05', 'ya', NULL),
(45, 3, 6, NULL, 'approved', NULL, '2026-02-18 00:23:05', '2026-02-18 00:23:05', 'ya', NULL),
(46, 3, 7, 5, 'approved', NULL, '2026-02-18 00:23:05', '2026-02-18 00:23:05', 'ya', 15),
(47, 3, 8, NULL, 'approved', NULL, '2026-02-18 00:23:05', '2026-02-18 00:23:05', 'ya', NULL),
(48, 3, 4, 4, 'approved', NULL, '2026-02-18 00:23:05', '2026-02-18 00:23:05', 'ya', 14);

-- --------------------------------------------------------

--
-- Table structure for table `kuesioner_aktivasi`
--

CREATE TABLE `kuesioner_aktivasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `semester_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fasilitas_kampus` int(11) NOT NULL COMMENT '1-5',
  `sistem_akademik` int(11) NOT NULL COMMENT '1-5',
  `kualitas_dosen` int(11) NOT NULL COMMENT '1-5',
  `layanan_administrasi` int(11) NOT NULL COMMENT '1-5',
  `kepuasan_keseluruhan` int(11) NOT NULL COMMENT '1-5',
  `saran` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kuesioner_mahasiswa_baru`
--

CREATE TABLE `kuesioner_mahasiswa_baru` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `prodi` varchar(255) DEFAULT NULL,
  `jenis_kelamin` varchar(255) DEFAULT NULL,
  `angkatan` smallint(6) DEFAULT NULL,
  `q1` tinyint(4) DEFAULT NULL,
  `q2` tinyint(4) DEFAULT NULL,
  `q3` tinyint(4) DEFAULT NULL,
  `q4` tinyint(4) DEFAULT NULL,
  `q5` tinyint(4) DEFAULT NULL,
  `q6` tinyint(4) DEFAULT NULL,
  `q7` tinyint(4) DEFAULT NULL,
  `saran` text DEFAULT NULL,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`answers`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kuesioner_mahasiswa_baru`
--

INSERT INTO `kuesioner_mahasiswa_baru` (`id`, `mahasiswa_id`, `email`, `prodi`, `jenis_kelamin`, `angkatan`, `q1`, `q2`, `q3`, `q4`, `q5`, `q6`, `q7`, `saran`, `answers`, `created_at`, `updated_at`) VALUES
(3, 3, 'rizki.firmansyah@student.stih.ac.id', 'Ilmu Hukum', NULL, 2026, 4, 4, 4, 4, 4, 4, 4, 'Cukup', NULL, '2026-02-17 19:46:52', '2026-02-17 19:46:52');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswas`
--

CREATE TABLE `mahasiswas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nim` varchar(255) NOT NULL,
  `prodi` varchar(255) NOT NULL,
  `angkatan` varchar(255) NOT NULL,
  `semester` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `last_semester_id` bigint(20) UNSIGNED DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `rt` varchar(255) DEFAULT NULL,
  `rw` varchar(255) DEFAULT NULL,
  `kota` varchar(255) DEFAULT NULL,
  `kecamatan` varchar(255) DEFAULT NULL,
  `desa` varchar(255) DEFAULT NULL,
  `alamat_ktp` text DEFAULT NULL,
  `rt_ktp` varchar(255) DEFAULT NULL,
  `rw_ktp` varchar(255) DEFAULT NULL,
  `provinsi_ktp` varchar(255) DEFAULT NULL,
  `kota_ktp` varchar(255) DEFAULT NULL,
  `kecamatan_ktp` varchar(255) DEFAULT NULL,
  `desa_ktp` varchar(255) DEFAULT NULL,
  `provinsi` varchar(255) DEFAULT NULL,
  `kabupaten` varchar(255) DEFAULT NULL,
  `jenis_sekolah` varchar(255) DEFAULT NULL,
  `jurusan_sekolah` varchar(255) DEFAULT NULL,
  `tahun_lulus` varchar(255) DEFAULT NULL,
  `nilai_kelulusan` decimal(5,2) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') DEFAULT NULL,
  `agama` varchar(255) DEFAULT NULL,
  `status_sipil` enum('Belum Menikah','Menikah','Cerai') DEFAULT NULL,
  `status` enum('aktif','cuti','lulus','do') NOT NULL DEFAULT 'aktif',
  `status_akun` enum('baru','aktif','tidak_aktif') NOT NULL DEFAULT 'baru',
  `new_survey_completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `file_ijazah` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`file_ijazah`)),
  `file_transkrip` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`file_transkrip`)),
  `file_kk` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`file_kk`)),
  `file_ktp` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`file_ktp`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mahasiswas`
--

INSERT INTO `mahasiswas` (`id`, `user_id`, `nim`, `prodi`, `angkatan`, `semester`, `last_semester_id`, `phone`, `no_hp`, `address`, `alamat`, `rt`, `rw`, `kota`, `kecamatan`, `desa`, `alamat_ktp`, `rt_ktp`, `rw_ktp`, `provinsi_ktp`, `kota_ktp`, `kecamatan_ktp`, `desa_ktp`, `provinsi`, `kabupaten`, `jenis_sekolah`, `jurusan_sekolah`, `tahun_lulus`, `nilai_kelulusan`, `foto`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `status_sipil`, `status`, `status_akun`, `new_survey_completed`, `created_at`, `updated_at`, `file_ijazah`, `file_transkrip`, `file_kk`, `file_ktp`) VALUES
(3, 7, '2024010003', 'Ilmu Hukum', '2026', 1, NULL, '081234567896', '1231231231231', 'Jl. Mahasiswa No. 7', 'test', '12', '01', 'KAB. BADUNG', 'ABIANSEMAL', 'ABIANSEMAL DAUH YEH CANI', 'test', '01', '01', 'ACEH', 'KAB. ACEH BARAT', 'ARONGAN LAMBALEK', 'ALUE SUNDAK', 'BALI', NULL, '2 - Kejuruan', 'SMU - IPS', '2022', 90.00, 'mahasiswa/foto/5CQ7PMs67APGbyHHMBnUZ27MyRucLsQUlLsCCwBw.jpg', 'Jakarta', '2004-02-18', 'Laki-Laki', 'Protestan', 'Menikah', 'aktif', 'baru', 1, '2026-02-10 04:54:58', '2026-02-17 21:23:20', '[\"mahasiswa\\/dokumen\\/2024010003\\/i2fjKYthSRSMKQ8e0N6cJSMhTIaaxrjAssfxJtSw.pdf\"]', '[\"mahasiswa\\/dokumen\\/2024010003\\/ntSDXLK6nMSvMOjUILGkbvphRaL1kER0zY8c7PeC.pdf\"]', '[\"mahasiswa\\/dokumen\\/2024010003\\/VvB1yPTI7OXybEH7nCyFuApHEKocUI4QBWps7GZt.png\"]', '[\"mahasiswa\\/dokumen\\/2024010003\\/r8eyAMyZ999CbUuz4AzzhFrdfMxlvLd1ZYUNvfuD.png\"]'),
(4, 11, '2024050007', 'Ilmu Hukum 2', '2026', 1, NULL, '08398393893', NULL, 'test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Laki-Laki', NULL, NULL, 'aktif', 'baru', 0, '2026-02-18 02:08:51', '2026-02-18 02:08:51', NULL, NULL, NULL, NULL),
(5, 12, '2024050009', 'Ilmu Hukum', '2026', 1, NULL, '0898438948943', NULL, 'test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Laki-Laki', NULL, NULL, 'aktif', 'baru', 0, '2026-02-18 02:53:49', '2026-02-18 02:53:49', NULL, NULL, NULL, NULL),
(6, 13, '2024050008', 'Ilmu Hukum 2', '2026', 1, NULL, '08895899949', NULL, 'test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Laki-Laki', NULL, NULL, 'aktif', 'baru', 0, '2026-02-18 02:54:28', '2026-02-18 02:54:28', NULL, NULL, NULL, NULL),
(7, 14, '202406000687', 'Ilmu Hukum', '2026', 1, NULL, '0848849894848', NULL, 'test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Laki-Laki', NULL, NULL, 'aktif', 'baru', 0, '2026-02-18 19:37:29', '2026-02-18 19:37:29', NULL, NULL, NULL, NULL),
(8, 15, '20240500032', 'Ilmu Hukum', '2026', 1, NULL, '0894948948494', NULL, 'test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Laki-Laki', NULL, NULL, 'aktif', 'baru', 0, '2026-02-18 19:38:00', '2026-02-18 19:38:00', NULL, NULL, NULL, NULL),
(9, 16, '20240500031', 'Ilmu Hukum', '2026', 1, NULL, '088437843843', NULL, 'test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Laki-Laki', NULL, NULL, 'aktif', 'baru', 0, '2026-02-18 19:38:30', '2026-02-18 19:38:30', NULL, NULL, NULL, NULL),
(10, 17, '20240500063', 'Ilmu Hukum', '2026', 1, NULL, '083298392329', NULL, 'test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Laki-Laki', NULL, NULL, 'aktif', 'baru', 0, '2026-02-18 19:39:00', '2026-02-18 19:39:00', NULL, NULL, NULL, NULL),
(11, 18, '20240500064', 'Ilmu Hukum', '2026', 1, NULL, '0893293329832', NULL, 'test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Laki-Laki', NULL, NULL, 'aktif', 'baru', 0, '2026-02-18 19:39:31', '2026-02-18 19:39:31', NULL, NULL, NULL, NULL),
(12, 19, '20240500059', 'Ilmu Hukum', '2026', 1, NULL, '089389328329', NULL, 'test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Laki-Laki', NULL, NULL, 'aktif', 'baru', 0, '2026-02-18 19:39:57', '2026-02-18 19:39:57', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mata_kuliahs`
--

CREATE TABLE `mata_kuliahs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_mk` varchar(20) NOT NULL,
  `kode_id` varchar(50) DEFAULT NULL COMMENT 'master kode like sms1, sms2',
  `nama_mk` varchar(255) NOT NULL,
  `praktikum` tinyint(4) DEFAULT NULL COMMENT 'jumlah sks praktikum',
  `sks` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `jenis` enum('wajib_nasional','wajib_prodi','pilihan','peminatan') NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `prodi_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fakultas_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mata_kuliahs`
--

INSERT INTO `mata_kuliahs` (`id`, `kode_mk`, `kode_id`, `nama_mk`, `praktikum`, `sks`, `semester`, `jenis`, `deskripsi`, `created_at`, `updated_at`, `prodi_id`, `fakultas_id`) VALUES
(1, 'ADH10010', 'sms1', 'Ilmu Agama', 0, 2, 1, 'wajib_nasional', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(2, 'ADH10006', 'sms1', 'Bahasa Indonesia Hukum', 0, 2, 1, 'wajib_nasional', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(3, 'ADH10007', 'sms1', 'Pancasila & Kewarganegaraan', 0, 3, 1, 'wajib_nasional', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(4, 'ADH30001', 'sms1', 'Ekonomi Pembangunan', 0, 2, 1, 'pilihan', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(5, 'ADH20001', 'sms1', 'Ilmu Negara', 0, 3, 1, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(6, 'ADH20002', 'sms1', 'Pengantar Ilmu Hukum', 0, 3, 1, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(7, 'ADH20003', 'sms1', 'Pengantar Hukum Indonesia', 0, 3, 1, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(8, 'ADH20004', 'sms1', 'Hukum & Hak Asasi Manusia', 0, 2, 1, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(9, 'ADH20050', 'sms2', 'Hukum Perdata', 0, 3, 2, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(10, 'ADH20006', 'sms2', 'Hukum Pidana', 0, 3, 2, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(11, 'ADH20009', 'sms2', 'Hukum Adat', 0, 4, 2, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(12, 'ADH20010', 'sms2', 'Hukum Islam', 0, 2, 2, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(13, 'ADH20012', 'sms2', 'Ilmu Perundang-undangan', 1, 2, 2, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(14, 'ADH20014', 'sms2', 'Hukum Internasional', 0, 3, 2, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(15, 'ADH20007', 'sms2', 'Hukum Tata Negara', 0, 3, 2, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(16, 'ADH20005', 'sms3', 'Hukum Benda & Orang', 0, 2, 3, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(17, 'ADH20015', 'sms3', 'Hukum Dagang', 0, 3, 3, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(18, 'ADH20018', 'sms3', 'Hukum Acara Pidana', 1, 3, 3, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(19, 'ADH20013', 'sms3', 'Hukum Acara Perdata', 1, 3, 3, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(20, 'ADH20008', 'sms3', 'Hukum Administrasi Negara', 0, 3, 3, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(21, 'ADH20020', 'sms3', 'Kejaksaan dan Badan Peradilan di Indonesia', 1, 2, 3, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(22, 'ADH20040', 'sms3', 'Hukum Sanksi', 1, 2, 3, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(23, 'ADH20051', 'sms3', 'Penerapan Asas-Asas Hukum Pidana', 0, 2, 3, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(24, 'ADH30011', 'sms3', 'Lembaga Negara Indonesia', 0, 2, 3, 'pilihan', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(25, 'ADH20011', 'sms3', 'Hukum Perikatan', 0, 2, 3, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(26, 'ADH30002', 'sms4', 'Legal English', 1, 2, 4, 'pilihan', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(27, 'ADH20019', 'sms4', 'Hukum Acara Tata Usaha Negara', 1, 3, 4, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(28, 'ADH20017', 'sms4', 'Hukum Agraria', 0, 2, 4, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(29, 'ADH20047', 'sms4', 'Hukum Perdata Internasional', 0, 2, 4, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(30, 'ADH20016', 'sms4', 'Hukum Ketenagakerjaan', 0, 2, 4, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(31, 'ADH20026', 'sms4', 'Hukum Kekayaan Interlektual', 1, 2, 4, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(32, 'ADH20052', 'sms4', 'Kejaksaan Dalam Sistem Peradilan Pidana', 1, 2, 4, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(33, 'ADH20036', 'sms4', 'Praktik Pembuktian Pidana', 2, 3, 4, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(34, 'ADH20023', 'sms4', 'Hukum Lingkungan', 0, 2, 4, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(35, 'ADH20022', 'sms4', 'Hukum Pajak', 0, 2, 4, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(36, 'ADH10009', 'sms4', 'Logika Hukum', 0, 2, 4, 'wajib_nasional', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(37, 'ADH20033', 'sms5', 'Metode Penelitian Hukum & Penulisan Jurnal Ilmiah', 1, 2, 5, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(38, 'ADH30005', 'sms5', 'Hukum Pidana Khusus', 0, 2, 5, 'pilihan', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(39, 'ADH20027', 'sms5', 'Hukum Perlindungan Anak', 0, 2, 5, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(40, 'ADH20029', 'sms5', 'Hukum Pidana Internasional', 0, 2, 5, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(41, 'ADH20025', 'sms5', 'Hukum Acara Mahkamah Konstitusi', 1, 2, 5, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(42, 'ADH30007', 'sms5', 'Hukum Siber', 0, 2, 5, 'pilihan', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(43, 'ADH20048', 'sms5', 'Hukum Humaniter', 0, 2, 5, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(44, 'ADH20035', 'sms5', 'Hukum Kepailitan', 1, 2, 5, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(45, 'ADH20021', 'sms5', 'Hukum Perdata Islam', 1, 2, 5, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(46, 'ADH20034', 'sms5', 'Pencucian Uang, Penyitaan & Pemulihan Aset', 0, 2, 5, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(47, 'ADH20028', 'sms5', 'Hukum Antar Tata Hukum', 0, 2, 5, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(48, 'ADH20053', 'sms5', 'Kejaksaan Dalam Bidang Perdata & Tata Usaha Negara', 1, 2, 5, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(49, 'ADH20037', 'sms6', 'Etika, Tanggung Jawab & Profesi Hukum', 0, 2, 6, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(50, 'ADH20038', 'sms6', 'Filsafat Hukum', 0, 2, 6, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(51, 'ADH20039', 'sms6', 'Legal Drafting', 2, 2, 6, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(52, 'ADH20041', 'sms6', 'Praktik Hukum Perdata', 1, 2, 6, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(53, 'ADH20042', 'sms6', 'Praktik Hukum Tata Usaha Negara', 1, 2, 6, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(54, 'ADH40004', 'sms6', 'Kriminologi & Viktimologi', 0, 2, 6, 'peminatan', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(55, 'ADH20044', 'sms6', 'Arbitrase & Alternative Dispute Resolution', 1, 2, 6, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(56, 'ADH30004', 'sms6', 'Hukum Perusahaan, Persaingan Usaha & Jaminan', 0, 2, 6, 'pilihan', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(57, 'ADH20030', 'sms6', 'Hukum Perbankan & Surat Berharga', 0, 2, 6, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(58, 'ADH20031', 'sms6', 'Penyelesaian Sengketa Industrial', 0, 2, 6, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(59, 'ADH20032', 'sms6', 'Perbandingan Hukum Pidana', 1, 2, 6, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(60, 'ADH30010', 'sms6', 'Hukum Organisasi Internasional', 0, 2, 6, 'pilihan', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(61, 'ADH20024', 'sms7', 'Hukum Administrasi Negara Sektoral', 1, 2, 7, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(62, 'ADH30003', 'sms7', 'Legal Enterpreneurship (Kewirausahaan)', 1, 2, 7, 'pilihan', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(63, 'ADD20049', 'sms7', 'Penulisan Skripsi/ Penulisan Jurnal Ilmiah', 3, 4, 7, 'wajib_prodi', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(64, 'ADH40001', 'sms7', 'Hukum Laut', 0, 2, 7, 'peminatan', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(65, 'ADH30014', 'sms7', 'Hukum Perlindungan Konsumen', 0, 2, 7, 'pilihan', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(66, 'ADH30006', 'sms7', 'Hukum Jaminan', 1, 2, 7, 'pilihan', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(67, 'ADH30012', 'sms7', 'Hukum Kesehatan & Medikolegal', 0, 2, 7, 'pilihan', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(68, 'ADH30008', 'sms7', 'Hukum Investasi', 0, 2, 7, 'pilihan', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(69, 'ADH30009', 'sms7', 'Hukum Perjanjian Internasional', 0, 2, 7, 'pilihan', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1),
(70, 'ADH30015', 'sms7', 'Kapita Selekta Hukum Acara Pidana', 1, 2, 7, 'pilihan', NULL, '2026-02-10 04:56:08', '2026-02-10 04:56:08', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `materis`
--

CREATE TABLE `materis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `dosen_id` bigint(20) UNSIGNED NOT NULL,
  `pertemuan` int(11) NOT NULL DEFAULT 1,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `materis`
--

INSERT INTO `materis` (`id`, `mata_kuliah_id`, `dosen_id`, `pertemuan`, `judul`, `deskripsi`, `file_path`, `file_name`, `file_type`, `file_size`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 'Pengenalan Hukum Indonesia', 'Hukum di indonesia', 'materi/1770793751_KRS_2024010001_9.pdf', 'KRS_2024010001_9.pdf', 'pdf', 907216, '2026-02-11 00:09:11', '2026-02-11 00:09:11'),
(2, 2, 1, 2, 'test', NULL, 'materi/1770797920_Kalender-Akademik-Gasal-T.A-2025-2026.pdf', 'Kalender-Akademik-Gasal-T.A-2025-2026.pdf', 'pdf', 785963, '2026-02-11 01:18:40', '2026-02-11 01:18:40');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_13_091431_add_role_to_users_table', 1),
(5, '2026_01_15_000001_create_mata_kuliahs_table', 1),
(6, '2026_01_15_000002_create_kelas_table', 1),
(7, '2026_01_15_000003_create_jadwals_table', 1),
(8, '2026_01_15_030141_create_admins_table', 1),
(9, '2026_01_15_030149_create_dosens_table', 1),
(10, '2026_01_15_030150_create_mahasiswas_table', 1),
(11, '2026_01_15_030151_create_parents_table', 1),
(12, '2026_01_15_030152_create_activity_logs_table', 1),
(13, '2026_01_15_030153_create_semesters_table', 1),
(14, '2026_01_15_030153_create_system_settings_table', 1),
(15, '2026_01_15_030154_create_kelas_mata_kuliahs_table', 1),
(16, '2026_01_15_030156_create_krs_table', 1),
(17, '2026_01_15_030157_create_nilai_table', 1),
(18, '2026_01_15_030158_create_presensis_table', 1),
(19, '2026_01_19_000001_create_jadwal_reschedules_table', 1),
(20, '2026_01_19_000002_add_apply_date_to_jadwal_reschedules_table', 1),
(21, '2026_01_19_000003_create_jadwal_exceptions_table', 1),
(22, '2026_01_19_040244_create_kuesioner_aktivasi_table', 1),
(23, '2026_01_19_040252_create_pembayaran_table', 1),
(24, '2026_01_19_040301_add_status_to_mahasiswas_table', 1),
(25, '2026_01_19_040556_add_ambil_mk_to_krs_table', 1),
(26, '2026_01_19_042201_add_is_active_to_semesters_table', 1),
(27, '2026_01_19_044316_add_krs_settings_to_semesters_table', 1),
(28, '2026_01_19_130000_add_qr_to_kelas_mata_kuliahs_table', 1),
(29, '2026_01_20_024300_add_fields_to_presensis_table', 1),
(30, '2026_01_20_080000_add_semester_to_mahasiswas_table', 1),
(31, '2026_01_20_090000_add_mata_kuliah_ids_to_dosens_table', 1),
(32, '2026_01_20_100000_update_jenis_mata_kuliah_enum', 1),
(33, '2026_01_20_101500_add_pendidikan_to_dosens_table', 1),
(34, '2026_01_21_000001_add_kode_id_to_mata_kuliahs_table', 1),
(35, '2026_01_21_000002_add_praktikum_to_mata_kuliahs_table', 1),
(36, '2026_01_21_032900_add_hari_to_kelas_mata_kuliahs_table', 1),
(37, '2026_01_21_033208_create_kelas_reschedules_table', 1),
(38, '2026_01_21_033546_add_new_kelas_to_kelas_reschedules_table', 1),
(39, '2026_01_21_033845_add_mata_kuliah_id_to_krs_table', 1),
(40, '2026_01_21_050000_add_times_to_kelas_mata_kuliahs_table', 1),
(41, '2026_01_21_115547_create_dosen_pa_table', 1),
(42, '2026_01_21_120000_update_krs_table_to_current_system', 1),
(43, '2026_01_22_000002_add_hubungan_pekerjaan_to_parents', 1),
(44, '2026_01_22_082913_add_data_pribadi_to_mahasiswas_table', 1),
(45, '2026_01_22_084657_add_orang_tua_to_parents_table', 1),
(46, '2026_01_22_084707_add_asal_sekolah_to_mahasiswas_table', 1),
(47, '2026_01_23_041401_create_academic_events_table', 1),
(48, '2026_01_23_065016_align_academic_event_types', 1),
(49, '2026_01_23_120000_add_pertemuan_to_presensis_table', 1),
(50, '2026_01_26_000001_add_new_survey_flag_to_mahasiswas', 1),
(51, '2026_01_26_000002_create_kuesioner_mahasiswa_baru_table', 1),
(52, '2026_01_26_000003_add_q_columns_to_kuesioner_mahasiswa_baru', 1),
(53, '2026_01_26_000004_add_meta_columns_to_kuesioner_mahasiswa_baru', 1),
(54, '2026_01_28_000001_create_tugas_table', 1),
(55, '2026_01_28_000002_create_tugas_submissions_table', 1),
(56, '2026_01_29_000000_rename_npm_to_nim_in_mahasiswas', 1),
(57, '2026_01_29_010000_convert_nama_semester_to_enum', 1),
(58, '2026_01_29_020000_add_unique_index_semesters', 1),
(59, '2026_01_30_000002_create_religions_table', 1),
(60, '2026_01_30_071940_add_desa_provinsi_to_mahasiswas', 1),
(61, '2026_01_30_072052_drop_propinsi_from_mahasiswas', 1),
(62, '2026_01_30_081140_add_document_fields_to_mahasiswas', 1),
(63, '2026_01_30_084701_add_wali_fields_to_parents', 1),
(64, '2026_01_30_092328_add_keluarga_to_parents', 1),
(65, '2026_01_30_110441_add_ktp_address_to_mahasiswas', 1),
(66, '2026_01_30_120000_add_metode_pengajaran_to_kelas_mata_kuliahs', 1),
(67, '2026_01_30_130000_create_pengumumans_table', 1),
(68, '2026_02_03_020911_create_prodis_table', 1),
(69, '2026_02_03_020920_create_fakultas_table', 1),
(70, '2026_02_03_023543_update_mata_kuliahs_add_prodi_fakultas_relations', 1),
(71, '2026_02_03_024544_add_foreign_key_to_prodis_table', 1),
(72, '2026_02_03_033934_add_qr_current_pertemuan_to_kelas_mata_kuliahs_table', 1),
(73, '2026_02_03_040919_create_jadwal_proposals_table', 1),
(74, '2026_02_03_040928_create_jadwal_approvals_table', 1),
(75, '2026_02_03_044000_add_fields_to_dosens_table', 1),
(76, '2026_02_03_045000_create_jadwal_generate_logs_table', 1),
(77, '2026_02_03_082432_add_target_to_pengumumans_table', 1),
(78, '2026_02_03_085058_create_ruangans_table', 1),
(79, '2026_02_03_091447_add_ruangan_id_to_jadwals_table', 1),
(80, '2026_02_03_091505_add_ruangan_id_to_jadwal_proposals_table', 1),
(81, '2026_02_03_091540_add_ruangan_id_to_kelas_mata_kuliahs_table', 1),
(82, '2026_02_04_034520_add_desa_columns_to_parents_table', 1),
(83, '2026_02_04_041443_create_jam_perkuliahan_table', 1),
(84, '2026_02_04_042815_split_parent_address_columns', 1),
(85, '2026_02_04_083453_add_universitas_to_dosens_table', 1),
(86, '2026_02_05_100000_create_materis_table', 1),
(87, '2026_02_05_100001_update_tugas_table_for_sharing', 1),
(88, '2026_02_05_164000_add_columns_to_ruangans_table', 1),
(89, '2026_02_05_164500_fix_kelas_dosen_foreign_key', 1),
(90, '2026_02_06_064041_create_dosen_availabilities_table', 1),
(91, '2026_02_06_070353_add_kecamatan_columns_to_mahasiswas_and_parents_tables', 1),
(92, '2026_02_06_093010_create_dosen_availability_checks_table', 1),
(93, '2026_02_09_000001_create_import_logs_table', 1),
(94, '2026_02_09_000001_update_nilai_table_add_components', 1),
(95, '2026_02_09_000002_create_bobot_penilaian_table', 1),
(96, '2026_02_09_000003_add_published_status_to_nilai_table', 1),
(97, '2026_02_10_070112_create_pengajuans_table', 1),
(98, '2026_02_11_000001_create_pertemuans_table', 2),
(99, '2026_02_11_000002_add_pertemuan_id_to_presensis_table', 2),
(100, '2026_02_11_033628_add_pertemuan_to_presensis_table', 3),
(101, '2026_02_11_034943_create_dokumen_kelas_table', 4),
(102, '2026_02_11_035309_add_asynchronous_file_to_kelas_mata_kuliahs', 5),
(103, '2026_02_12_041345_add_approval_fields_to_pengajuans_table', 6),
(104, '2026_02_12_080836_add_metode_columns_to_kelas_reschedules_table', 7),
(105, '2026_02_13_000001_add_semester_transition_fields', 8),
(106, '2026_02_18_021638_make_user_id_nullable_in_parents_table', 9),
(107, '2026_02_18_022023_make_mahasiswa_id_nullable_in_parents_table', 10),
(108, '2026_02_18_000001_add_role_to_users_table', 11),
(109, '2026_02_18_000002_create_students_table', 11),
(110, '2026_02_18_000003_create_invoices_table', 11),
(111, '2026_02_18_000004_create_installment_requests_table', 11),
(112, '2026_02_18_000005_create_installments_table', 11),
(113, '2026_02_18_000006_create_payment_proofs_table', 11),
(114, '2026_02_18_000007_create_payments_table', 11),
(115, '2026_02_18_000008_create_audit_logs_table', 11),
(116, '2026_02_18_000000_fix_invoices_student_foreign_to_mahasiswas', 12),
(117, '2026_02_18_000001_fix_installment_requests_student_foreign_to_mahasiswas', 13),
(118, '2026_02_19_050352_add_kuota_to_dosens_table', 14),
(119, '2026_02_20_000001_add_metode_pengajaran_to_pertemuans', 15),
(120, '2026_02_20_000002_add_absen_password_hash_to_dosens', 16),
(121, '2026_02_20_000003_create_dosen_attendances_table', 17),
(122, '2026_02_19_000001_drop_absen_password_hash_from_dosens', 18),
(123, '2026_02_19_065019_add_online_meeting_link_to_pertemuans_table', 19);

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE `nilai` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `krs_id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nilai_partisipatif` decimal(5,2) DEFAULT NULL,
  `nilai_proyek` decimal(5,2) DEFAULT NULL,
  `nilai_quiz` decimal(5,2) DEFAULT NULL,
  `nilai_tugas` decimal(5,2) DEFAULT NULL,
  `nilai_uts` decimal(5,2) DEFAULT NULL,
  `nilai_uas` decimal(5,2) DEFAULT NULL,
  `nilai_akhir` decimal(5,2) DEFAULT NULL,
  `grade` char(2) DEFAULT NULL,
  `bobot` decimal(4,2) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `published_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`id`, `krs_id`, `kelas_id`, `nilai_partisipatif`, `nilai_proyek`, `nilai_quiz`, `nilai_tugas`, `nilai_uts`, `nilai_uas`, `nilai_akhir`, `grade`, `bobot`, `is_published`, `published_at`, `published_by`, `created_at`, `updated_at`) VALUES
(5, 41, 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'E', 0.00, 1, '2026-02-18 23:24:12', 2, '2026-02-18 23:24:12', '2026-02-18 23:24:12');

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hubungan` varchar(255) DEFAULT NULL,
  `pekerjaan` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tipe_wali` enum('orang_tua','wali') NOT NULL DEFAULT 'orang_tua',
  `nama_ayah` varchar(255) DEFAULT NULL,
  `pendidikan_ayah` varchar(255) DEFAULT NULL,
  `pekerjaan_ayah` varchar(255) DEFAULT NULL,
  `agama_ayah` varchar(255) DEFAULT NULL,
  `alamat_ayah` text DEFAULT NULL,
  `kota_ayah` varchar(255) DEFAULT NULL,
  `kecamatan_ayah` varchar(255) DEFAULT NULL,
  `propinsi_ayah` varchar(255) DEFAULT NULL,
  `desa_ayah` varchar(255) DEFAULT NULL,
  `negara_ayah` varchar(255) DEFAULT NULL,
  `handphone_ayah` varchar(20) DEFAULT NULL,
  `nama_ibu` varchar(255) DEFAULT NULL,
  `pendidikan_ibu` varchar(255) DEFAULT NULL,
  `pekerjaan_ibu` varchar(255) DEFAULT NULL,
  `agama_ibu` varchar(255) DEFAULT NULL,
  `alamat_ibu` text DEFAULT NULL,
  `kota_ibu` varchar(255) DEFAULT NULL,
  `kecamatan_ibu` varchar(255) DEFAULT NULL,
  `propinsi_ibu` varchar(255) DEFAULT NULL,
  `desa_ibu` varchar(255) DEFAULT NULL,
  `negara_ibu` varchar(255) DEFAULT NULL,
  `handphone_ibu` varchar(20) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `handphone_ortu` varchar(255) DEFAULT NULL,
  `nama_wali` varchar(255) DEFAULT NULL,
  `hubungan_wali` varchar(255) DEFAULT NULL,
  `pendidikan_wali` varchar(255) DEFAULT NULL,
  `pekerjaan_wali` varchar(255) DEFAULT NULL,
  `agama_wali` varchar(255) DEFAULT NULL,
  `alamat_wali` text DEFAULT NULL,
  `kota_wali` varchar(255) DEFAULT NULL,
  `kecamatan_wali` varchar(255) DEFAULT NULL,
  `provinsi_wali` varchar(255) DEFAULT NULL,
  `negara_wali` varchar(255) DEFAULT NULL,
  `handphone_wali` varchar(20) DEFAULT NULL,
  `keluarga` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`keluarga`)),
  `address` text DEFAULT NULL,
  `alamat_ortu` text DEFAULT NULL,
  `kota_ortu` varchar(255) DEFAULT NULL,
  `provinsi_ortu` varchar(255) DEFAULT NULL,
  `kabupaten_ortu` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `desa_ortu` varchar(255) DEFAULT NULL,
  `negara_ortu` varchar(255) DEFAULT NULL,
  `desa_wali` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`id`, `hubungan`, `pekerjaan`, `user_id`, `mahasiswa_id`, `tipe_wali`, `nama_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `agama_ayah`, `alamat_ayah`, `kota_ayah`, `kecamatan_ayah`, `propinsi_ayah`, `desa_ayah`, `negara_ayah`, `handphone_ayah`, `nama_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `agama_ibu`, `alamat_ibu`, `kota_ibu`, `kecamatan_ibu`, `propinsi_ibu`, `desa_ibu`, `negara_ibu`, `handphone_ibu`, `phone`, `handphone_ortu`, `nama_wali`, `hubungan_wali`, `pendidikan_wali`, `pekerjaan_wali`, `agama_wali`, `alamat_wali`, `kota_wali`, `kecamatan_wali`, `provinsi_wali`, `negara_wali`, `handphone_wali`, `keluarga`, `address`, `alamat_ortu`, `kota_ortu`, `provinsi_ortu`, `kabupaten_ortu`, `created_at`, `updated_at`, `desa_ortu`, `negara_ortu`, `desa_wali`) VALUES
(3, 'ayah', 'Dokter', 7, 3, 'orang_tua', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '081234567894', NULL, 'budi', 'Nenek', 'Tamat SD', 'Mengurus Rumah Tangga', 'Protestan', 'test', 'KAB. PANDEGLANG', 'ANGSANA', 'BANTEN', NULL, '822222222222', NULL, 'Jl in aja dulu', NULL, NULL, NULL, NULL, '2026-02-17 19:20:58', '2026-02-17 19:49:12', NULL, NULL, 'KADUBADAK');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `installment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `proof_id` bigint(20) UNSIGNED NOT NULL,
  `amount_approved` bigint(20) UNSIGNED NOT NULL,
  `paid_date` date NOT NULL,
  `transfer_date` date NOT NULL,
  `approved_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `invoice_id`, `installment_id`, `proof_id`, `amount_approved`, `paid_date`, `transfer_date`, `approved_by`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 4166000, '2026-02-18', '2026-02-18', 9, '2026-02-17 21:26:40', '2026-02-17 21:26:40');

-- --------------------------------------------------------

--
-- Table structure for table `payment_proofs`
--

CREATE TABLE `payment_proofs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `installment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `uploaded_by` bigint(20) UNSIGNED NOT NULL,
  `transfer_date` date NOT NULL,
  `amount_submitted` bigint(20) UNSIGNED NOT NULL,
  `method` varchar(50) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `status` enum('UPLOADED','APPROVED','REJECTED') NOT NULL DEFAULT 'UPLOADED',
  `finance_notes` text DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `rejected_at` datetime DEFAULT NULL,
  `student_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_proofs`
--

INSERT INTO `payment_proofs` (`id`, `invoice_id`, `installment_id`, `uploaded_by`, `transfer_date`, `amount_submitted`, `method`, `file_path`, `status`, `finance_notes`, `approved_by`, `approved_at`, `rejected_at`, `student_notes`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 7, '2026-02-18', 4166000, 'Transfer Bank', 'payment-proofs/1771388224_2024010003_1.pdf', 'APPROVED', NULL, 9, '2026-02-18 04:26:40', NULL, NULL, '2026-02-17 21:17:04', '2026-02-17 21:26:40');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `semester_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jenis` varchar(255) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `dibayar` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('belum_bayar','sebagian','lunas') NOT NULL DEFAULT 'belum_bayar',
  `tanggal_bayar` date DEFAULT NULL,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuans`
--

CREATE TABLE `pengajuans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `jenis` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `file_path` varchar(255) DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `nomor_surat` varchar(255) DEFAULT NULL,
  `file_surat` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengumumans`
--

CREATE TABLE `pengumumans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `target` enum('semua','dosen','mahasiswa') NOT NULL DEFAULT 'semua',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengumumans`
--

INSERT INTO `pengumumans` (`id`, `judul`, `isi`, `target`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'test', 'pengumuman untuk dosen dan mahasiswa KRS', 'semua', '2026-02-13 00:00:00', '2026-02-12 21:51:08', '2026-02-12 21:51:08'),
(4, 'Testing Konten', 'Test', 'semua', '2026-02-13 06:28:00', '2026-02-12 23:28:59', '2026-02-12 23:28:59');

-- --------------------------------------------------------

--
-- Table structure for table `pertemuans`
--

CREATE TABLE `pertemuans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kelas_mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `nomor_pertemuan` int(10) UNSIGNED NOT NULL,
  `tanggal` date DEFAULT NULL,
  `topik` varchar(255) DEFAULT NULL,
  `metode_pengajaran` enum('offline','online','asynchronous') NOT NULL DEFAULT 'offline',
  `online_meeting_link` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `qr_token` varchar(64) DEFAULT NULL,
  `qr_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `qr_expires_at` timestamp NULL DEFAULT NULL,
  `qr_generated_at` timestamp NULL DEFAULT NULL,
  `status` enum('scheduled','active','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pertemuans`
--

INSERT INTO `pertemuans` (`id`, `kelas_mata_kuliah_id`, `nomor_pertemuan`, `tanggal`, `topik`, `metode_pengajaran`, `online_meeting_link`, `deskripsi`, `qr_token`, `qr_enabled`, `qr_expires_at`, `qr_generated_at`, `status`, `created_at`, `updated_at`) VALUES
(65, 14, 1, NULL, 'Pertemuan 1', 'offline', NULL, NULL, '5crqamuc09hWgkrFlepkA9oyzW1zwDubW1PUFULhlVvmGT8UOOM3UjC30K2ifAJJ', 0, NULL, '2026-02-17 23:25:05', 'scheduled', '2026-02-17 23:25:05', '2026-02-17 23:26:04'),
(66, 11, 1, NULL, 'Pertemuan 1', 'online', 'https://meet.google.com/landing?pli=1', NULL, 'CDI8lJ0TwtLMnyeOFNW6IHAY1KAeh2lMifAAXggiaICldMBOFWn64cjep21omhFy', 0, NULL, '2026-02-18 03:27:40', 'scheduled', '2026-02-18 03:27:40', '2026-02-18 23:53:01'),
(67, 13, 4, NULL, 'Pertemuan 4', 'offline', NULL, NULL, 'fabywF0QuUedjZu8MEKjS9NvxrcqP7KBJzcJmWXwvqCj6KotE2dJBbdIpWqKrE3t', 0, NULL, '2026-02-18 03:28:13', 'scheduled', '2026-02-18 03:28:13', '2026-02-18 03:30:52'),
(68, 11, 2, NULL, 'Pertemuan 2', 'offline', NULL, NULL, 'JuofJTM4uZLgMoxlGVg1BANfJwVPyYYVwoWP6MR8tKMlqKbH4eTiV3vAUJpqX7E3', 0, NULL, '2026-02-18 22:11:21', 'scheduled', '2026-02-18 22:11:21', '2026-02-18 23:46:57'),
(69, 12, 1, NULL, 'Pertemuan 1', 'offline', NULL, NULL, 'NE3UF8gEV0MNFaMHqpMpfZuEDFmP8qqfSAyjvovYIsZTKyp18YhEzIYQiI2i551T', 0, NULL, NULL, 'scheduled', '2026-02-18 22:48:26', '2026-02-18 23:20:27'),
(70, 11, 3, NULL, 'Pertemuan 3', 'offline', NULL, NULL, 'wnWhKzRY2Mwb9mvXjqJIpzzGLzqmtPcDkntJ2Q650cUNw5BrwcDhuA8vSpVQRoMv', 0, NULL, NULL, 'scheduled', '2026-02-18 22:53:27', '2026-02-18 22:53:29'),
(71, 11, 10, NULL, 'Pertemuan 10', 'offline', NULL, NULL, 'xtr9Qr4qhcV3qdA4qj2avy7TIo59L1ITSpDatp4E4HuLRlVtuG0W2KHI1tohpUAr', 0, NULL, NULL, 'scheduled', '2026-02-18 23:37:11', '2026-02-18 23:56:46'),
(72, 11, 5, NULL, 'Pertemuan 5', 'online', NULL, NULL, NULL, 0, NULL, NULL, 'scheduled', '2026-02-18 23:44:57', '2026-02-18 23:44:57');

-- --------------------------------------------------------

--
-- Table structure for table `presensis`
--

CREATE TABLE `presensis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kelas_mata_kuliah_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pertemuan` int(11) DEFAULT NULL,
  `pertemuan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `kontak` varchar(255) DEFAULT NULL,
  `waktu` timestamp NULL DEFAULT NULL,
  `krs_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('hadir','izin','sakit','alpa') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `presensis`
--

INSERT INTO `presensis` (`id`, `mahasiswa_id`, `kelas_mata_kuliah_id`, `pertemuan`, `pertemuan_id`, `nama`, `kontak`, `waktu`, `krs_id`, `tanggal`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
(6, 3, 13, 4, NULL, 'Rizki Firmansyah', '081234567896', '2026-02-18 03:29:15', 42, '2026-02-18', 'hadir', 'ip:192.168.1.79 | ua:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-18 03:29:15', '2026-02-18 03:29:15'),
(7, 3, 11, 1, NULL, 'Rizki Firmansyah', '081234567896', '2026-02-18 20:20:12', 41, '2026-02-19', 'hadir', 'ip:192.168.1.34 | ua:Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Mobile Safari/537.36', '2026-02-18 20:20:12', '2026-02-18 20:20:12'),
(8, 3, 11, 2, NULL, 'Rizki Firmansyah', '081234567896', '2026-02-18 22:11:47', 41, '2026-02-19', 'hadir', 'ip:192.168.1.79 | ua:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-18 22:11:47', '2026-02-18 22:11:47');

-- --------------------------------------------------------

--
-- Table structure for table `prodis`
--

CREATE TABLE `prodis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_prodi` varchar(10) NOT NULL,
  `nama_prodi` varchar(255) NOT NULL,
  `fakultas_id` bigint(20) UNSIGNED NOT NULL,
  `jenjang` enum('D3','S1','S2','S3') NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prodis`
--

INSERT INTO `prodis` (`id`, `kode_prodi`, `nama_prodi`, `fakultas_id`, `jenjang`, `status`, `created_at`, `updated_at`) VALUES
(1, 'HK01', 'Ilmu Hukum', 1, 'S1', 'aktif', '2026-02-10 04:54:55', '2026-02-10 04:54:55'),
(5, 'HK02', 'Ilmu Hukum 2', 1, 'S1', 'aktif', '2026-02-18 02:08:14', '2026-02-18 02:08:14');

-- --------------------------------------------------------

--
-- Table structure for table `religions`
--

CREATE TABLE `religions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `religions`
--

INSERT INTO `religions` (`id`, `code`, `name`, `created_at`, `updated_at`) VALUES
(1, '1001001', 'Islam', '2026-02-10 04:54:58', '2026-02-10 04:54:58'),
(2, '1001002', 'Protestan', '2026-02-10 04:54:58', '2026-02-10 04:54:58'),
(3, '1001003', 'Hindu', '2026-02-10 04:54:58', '2026-02-10 04:54:58'),
(4, '1001004', 'Buddha', '2026-02-10 04:54:58', '2026-02-10 04:54:58'),
(5, '1001005', 'Katolik', '2026-02-10 04:54:58', '2026-02-10 04:54:58'),
(6, '1001006', 'Khonghucu', '2026-02-10 04:54:58', '2026-02-10 04:54:58'),
(7, '1001007', 'Penganut Kepercayaan Lainnya', '2026-02-10 04:54:58', '2026-02-10 04:54:58');

-- --------------------------------------------------------

--
-- Table structure for table `ruangans`
--

CREATE TABLE `ruangans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_ruangan` varchar(20) NOT NULL,
  `nama_ruangan` varchar(255) NOT NULL,
  `gedung` varchar(50) DEFAULT NULL,
  `lantai` int(11) DEFAULT NULL,
  `kapasitas` int(11) NOT NULL DEFAULT 30,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ruangans`
--

INSERT INTO `ruangans` (`id`, `kode_ruangan`, `nama_ruangan`, `gedung`, `lantai`, `kapasitas`, `status`, `created_at`, `updated_at`) VALUES
(2, 'R.101', 'Ruang Kelas A1', 'Gedung A', 1, 40, 'aktif', '2026-02-10 05:37:56', '2026-02-10 05:37:56'),
(3, 'R.102', 'Ruang Kelas A2', 'Gedung A', 1, 35, 'aktif', '2026-02-10 05:37:56', '2026-02-10 05:37:56'),
(4, 'R.103', 'Ruang Kelas A3', 'Gedung A', 1, 30, 'aktif', '2026-02-10 05:37:56', '2026-02-10 05:37:56'),
(5, 'R.104', 'Ruang Kelas A4', 'Gedung A', 1, 45, 'aktif', '2026-02-10 05:37:56', '2026-02-10 05:37:56'),
(6, 'R.105', 'Ruang Kelas A5', 'Gedung A', 1, 40, 'aktif', '2026-02-10 05:37:56', '2026-02-10 05:37:56'),
(7, 'R.201', 'Ruang Kelas B1', 'Gedung A', 2, 50, 'aktif', '2026-02-10 05:37:56', '2026-02-10 05:37:56'),
(8, 'R.202', 'Ruang Kelas B2', 'Gedung A', 2, 45, 'aktif', '2026-02-10 05:37:56', '2026-02-10 05:37:56'),
(9, 'R.203', 'Ruang Kelas B3', 'Gedung A', 2, 40, 'aktif', '2026-02-10 05:37:56', '2026-02-10 05:37:56'),
(10, 'LAB.01', 'Lab Komputer 1', 'Gedung B', 1, 30, 'aktif', '2026-02-10 05:37:56', '2026-02-10 05:37:56'),
(11, 'LAB.02', 'Lab Komputer 2', 'Gedung B', 1, 25, 'aktif', '2026-02-10 05:37:56', '2026-02-10 05:37:56'),
(12, 'PRAK.01', 'Ruang Praktikum Hukum 1', 'Gedung B', 2, 35, 'aktif', '2026-02-10 05:37:56', '2026-02-10 05:37:56'),
(13, 'PRAK.02', 'Ruang Praktikum Hukum 2', 'Gedung B', 2, 30, 'aktif', '2026-02-10 05:37:56', '2026-02-10 05:37:56'),
(14, 'AULA.01', 'Aula Utama', 'Gedung C', 1, 200, 'aktif', '2026-02-10 05:37:56', '2026-02-10 05:37:56'),
(15, 'SEMINAR.01', 'Ruang Seminar', 'Gedung C', 1, 80, 'aktif', '2026-02-10 05:37:56', '2026-02-10 05:37:56');

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_semester` enum('Ganjil','Genap') NOT NULL,
  `nama_semester_old` varchar(255) DEFAULT NULL,
  `tahun_ajaran` varchar(255) NOT NULL,
  `status` enum('aktif','non-aktif') NOT NULL DEFAULT 'non-aktif',
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `krs_dapat_diisi` tinyint(1) NOT NULL DEFAULT 0,
  `max_sks_rendah` int(11) NOT NULL DEFAULT 20 COMMENT 'Max SKS untuk IPK < 3.0',
  `max_sks_tinggi` int(11) NOT NULL DEFAULT 24 COMMENT 'Max SKS untuk IPK >= 3.0',
  `krs_mulai` date DEFAULT NULL,
  `krs_selesai` date DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`id`, `nama_semester`, `nama_semester_old`, `tahun_ajaran`, `status`, `is_active`, `krs_dapat_diisi`, `max_sks_rendah`, `max_sks_tinggi`, `krs_mulai`, `krs_selesai`, `tanggal_mulai`, `tanggal_selesai`, `created_at`, `updated_at`) VALUES
(4, 'Ganjil', NULL, '2025/2026', 'aktif', 1, 1, 20, 24, '2026-02-13', '2026-02-28', '2026-02-13', '2026-08-13', '2026-02-13 00:31:02', '2026-02-13 01:10:55'),
(5, 'Genap', NULL, '2025/2026', 'non-aktif', 0, 0, 20, 24, NULL, NULL, '2026-08-14', '2027-02-14', '2026-02-13 00:31:10', '2026-02-13 00:31:10');

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

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `npm` varchar(50) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `prodi` varchar(100) NOT NULL,
  `angkatan` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kelas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pertemuan` int(11) NOT NULL DEFAULT 1,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `dosen_id` bigint(20) UNSIGNED DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `max_score` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tugas`
--

INSERT INTO `tugas` (`id`, `mata_kuliah_id`, `kelas_id`, `pertemuan`, `title`, `description`, `due_date`, `dosen_id`, `file_path`, `max_score`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 'Buat resume pert 1', '<p><strong>buat yang bener</strong></p>\r\n<ul>\r\n<li><strong>1</strong></li>\r\n<li><strong>1</strong></li>\r\n<li><strong>1</strong></li>\r\n<li><strong>1</strong></li>\r\n</ul>', '2026-02-11 20:10:00', 1, 'tugas/GoOWaZgmlTxjxRjVyZtwopsZdR4GqmkFU2sfxzEE.png', NULL, '2026-02-11 00:10:15', '2026-02-11 00:10:15');

-- --------------------------------------------------------

--
-- Table structure for table `tugas_submissions`
--

CREATE TABLE `tugas_submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tugas_id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `graded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `graded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'mahasiswa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin STIH', 'admin@stih.ac.id', NULL, '$2y$12$js9GNyR2/33wUfLUzgu91uXXaTwPnWL.0MiKGJDf.Qp976c5gRVwi', NULL, 'admin', '2026-02-10 04:54:57', '2026-02-17 21:46:14'),
(2, 'Dr. Ahmad Fauzi, S.H., M.H.', 'ahmad.fauzi@stih.ac.id', NULL, '$2y$12$xmX2Djdz2r/sc5ZVxdb92.FemQSz20RIgM..KVYswHEJTl9eSzKhS', NULL, 'dosen', '2026-02-10 04:54:57', '2026-02-10 04:54:57'),
(3, 'Prof. Dr. Siti Nurjanah, S.H., M.H.', 'siti.nurjanah@stih.ac.id', NULL, '$2y$12$R7L8TjxM6o0tKI5ftcOZZe5Astw.QmFbx21L1ihjbigruWHU0GJvS', NULL, 'dosen', '2026-02-10 04:54:57', '2026-02-10 04:54:57'),
(4, 'Dr. Budi Santoso, S.H., M.H.', 'budi.santoso@stih.ac.id', NULL, '$2y$12$hElFQKhBjOyl5l45ntlqj.oP2iAZkOYVwaBUx2Y0Fa.8kxRyuW0M2', NULL, 'dosen', '2026-02-10 04:54:57', '2026-02-10 04:54:57'),
(7, 'Rizki Firmansyah', 'rizki.firmansyah@student.stih.ac.id', NULL, '$2y$12$vbM1/kG2R7hiqWV8Xi/3sORHmkcCEHVlo0DgLY..lfWpI/sX9qFei', NULL, 'mahasiswa', '2026-02-10 04:54:58', '2026-02-10 04:54:58'),
(8, 'Bapak Pratama', 'parent.pratama@stih.ac.id', NULL, '$2y$12$crPgqJOx3CnhC.PoTVkDWO7ZPKz0BTveoPC1AgPDnAquCIC8CnH9W', NULL, 'parent', '2026-02-10 04:54:58', '2026-02-10 04:54:58'),
(9, 'Admin Keuangan', 'keuangan@stih.ac.id', NULL, '$2y$12$m9Vboea8L1NnJIQanZlrauq5qQfaYc3QFnV0dU6.PEISralj5YFSC', NULL, 'finance', '2026-02-17 20:11:01', '2026-02-17 20:11:01'),
(10, 'yosi', 'yosi@stih.ac.id', NULL, '$2y$12$71MNSwZlNFsdkKZ8zBdVROzhPjZpZd52FuF5AcQRNqDScDjAM5EzO', NULL, 'dosen', '2026-02-17 23:33:51', '2026-02-17 23:33:51'),
(11, 'Rifqi', 'rifqi@stih.ac.id', NULL, '$2y$12$RHCEP3j7cSJddULT23mW8uetbjMLmsLJcPwEnmA2Trb3zMUaFSYOO', NULL, 'mahasiswa', '2026-02-18 02:08:51', '2026-02-18 02:08:51'),
(12, 'Bagus', 'bagus@stih.ac.id', NULL, '$2y$12$3k99f47uOGTZckIzigbB6.gNjh3fUSAt3vazD8PMSfMTfCGDFgZgG', NULL, 'mahasiswa', '2026-02-18 02:53:49', '2026-02-18 02:53:49'),
(13, 'Pratama', 'pratama@stih.ac.id', NULL, '$2y$12$GsebPXsKEEX3XtkeNQetc.4m9/1w59oVXELnCo.pkLykcp48fPW3.', NULL, 'mahasiswa', '2026-02-18 02:54:28', '2026-02-18 02:54:28'),
(14, 'test1', 'test1@stih.ac.id', NULL, '$2y$12$meF0qu21y91kDeNpMNyrRuRopyTE0uyQXO2JB2yk.6WVNj9Bo3SK2', NULL, 'mahasiswa', '2026-02-18 19:37:29', '2026-02-18 19:37:29'),
(15, 'test2', 'test2@stih.ac.id', NULL, '$2y$12$NL7o9fiDXHY/pyJA.9EOGu5eruexpPsVjWacyAgcXioELR.axE7Zi', NULL, 'mahasiswa', '2026-02-18 19:38:00', '2026-02-18 19:38:00'),
(16, 'test3', 'test3@stih.ac.id', NULL, '$2y$12$jF0ocTVJfgciu809knHoQeLDxSeKS1tSZrgbrZQcu1lCl54U8.lby', NULL, 'mahasiswa', '2026-02-18 19:38:30', '2026-02-18 19:38:30'),
(17, 'test4', 'test4@stih.ac.id', NULL, '$2y$12$QUs5NAPB/hNv2aPWUiLPmOjPlKih0AK/PUhUO.6jn1biwLfNqniiy', NULL, 'mahasiswa', '2026-02-18 19:39:00', '2026-02-18 19:39:00'),
(18, 'test5', 'test5@stih.ac.id', NULL, '$2y$12$J0SpBesxfLzD/ldmB/ZQse5SiNtRr25q7N6b759u6kEWjvJYibfXq', NULL, 'mahasiswa', '2026-02-18 19:39:31', '2026-02-18 19:39:31'),
(19, 'test6', 'test6@stih.ac.id', NULL, '$2y$12$WM1U/ulFB8.F7REhuOVZxOpr3nxL.pV3IdzkGghzYDAKxzFM7oWbK', NULL, 'mahasiswa', '2026-02-18 19:39:57', '2026-02-18 19:39:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_events`
--
ALTER TABLE `academic_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `academic_events_semester_id_foreign` (`semester_id`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_nip_unique` (`nip`),
  ADD KEY `admins_user_id_foreign` (`user_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_actor_id_index` (`actor_id`),
  ADD KEY `audit_logs_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  ADD KEY `audit_logs_action_index` (`action`);

--
-- Indexes for table `bobot_penilaian`
--
ALTER TABLE `bobot_penilaian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bobot_penilaian_kelas_id_unique` (`kelas_id`),
  ADD KEY `bobot_penilaian_locked_by_foreign` (`locked_by`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `dokumen_kelas`
--
ALTER TABLE `dokumen_kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dokumen_kelas_kelas_id_tipe_dokumen_unique` (`kelas_id`,`tipe_dokumen`),
  ADD KEY `dokumen_kelas_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `dosens`
--
ALTER TABLE `dosens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dosens_nidn_unique` (`nidn`),
  ADD KEY `dosens_user_id_foreign` (`user_id`);

--
-- Indexes for table `dosen_attendances`
--
ALTER TABLE `dosen_attendances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dosen_attendance_unique` (`dosen_id`,`pertemuan_id`),
  ADD KEY `dosen_attendances_kelas_mata_kuliah_id_foreign` (`kelas_mata_kuliah_id`),
  ADD KEY `dosen_attendances_pertemuan_id_foreign` (`pertemuan_id`);

--
-- Indexes for table `dosen_availabilities`
--
ALTER TABLE `dosen_availabilities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_dosen_slot` (`dosen_id`,`semester_id`,`hari`,`jam_perkuliahan_id`),
  ADD KEY `dosen_availabilities_semester_id_foreign` (`semester_id`),
  ADD KEY `dosen_availabilities_jam_perkuliahan_id_foreign` (`jam_perkuliahan_id`);

--
-- Indexes for table `dosen_availability_checks`
--
ALTER TABLE `dosen_availability_checks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dosen_availability_checks_dosen_id_foreign` (`dosen_id`),
  ADD KEY `dosen_availability_checks_mata_kuliah_id_foreign` (`mata_kuliah_id`);

--
-- Indexes for table `dosen_pa`
--
ALTER TABLE `dosen_pa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dosen_pa_mahasiswa_id_unique` (`mahasiswa_id`),
  ADD KEY `dosen_pa_dosen_id_foreign` (`dosen_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fakultas`
--
ALTER TABLE `fakultas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fakultas_kode_fakultas_unique` (`kode_fakultas`);

--
-- Indexes for table `import_logs`
--
ALTER TABLE `import_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `import_logs_user_id_foreign` (`user_id`),
  ADD KEY `import_logs_type_index` (`type`),
  ADD KEY `import_logs_created_at_index` (`created_at`);

--
-- Indexes for table `installments`
--
ALTER TABLE `installments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `installments_invoice_id_installment_no_unique` (`invoice_id`,`installment_no`),
  ADD KEY `installments_invoice_id_status_index` (`invoice_id`,`status`);

--
-- Indexes for table `installment_requests`
--
ALTER TABLE `installment_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `installment_requests_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `installment_requests_invoice_id_status_index` (`invoice_id`,`status`),
  ADD KEY `installment_requests_status_index` (`status`),
  ADD KEY `installment_requests_student_id_foreign` (`student_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_created_by_foreign` (`created_by`),
  ADD KEY `invoices_student_id_status_index` (`student_id`,`status`),
  ADD KEY `invoices_tahun_ajaran_index` (`tahun_ajaran`);

--
-- Indexes for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwals_kelas_id_foreign` (`kelas_id`),
  ADD KEY `jadwals_approved_by_foreign` (`approved_by`),
  ADD KEY `jadwals_ruangan_id_index` (`ruangan_id`);

--
-- Indexes for table `jadwal_approvals`
--
ALTER TABLE `jadwal_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwal_approvals_jadwal_proposal_id_role_index` (`jadwal_proposal_id`,`role`),
  ADD KEY `jadwal_approvals_approved_by_action_index` (`approved_by`,`action`);

--
-- Indexes for table `jadwal_exceptions`
--
ALTER TABLE `jadwal_exceptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwal_exceptions_jadwal_id_foreign` (`jadwal_id`);

--
-- Indexes for table `jadwal_generate_logs`
--
ALTER TABLE `jadwal_generate_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwal_generate_logs_user_id_foreign` (`user_id`),
  ADD KEY `jadwal_generate_logs_created_at_index` (`created_at`);

--
-- Indexes for table `jadwal_proposals`
--
ALTER TABLE `jadwal_proposals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwal_proposals_kelas_id_foreign` (`kelas_id`),
  ADD KEY `jadwal_proposals_dosen_id_foreign` (`dosen_id`),
  ADD KEY `jadwal_proposals_generated_by_foreign` (`generated_by`),
  ADD KEY `jadwal_proposals_status_dosen_id_index` (`status`,`dosen_id`),
  ADD KEY `jadwal_proposals_mata_kuliah_id_hari_jam_mulai_index` (`mata_kuliah_id`,`hari`,`jam_mulai`),
  ADD KEY `jadwal_proposals_ruangan_id_index` (`ruangan_id`);

--
-- Indexes for table `jadwal_reschedules`
--
ALTER TABLE `jadwal_reschedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwal_reschedules_jadwal_id_foreign` (`jadwal_id`),
  ADD KEY `jadwal_reschedules_dosen_id_foreign` (`dosen_id`);

--
-- Indexes for table `jam_perkuliahan`
--
ALTER TABLE `jam_perkuliahan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jam_perkuliahan_jam_ke_unique` (`jam_ke`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  ADD KEY `kelas_dosen_id_foreign` (`dosen_id`);

--
-- Indexes for table `kelas_mata_kuliahs`
--
ALTER TABLE `kelas_mata_kuliahs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kelas_mata_kuliahs_qr_token_unique` (`qr_token`),
  ADD KEY `kelas_mata_kuliahs_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  ADD KEY `kelas_mata_kuliahs_dosen_id_foreign` (`dosen_id`),
  ADD KEY `kelas_mata_kuliahs_semester_id_foreign` (`semester_id`),
  ADD KEY `kelas_mata_kuliahs_ruangan_id_index` (`ruangan_id`);

--
-- Indexes for table `kelas_reschedules`
--
ALTER TABLE `kelas_reschedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_reschedules_kelas_mata_kuliah_id_foreign` (`kelas_mata_kuliah_id`),
  ADD KEY `kelas_reschedules_dosen_id_foreign` (`dosen_id`),
  ADD KEY `kelas_reschedules_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `krs`
--
ALTER TABLE `krs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `krs_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `krs_kelas_id_foreign` (`kelas_id`),
  ADD KEY `krs_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  ADD KEY `krs_kelas_mata_kuliah_id_foreign` (`kelas_mata_kuliah_id`);

--
-- Indexes for table `kuesioner_aktivasi`
--
ALTER TABLE `kuesioner_aktivasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kuesioner_aktivasi_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `kuesioner_aktivasi_semester_id_foreign` (`semester_id`);

--
-- Indexes for table `kuesioner_mahasiswa_baru`
--
ALTER TABLE `kuesioner_mahasiswa_baru`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kuesioner_mahasiswa_baru_mahasiswa_id_index` (`mahasiswa_id`);

--
-- Indexes for table `mahasiswas`
--
ALTER TABLE `mahasiswas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mahasiswas_nim_unique` (`nim`),
  ADD KEY `mahasiswas_user_id_foreign` (`user_id`),
  ADD KEY `mahasiswas_last_semester_id_foreign` (`last_semester_id`);

--
-- Indexes for table `mata_kuliahs`
--
ALTER TABLE `mata_kuliahs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mata_kuliahs_kode_mk_unique` (`kode_mk`),
  ADD KEY `mata_kuliahs_kode_id_index` (`kode_id`),
  ADD KEY `mata_kuliahs_prodi_id_foreign` (`prodi_id`),
  ADD KEY `mata_kuliahs_fakultas_id_foreign` (`fakultas_id`);

--
-- Indexes for table `materis`
--
ALTER TABLE `materis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `materis_mata_kuliah_id_pertemuan_index` (`mata_kuliah_id`,`pertemuan`),
  ADD KEY `materis_dosen_id_index` (`dosen_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nilai_krs_id_foreign` (`krs_id`),
  ADD KEY `nilai_kelas_id_foreign` (`kelas_id`),
  ADD KEY `nilai_published_by_foreign` (`published_by`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parents_user_id_foreign` (`user_id`),
  ADD KEY `parents_mahasiswa_id_foreign` (`mahasiswa_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_proof_id_unique` (`proof_id`),
  ADD KEY `payments_approved_by_foreign` (`approved_by`),
  ADD KEY `payments_invoice_id_paid_date_index` (`invoice_id`,`paid_date`),
  ADD KEY `payments_installment_id_index` (`installment_id`);

--
-- Indexes for table `payment_proofs`
--
ALTER TABLE `payment_proofs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_proofs_uploaded_by_foreign` (`uploaded_by`),
  ADD KEY `payment_proofs_approved_by_foreign` (`approved_by`),
  ADD KEY `payment_proofs_status_index` (`status`),
  ADD KEY `payment_proofs_installment_id_status_index` (`installment_id`,`status`),
  ADD KEY `payment_proofs_invoice_id_status_index` (`invoice_id`,`status`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembayaran_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `pembayaran_semester_id_foreign` (`semester_id`);

--
-- Indexes for table `pengajuans`
--
ALTER TABLE `pengajuans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengajuans_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `pengajuans_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `pengumumans`
--
ALTER TABLE `pengumumans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pertemuans`
--
ALTER TABLE `pertemuans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pertemuans_kelas_mata_kuliah_id_nomor_pertemuan_unique` (`kelas_mata_kuliah_id`,`nomor_pertemuan`),
  ADD UNIQUE KEY `pertemuans_qr_token_unique` (`qr_token`),
  ADD KEY `pertemuans_kelas_mata_kuliah_id_status_index` (`kelas_mata_kuliah_id`,`status`),
  ADD KEY `pertemuans_qr_token_index` (`qr_token`);

--
-- Indexes for table `presensis`
--
ALTER TABLE `presensis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `presensis_krs_id_foreign` (`krs_id`),
  ADD KEY `presensis_kelas_mata_kuliah_id_foreign` (`kelas_mata_kuliah_id`),
  ADD KEY `presensis_pertemuan_id_foreign` (`pertemuan_id`),
  ADD KEY `presensis_mahasiswa_id_pertemuan_id_index` (`mahasiswa_id`,`pertemuan_id`),
  ADD KEY `presensis_pertemuan_index` (`pertemuan`);

--
-- Indexes for table `prodis`
--
ALTER TABLE `prodis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prodis_kode_prodi_unique` (`kode_prodi`),
  ADD KEY `prodis_fakultas_id_foreign` (`fakultas_id`);

--
-- Indexes for table `religions`
--
ALTER TABLE `religions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `religions_code_index` (`code`);

--
-- Indexes for table `ruangans`
--
ALTER TABLE `ruangans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ruangans_kode_ruangan_unique` (`kode_ruangan`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `semesters_nama_tahun_tanggal_unique` (`nama_semester`,`tahun_ajaran`,`tanggal_mulai`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_npm_unique` (`npm`),
  ADD KEY `students_user_id_foreign` (`user_id`),
  ADD KEY `students_npm_index` (`npm`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_settings_key_unique` (`key`);

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tugas_kelas_id_pertemuan_index` (`kelas_id`,`pertemuan`),
  ADD KEY `tugas_mata_kuliah_id_index` (`mata_kuliah_id`);

--
-- Indexes for table `tugas_submissions`
--
ALTER TABLE `tugas_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tugas_submissions_tugas_id_mahasiswa_id_index` (`tugas_id`,`mahasiswa_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_events`
--
ALTER TABLE `academic_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bobot_penilaian`
--
ALTER TABLE `bobot_penilaian`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dokumen_kelas`
--
ALTER TABLE `dokumen_kelas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `dosens`
--
ALTER TABLE `dosens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dosen_attendances`
--
ALTER TABLE `dosen_attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dosen_availabilities`
--
ALTER TABLE `dosen_availabilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dosen_availability_checks`
--
ALTER TABLE `dosen_availability_checks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dosen_pa`
--
ALTER TABLE `dosen_pa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fakultas`
--
ALTER TABLE `fakultas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `import_logs`
--
ALTER TABLE `import_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `installments`
--
ALTER TABLE `installments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `installment_requests`
--
ALTER TABLE `installment_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jadwals`
--
ALTER TABLE `jadwals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `jadwal_approvals`
--
ALTER TABLE `jadwal_approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `jadwal_exceptions`
--
ALTER TABLE `jadwal_exceptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_generate_logs`
--
ALTER TABLE `jadwal_generate_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jadwal_proposals`
--
ALTER TABLE `jadwal_proposals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `jadwal_reschedules`
--
ALTER TABLE `jadwal_reschedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jam_perkuliahan`
--
ALTER TABLE `jam_perkuliahan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `kelas_mata_kuliahs`
--
ALTER TABLE `kelas_mata_kuliahs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `kelas_reschedules`
--
ALTER TABLE `kelas_reschedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `krs`
--
ALTER TABLE `krs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `kuesioner_aktivasi`
--
ALTER TABLE `kuesioner_aktivasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kuesioner_mahasiswa_baru`
--
ALTER TABLE `kuesioner_mahasiswa_baru`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mahasiswas`
--
ALTER TABLE `mahasiswas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `mata_kuliahs`
--
ALTER TABLE `mata_kuliahs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `materis`
--
ALTER TABLE `materis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment_proofs`
--
ALTER TABLE `payment_proofs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengajuans`
--
ALTER TABLE `pengajuans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pengumumans`
--
ALTER TABLE `pengumumans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pertemuans`
--
ALTER TABLE `pertemuans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `presensis`
--
ALTER TABLE `presensis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `prodis`
--
ALTER TABLE `prodis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `religions`
--
ALTER TABLE `religions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ruangans`
--
ALTER TABLE `ruangans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tugas_submissions`
--
ALTER TABLE `tugas_submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `academic_events`
--
ALTER TABLE `academic_events`
  ADD CONSTRAINT `academic_events_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_actor_id_foreign` FOREIGN KEY (`actor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `bobot_penilaian`
--
ALTER TABLE `bobot_penilaian`
  ADD CONSTRAINT `bobot_penilaian_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bobot_penilaian_locked_by_foreign` FOREIGN KEY (`locked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `dokumen_kelas`
--
ALTER TABLE `dokumen_kelas`
  ADD CONSTRAINT `dokumen_kelas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dokumen_kelas_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dosens`
--
ALTER TABLE `dosens`
  ADD CONSTRAINT `dosens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dosen_attendances`
--
ALTER TABLE `dosen_attendances`
  ADD CONSTRAINT `dosen_attendances_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dosen_attendances_kelas_mata_kuliah_id_foreign` FOREIGN KEY (`kelas_mata_kuliah_id`) REFERENCES `kelas_mata_kuliahs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dosen_attendances_pertemuan_id_foreign` FOREIGN KEY (`pertemuan_id`) REFERENCES `pertemuans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dosen_availabilities`
--
ALTER TABLE `dosen_availabilities`
  ADD CONSTRAINT `dosen_availabilities_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dosen_availabilities_jam_perkuliahan_id_foreign` FOREIGN KEY (`jam_perkuliahan_id`) REFERENCES `jam_perkuliahan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dosen_availabilities_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dosen_availability_checks`
--
ALTER TABLE `dosen_availability_checks`
  ADD CONSTRAINT `dosen_availability_checks_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dosen_availability_checks_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dosen_pa`
--
ALTER TABLE `dosen_pa`
  ADD CONSTRAINT `dosen_pa_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dosen_pa_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `import_logs`
--
ALTER TABLE `import_logs`
  ADD CONSTRAINT `import_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `installments`
--
ALTER TABLE `installments`
  ADD CONSTRAINT `installments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `installment_requests`
--
ALTER TABLE `installment_requests`
  ADD CONSTRAINT `installment_requests_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `installment_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `installment_requests_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `invoices_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD CONSTRAINT `jadwals_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `jadwals_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwals_ruangan_id_foreign` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangans` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `jadwal_approvals`
--
ALTER TABLE `jadwal_approvals`
  ADD CONSTRAINT `jadwal_approvals_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `jadwal_approvals_jadwal_proposal_id_foreign` FOREIGN KEY (`jadwal_proposal_id`) REFERENCES `jadwal_proposals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal_exceptions`
--
ALTER TABLE `jadwal_exceptions`
  ADD CONSTRAINT `jadwal_exceptions_jadwal_id_foreign` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal_generate_logs`
--
ALTER TABLE `jadwal_generate_logs`
  ADD CONSTRAINT `jadwal_generate_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal_proposals`
--
ALTER TABLE `jadwal_proposals`
  ADD CONSTRAINT `jadwal_proposals_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_proposals_generated_by_foreign` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `jadwal_proposals_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_proposals_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_proposals_ruangan_id_foreign` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangans` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `jadwal_reschedules`
--
ALTER TABLE `jadwal_reschedules`
  ADD CONSTRAINT `jadwal_reschedules_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_reschedules_jadwal_id_foreign` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelas_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kelas_mata_kuliahs`
--
ALTER TABLE `kelas_mata_kuliahs`
  ADD CONSTRAINT `kelas_mata_kuliahs_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelas_mata_kuliahs_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelas_mata_kuliahs_ruangan_id_foreign` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kelas_mata_kuliahs_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kelas_reschedules`
--
ALTER TABLE `kelas_reschedules`
  ADD CONSTRAINT `kelas_reschedules_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kelas_reschedules_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelas_reschedules_kelas_mata_kuliah_id_foreign` FOREIGN KEY (`kelas_mata_kuliah_id`) REFERENCES `kelas_mata_kuliahs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `krs`
--
ALTER TABLE `krs`
  ADD CONSTRAINT `krs_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `krs_kelas_mata_kuliah_id_foreign` FOREIGN KEY (`kelas_mata_kuliah_id`) REFERENCES `kelas_mata_kuliahs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `krs_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `krs_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kuesioner_aktivasi`
--
ALTER TABLE `kuesioner_aktivasi`
  ADD CONSTRAINT `kuesioner_aktivasi_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kuesioner_aktivasi_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `kuesioner_mahasiswa_baru`
--
ALTER TABLE `kuesioner_mahasiswa_baru`
  ADD CONSTRAINT `kuesioner_mahasiswa_baru_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mahasiswas`
--
ALTER TABLE `mahasiswas`
  ADD CONSTRAINT `mahasiswas_last_semester_id_foreign` FOREIGN KEY (`last_semester_id`) REFERENCES `semesters` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `mahasiswas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mata_kuliahs`
--
ALTER TABLE `mata_kuliahs`
  ADD CONSTRAINT `mata_kuliahs_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`),
  ADD CONSTRAINT `mata_kuliahs_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodis` (`id`);

--
-- Constraints for table `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `nilai_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_krs_id_foreign` FOREIGN KEY (`krs_id`) REFERENCES `krs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_published_by_foreign` FOREIGN KEY (`published_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `parents`
--
ALTER TABLE `parents`
  ADD CONSTRAINT `parents_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `parents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payments_installment_id_foreign` FOREIGN KEY (`installment_id`) REFERENCES `installments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_proof_id_foreign` FOREIGN KEY (`proof_id`) REFERENCES `payment_proofs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_proofs`
--
ALTER TABLE `payment_proofs`
  ADD CONSTRAINT `payment_proofs_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payment_proofs_installment_id_foreign` FOREIGN KEY (`installment_id`) REFERENCES `installments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_proofs_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_proofs_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayaran_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pengajuans`
--
ALTER TABLE `pengajuans`
  ADD CONSTRAINT `pengajuans_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pengajuans_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pertemuans`
--
ALTER TABLE `pertemuans`
  ADD CONSTRAINT `pertemuans_kelas_mata_kuliah_id_foreign` FOREIGN KEY (`kelas_mata_kuliah_id`) REFERENCES `kelas_mata_kuliahs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `presensis`
--
ALTER TABLE `presensis`
  ADD CONSTRAINT `presensis_kelas_mata_kuliah_id_foreign` FOREIGN KEY (`kelas_mata_kuliah_id`) REFERENCES `kelas_mata_kuliahs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `presensis_krs_id_foreign` FOREIGN KEY (`krs_id`) REFERENCES `krs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `presensis_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `presensis_pertemuan_id_foreign` FOREIGN KEY (`pertemuan_id`) REFERENCES `pertemuans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prodis`
--
ALTER TABLE `prodis`
  ADD CONSTRAINT `prodis_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
