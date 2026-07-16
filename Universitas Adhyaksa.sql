-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 10, 2026 at 12:05 PM
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
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academic_events`
--

INSERT INTO `academic_events` (`id`, `title`, `description`, `event_type`, `start_date`, `end_date`, `semester_id`, `color`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(51, 'Bimbingan akademik', 'Imported from PDF', 'lainnya', '2025-09-15', '2025-09-17', 1, '#6b7280', 1, 1, 1, '2026-05-25 02:30:55', '2026-05-25 02:30:55'),
(52, 'KRS online mahasiswa', 'Imported from PDF', 'krs', '2025-09-15', '2025-09-17', 1, '#10b981', 1, 1, 1, '2026-05-25 02:30:55', '2026-05-25 02:30:55'),
(53, 'Batas waktu pengajuan Judul Skripsi', 'Imported from PDF', 'lainnya', '2025-09-18', '2025-09-19', 1, '#6b7280', 1, 1, 1, '2026-05-25 02:30:55', '2026-05-25 02:30:55'),
(54, 'Perkenalan Kehidupan Kampus Bagi Mahasiswa Baru (PKKMB) dan Pelantikan Mahasiswa baru', 'Imported from PDF', 'lainnya', '2025-10-02', '2025-10-03', 1, '#6b7280', 1, 1, 1, '2026-05-25 02:30:55', '2026-05-25 02:30:55'),
(55, 'Awal Perkuliahaan', 'Imported from PDF', 'perkuliahan', '2025-10-06', '2025-10-06', 1, '#3b82f6', 1, 1, 1, '2026-05-25 02:30:55', '2026-05-25 02:30:55'),
(56, 'Masa Perkuliahaan efektif sebelum UTS (7x pertemuan)', 'Imported from PDF', 'uts', '2025-10-06', '2025-11-21', 1, '#f59e0b', 1, 1, 1, '2026-05-25 02:30:55', '2026-05-25 02:30:55'),
(57, 'Ujian Tengah Semester (UTS)', 'Imported from PDF', 'uts', '2025-11-24', '2025-11-28', 1, '#f59e0b', 1, 1, 1, '2026-05-25 02:30:55', '2026-05-25 02:30:55'),
(58, 'Masa Perkuliahaan efektif setelah UTS (7x pertemuan)', 'Imported from PDF', 'uts', '2025-12-01', '2026-02-06', 1, '#f59e0b', 1, 1, 1, '2026-05-25 02:30:55', '2026-05-25 02:30:55'),
(59, 'Libur Hari Raya Natal dan Tahun Baru 2025/2026', 'Imported from PDF', 'libur_akademik', '2025-12-22', '2026-01-06', 1, '#ef4444', 1, 1, 1, '2026-05-25 02:30:55', '2026-05-25 02:30:55'),
(60, 'Perkuliahaan Setelah Libur Hari Raya Natal dan Tahun Baru 2025/2026', 'Imported from PDF', 'libur_akademik', '2026-01-09', '2026-02-13', 1, '#ef4444', 1, 1, 1, '2026-05-25 02:30:55', '2026-05-25 02:30:55'),
(61, 'Masa Pendaftaran Sidang Skripsi', 'Imported from PDF', 'lainnya', '2025-11-24', '2025-02-13', 1, '#6b7280', 1, 1, 1, '2026-05-25 02:30:55', '2026-05-25 02:30:55'),
(62, 'Ujian Akhir Semester (UAS)', 'Imported from PDF', 'uas', '2026-02-09', '2026-02-13', 1, '#d97706', 1, 1, 1, '2026-05-25 02:30:55', '2026-05-25 02:30:55'),
(63, 'Penginputan Nilai Mahasiswa', 'Imported from PDF', 'lainnya', '2026-02-16', '2026-02-20', 1, '#6b7280', 1, 1, 1, '2026-05-25 02:30:55', '2026-05-25 02:30:55'),
(64, 'Cetak Kartu Hasil Studi (KHS)', 'Imported from PDF', 'lainnya', '2026-02-27', '2026-02-27', 1, '#6b7280', 1, 1, 1, '2026-05-25 02:30:55', '2026-05-25 02:30:55'),
(65, 'Bimbingan akademik', 'Imported from PDF', 'lainnya', '2026-03-09', '2026-03-11', 2, '#6b7280', 1, 1, 1, '2026-05-25 02:31:12', '2026-05-25 02:31:12'),
(66, 'KRS online mahasiswa', 'Imported from PDF', 'krs', '2026-03-09', '2026-03-11', 2, '#10b981', 1, 1, 1, '2026-05-25 02:31:12', '2026-05-25 02:31:12'),
(67, 'Libur Idul Fitri', 'Imported from PDF', 'libur_akademik', '2026-03-16', '2026-03-27', 2, '#ef4444', 1, 1, 1, '2026-05-25 02:31:12', '2026-05-25 02:31:12'),
(68, 'Awal Perkuliahaan', 'Imported from PDF', 'perkuliahan', '2026-03-30', '2026-03-30', 2, '#3b82f6', 1, 1, 1, '2026-05-25 02:31:12', '2026-05-25 02:31:12'),
(69, 'Masa Perkuliahaan efektif sebelum UTS (7x pertemuan)', 'Imported from PDF', 'uts', '2026-03-30', '2026-05-15', 2, '#f59e0b', 1, 1, 1, '2026-05-25 02:31:12', '2026-05-25 02:31:12'),
(70, 'Ujian Tengah Semester (UTS)', 'Imported from PDF', 'uts', '2026-05-18', '2026-05-22', 2, '#f59e0b', 1, 1, 1, '2026-05-25 02:31:12', '2026-05-25 02:31:12'),
(71, 'Libur Idul Adha', 'Imported from PDF', 'libur_akademik', '2026-05-27', '2026-05-27', 2, '#ef4444', 1, 1, 1, '2026-05-25 02:31:12', '2026-05-25 02:31:12'),
(72, 'Masa Perkuliahaan efektif setelah UTS (7x pertemuan)', 'Imported from PDF', 'uts', '2026-05-25', '2026-07-10', 2, '#f59e0b', 1, 1, 1, '2026-05-25 02:31:12', '2026-05-25 02:31:12'),
(73, 'Ujian Akhir Semester (UAS)', 'Imported from PDF', 'uas', '2026-07-13', '2026-07-17', 2, '#d97706', 1, 1, 1, '2026-05-25 02:31:12', '2026-05-25 02:31:12'),
(74, 'Penginputan Nilai Mahasiswa', 'Imported from PDF', 'lainnya', '2026-07-20', '2026-07-24', 2, '#6b7280', 1, 1, 1, '2026-05-25 02:31:12', '2026-05-25 02:31:12'),
(75, 'Cetak Kartu Hasil Studi (KHS)', 'Imported from PDF', 'lainnya', '2026-07-27', '2026-07-30', 2, '#6b7280', 1, 1, 1, '2026-05-25 02:31:12', '2026-05-25 02:31:12');

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
(1, 1, '198501012010011001', '081234567890', 'Jl. Kampus STIH No. 1', '2026-05-21 09:13:22', '2026-05-21 09:13:22');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `actor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `actor_role` varchar(50) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `module` varchar(100) DEFAULT NULL COMMENT 'Feature module: akademik, keuangan, magang, skripsi, wisuda, system, auth',
  `auditable_type` varchar(255) NOT NULL,
  `auditable_id` bigint(20) UNSIGNED NOT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `before` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`before`)),
  `after` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`after`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `session_id` varchar(191) DEFAULT NULL COMMENT 'Laravel session ID for correlating events within one login session',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `actor_id`, `actor_role`, `action`, `module`, `auditable_type`, `auditable_id`, `meta`, `before`, `after`, `ip_address`, `user_agent`, `session_id`, `created_at`) VALUES
(1, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(2, 1, 'admin', 'user.created', 'system', 'App\\Models\\User', 12, NULL, NULL, '{\"name\":\"Dr. R Muhamad Ibnu Mazjah, S.H., M.H.\",\"email\":\"417017906@stihadhyaksa.ac.id\",\"role\":\"dosen\",\"id\":12}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(3, 1, 'admin', 'dosen.created', 'system', 'App\\Models\\Dosen', 4, NULL, NULL, '{\"user_id\":12,\"nidn\":\"417017906\",\"pendidikan\":\"S3\",\"prodi\":[\"ilmu hukum\"],\"phone\":\"\",\"address\":\"\",\"status\":\"aktif\",\"pendidikan_terakhir\":[\"S1\",\"S2\",\"S3\"],\"universitas\":[\"Universitas Pancasila\",\"Magister Ilmu Hukum Universitas Trisakti\",\"Doktor Ilmu Hukum Universitas Airlangga\"],\"dosen_tetap\":true,\"jabatan_fungsional\":[\"Lektor\"],\"id\":4}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(4, 1, 'admin', 'user.created', 'system', 'App\\Models\\User', 13, NULL, NULL, '{\"name\":\"Dr. Armansyah, S.H., M.H.\",\"email\":\"301067501@stihadhyaksa.ac.id\",\"role\":\"dosen\",\"id\":13}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(5, 1, 'admin', 'dosen.created', 'system', 'App\\Models\\Dosen', 5, NULL, NULL, '{\"user_id\":13,\"nidn\":\"301067501\",\"pendidikan\":\"S3\",\"prodi\":[\"ilmu hukum\"],\"phone\":\"\",\"address\":\"\",\"status\":\"aktif\",\"pendidikan_terakhir\":[\"S1\",\"S2\",\"S3\"],\"universitas\":[\"Universitas Muhammadiyah Jakarta\",\"Universitas Muhammadiyah Jakarta\",\"Universitas Islam Bandung\"],\"dosen_tetap\":true,\"jabatan_fungsional\":[\"Lektor\"],\"id\":5}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(6, 1, 'admin', 'user.created', 'system', 'App\\Models\\User', 14, NULL, NULL, '{\"name\":\"Dr. Mukhlis, S.H., M.H.\",\"email\":\"3146747648130140@stihadhyaksa.ac.id\",\"role\":\"dosen\",\"id\":14}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(7, 1, 'admin', 'dosen.created', 'system', 'App\\Models\\Dosen', 6, NULL, NULL, '{\"user_id\":14,\"nidn\":\"3146747648130140\",\"pendidikan\":\"S3\",\"prodi\":[\"ilmu hukum\"],\"phone\":\"\",\"address\":\"\",\"status\":\"aktif\",\"pendidikan_terakhir\":[\"S1\",\"S2\",\"S3\"],\"universitas\":[\"Universitas Andalas\",\"Universitas Andalas\",\"Universitas Jayabaya\"],\"dosen_tetap\":false,\"jabatan_fungsional\":[\"Lektor\"],\"id\":6}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(8, 1, 'admin', 'user.created', 'system', 'App\\Models\\User', 15, NULL, NULL, '{\"name\":\"Dr. Joko Cahyono, SH., MH.\",\"email\":\"714076601@stihadhyaksa.ac.id\",\"role\":\"dosen\",\"id\":15}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(9, 1, 'admin', 'dosen.created', 'system', 'App\\Models\\Dosen', 7, NULL, NULL, '{\"user_id\":15,\"nidn\":\"714076601\",\"pendidikan\":\"S3\",\"prodi\":[\"ilmu hukum\"],\"phone\":\"\",\"address\":\"\",\"status\":\"aktif\",\"pendidikan_terakhir\":[\"S1\",\"S2\",\"S3\"],\"universitas\":[\"Universitas Bhayangkara Surabaya\",\"Universitas Airlangga\",\"Universitas Brawijaya\"],\"dosen_tetap\":true,\"jabatan_fungsional\":[\"Lektor\"],\"id\":7}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(10, 1, 'admin', 'user.created', 'system', 'App\\Models\\User', 16, NULL, NULL, '{\"name\":\"Sandi Yudha Prayoga, S.H., M.H.\",\"email\":\"302129701@stihadhyaksa.ac.id\",\"role\":\"dosen\",\"id\":16}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(11, 1, 'admin', 'dosen.created', 'system', 'App\\Models\\Dosen', 8, NULL, NULL, '{\"user_id\":16,\"nidn\":\"302129701\",\"pendidikan\":\"S2\",\"prodi\":[\"ilmu hukum\"],\"phone\":\"\",\"address\":\"\",\"status\":\"aktif\",\"pendidikan_terakhir\":[\"S1\",\"S2\"],\"universitas\":[\"Universitas Pancasila\",\"Magister Ilmu Hukum Universitas Indonesia\"],\"dosen_tetap\":true,\"jabatan_fungsional\":[\"Lektor\"],\"id\":8}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(12, 1, 'admin', 'user.created', 'system', 'App\\Models\\User', 17, NULL, NULL, '{\"name\":\"Adilla Meytiara Intan, S.H., LL.M.\",\"email\":\"302059501@stihadhyaksa.ac.id\",\"role\":\"dosen\",\"id\":17}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(13, 1, 'admin', 'dosen.created', 'system', 'App\\Models\\Dosen', 9, NULL, NULL, '{\"user_id\":17,\"nidn\":\"302059501\",\"pendidikan\":\"S2\",\"prodi\":[\"ilmu hukum\"],\"phone\":\"\",\"address\":\"\",\"status\":\"aktif\",\"pendidikan_terakhir\":[\"S1\",\"S2\"],\"universitas\":[\"Universitas Indonesia\",\"Master of Laws Lancaster University\"],\"dosen_tetap\":true,\"jabatan_fungsional\":[\"Lektor\"],\"id\":9}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(14, 1, 'admin', 'user.created', 'system', 'App\\Models\\User', 18, NULL, NULL, '{\"name\":\"Adery Ardhan Saputro, S.H., LL.M.\",\"email\":\"313089202@stihadhyaksa.ac.id\",\"role\":\"dosen\",\"id\":18}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(15, 1, 'admin', 'dosen.created', 'system', 'App\\Models\\Dosen', 10, NULL, NULL, '{\"user_id\":18,\"nidn\":\"313089202\",\"pendidikan\":\"S2\",\"prodi\":[\"ilmu hukum\"],\"phone\":\"\",\"address\":\"\",\"status\":\"aktif\",\"pendidikan_terakhir\":[\"S1\",\"S2\"],\"universitas\":[\"Universitas Indonesia\",\"Master of Laws Vrije Universiteit Amsterdam\"],\"dosen_tetap\":true,\"jabatan_fungsional\":[\"Lektor\"],\"id\":10}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(16, 1, 'admin', 'user.created', 'system', 'App\\Models\\User', 19, NULL, NULL, '{\"name\":\"Dio Ashar Wicaksana, S.H., M.A.\",\"email\":\"307089005@stihadhyaksa.ac.id\",\"role\":\"dosen\",\"id\":19}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(17, 1, 'admin', 'dosen.created', 'system', 'App\\Models\\Dosen', 11, NULL, NULL, '{\"user_id\":19,\"nidn\":\"307089005\",\"pendidikan\":\"S2\",\"prodi\":[\"ilmu hukum\"],\"phone\":\"\",\"address\":\"\",\"status\":\"aktif\",\"pendidikan_terakhir\":[\"S1\",\"S2\"],\"universitas\":[\"Universitas Indonesia\",\"Master of Laws University of Basque Country\"],\"dosen_tetap\":true,\"jabatan_fungsional\":[\"Lektor\"],\"id\":11}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(18, 1, 'admin', 'user.created', 'system', 'App\\Models\\User', 20, NULL, NULL, '{\"name\":\"Prof. Dr. Bambang Sugeng Rukmono, S.H.,M.M., M.H.\",\"email\":\"8918290024@stihadhyaksa.ac.id\",\"role\":\"dosen\",\"id\":20}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(19, 1, 'admin', 'dosen.created', 'system', 'App\\Models\\Dosen', 12, NULL, NULL, '{\"user_id\":20,\"nidn\":\"8918290024\",\"pendidikan\":\"S3\",\"prodi\":[\"ilmu hukum\"],\"phone\":\"\",\"address\":\"\",\"status\":\"aktif\",\"pendidikan_terakhir\":[\"S1\",\"S2\",\"S3\"],\"universitas\":[\"Universitas Sebelas Maret\",\"Universitas Padjajaran\",\"Universitas Hasanuddin Makassar\"],\"dosen_tetap\":false,\"jabatan_fungsional\":[\"Tenaga Pengajar\"],\"id\":12}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(20, 1, 'admin', 'user.created', 'system', 'App\\Models\\User', 21, NULL, NULL, '{\"name\":\"Maydika Ramadani, S.H., M.H.\",\"email\":\"3860765666130310@stihadhyaksa.ac.id\",\"role\":\"dosen\",\"id\":21}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(21, 1, 'admin', 'dosen.created', 'system', 'App\\Models\\Dosen', 13, NULL, NULL, '{\"user_id\":21,\"nidn\":\"3860765666130310\",\"pendidikan\":\"S2\",\"prodi\":[\"ilmu hukum\"],\"phone\":\"\",\"address\":\"\",\"status\":\"aktif\",\"pendidikan_terakhir\":[\"S1\",\"S2\"],\"universitas\":[\"Universitas Muhammadiyah Aceh Banda Aceh\",\"Universitas Pembangunan Nasional Veteran Jakarta\"],\"dosen_tetap\":true,\"jabatan_fungsional\":[\"Tenaga Pengajar\"],\"id\":13}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(22, 1, 'admin', 'user.created', 'system', 'App\\Models\\User', 22, NULL, NULL, '{\"name\":\"Raul Gindo cahyono, S.H., M.H.\",\"email\":\"1956751652130120@stihadhyaksa.ac.id\",\"role\":\"dosen\",\"id\":22}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(23, 1, 'admin', 'dosen.created', 'system', 'App\\Models\\Dosen', 14, NULL, NULL, '{\"user_id\":22,\"nidn\":\"1956751652130120\",\"pendidikan\":\"S2\",\"prodi\":[\"ilmu hukum\"],\"phone\":\"\",\"address\":\"\",\"status\":\"aktif\",\"pendidikan_terakhir\":[\"S1\",\"S2\"],\"universitas\":[\"Universitas Islam Attahiriyah\",\"Universitas Pancasila\"],\"dosen_tetap\":true,\"jabatan_fungsional\":[\"Tenaga Pengajar\"],\"id\":14}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(24, 1, 'admin', 'user.created', 'system', 'App\\Models\\User', 23, NULL, NULL, '{\"name\":\"Muhammad Arbani, S.H., M.Kn.\",\"email\":\"3345774675130210@stihadhyaksa.ac.id\",\"role\":\"dosen\",\"id\":23}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(25, 1, 'admin', 'dosen.created', 'system', 'App\\Models\\Dosen', 15, NULL, NULL, '{\"user_id\":23,\"nidn\":\"3345774675130210\",\"pendidikan\":\"S2\",\"prodi\":[\"ilmu hukum\"],\"phone\":\"\",\"address\":\"\",\"status\":\"aktif\",\"pendidikan_terakhir\":[\"S1\",\"S2\"],\"universitas\":[\"Universitas Indonesia\",\"Universitas Indonesia\"],\"dosen_tetap\":true,\"jabatan_fungsional\":[\"Tenaga Pengajar\"],\"id\":15}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(26, 1, 'admin', 'user.created', 'system', 'App\\Models\\User', 24, NULL, NULL, '{\"name\":\"Muhammad Rizqi Alfarizi, S.H., LL.M.\",\"email\":\"4434778679130070@stihadhyaksa.ac.id\",\"role\":\"dosen\",\"id\":24}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(27, 1, 'admin', 'dosen.created', 'system', 'App\\Models\\Dosen', 16, NULL, NULL, '{\"user_id\":24,\"nidn\":\"4434778679130070\",\"pendidikan\":\"S2\",\"prodi\":[\"ilmu hukum\"],\"phone\":\"\",\"address\":\"\",\"status\":\"aktif\",\"pendidikan_terakhir\":[\"S1\",\"S2\"],\"universitas\":[\"Universitas Indonesia\",\"Universitas Malaya\"],\"dosen_tetap\":true,\"jabatan_fungsional\":[\"Asisten Ahli\"],\"id\":16}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(28, 1, 'admin', 'user.created', 'system', 'App\\Models\\User', 25, NULL, NULL, '{\"name\":\"Amir Firmansyah, S.H. M.H\",\"email\":\"7641763664130240@stihadhyaksa.ac.id\",\"role\":\"dosen\",\"id\":25}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(29, 1, 'admin', 'dosen.created', 'system', 'App\\Models\\Dosen', 17, NULL, NULL, '{\"user_id\":25,\"nidn\":\"7641763664130240\",\"pendidikan\":\"S2\",\"prodi\":[\"ilmu hukum\"],\"phone\":\"\",\"address\":\"\",\"status\":\"aktif\",\"pendidikan_terakhir\":[\"S1\",\"S2\"],\"universitas\":[\"Universitas Wiraswasta Indonesia\",\"Universitas Al Azhar Indonesia\"],\"dosen_tetap\":true,\"jabatan_fungsional\":[\"Asisten Ahli\"],\"id\":17}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(30, 1, 'admin', 'user.created', 'system', 'App\\Models\\User', 26, NULL, NULL, '{\"name\":\"Akhmad Ikraam, S.H., M.H.\",\"email\":\"2150767668137030@stihadhyaksa.ac.id\",\"role\":\"dosen\",\"id\":26}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(31, 1, 'admin', 'dosen.created', 'system', 'App\\Models\\Dosen', 18, NULL, NULL, '{\"user_id\":26,\"nidn\":\"2150767668137030\",\"pendidikan\":\"S2\",\"prodi\":[\"ilmu hukum\"],\"phone\":\"\",\"address\":\"\",\"status\":\"aktif\",\"pendidikan_terakhir\":[\"S1\",\"S2\"],\"universitas\":[\"Universitas Islam Indonesia\",\"Universitas Al Azhar Indonesia\"],\"dosen_tetap\":true,\"jabatan_fungsional\":[\"Asisten Ahli\"],\"id\":18}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(32, 1, 'admin', 'user.created', 'system', 'App\\Models\\User', 27, NULL, NULL, '{\"name\":\"Zul Karnen, S.S., M.Si.\",\"email\":\"3454762663130160@stihadhyaksa.ac.id\",\"role\":\"dosen\",\"id\":27}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(33, 1, 'admin', 'dosen.created', 'system', 'App\\Models\\Dosen', 19, NULL, NULL, '{\"user_id\":27,\"nidn\":\"3454762663130160\",\"pendidikan\":\"S2\",\"prodi\":[\"ilmu hukum\"],\"phone\":\"\",\"address\":\"\",\"status\":\"aktif\",\"pendidikan_terakhir\":[\"S1\",\"S2\"],\"universitas\":[\"Universitas Al-Azhar Indonesia\",\"Universitas Indonesia\"],\"dosen_tetap\":true,\"jabatan_fungsional\":[\"Tenaga Pengajar\"],\"id\":19}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(34, 1, 'admin', 'user.created', 'system', 'App\\Models\\User', 28, NULL, NULL, '{\"name\":\"Dr. Rudi Pradisetia Sudirdja., S.H., M.H.\",\"email\":\"3204070406910000@stihadhyaksa.ac.id\",\"role\":\"dosen\",\"id\":28}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(35, 1, 'admin', 'dosen.created', 'system', 'App\\Models\\Dosen', 20, NULL, NULL, '{\"user_id\":28,\"nidn\":\"3204070406910000\",\"pendidikan\":\"S3\",\"prodi\":[\"ilmu hukum\"],\"phone\":\"\",\"address\":\"\",\"status\":\"aktif\",\"pendidikan_terakhir\":[\"S1\",\"S2\",\"S3\"],\"universitas\":[\"Universitas Pasundan\",\"Universitas Padjajaran\",\"Universitas Indonesia\"],\"dosen_tetap\":false,\"jabatan_fungsional\":[\"Tenaga Pengajar\"],\"id\":20}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(36, 1, 'admin', 'semester.created', 'system', 'App\\Models\\Semester', 1, NULL, NULL, '{\"nama_semester\":\"Ganjil\",\"tahun_ajaran\":\"2025\\/2026\",\"tanggal_mulai\":\"2026-05-20T17:00:00.000000Z\",\"tanggal_selesai\":\"2026-11-20T17:00:00.000000Z\",\"status\":\"aktif\",\"id\":1}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(37, 1, 'admin', 'semester.created', 'system', 'App\\Models\\Semester', 2, NULL, NULL, '{\"nama_semester\":\"Genap\",\"tahun_ajaran\":\"2025\\/2026\",\"tanggal_mulai\":\"2026-11-21T17:00:00.000000Z\",\"tanggal_selesai\":\"2027-05-21T17:00:00.000000Z\",\"status\":\"non-aktif\",\"id\":2}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(38, 1, 'admin', 'semester.updated', 'system', 'App\\Models\\Semester', 1, NULL, '{\"krs_dapat_diisi\":false,\"krs_mulai\":null,\"krs_selesai\":null}', '{\"krs_dapat_diisi\":true,\"krs_mulai\":\"2026-05-21 00:00:00\",\"krs_selesai\":\"2026-07-31 00:00:00\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(39, 1, 'admin', 'prodi.updated', 'system', 'App\\Models\\Prodi', 1, NULL, '{\"fakultas_id\":null}', '{\"fakultas_id\":\"1\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(40, 1, 'admin', 'prodi.updated', 'system', 'App\\Models\\Prodi', 2, NULL, '{\"fakultas_id\":null}', '{\"fakultas_id\":\"2\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(41, 1, 'admin', 'prodi.deleted', 'system', 'App\\Models\\Prodi', 3, NULL, '{\"id\":3,\"kode_prodi\":\"MAN\",\"fakultas_id\":null,\"nama_prodi\":\"Manajemen\",\"jenjang\":\"S1\",\"status\":\"aktif\"}', NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(42, 1, 'admin', 'prodi.deleted', 'system', 'App\\Models\\Prodi', 4, NULL, '{\"id\":4,\"kode_prodi\":\"AKT\",\"fakultas_id\":null,\"nama_prodi\":\"Akuntansi\",\"jenjang\":\"D3\",\"status\":\"aktif\"}', NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(43, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 1, '{\"semester_id\":1,\"mata_kuliah_id\":\"55\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"55\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":1}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(44, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 2, '{\"semester_id\":1,\"mata_kuliah_id\":\"2\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"2\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":2}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(45, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 3, '{\"semester_id\":1,\"mata_kuliah_id\":\"4\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"4\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":3}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(46, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 4, '{\"semester_id\":1,\"mata_kuliah_id\":\"49\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"49\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":4}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(47, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 5, '{\"semester_id\":1,\"mata_kuliah_id\":\"50\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"50\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":5}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(48, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 6, '{\"semester_id\":1,\"mata_kuliah_id\":\"8\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"8\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":6}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(49, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 7, '{\"semester_id\":1,\"mata_kuliah_id\":\"41\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"41\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":7}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(50, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 8, '{\"semester_id\":1,\"mata_kuliah_id\":\"19\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"19\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":8}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(51, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 9, '{\"semester_id\":1,\"mata_kuliah_id\":\"18\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"18\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":9}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(52, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 10, '{\"semester_id\":1,\"mata_kuliah_id\":\"27\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"27\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":10}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(53, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 11, '{\"semester_id\":1,\"mata_kuliah_id\":\"11\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"11\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":11}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(54, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 12, '{\"semester_id\":1,\"mata_kuliah_id\":\"20\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"20\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":12}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(55, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 13, '{\"semester_id\":1,\"mata_kuliah_id\":\"61\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"61\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":13}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(56, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 14, '{\"semester_id\":1,\"mata_kuliah_id\":\"28\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"28\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":14}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(57, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 15, '{\"semester_id\":1,\"mata_kuliah_id\":\"47\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"47\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":15}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(58, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 16, '{\"semester_id\":1,\"mata_kuliah_id\":\"16\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"16\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":16}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(59, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 17, '{\"semester_id\":1,\"mata_kuliah_id\":\"17\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"17\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":17}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(60, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 18, '{\"semester_id\":1,\"mata_kuliah_id\":\"43\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"43\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":18}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(61, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 19, '{\"semester_id\":1,\"mata_kuliah_id\":\"14\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"14\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":19}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(62, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 20, '{\"semester_id\":1,\"mata_kuliah_id\":\"68\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"68\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":20}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(63, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 21, '{\"semester_id\":1,\"mata_kuliah_id\":\"12\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"12\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":21}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(64, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 22, '{\"semester_id\":1,\"mata_kuliah_id\":\"66\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"66\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":22}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(65, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 23, '{\"semester_id\":1,\"mata_kuliah_id\":\"31\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"31\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":23}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(66, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 24, '{\"semester_id\":1,\"mata_kuliah_id\":\"44\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"44\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":24}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(67, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 25, '{\"semester_id\":1,\"mata_kuliah_id\":\"67\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"67\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":25}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(68, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 26, '{\"semester_id\":1,\"mata_kuliah_id\":\"30\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"30\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":26}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(69, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 27, '{\"semester_id\":1,\"mata_kuliah_id\":\"64\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"64\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":27}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(70, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 28, '{\"semester_id\":1,\"mata_kuliah_id\":\"34\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"34\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":28}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(71, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 29, '{\"semester_id\":1,\"mata_kuliah_id\":\"60\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"60\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":29}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(72, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 30, '{\"semester_id\":1,\"mata_kuliah_id\":\"35\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"35\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":30}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(73, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 31, '{\"semester_id\":1,\"mata_kuliah_id\":\"57\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"57\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":31}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(74, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 32, '{\"semester_id\":1,\"mata_kuliah_id\":\"9\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"9\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":32}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(75, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 33, '{\"semester_id\":1,\"mata_kuliah_id\":\"29\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"29\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":33}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(76, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 34, '{\"semester_id\":1,\"mata_kuliah_id\":\"45\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"45\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":34}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(77, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 35, '{\"semester_id\":1,\"mata_kuliah_id\":\"25\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"25\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":35}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(78, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 36, '{\"semester_id\":1,\"mata_kuliah_id\":\"69\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"69\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":36}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(79, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 37, '{\"semester_id\":1,\"mata_kuliah_id\":\"39\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"39\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":37}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(80, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 38, '{\"semester_id\":1,\"mata_kuliah_id\":\"65\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"65\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":38}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(81, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 39, '{\"semester_id\":1,\"mata_kuliah_id\":\"56\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"56\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":39}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(82, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 40, '{\"semester_id\":1,\"mata_kuliah_id\":\"10\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"10\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":40}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(83, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 41, '{\"semester_id\":1,\"mata_kuliah_id\":\"40\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"40\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":41}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(84, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 42, '{\"semester_id\":1,\"mata_kuliah_id\":\"38\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"38\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":42}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(85, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 43, '{\"semester_id\":1,\"mata_kuliah_id\":\"22\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"22\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":43}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(86, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 44, '{\"semester_id\":1,\"mata_kuliah_id\":\"42\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"42\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":44}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(87, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 45, '{\"semester_id\":1,\"mata_kuliah_id\":\"15\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"15\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":45}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(88, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 46, '{\"semester_id\":1,\"mata_kuliah_id\":\"1\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"1\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":46}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(89, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 47, '{\"semester_id\":1,\"mata_kuliah_id\":\"5\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"5\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":47}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(90, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 48, '{\"semester_id\":1,\"mata_kuliah_id\":\"13\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"13\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":48}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(91, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 49, '{\"semester_id\":1,\"mata_kuliah_id\":\"70\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"70\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":49}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(92, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 50, '{\"semester_id\":1,\"mata_kuliah_id\":\"48\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"48\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":50}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(93, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 51, '{\"semester_id\":1,\"mata_kuliah_id\":\"32\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"32\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":51}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(94, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 52, '{\"semester_id\":1,\"mata_kuliah_id\":\"21\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"21\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":52}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(95, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 53, '{\"semester_id\":1,\"mata_kuliah_id\":\"54\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"54\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":53}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(96, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 54, '{\"semester_id\":1,\"mata_kuliah_id\":\"51\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"51\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":54}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(97, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 55, '{\"semester_id\":1,\"mata_kuliah_id\":\"26\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"26\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":55}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(98, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 56, '{\"semester_id\":1,\"mata_kuliah_id\":\"62\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"62\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":56}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(99, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 57, '{\"semester_id\":1,\"mata_kuliah_id\":\"24\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"24\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":57}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(100, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 58, '{\"semester_id\":1,\"mata_kuliah_id\":\"36\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"36\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":58}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(101, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 59, '{\"semester_id\":1,\"mata_kuliah_id\":\"37\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"37\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":59}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57');
INSERT INTO `audit_logs` (`id`, `actor_id`, `actor_role`, `action`, `module`, `auditable_type`, `auditable_id`, `meta`, `before`, `after`, `ip_address`, `user_agent`, `session_id`, `created_at`) VALUES
(102, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 60, '{\"semester_id\":1,\"mata_kuliah_id\":\"3\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"3\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":60}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(103, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 61, '{\"semester_id\":1,\"mata_kuliah_id\":\"46\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"46\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":61}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(104, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 62, '{\"semester_id\":1,\"mata_kuliah_id\":\"23\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"23\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":62}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(105, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 63, '{\"semester_id\":1,\"mata_kuliah_id\":\"7\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"7\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":63}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(106, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 64, '{\"semester_id\":1,\"mata_kuliah_id\":\"6\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"6\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":64}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(107, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 65, '{\"semester_id\":1,\"mata_kuliah_id\":\"63\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"63\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":65}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(108, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 66, '{\"semester_id\":1,\"mata_kuliah_id\":\"58\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"58\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":66}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(109, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 67, '{\"semester_id\":1,\"mata_kuliah_id\":\"59\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"59\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":67}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(110, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 68, '{\"semester_id\":1,\"mata_kuliah_id\":\"52\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"52\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":68}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(111, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 69, '{\"semester_id\":1,\"mata_kuliah_id\":\"53\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"53\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":69}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(112, 1, 'user', 'attach_mk', 'system', 'mata_kuliah_semester', 70, '{\"semester_id\":1,\"mata_kuliah_id\":\"33\"}', NULL, '{\"semester_id\":1,\"mata_kuliah_id\":\"33\",\"status\":\"active\",\"activated_at\":\"2026-05-21T09:51:45.000000Z\",\"updated_at\":\"2026-05-21T09:51:45.000000Z\",\"created_at\":\"2026-05-21T09:51:45.000000Z\",\"id\":70}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(113, 1, 'admin', 'ruangan.created', 'system', 'App\\Models\\Ruangan', 15, NULL, NULL, '{\"kode_ruangan\":\"RI 1\",\"nama_ruangan\":\"Ruang Kelas Internasional 1\",\"gedung\":\"STIH Adhyaksa\",\"lantai\":1,\"kapasitas\":50,\"kategori_id\":null,\"status\":\"Aktif\",\"id\":15}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(114, 1, 'admin', 'ruangan.created', 'system', 'App\\Models\\Ruangan', 16, NULL, NULL, '{\"kode_ruangan\":\"RI 2\",\"nama_ruangan\":\"Ruang Kelas Internasional 2\",\"gedung\":\"STIH Adhyaksa\",\"lantai\":1,\"kapasitas\":30,\"kategori_id\":null,\"status\":\"Aktif\",\"id\":16}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(115, 1, 'admin', 'ruangan.created', 'system', 'App\\Models\\Ruangan', 17, NULL, NULL, '{\"kode_ruangan\":\"RI 3\",\"nama_ruangan\":\"Ruang Kelas Internasional 3\",\"gedung\":\"STIH Adhyaksa\",\"lantai\":1,\"kapasitas\":50,\"kategori_id\":null,\"status\":\"Aktif\",\"id\":17}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(116, 1, 'admin', 'ruangan.created', 'system', 'App\\Models\\Ruangan', 18, NULL, NULL, '{\"kode_ruangan\":\"R 1\",\"nama_ruangan\":\"Ruang Kelas R 1\",\"gedung\":\"STIH Adhyaksa\",\"lantai\":2,\"kapasitas\":50,\"kategori_id\":null,\"status\":\"Aktif\",\"id\":18}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(117, 1, 'admin', 'ruangan.created', 'system', 'App\\Models\\Ruangan', 19, NULL, NULL, '{\"kode_ruangan\":\"R 2\",\"nama_ruangan\":\"Ruang Kelas R 2\",\"gedung\":\"STIH Adhyaksa\",\"lantai\":2,\"kapasitas\":50,\"kategori_id\":null,\"status\":\"Aktif\",\"id\":19}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(118, 1, 'admin', 'ruangan.created', 'system', 'App\\Models\\Ruangan', 20, NULL, NULL, '{\"kode_ruangan\":\"R 3\",\"nama_ruangan\":\"Ruang Kelas R 3\",\"gedung\":\"STIH Adhyaksa\",\"lantai\":2,\"kapasitas\":50,\"kategori_id\":null,\"status\":\"Aktif\",\"id\":20}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(119, 1, 'admin', 'ruangan.created', 'system', 'App\\Models\\Ruangan', 21, NULL, NULL, '{\"kode_ruangan\":\"R 4\",\"nama_ruangan\":\"Ruang Kelas R 4\",\"gedung\":\"STIH Adhyaksa\",\"lantai\":2,\"kapasitas\":50,\"kategori_id\":null,\"status\":\"Aktif\",\"id\":21}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(120, 1, 'admin', 'kelasperkuliahan.created', 'akademik', 'App\\Models\\KelasPerkuliahan', 1, NULL, NULL, '{\"angkatan\":\"2026\",\"tingkat\":0,\"kode_prodi\":\"HK\",\"kode_kelas\":\"01\",\"prodi_id\":1,\"tahun_akademik_id\":1,\"nama_kelas\":\"26HK01\",\"id\":1}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(121, 1, 'admin', 'kelasperkuliahan.created', 'akademik', 'App\\Models\\KelasPerkuliahan', 2, NULL, NULL, '{\"angkatan\":\"2026\",\"tingkat\":0,\"kode_prodi\":\"HK\",\"kode_kelas\":\"02\",\"prodi_id\":1,\"tahun_akademik_id\":1,\"nama_kelas\":\"26HK02\",\"id\":2}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(122, 1, 'admin', 'kelasperkuliahan.created', 'akademik', 'App\\Models\\KelasPerkuliahan', 3, NULL, NULL, '{\"angkatan\":\"2026\",\"tingkat\":0,\"kode_prodi\":\"HK\",\"kode_kelas\":\"03\",\"prodi_id\":1,\"tahun_akademik_id\":1,\"nama_kelas\":\"26HK03\",\"id\":3}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(123, 1, 'admin', 'kelasperkuliahan.created', 'akademik', 'App\\Models\\KelasPerkuliahan', 4, NULL, NULL, '{\"angkatan\":\"2026\",\"tingkat\":0,\"kode_prodi\":\"HK\",\"kode_kelas\":\"04\",\"prodi_id\":1,\"tahun_akademik_id\":1,\"nama_kelas\":\"26HK04\",\"id\":4}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(124, 1, 'admin', 'user.updated', 'system', 'App\\Models\\User', 10, NULL, '{\"email\":\"student1@stih.ac.id\"}', '{\"email\":\"ahmadmahasiswa@student.stih.ac.id\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(125, 1, 'admin', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 4, NULL, '{\"prodi_id\":null,\"angkatan\":\"2024\",\"tahun_akademik_id\":null,\"kelas_perkuliahan_id\":null,\"email_kampus\":null,\"email_aktif\":\"pribadi\"}', '{\"prodi_id\":1,\"angkatan\":\"2026\",\"tahun_akademik_id\":1,\"kelas_perkuliahan_id\":1,\"email_kampus\":\"ahmadmahasiswa@student.stih.ac.id\",\"email_aktif\":\"kampus\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(126, 1, 'user', 'mahasiswa.class_assignment_updated', 'system', 'App\\Models\\Mahasiswa', 4, '{\"mahasiswa_id\":4,\"mahasiswa_nim\":\"2024001\",\"mahasiswa_nama\":\"Ahmad Mahasiswa\"}', NULL, '{\"id\":1,\"nama_kelas\":\"26HK01\",\"display_label\":\"26HK01 - Ilmu Hukum Kelas 01\",\"prodi_id\":1,\"prodi\":\"Ilmu Hukum\",\"angkatan\":\"2026\",\"tingkat\":0,\"tahun_akademik_id\":1,\"tahun_akademik\":\"Ganjil 2025\\/2026\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(127, 1, 'admin', 'dosen.updated', 'system', 'App\\Models\\Dosen', 10, NULL, '{\"mata_kuliah_ids\":null}', '{\"mata_kuliah_ids\":\"[1,2,3]\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(128, 1, 'admin', 'dosen.updated', 'system', 'App\\Models\\Dosen', 1, NULL, '{\"mata_kuliah_ids\":null}', '{\"mata_kuliah_ids\":\"[5,6,7]\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(129, 1, 'admin', 'dosen.updated', 'system', 'App\\Models\\Dosen', 9, NULL, '{\"mata_kuliah_ids\":null}', '{\"mata_kuliah_ids\":\"[4,8]\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(130, 18, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 18, '{\"ip\":\"192.168.1.7\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(131, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(132, 18, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 18, '{\"ip\":\"192.168.1.7\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(133, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(134, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.7\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(135, 17, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 17, '{\"ip\":\"192.168.1.7\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(136, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(137, 18, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 18, '{\"ip\":\"192.168.1.7\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(138, 18, 'dosen', 'jadwal.created', 'system', 'App\\Models\\Jadwal', 1, NULL, NULL, '{\"kelas_id\":1,\"hari\":\"Selasa\",\"jam_mulai\":\"13:45:00\",\"jam_selesai\":\"16:15:00\",\"ruangan\":\"RI 1\",\"ruangan_id\":null,\"status\":\"active\",\"approved_by\":18,\"approved_at\":\"2026-05-21T10:01:11.000000Z\",\"id\":1}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(139, 18, 'dosen', 'kelasmatakuliah.created', 'akademik', 'App\\Models\\KelasMataKuliah', 1, NULL, NULL, '{\"mata_kuliah_id\":3,\"dosen_id\":10,\"semester_id\":1,\"kelas_perkuliahan_id\":1,\"kode_kelas\":\"01\",\"kapasitas\":40,\"ruang\":\"RI 1\",\"ruangan_id\":null,\"hari\":\"Selasa\",\"jam_mulai\":\"13:45:00\",\"jam_selesai\":\"16:15:00\",\"id\":1}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(140, 18, 'dosen', 'jadwal.created', 'system', 'App\\Models\\Jadwal', 2, NULL, NULL, '{\"kelas_id\":5,\"hari\":\"Selasa\",\"jam_mulai\":\"20:40:00\",\"jam_selesai\":\"22:10:00\",\"ruangan\":\"R 1\",\"ruangan_id\":null,\"status\":\"active\",\"approved_by\":18,\"approved_at\":\"2026-05-21T10:01:12.000000Z\",\"id\":2}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(141, 18, 'dosen', 'kelasmatakuliah.created', 'akademik', 'App\\Models\\KelasMataKuliah', 2, NULL, NULL, '{\"mata_kuliah_id\":2,\"dosen_id\":10,\"semester_id\":1,\"kelas_perkuliahan_id\":1,\"kode_kelas\":\"01\",\"kapasitas\":40,\"ruang\":\"R 1\",\"ruangan_id\":null,\"hari\":\"Selasa\",\"jam_mulai\":\"20:40:00\",\"jam_selesai\":\"22:10:00\",\"id\":2}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(142, 18, 'dosen', 'jadwal.created', 'system', 'App\\Models\\Jadwal', 3, NULL, NULL, '{\"kelas_id\":8,\"hari\":\"Senin\",\"jam_mulai\":\"10:30:00\",\"jam_selesai\":\"12:00:00\",\"ruangan\":\"R 1\",\"ruangan_id\":null,\"status\":\"active\",\"approved_by\":18,\"approved_at\":\"2026-05-21T10:01:13.000000Z\",\"id\":3}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(143, 18, 'dosen', 'kelasmatakuliah.created', 'akademik', 'App\\Models\\KelasMataKuliah', 3, NULL, NULL, '{\"mata_kuliah_id\":1,\"dosen_id\":10,\"semester_id\":1,\"kelas_perkuliahan_id\":1,\"kode_kelas\":\"01\",\"kapasitas\":40,\"ruang\":\"R 1\",\"ruangan_id\":null,\"hari\":\"Senin\",\"jam_mulai\":\"10:30:00\",\"jam_selesai\":\"12:00:00\",\"id\":3}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(144, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.7\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(145, 2, 'dosen', 'jadwal.created', 'system', 'App\\Models\\Jadwal', 4, NULL, NULL, '{\"kelas_id\":2,\"hari\":\"Kamis\",\"jam_mulai\":\"13:45:00\",\"jam_selesai\":\"16:15:00\",\"ruangan\":\"R 4\",\"ruangan_id\":null,\"status\":\"active\",\"approved_by\":2,\"approved_at\":\"2026-05-21T10:01:26.000000Z\",\"id\":4}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(146, 2, 'dosen', 'kelasmatakuliah.created', 'akademik', 'App\\Models\\KelasMataKuliah', 4, NULL, NULL, '{\"mata_kuliah_id\":6,\"dosen_id\":1,\"semester_id\":1,\"kelas_perkuliahan_id\":1,\"kode_kelas\":\"01\",\"kapasitas\":40,\"ruang\":\"R 4\",\"ruangan_id\":null,\"hari\":\"Kamis\",\"jam_mulai\":\"13:45:00\",\"jam_selesai\":\"16:15:00\",\"id\":4}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(147, 2, 'dosen', 'jadwal.created', 'system', 'App\\Models\\Jadwal', 5, NULL, NULL, '{\"kelas_id\":3,\"hari\":\"Kamis\",\"jam_mulai\":\"19:55:00\",\"jam_selesai\":\"22:10:00\",\"ruangan\":\"RI 1\",\"ruangan_id\":null,\"status\":\"active\",\"approved_by\":2,\"approved_at\":\"2026-05-21T10:01:28.000000Z\",\"id\":5}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(148, 2, 'dosen', 'kelasmatakuliah.created', 'akademik', 'App\\Models\\KelasMataKuliah', 5, NULL, NULL, '{\"mata_kuliah_id\":7,\"dosen_id\":1,\"semester_id\":1,\"kelas_perkuliahan_id\":1,\"kode_kelas\":\"01\",\"kapasitas\":40,\"ruang\":\"RI 1\",\"ruangan_id\":null,\"hari\":\"Kamis\",\"jam_mulai\":\"19:55:00\",\"jam_selesai\":\"22:10:00\",\"id\":5}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(149, 2, 'dosen', 'jadwal.created', 'system', 'App\\Models\\Jadwal', 6, NULL, NULL, '{\"kelas_id\":4,\"hari\":\"Rabu\",\"jam_mulai\":\"16:15:00\",\"jam_selesai\":\"18:30:00\",\"ruangan\":\"R 2\",\"ruangan_id\":null,\"status\":\"active\",\"approved_by\":2,\"approved_at\":\"2026-05-21T10:01:29.000000Z\",\"id\":6}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(150, 2, 'dosen', 'kelasmatakuliah.created', 'akademik', 'App\\Models\\KelasMataKuliah', 6, NULL, NULL, '{\"mata_kuliah_id\":5,\"dosen_id\":1,\"semester_id\":1,\"kelas_perkuliahan_id\":1,\"kode_kelas\":\"01\",\"kapasitas\":40,\"ruang\":\"R 2\",\"ruangan_id\":null,\"hari\":\"Rabu\",\"jam_mulai\":\"16:15:00\",\"jam_selesai\":\"18:30:00\",\"id\":6}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(151, 17, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 17, '{\"ip\":\"192.168.1.7\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(152, 17, 'dosen', 'jadwal.created', 'system', 'App\\Models\\Jadwal', 7, NULL, NULL, '{\"kelas_id\":6,\"hari\":\"Jumat\",\"jam_mulai\":\"15:30:00\",\"jam_selesai\":\"16:55:00\",\"ruangan\":\"R 2\",\"ruangan_id\":null,\"status\":\"active\",\"approved_by\":17,\"approved_at\":\"2026-05-21T10:01:38.000000Z\",\"id\":7}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(153, 17, 'dosen', 'kelasmatakuliah.created', 'akademik', 'App\\Models\\KelasMataKuliah', 7, NULL, NULL, '{\"mata_kuliah_id\":8,\"dosen_id\":9,\"semester_id\":1,\"kelas_perkuliahan_id\":1,\"kode_kelas\":\"01\",\"kapasitas\":40,\"ruang\":\"R 2\",\"ruangan_id\":null,\"hari\":\"Jumat\",\"jam_mulai\":\"15:30:00\",\"jam_selesai\":\"16:55:00\",\"id\":7}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(154, 17, 'dosen', 'jadwal.created', 'system', 'App\\Models\\Jadwal', 8, NULL, NULL, '{\"kelas_id\":7,\"hari\":\"Jumat\",\"jam_mulai\":\"20:40:00\",\"jam_selesai\":\"22:10:00\",\"ruangan\":\"R 1\",\"ruangan_id\":null,\"status\":\"active\",\"approved_by\":17,\"approved_at\":\"2026-05-21T10:01:39.000000Z\",\"id\":8}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(155, 17, 'dosen', 'kelasmatakuliah.created', 'akademik', 'App\\Models\\KelasMataKuliah', 8, NULL, NULL, '{\"mata_kuliah_id\":4,\"dosen_id\":9,\"semester_id\":1,\"kelas_perkuliahan_id\":1,\"kode_kelas\":\"01\",\"kapasitas\":40,\"ruang\":\"R 1\",\"ruangan_id\":null,\"hari\":\"Jumat\",\"jam_mulai\":\"20:40:00\",\"jam_selesai\":\"22:10:00\",\"id\":8}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(156, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(157, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(158, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(159, 1, 'admin', 'user.updated', 'system', 'App\\Models\\User', 10, NULL, '{\"role\":\"student\"}', '{\"role\":\"mahasiswa\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(160, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(161, 10, 'mahasiswa', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 4, NULL, '{\"new_survey_completed\":0}', '{\"new_survey_completed\":true}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(162, 10, 'mahasiswa', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 4, NULL, '{\"no_hp\":null,\"alamat\":null,\"rt\":null,\"rw\":null,\"kota\":null,\"kecamatan\":null,\"desa\":null,\"alamat_ktp\":null,\"rt_ktp\":null,\"rw_ktp\":null,\"provinsi_ktp\":null,\"kota_ktp\":null,\"kecamatan_ktp\":null,\"desa_ktp\":null,\"provinsi\":null,\"jenis_sekolah\":null,\"jurusan_sekolah\":null,\"tahun_lulus\":null,\"nilai_kelulusan\":null,\"tempat_lahir\":null,\"tanggal_lahir\":null,\"jenis_kelamin\":null,\"agama\":null,\"status_sipil\":null,\"file_ijazah\":null,\"file_transkrip\":null,\"file_kk\":null,\"file_ktp\":null}', '{\"no_hp\":\"1231231231231\",\"alamat\":\"Jakarta\",\"rt\":\"12\",\"rw\":\"12\",\"kota\":\"KAB. ACEH BARAT\",\"kecamatan\":\"ARONGAN LAMBALEK\",\"desa\":\"ALUE SUNDAK\",\"alamat_ktp\":\"Jakarta\",\"rt_ktp\":\"12\",\"rw_ktp\":\"12\",\"provinsi_ktp\":\"ACEH\",\"kota_ktp\":\"KAB. ACEH BARAT\",\"kecamatan_ktp\":\"ARONGAN LAMBALEK\",\"desa_ktp\":\"ALUE SUNDAK\",\"provinsi\":\"ACEH\",\"jenis_sekolah\":\"1 - Umum\",\"jurusan_sekolah\":\"SMA\",\"tahun_lulus\":\"2026\",\"nilai_kelulusan\":\"100\",\"tempat_lahir\":\"Jakarta\",\"tanggal_lahir\":\"2000-10-10\",\"jenis_kelamin\":\"Laki-Laki\",\"agama\":\"Buddha\",\"status_sipil\":\"Belum Menikah\",\"file_ijazah\":\"[\\\"documents\\\\\\/mahasiswa\\\\\\/AHMAD_MAHASISWA_2024001\\\\\\/592bd5c7-f8e9-47a8-bf72-006c67b1f252.pdf\\\"]\",\"file_transkrip\":\"[\\\"documents\\\\\\/mahasiswa\\\\\\/AHMAD_MAHASISWA_2024001\\\\\\/0bc5dd24-4912-4018-863d-6a46d30e5294.pdf\\\"]\",\"file_kk\":\"[\\\"documents\\\\\\/mahasiswa\\\\\\/AHMAD_MAHASISWA_2024001\\\\\\/10ebf57a-ba43-4832-8bb9-ed944738a5be.pdf\\\"]\",\"file_ktp\":\"[\\\"documents\\\\\\/mahasiswa\\\\\\/AHMAD_MAHASISWA_2024001\\\\\\/d5af8e70-0bb6-42bc-920f-db7472864937.pdf\\\"]\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(163, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.81\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.81', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(164, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.81\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.81', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(165, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.81\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.81', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(166, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(167, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.81\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.81', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(168, 1, 'admin', 'user.created', 'system', 'App\\Models\\User', 29, NULL, NULL, '{\"name\":\"Akbar\",\"email\":\"2024001@parent.stih.ac.id\",\"role\":\"parent\",\"id\":29}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(169, 1, 'admin', 'dosen.updated', 'system', 'App\\Models\\Dosen', 1, NULL, '{\"fakultas_id\":null,\"pendidikan_terakhir\":null,\"universitas\":null,\"dosen_tetap\":false,\"jabatan_fungsional\":null,\"pendidikan\":null,\"prodi\":\"Hukum Tata Kabupaten\"}', '{\"fakultas_id\":\"1\",\"pendidikan_terakhir\":\"[\\\"S3\\\"]\",\"universitas\":\"[\\\"Universitas Gunadarma\\\"]\",\"dosen_tetap\":true,\"jabatan_fungsional\":\"[\\\"Asisten Ahli\\\"]\",\"pendidikan\":\"S3\",\"prodi\":\"[\\\"Ilmu Hukum\\\"]\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(170, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(171, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(172, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(173, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(174, 1, 'admin', 'user.created', 'system', 'App\\Models\\User', 30, NULL, NULL, '{\"name\":\"Jojo\",\"email\":\"jojo@student.stih.ac.id\",\"role\":\"mahasiswa\",\"id\":30}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(175, 1, 'admin', 'mahasiswa.created', 'system', 'App\\Models\\Mahasiswa', 6, NULL, NULL, '{\"nim\":\"50421684\",\"prodi\":\"Ilmu Hukum\",\"prodi_id\":1,\"angkatan\":\"2026\",\"semester\":1,\"jenis_kelamin\":\"Laki-Laki\",\"phone\":null,\"address\":null,\"email_pribadi\":\"gregoriusjoel28@gmail.com\",\"email_kampus\":\"jojo@student.stih.ac.id\",\"email_aktif\":\"kampus\",\"status\":\"aktif\",\"tahun_akademik_id\":1,\"kelas_perkuliahan_id\":1,\"user_id\":30,\"status_akun\":\"baru\",\"id\":6}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(176, 1, 'user', 'mahasiswa.class_assignment_created', 'system', 'App\\Models\\Mahasiswa', 6, '{\"mahasiswa_id\":6,\"mahasiswa_nim\":\"50421684\",\"mahasiswa_nama\":\"Jojo\"}', NULL, '{\"id\":1,\"nama_kelas\":\"26HK01\",\"display_label\":\"26HK01 - Ilmu Hukum Kelas 01\",\"prodi_id\":1,\"prodi\":\"Ilmu Hukum\",\"angkatan\":\"2026\",\"tingkat\":0,\"tahun_akademik_id\":1,\"tahun_akademik\":\"Ganjil 2025\\/2026\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(177, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(178, 30, 'mahasiswa', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 6, NULL, '{\"new_survey_completed\":0}', '{\"new_survey_completed\":true}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(179, 30, 'mahasiswa', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 6, NULL, '{\"no_hp\":null,\"alamat\":null,\"rt\":null,\"rw\":null,\"kota\":null,\"kecamatan\":null,\"desa\":null,\"alamat_ktp\":null,\"rt_ktp\":null,\"rw_ktp\":null,\"provinsi_ktp\":null,\"kota_ktp\":null,\"kecamatan_ktp\":null,\"desa_ktp\":null,\"provinsi\":null,\"jenis_sekolah\":null,\"jurusan_sekolah\":null,\"tahun_lulus\":null,\"nilai_kelulusan\":null,\"tempat_lahir\":null,\"tanggal_lahir\":null,\"agama\":null,\"status_sipil\":null,\"file_ijazah\":null,\"file_transkrip\":null,\"file_kk\":null,\"file_ktp\":null}', '{\"no_hp\":\"82282228222\",\"alamat\":\"Jakarta\",\"rt\":\"12\",\"rw\":\"12\",\"kota\":\"KOTA ADM. JAKARTA PUSAT\",\"kecamatan\":\"CEMPAKA PUTIH\",\"desa\":\"CEMPAKA PUTIH BARAT\",\"alamat_ktp\":\"Jakarta\",\"rt_ktp\":\"12\",\"rw_ktp\":\"12\",\"provinsi_ktp\":\"DKI JAKARTA\",\"kota_ktp\":\"KOTA ADM. JAKARTA PUSAT\",\"kecamatan_ktp\":\"CEMPAKA PUTIH\",\"desa_ktp\":\"CEMPAKA PUTIH BARAT\",\"provinsi\":\"DKI JAKARTA\",\"jenis_sekolah\":\"1 - Umum\",\"jurusan_sekolah\":\"SMA\",\"tahun_lulus\":\"2026\",\"nilai_kelulusan\":\"100\",\"tempat_lahir\":\"Jakarta\",\"tanggal_lahir\":\"2000-02-28\",\"agama\":\"Katolik\",\"status_sipil\":\"Belum Menikah\",\"file_ijazah\":\"[\\\"documents\\\\\\/mahasiswa\\\\\\/JOJO_50421684\\\\\\/5cd84b5e-7cbc-4022-9f90-19218678b38a.pdf\\\"]\",\"file_transkrip\":\"[\\\"documents\\\\\\/mahasiswa\\\\\\/JOJO_50421684\\\\\\/00ad7ed6-9b7f-4cff-94c9-06c1344d4047.pdf\\\"]\",\"file_kk\":\"[\\\"documents\\\\\\/mahasiswa\\\\\\/JOJO_50421684\\\\\\/cbd00a11-75c7-46c8-993b-2f4180cba5f3.pdf\\\"]\",\"file_ktp\":\"[\\\"documents\\\\\\/mahasiswa\\\\\\/JOJO_50421684\\\\\\/7318a893-fae4-4f36-9860-c173a4bac7e1.pdf\\\"]\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(180, 30, 'mahasiswa', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 6, NULL, '{\"foto\":null}', '{\"foto\":\"images\\/mahasiswa\\/foto\\/JOJO_50421684\\/b617b89b-5e19-4338-8996-ec8e168371df.png\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(181, 30, 'mahasiswa', 'krs.submitted', 'akademik', 'App\\Models\\Mahasiswa', 6, '{\"semester_id\":1,\"total_mk\":8,\"total_sks\":20}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(182, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', NULL, '2026-06-03 03:50:57'),
(183, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(184, 1, 'admin', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 6, NULL, '{\"semester\":1}', '{\"semester\":2}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', NULL, '2026-06-03 03:50:57'),
(185, 30, 'mahasiswa', 'krs.submitted', 'akademik', 'App\\Models\\Mahasiswa', 6, '{\"semester_id\":1,\"total_mk\":7,\"total_sks\":20}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(186, 1, 'admin', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 6, NULL, '{\"semester\":2}', '{\"semester\":3}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', NULL, '2026-06-03 03:50:57'),
(187, 1, 'admin', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 6, NULL, '{\"semester\":3}', '{\"semester\":1}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', NULL, '2026-06-03 03:50:57'),
(188, 1, 'admin', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 6, NULL, '{\"semester\":1}', '{\"semester\":3}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', NULL, '2026-06-03 03:50:57'),
(189, 1, 'admin', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 6, NULL, '{\"semester\":3}', '{\"semester\":4}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', NULL, '2026-06-03 03:50:57'),
(190, 1, 'admin', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 6, NULL, '{\"semester\":4}', '{\"semester\":3}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', NULL, '2026-06-03 03:50:57'),
(191, 1, 'admin', 'semester.created', 'system', 'App\\Models\\Semester', 3, NULL, NULL, '{\"nama_semester\":\"Genap\",\"tahun_ajaran\":\"2026\\/2027\",\"tanggal_mulai\":\"2027-05-22T17:00:00.000000Z\",\"tanggal_selesai\":\"2027-11-22T17:00:00.000000Z\",\"status\":\"non-aktif\",\"id\":3}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', NULL, '2026-06-03 03:50:57'),
(192, 1, 'admin', 'semester.updated', 'system', 'App\\Models\\Semester', 3, NULL, '{\"nama_semester\":\"Genap\"}', '{\"nama_semester\":\"Ganjil\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', NULL, '2026-06-03 03:50:57'),
(193, 1, 'admin', 'semester.created', 'system', 'App\\Models\\Semester', 4, NULL, NULL, '{\"nama_semester\":\"Genap\",\"tahun_ajaran\":\"2026\\/2027\",\"tanggal_mulai\":\"2027-11-23T17:00:00.000000Z\",\"tanggal_selesai\":\"2028-05-23T17:00:00.000000Z\",\"status\":\"non-aktif\",\"id\":4}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', NULL, '2026-06-03 03:50:57'),
(194, 1, 'admin', 'semester.created', 'system', 'App\\Models\\Semester', 5, NULL, NULL, '{\"nama_semester\":\"Ganjil\",\"tahun_ajaran\":\"2027\\/2028\",\"tanggal_mulai\":\"2028-05-24T17:00:00.000000Z\",\"tanggal_selesai\":\"2028-11-24T17:00:00.000000Z\",\"status\":\"non-aktif\",\"id\":5}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', NULL, '2026-06-03 03:50:57'),
(195, 1, 'admin', 'semester.created', 'system', 'App\\Models\\Semester', 6, NULL, NULL, '{\"nama_semester\":\"Genap\",\"tahun_ajaran\":\"2027\\/2028\",\"tanggal_mulai\":\"2028-11-25T17:00:00.000000Z\",\"tanggal_selesai\":\"2029-05-25T17:00:00.000000Z\",\"status\":\"non-aktif\",\"id\":6}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', NULL, '2026-06-03 03:50:57'),
(196, 1, 'admin', 'semester.created', 'system', 'App\\Models\\Semester', 7, NULL, NULL, '{\"nama_semester\":\"Ganjil\",\"tahun_ajaran\":\"2028\\/2029\",\"tanggal_mulai\":\"2029-05-26T17:00:00.000000Z\",\"tanggal_selesai\":\"2029-11-26T17:00:00.000000Z\",\"status\":\"non-aktif\",\"id\":7}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', NULL, '2026-06-03 03:50:57'),
(197, 1, 'admin', 'semester.created', 'system', 'App\\Models\\Semester', 8, NULL, NULL, '{\"nama_semester\":\"Genap\",\"tahun_ajaran\":\"2028\\/2029\",\"tanggal_mulai\":\"2029-11-27T17:00:00.000000Z\",\"tanggal_selesai\":\"2030-05-27T17:00:00.000000Z\",\"status\":\"non-aktif\",\"id\":8}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', NULL, '2026-06-03 03:50:57'),
(200, 30, 'mahasiswa', 'krs.submitted', 'akademik', 'App\\Models\\Mahasiswa', 6, '{\"semester_id\":1,\"total_mk\":10,\"total_sks\":24}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(201, 1, 'admin', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 6, NULL, '{\"semester\":3}', '{\"semester\":4}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', NULL, '2026-06-03 03:50:57'),
(202, 30, 'mahasiswa', 'krs.submitted', 'akademik', 'App\\Models\\Mahasiswa', 6, '{\"semester_id\":1,\"total_mk\":11,\"total_sks\":24}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(203, 1, 'admin', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 6, NULL, '{\"semester\":4}', '{\"semester\":5}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', NULL, '2026-06-03 03:50:57'),
(204, 30, 'mahasiswa', 'krs.submitted', 'akademik', 'App\\Models\\Mahasiswa', 6, '{\"semester_id\":1,\"total_mk\":12,\"total_sks\":24}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(205, 1, 'admin', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 6, NULL, '{\"semester\":5}', '{\"semester\":6}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', NULL, '2026-06-03 03:50:57'),
(206, 30, 'mahasiswa', 'krs.submitted', 'akademik', 'App\\Models\\Mahasiswa', 6, '{\"semester_id\":1,\"total_mk\":12,\"total_sks\":24}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(207, 1, 'admin', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 6, NULL, '{\"semester\":6}', '{\"semester\":7}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', NULL, '2026-06-03 03:50:57'),
(208, 30, 'mahasiswa', 'krs.submitted', 'akademik', 'App\\Models\\Mahasiswa', 6, '{\"semester_id\":1,\"total_mk\":10,\"total_sks\":22}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(209, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(210, 30, 'mahasiswa', 'skripsi.proposal_submitted', 'skripsi', 'App\\Models\\SkripsiSubmission', 1, '{\"judul\":\"Analisis Sentimen DOSEN Resek\",\"supervisor_id\":\"1\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(211, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(212, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.7\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(213, 2, 'dosen', 'skripsi.supervisor_accepted', 'skripsi', 'App\\Models\\SkripsiSubmission', 1, '{\"mahasiswa_id\":6}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(214, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(215, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(216, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(217, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.7\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(218, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(219, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.7\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(220, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(221, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(222, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(223, 30, 'mahasiswa', 'skripsi.revision_uploaded', 'skripsi', 'App\\Models\\SkripsiSubmission', 1, '{\"notes\":null}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(224, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.7\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(225, 2, 'dosen', 'skripsi.revision_approved', 'skripsi', 'App\\Models\\SkripsiSubmission', 1, '{\"revision_id\":1,\"notes\":null}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(226, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(227, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57');
INSERT INTO `audit_logs` (`id`, `actor_id`, `actor_role`, `action`, `module`, `auditable_type`, `auditable_id`, `meta`, `before`, `after`, `ip_address`, `user_agent`, `session_id`, `created_at`) VALUES
(228, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(229, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(230, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(231, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(232, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.46\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.46', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', NULL, '2026-06-03 03:50:57'),
(233, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.81\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.81', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(234, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(235, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(236, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(237, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(238, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(239, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(240, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(241, 1, 'admin', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 4, NULL, '{\"semester\":1}', '{\"semester\":2}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(242, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(243, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(244, 1, 'admin', 'user.updated', 'system', 'App\\Models\\User', 5, NULL, '{\"email\":\"andi.pratama@student.stih.ac.id\"}', '{\"email\":\"andipratama@student.stih.ac.id\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(245, 1, 'admin', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 1, NULL, '{\"prodi\":\"Hukum Tata Kabupaten\",\"prodi_id\":null,\"angkatan\":\"2024\",\"tahun_akademik_id\":null,\"kelas_perkuliahan_id\":null,\"email_kampus\":null,\"email_aktif\":\"pribadi\"}', '{\"prodi\":\"Ilmu Hukum\",\"prodi_id\":1,\"angkatan\":\"2026\",\"tahun_akademik_id\":1,\"kelas_perkuliahan_id\":1,\"email_kampus\":\"andipratama@student.stih.ac.id\",\"email_aktif\":\"kampus\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(246, 1, 'user', 'mahasiswa.class_assignment_updated', 'system', 'App\\Models\\Mahasiswa', 1, '{\"mahasiswa_id\":1,\"mahasiswa_nim\":\"2024010001\",\"mahasiswa_nama\":\"Andi Pratama\"}', NULL, '{\"id\":1,\"nama_kelas\":\"26HK01\",\"display_label\":\"26HK01 - Ilmu Hukum Kelas 01\",\"prodi_id\":1,\"prodi\":\"Ilmu Hukum\",\"angkatan\":\"2026\",\"tingkat\":0,\"tahun_akademik_id\":1,\"tahun_akademik\":\"Ganjil 2025\\/2026\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:57'),
(247, 5, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 5, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(248, 5, 'mahasiswa', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 1, NULL, '{\"new_survey_completed\":0}', '{\"new_survey_completed\":true}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(249, 5, 'mahasiswa', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 1, NULL, '{\"no_hp\":null,\"alamat\":null,\"rt\":null,\"rw\":null,\"kota\":null,\"kecamatan\":null,\"desa\":null,\"alamat_ktp\":null,\"rt_ktp\":null,\"rw_ktp\":null,\"provinsi_ktp\":null,\"kota_ktp\":null,\"kecamatan_ktp\":null,\"desa_ktp\":null,\"provinsi\":null,\"jenis_sekolah\":null,\"jurusan_sekolah\":null,\"tahun_lulus\":null,\"nilai_kelulusan\":null,\"tempat_lahir\":null,\"tanggal_lahir\":null,\"jenis_kelamin\":null,\"agama\":null,\"status_sipil\":null,\"file_ijazah\":null,\"file_transkrip\":null,\"file_kk\":null,\"file_ktp\":null}', '{\"no_hp\":\"1231231231231\",\"alamat\":\"123\",\"rt\":\"12\",\"rw\":\"12\",\"kota\":\"KAB. ACEH BARAT DAYA\",\"kecamatan\":\"LEMBAH SABIL\",\"desa\":\"LADANG TUHA I\",\"alamat_ktp\":\"123\",\"rt_ktp\":\"12\",\"rw_ktp\":\"12\",\"provinsi_ktp\":\"ACEH\",\"kota_ktp\":\"KAB. ACEH BARAT DAYA\",\"kecamatan_ktp\":\"LEMBAH SABIL\",\"desa_ktp\":\"LADANG TUHA I\",\"provinsi\":\"ACEH\",\"jenis_sekolah\":\"1 - Umum\",\"jurusan_sekolah\":\"SMA\",\"tahun_lulus\":\"2026\",\"nilai_kelulusan\":\"100\",\"tempat_lahir\":\"Jakarta\",\"tanggal_lahir\":\"2000-10-10\",\"jenis_kelamin\":\"Laki-Laki\",\"agama\":\"Katolik\",\"status_sipil\":\"Belum Menikah\",\"file_ijazah\":\"[\\\"documents\\\\\\/mahasiswa\\\\\\/ANDI_PRATAMA_2024010001\\\\\\/f54c30e6-eb0c-40fe-a7d9-0dd9aef01121.pdf\\\"]\",\"file_transkrip\":\"[\\\"documents\\\\\\/mahasiswa\\\\\\/ANDI_PRATAMA_2024010001\\\\\\/2525e546-e150-40f7-aa01-8f9c8af46f36.pdf\\\"]\",\"file_kk\":\"[\\\"documents\\\\\\/mahasiswa\\\\\\/ANDI_PRATAMA_2024010001\\\\\\/bb91228c-cb56-48c5-8800-624cb58d1c97.pdf\\\"]\",\"file_ktp\":\"[\\\"documents\\\\\\/mahasiswa\\\\\\/ANDI_PRATAMA_2024010001\\\\\\/8ead5f91-9f2c-4d98-bcc6-814b14d6e7ac.pdf\\\"]\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(250, 5, 'mahasiswa', 'krs.submitted', 'akademik', 'App\\Models\\Mahasiswa', 1, '{\"semester_id\":1,\"total_mk\":8,\"total_sks\":20}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(251, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(252, 1, 'admin', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 1, NULL, '{\"semester\":1}', '{\"semester\":2}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(253, 5, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 5, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(254, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(255, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(256, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(257, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(258, 1, 'admin', 'semester.updated', 'system', 'App\\Models\\Semester', 2, NULL, '{\"krs_mulai\":null,\"krs_selesai\":null}', '{\"krs_mulai\":\"2026-03-09 00:00:00\",\"krs_selesai\":\"2026-03-11 00:00:00\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(259, 1, 'user', 'academic_event.deleted', 'akademik', 'App\\Models\\AcademicEvent', 6, '{\"deleted_by\":1}', '{\"id\":6,\"title\":\"Ujian Tengah Semester (UTS)\",\"description\":\"Imported from PDF\",\"event_type\":\"uts\",\"start_date\":\"2026-05-17T17:00:00.000000Z\",\"end_date\":\"2026-05-21T17:00:00.000000Z\",\"semester_id\":null,\"color\":\"#f59e0b\",\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"created_at\":\"2026-05-22T02:25:43.000000Z\",\"updated_at\":\"2026-05-22T02:25:43.000000Z\"}', NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(260, 1, 'user', 'academic_event.deleted', 'akademik', 'App\\Models\\AcademicEvent', 8, '{\"deleted_by\":1}', '{\"id\":8,\"title\":\"Masa Perkuliahaan efektif setelah UTS (7x pertemuan)\",\"description\":\"Imported from PDF\",\"event_type\":\"uts\",\"start_date\":\"2026-05-24T17:00:00.000000Z\",\"end_date\":\"2026-07-09T17:00:00.000000Z\",\"semester_id\":null,\"color\":\"#f59e0b\",\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"created_at\":\"2026-05-22T02:25:44.000000Z\",\"updated_at\":\"2026-05-22T02:25:44.000000Z\"}', NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(261, 1, 'user', 'academic_event.deleted', 'akademik', 'App\\Models\\AcademicEvent', 9, '{\"deleted_by\":1}', '{\"id\":9,\"title\":\"Ujian Akhir Semester (UAS)\",\"description\":\"Imported from PDF\",\"event_type\":\"uas\",\"start_date\":\"2026-07-12T17:00:00.000000Z\",\"end_date\":\"2026-07-16T17:00:00.000000Z\",\"semester_id\":null,\"color\":\"#d97706\",\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"created_at\":\"2026-05-22T02:25:44.000000Z\",\"updated_at\":\"2026-05-22T02:25:44.000000Z\"}', NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(262, 1, 'user', 'academic_event.deleted', 'akademik', 'App\\Models\\AcademicEvent', 10, '{\"deleted_by\":1}', '{\"id\":10,\"title\":\"Penginputan Nilai Mahasiswa\",\"description\":\"Imported from PDF\",\"event_type\":\"lainnya\",\"start_date\":\"2026-07-19T17:00:00.000000Z\",\"end_date\":\"2026-07-23T17:00:00.000000Z\",\"semester_id\":null,\"color\":\"#6b7280\",\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"created_at\":\"2026-05-22T02:25:44.000000Z\",\"updated_at\":\"2026-05-22T02:25:44.000000Z\"}', NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(263, 1, 'user', 'academic_event.deleted', 'akademik', 'App\\Models\\AcademicEvent', 11, '{\"deleted_by\":1}', '{\"id\":11,\"title\":\"Cetak Kartu Hasil Studi (KHS)\",\"description\":\"Imported from PDF\",\"event_type\":\"lainnya\",\"start_date\":\"2026-07-26T17:00:00.000000Z\",\"end_date\":\"2026-07-29T17:00:00.000000Z\",\"semester_id\":null,\"color\":\"#6b7280\",\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"created_at\":\"2026-05-22T02:25:44.000000Z\",\"updated_at\":\"2026-05-22T02:25:44.000000Z\"}', NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(264, 1, 'admin', 'semester.updated', 'system', 'App\\Models\\Semester', 1, NULL, '{\"krs_mulai\":\"2026-05-20T17:00:00.000000Z\",\"krs_selesai\":\"2026-07-30T17:00:00.000000Z\"}', '{\"krs_mulai\":\"2025-09-15 00:00:00\",\"krs_selesai\":\"2025-09-17 00:00:00\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(265, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.29\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.29', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(266, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.29\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.29', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(267, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.29\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.29', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(268, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(269, 1, 'admin', 'ruangan.updated', 'system', 'App\\Models\\Ruangan', 18, NULL, '{\"kategori_id\":null}', '{\"kategori_id\":\"1\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(270, 1, 'admin', 'ruangan.updated', 'system', 'App\\Models\\Ruangan', 19, NULL, '{\"kategori_id\":null}', '{\"kategori_id\":\"1\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(271, 1, 'admin', 'ruangan.updated', 'system', 'App\\Models\\Ruangan', 20, NULL, '{\"kategori_id\":null}', '{\"kategori_id\":\"1\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(272, 1, 'admin', 'ruangan.updated', 'system', 'App\\Models\\Ruangan', 21, NULL, '{\"kategori_id\":null}', '{\"kategori_id\":\"1\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(273, 1, 'admin', 'ruangan.updated', 'system', 'App\\Models\\Ruangan', 15, NULL, '{\"kategori_id\":null}', '{\"kategori_id\":\"3\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(274, 6, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 6, '{\"ip\":\"192.168.1.29\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.29', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(275, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(276, 5, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 5, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(277, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(278, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.29\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.29', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(279, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.29\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.29', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(280, 5, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 5, '{\"ip\":\"192.168.1.29\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.29', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(281, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.29\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.29', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(282, 5, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 5, '{\"ip\":\"192.168.1.29\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.29', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(283, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(284, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.7\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(285, 5, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 5, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(286, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(287, 5, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 5, '{\"ip\":\"192.168.1.32\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.32', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(288, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.32\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.32', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(289, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.32\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.32', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(290, 6, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 6, '{\"ip\":\"192.168.1.32\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.32', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(291, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.32\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.32', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(292, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.32\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.32', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(293, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.7\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(294, 5, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 5, '{\"ip\":\"192.168.1.32\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.32', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(295, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(296, 5, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 5, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(297, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(298, 1, 'admin', 'user.updated', 'system', 'App\\Models\\User', 9, NULL, '{\"role\":\"finance\"}', '{\"role\":\"keuangan\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(299, 1, 'admin', 'user.updated', 'system', 'App\\Models\\User', 9, NULL, '{\"email\":\"finance@stih.ac.id\"}', '{\"email\":\"keuangan@stih.ac.id\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(300, 9, 'user', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.7\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(301, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(302, 5, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 5, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(303, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(304, 1, 'admin', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 4, NULL, '{\"semester\":2}', '{\"semester\":3}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(305, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(306, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(307, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(308, 1, 'admin', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 1, NULL, '{\"semester\":2}', '{\"semester\":3}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(309, 5, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 5, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(310, NULL, 'system', 'semester.updated', 'system', 'App\\Models\\Semester', 1, NULL, '{\"is_active\":false}', '{\"is_active\":true}', '127.0.0.1', 'Symfony', NULL, '2026-06-03 03:50:58'),
(311, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(312, NULL, 'system', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 1, NULL, '{\"status_akun\":\"baru\"}', '{\"status_akun\":\"aktif\"}', '127.0.0.1', 'Symfony', NULL, '2026-06-03 03:50:58'),
(313, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(314, 5, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 5, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(315, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(316, 1, 'admin', 'mahasiswa.updated', 'system', 'App\\Models\\Mahasiswa', 1, NULL, '{\"semester\":3}', '{\"semester\":4}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(317, 5, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 5, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(318, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(319, 5, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 5, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(320, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(321, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(322, NULL, 'system', 'user.created', 'system', 'App\\Models\\User', 31, NULL, NULL, '{\"email\":\"superadmin@stih.ac.id\",\"name\":\"Super Admin STIH\",\"role\":\"super_admin\",\"id\":31}', '127.0.0.1', 'Symfony', NULL, '2026-06-03 03:50:58'),
(323, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(324, NULL, 'system', 'user.updated', 'system', 'App\\Models\\User', 1, NULL, '{\"role\":\"admin\"}', '{\"role\":\"super_admin\"}', '127.0.0.1', 'Symfony', NULL, '2026-06-03 03:50:58'),
(325, NULL, 'system', 'user.updated', 'system', 'App\\Models\\User', 1, NULL, '{\"role\":\"super_admin\"}', '{\"role\":\"admin\"}', '127.0.0.1', 'Symfony', NULL, '2026-06-03 03:50:58'),
(326, 31, 'admin', 'user.login', 'auth', 'App\\Models\\User', 31, '{\"ip\":\"192.168.1.7\",\"role\":\"super_admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(327, 31, 'admin', 'user.impersonate_start', 'auth', 'App\\Models\\User', 30, '{\"impersonator_id\":31,\"impersonator_email\":\"superadmin@stih.ac.id\",\"target_id\":30,\"target_email\":\"jojo@student.stih.ac.id\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(328, 31, 'admin', 'user.login', 'auth', 'App\\Models\\User', 31, '{\"ip\":\"192.168.1.7\",\"role\":\"super_admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(329, 31, 'admin', 'nilai.updated', 'akademik', 'App\\Models\\Nilai', 53, NULL, '{\"nilai_akhir\":\"88.13\"}', '{\"nilai_akhir\":100}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(330, 31, 'admin', 'nilai.override', 'akademik', 'App\\Models\\Nilai', 53, '{\"reason\":\"perbaikkan\"}', '{\"nilai_akhir\":\"88.13\",\"grade\":\"A\",\"bobot\":\"4.00\"}', '{\"nilai_akhir\":\"100.00\",\"grade\":\"A\",\"bobot\":\"4.00\"}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(331, 1, 'admin', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(332, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(333, 31, 'admin', 'user.login', 'auth', 'App\\Models\\User', 31, '{\"ip\":\"192.168.1.7\",\"role\":\"super_admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(334, 31, 'super_admin', 'user.login', 'auth', 'App\\Models\\User', 31, '{\"ip\":\"192.168.1.7\",\"role\":\"super_admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(335, 31, 'super_admin', 'system.backup_created', 'system', 'App\\Models\\User', 31, '{\"filename\":\"backup_20260603_101614.sql\",\"size_bytes\":603087}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(336, 31, 'super_admin', 'system.backup_downloaded', 'system', 'App\\Models\\User', 31, '{\"filename\":\"backup_20260603_101614.sql\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(337, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, '2026-06-03 03:50:58'),
(338, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'ZHO9rRvoLb0CODPdlrUYPm7TP6Feo5DtJpx8j0iM', '2026-06-03 04:06:08'),
(339, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'ZHO9rRvoLb0CODPdlrUYPm7TP6Feo5DtJpx8j0iM', '2026-06-03 04:06:08'),
(340, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'UIGr2gyuhsqtqVqiyMLpIKuuTXKICL78IQbZT8T4', '2026-06-03 04:06:51'),
(341, 31, 'super_admin', 'permission.assigned', 'system', 'Spatie\\Permission\\Models\\Role', 2, '{\"role\":\"akademik\",\"permission\":\"impersonate-user\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'LJFh5zCGTxnckOHKygNTjeXVsWU4uRk0U3pao3RC', '2026-06-03 04:09:01'),
(342, 31, 'super_admin', 'permission.revoked', 'system', 'Spatie\\Permission\\Models\\Role', 2, '{\"role\":\"akademik\",\"permission\":\"impersonate-user\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'LJFh5zCGTxnckOHKygNTjeXVsWU4uRk0U3pao3RC', '2026-06-03 04:09:03'),
(343, 31, 'super_admin', 'nilai.updated', 'akademik', 'App\\Models\\Nilai', 53, NULL, '{\"nilai_akhir\":\"100.00\"}', '{\"nilai_akhir\":90}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'LJFh5zCGTxnckOHKygNTjeXVsWU4uRk0U3pao3RC', '2026-06-03 04:58:22'),
(344, 31, 'super_admin', 'nilai.override', 'akademik', 'App\\Models\\Nilai', 53, '{\"reason\":\"testing testing\",\"mahasiswa_id\":6,\"krs_id\":53,\"mata_kuliah\":\"Bahasa Indonesia Hukum\"}', '{\"nilai_akhir\":\"100.00\",\"grade\":\"A\",\"bobot\":\"4.00\",\"is_published\":true}', '{\"nilai_akhir\":90,\"grade\":\"A\",\"bobot\":4}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'LJFh5zCGTxnckOHKygNTjeXVsWU4uRk0U3pao3RC', '2026-06-03 04:58:22'),
(345, 31, 'super_admin', 'user.impersonate_start', 'auth', 'App\\Models\\User', 29, '{\"impersonator_id\":31,\"impersonator_email\":\"superadmin@stih.ac.id\",\"target_id\":29,\"target_email\":\"2024001@parent.stih.ac.id\",\"target_role\":\"parents\",\"reason\":\"Tidak ada alasan\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'LJFh5zCGTxnckOHKygNTjeXVsWU4uRk0U3pao3RC', '2026-06-03 04:58:54'),
(346, 29, 'parents', 'parent.view_dashboard', 'system', 'App\\Models\\Mahasiswa', 4, '{\"student_name\":\"Ahmad Mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'c2vzQZxY7mF3nTKEWDU7UtwfStyVMXEKOIARozNt', '2026-06-03 04:58:54'),
(347, 29, 'parents', 'parent.view_dashboard', 'system', 'App\\Models\\Mahasiswa', 4, '{\"student_name\":\"Ahmad Mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'c2vzQZxY7mF3nTKEWDU7UtwfStyVMXEKOIARozNt', '2026-06-03 04:59:07'),
(348, 29, 'parents', 'user.logout', 'auth', 'App\\Models\\User', 29, '{\"user_id\":29,\"user_email\":\"2024001@parent.stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'c2vzQZxY7mF3nTKEWDU7UtwfStyVMXEKOIARozNt', '2026-06-03 04:59:08'),
(349, 29, 'parents', 'user.logout', 'auth', 'App\\Models\\User', 29, '{\"user_id\":29,\"user_email\":\"2024001@parent.stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'c2vzQZxY7mF3nTKEWDU7UtwfStyVMXEKOIARozNt', '2026-06-03 04:59:08'),
(350, 31, 'super_admin', 'user.login', 'auth', 'App\\Models\\User', 31, '{\"ip\":\"192.168.1.7\",\"role\":\"super_admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '1iyzwNrIIReAcLlVtzOVmSUs4cKzOq0GUvOc7Fnl', '2026-06-03 04:59:27'),
(351, 31, 'super_admin', 'permission.assigned', 'system', 'Spatie\\Permission\\Models\\Role', 2, '{\"role\":\"akademik\",\"permission\":\"impersonate-user\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '1iyzwNrIIReAcLlVtzOVmSUs4cKzOq0GUvOc7Fnl', '2026-06-03 05:12:14'),
(352, 31, 'super_admin', 'permission.assigned', 'system', 'Spatie\\Permission\\Models\\Role', 2, '{\"role\":\"akademik\",\"permission\":\"manage-permissions\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '1iyzwNrIIReAcLlVtzOVmSUs4cKzOq0GUvOc7Fnl', '2026-06-03 05:12:14'),
(353, 31, 'super_admin', 'permission.revoked', 'system', 'Spatie\\Permission\\Models\\Role', 2, '{\"role\":\"akademik\",\"permission\":\"manage-permissions\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '1iyzwNrIIReAcLlVtzOVmSUs4cKzOq0GUvOc7Fnl', '2026-06-03 05:12:16'),
(354, 31, 'super_admin', 'permission.revoked', 'system', 'Spatie\\Permission\\Models\\Role', 2, '{\"role\":\"akademik\",\"permission\":\"impersonate-user\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '1iyzwNrIIReAcLlVtzOVmSUs4cKzOq0GUvOc7Fnl', '2026-06-03 05:12:17'),
(355, 31, 'super_admin', 'system.backup_deleted', 'system', 'App\\Models\\User', 31, '{\"filename\":\"backup_20260603_101614.sql\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '1iyzwNrIIReAcLlVtzOVmSUs4cKzOq0GUvOc7Fnl', '2026-06-03 05:14:45'),
(356, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'UIGr2gyuhsqtqVqiyMLpIKuuTXKICL78IQbZT8T4', '2026-06-03 05:25:24'),
(357, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'UIGr2gyuhsqtqVqiyMLpIKuuTXKICL78IQbZT8T4', '2026-06-03 05:25:24'),
(358, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'tGaZJI34YrpwJaM8CGxgoIjpXxcnDKEkad94ptak', '2026-06-03 05:25:32'),
(359, 31, 'super_admin', 'user.logout', 'auth', 'App\\Models\\User', 31, '{\"user_id\":31,\"user_email\":\"superadmin@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '1iyzwNrIIReAcLlVtzOVmSUs4cKzOq0GUvOc7Fnl', '2026-06-03 07:18:02'),
(360, 31, 'super_admin', 'user.logout', 'auth', 'App\\Models\\User', 31, '{\"user_id\":31,\"user_email\":\"superadmin@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '1iyzwNrIIReAcLlVtzOVmSUs4cKzOq0GUvOc7Fnl', '2026-06-03 07:18:02'),
(361, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'hcgOscCEjTLSOvf2X2rZKXJqDgUFoJpPesIhCakq', '2026-06-03 07:18:07'),
(362, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'hcgOscCEjTLSOvf2X2rZKXJqDgUFoJpPesIhCakq', '2026-06-03 07:18:27'),
(363, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'hcgOscCEjTLSOvf2X2rZKXJqDgUFoJpPesIhCakq', '2026-06-03 07:18:27'),
(364, 31, 'super_admin', 'user.login', 'auth', 'App\\Models\\User', 31, '{\"ip\":\"192.168.1.7\",\"role\":\"super_admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'vxfw6o9CiT8ZKZiWRQcDAVIAMgdPU4RaSK5kLdjR', '2026-06-03 07:18:32'),
(365, 5, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 5, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'pXhLfr5monYs5jKdIYgS4VZsg2F7G4xp0uRaKRNl', '2026-06-04 02:11:27'),
(366, 5, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 5, '{\"user_id\":5,\"user_email\":\"andipratama@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'pXhLfr5monYs5jKdIYgS4VZsg2F7G4xp0uRaKRNl', '2026-06-04 02:11:47'),
(367, 5, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 5, '{\"user_id\":5,\"user_email\":\"andipratama@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'pXhLfr5monYs5jKdIYgS4VZsg2F7G4xp0uRaKRNl', '2026-06-04 02:11:47');
INSERT INTO `audit_logs` (`id`, `actor_id`, `actor_role`, `action`, `module`, `auditable_type`, `auditable_id`, `meta`, `before`, `after`, `ip_address`, `user_agent`, `session_id`, `created_at`) VALUES
(368, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'rmuzOPbttFRAhNJ3vtMMcskRCz95ZUQHbcm4VJkZ', '2026-06-04 02:11:52'),
(369, 31, 'super_admin', 'user.login', 'auth', 'App\\Models\\User', 31, '{\"ip\":\"192.168.1.7\",\"role\":\"super_admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'ifxgl9xc4ixIExyptMky5iJiEcs5ecLc1kvMvX40', '2026-06-04 02:16:07'),
(370, 31, 'super_admin', 'user.logout', 'auth', 'App\\Models\\User', 31, '{\"user_id\":31,\"user_email\":\"superadmin@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'ifxgl9xc4ixIExyptMky5iJiEcs5ecLc1kvMvX40', '2026-06-04 02:18:03'),
(371, 31, 'super_admin', 'user.logout', 'auth', 'App\\Models\\User', 31, '{\"user_id\":31,\"user_email\":\"superadmin@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'ifxgl9xc4ixIExyptMky5iJiEcs5ecLc1kvMvX40', '2026-06-04 02:18:03'),
(372, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '3R3vj204BEKxuonxKJyZXozgeAau8kINZBL8b3Mv', '2026-06-04 02:18:07'),
(373, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'rmuzOPbttFRAhNJ3vtMMcskRCz95ZUQHbcm4VJkZ', '2026-06-04 02:28:31'),
(374, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'rmuzOPbttFRAhNJ3vtMMcskRCz95ZUQHbcm4VJkZ', '2026-06-04 02:28:31'),
(375, 5, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 5, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'f1XUNvdKGPpne1IKbNOJy1CSweo8b2zNmhsHsXVU', '2026-06-04 02:28:40'),
(376, 5, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 5, '{\"user_id\":5,\"user_email\":\"andipratama@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'f1XUNvdKGPpne1IKbNOJy1CSweo8b2zNmhsHsXVU', '2026-06-04 02:40:41'),
(377, 5, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 5, '{\"user_id\":5,\"user_email\":\"andipratama@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'f1XUNvdKGPpne1IKbNOJy1CSweo8b2zNmhsHsXVU', '2026-06-04 02:40:41'),
(378, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'hr6mMhdplvmXryZcMX0R1GKqrRZ2MS7B7lXMOwh1', '2026-06-04 02:40:50'),
(379, 10, 'mahasiswa', 'skripsi.proposal_submitted', 'skripsi', 'App\\Models\\SkripsiSubmission', 2, '{\"judul\":\"TEST SKRIPSI YA\",\"supervisor_id\":\"1\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'hr6mMhdplvmXryZcMX0R1GKqrRZ2MS7B7lXMOwh1', '2026-06-04 03:05:15'),
(380, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'hr6mMhdplvmXryZcMX0R1GKqrRZ2MS7B7lXMOwh1', '2026-06-04 03:06:01'),
(381, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'hr6mMhdplvmXryZcMX0R1GKqrRZ2MS7B7lXMOwh1', '2026-06-04 03:06:01'),
(382, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.16\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'SvZQFtAjThMZJs72O8nzwa8DaY1dFLrjMQ4p3s31', '2026-06-04 03:06:05'),
(383, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '3R3vj204BEKxuonxKJyZXozgeAau8kINZBL8b3Mv', '2026-06-04 03:10:36'),
(384, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '3R3vj204BEKxuonxKJyZXozgeAau8kINZBL8b3Mv', '2026-06-04 03:10:36'),
(385, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'JMvd1ERdkenbEOTwAAx9oVpsNqeTHZciqq4lneS1', '2026-06-04 03:10:42'),
(386, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'JMvd1ERdkenbEOTwAAx9oVpsNqeTHZciqq4lneS1', '2026-06-04 03:10:49'),
(387, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'JMvd1ERdkenbEOTwAAx9oVpsNqeTHZciqq4lneS1', '2026-06-04 03:10:49'),
(388, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'V7GnnBc1qXgmGkPJdAeFrzpZNifBggeZh0U1sRSi', '2026-06-04 03:10:58'),
(389, 10, 'mahasiswa', 'skripsi.proposal_submitted', 'skripsi', 'App\\Models\\SkripsiSubmission', 3, '{\"judul\":\"Sentimen DOSEN RESEK\",\"supervisor_id\":\"1\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'V7GnnBc1qXgmGkPJdAeFrzpZNifBggeZh0U1sRSi', '2026-06-04 03:16:16'),
(390, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'SvZQFtAjThMZJs72O8nzwa8DaY1dFLrjMQ4p3s31', '2026-06-04 03:16:55'),
(391, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'SvZQFtAjThMZJs72O8nzwa8DaY1dFLrjMQ4p3s31', '2026-06-04 03:16:55'),
(392, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'sqJdd8KzcqqAhn9vDNTfztb8scCCjjx1kXHEaUFz', '2026-06-04 03:16:58'),
(393, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'sqJdd8KzcqqAhn9vDNTfztb8scCCjjx1kXHEaUFz', '2026-06-04 03:17:15'),
(394, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'sqJdd8KzcqqAhn9vDNTfztb8scCCjjx1kXHEaUFz', '2026-06-04 03:17:15'),
(395, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.16\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'jVle5lKdp8YQvKMKnSvMvpN5z51JBgBD509E9ovz', '2026-06-04 03:17:19'),
(396, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'jVle5lKdp8YQvKMKnSvMvpN5z51JBgBD509E9ovz', '2026-06-04 03:20:38'),
(397, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'jVle5lKdp8YQvKMKnSvMvpN5z51JBgBD509E9ovz', '2026-06-04 03:20:38'),
(398, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '5D2ZqtJEXPa4i5u0yo2hPwjBkX1yIG47oSSeLg0K', '2026-06-04 03:20:45'),
(399, 10, 'mahasiswa', 'skripsi.proposal_submitted', 'skripsi', 'App\\Models\\SkripsiSubmission', 4, '{\"judul\":\"Sentimen DOSEN RESEK\",\"supervisor_id\":\"1\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'V7GnnBc1qXgmGkPJdAeFrzpZNifBggeZh0U1sRSi', '2026-06-04 03:20:57'),
(400, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'V7GnnBc1qXgmGkPJdAeFrzpZNifBggeZh0U1sRSi', '2026-06-04 03:21:20'),
(401, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'V7GnnBc1qXgmGkPJdAeFrzpZNifBggeZh0U1sRSi', '2026-06-04 03:21:20'),
(402, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'wJ4zHSi2aWyqft4ns726Tq5AnaSjzCkP4wAdQdUj', '2026-06-04 03:21:24'),
(403, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'wJ4zHSi2aWyqft4ns726Tq5AnaSjzCkP4wAdQdUj', '2026-06-04 03:21:38'),
(404, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'wJ4zHSi2aWyqft4ns726Tq5AnaSjzCkP4wAdQdUj', '2026-06-04 03:21:38'),
(405, 10, 'mahasiswa', 'skripsi.proposal_submitted', 'skripsi', 'App\\Models\\SkripsiSubmission', 5, '{\"judul\":\"TEST SKRIPSI\",\"supervisor_id\":\"1\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '5D2ZqtJEXPa4i5u0yo2hPwjBkX1yIG47oSSeLg0K', '2026-06-04 03:22:02'),
(406, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Q1nxWjgiqKUdrrfQpvgig0glGKTdnTphCYWuxbqh', '2026-06-04 03:22:12'),
(407, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '5D2ZqtJEXPa4i5u0yo2hPwjBkX1yIG47oSSeLg0K', '2026-06-04 03:22:26'),
(408, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '5D2ZqtJEXPa4i5u0yo2hPwjBkX1yIG47oSSeLg0K', '2026-06-04 03:22:26'),
(409, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.16\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'yzq5NL6Htymq9UZ3QCtbkkXr0kZTxM9sxWlJ6vCq', '2026-06-04 03:22:29'),
(410, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'yzq5NL6Htymq9UZ3QCtbkkXr0kZTxM9sxWlJ6vCq', '2026-06-04 03:22:35'),
(411, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'yzq5NL6Htymq9UZ3QCtbkkXr0kZTxM9sxWlJ6vCq', '2026-06-04 03:22:35'),
(412, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Q1nxWjgiqKUdrrfQpvgig0glGKTdnTphCYWuxbqh', '2026-06-04 03:22:36'),
(413, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Q1nxWjgiqKUdrrfQpvgig0glGKTdnTphCYWuxbqh', '2026-06-04 03:22:36'),
(414, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.7\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'LnHjqPT7YN23UD8ow4hlsw1IJKep2bJsSibtw5Rd', '2026-06-04 03:22:40'),
(415, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.16\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '8EdgoLHS1DMUDobQUDfKukpoRIpxsvGrqXTTDfTn', '2026-06-04 03:22:41'),
(416, 2, 'dosen', 'skripsi.supervisor_accepted', 'skripsi', 'App\\Models\\SkripsiSubmission', 5, '{\"mahasiswa_id\":4}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '8EdgoLHS1DMUDobQUDfKukpoRIpxsvGrqXTTDfTn', '2026-06-04 03:23:09'),
(417, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '8EdgoLHS1DMUDobQUDfKukpoRIpxsvGrqXTTDfTn', '2026-06-04 03:23:19'),
(418, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '8EdgoLHS1DMUDobQUDfKukpoRIpxsvGrqXTTDfTn', '2026-06-04 03:23:19'),
(419, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.16\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'm90m6tRlJJeXWbCj3BUHzQPrbdh6gSf5FlRSX7Xe', '2026-06-04 03:23:23'),
(420, 2, 'dosen', 'skripsi.supervisor_accepted', 'skripsi', 'App\\Models\\SkripsiSubmission', 4, '{\"mahasiswa_id\":4}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'LnHjqPT7YN23UD8ow4hlsw1IJKep2bJsSibtw5Rd', '2026-06-04 03:24:13'),
(421, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'm90m6tRlJJeXWbCj3BUHzQPrbdh6gSf5FlRSX7Xe', '2026-06-04 03:24:25'),
(422, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'm90m6tRlJJeXWbCj3BUHzQPrbdh6gSf5FlRSX7Xe', '2026-06-04 03:24:25'),
(423, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'SkrAZ53nPg8T00t4LKcGMq9EBkBHZlIuiOVMW0SO', '2026-06-04 03:24:29'),
(424, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'SkrAZ53nPg8T00t4LKcGMq9EBkBHZlIuiOVMW0SO', '2026-06-04 03:24:43'),
(425, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'SkrAZ53nPg8T00t4LKcGMq9EBkBHZlIuiOVMW0SO', '2026-06-04 03:24:43'),
(426, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.16\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'NY2eukvP8iD5lIUkhzhFkkfr21gdqOPdT4PzHKAc', '2026-06-04 03:24:46'),
(427, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'NY2eukvP8iD5lIUkhzhFkkfr21gdqOPdT4PzHKAc', '2026-06-04 03:25:07'),
(428, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'NY2eukvP8iD5lIUkhzhFkkfr21gdqOPdT4PzHKAc', '2026-06-04 03:25:07'),
(429, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'TNIQKuC25LtBnIXTDhj1hsW5prCl5toY5m71hi5Z', '2026-06-04 03:25:11'),
(430, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'LnHjqPT7YN23UD8ow4hlsw1IJKep2bJsSibtw5Rd', '2026-06-04 03:26:29'),
(431, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'LnHjqPT7YN23UD8ow4hlsw1IJKep2bJsSibtw5Rd', '2026-06-04 03:26:29'),
(432, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '9o6ho3XlLZccDWn5redvgh613dFlhpt8fZwj7YFF', '2026-06-04 03:26:33'),
(433, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'TNIQKuC25LtBnIXTDhj1hsW5prCl5toY5m71hi5Z', '2026-06-04 04:07:21'),
(434, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'TNIQKuC25LtBnIXTDhj1hsW5prCl5toY5m71hi5Z', '2026-06-04 04:07:21'),
(435, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.16\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'MFQJBajNZfLe7vmYLrkBbNgyt1CW39jU7j4EGaFP', '2026-06-04 04:07:31'),
(436, 2, 'dosen', 'skripsi.guidance_approved', 'skripsi', 'App\\Models\\SkripsiGuidance', 1, '{\"mahasiswa_id\":4,\"note\":null}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'MFQJBajNZfLe7vmYLrkBbNgyt1CW39jU7j4EGaFP', '2026-06-04 04:07:48'),
(437, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'MFQJBajNZfLe7vmYLrkBbNgyt1CW39jU7j4EGaFP', '2026-06-04 04:08:54'),
(438, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'MFQJBajNZfLe7vmYLrkBbNgyt1CW39jU7j4EGaFP', '2026-06-04 04:08:54'),
(439, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'FLIGBEPNlgrVyI0zxVgWaL4xhZaTVfijBGJMo2hN', '2026-06-04 04:09:05'),
(440, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'FLIGBEPNlgrVyI0zxVgWaL4xhZaTVfijBGJMo2hN', '2026-06-04 04:28:38'),
(441, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'FLIGBEPNlgrVyI0zxVgWaL4xhZaTVfijBGJMo2hN', '2026-06-04 04:28:38'),
(442, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.16\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'OoxfxjwwXsR2J4RWO1ceBlBwjmT1EuAquGzmi2ga', '2026-06-04 04:28:44'),
(443, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'OoxfxjwwXsR2J4RWO1ceBlBwjmT1EuAquGzmi2ga', '2026-06-04 04:30:39'),
(444, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'OoxfxjwwXsR2J4RWO1ceBlBwjmT1EuAquGzmi2ga', '2026-06-04 04:30:39'),
(445, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'ygvGBc5f2qeaoERLWGoudodnh3HWVbUG9zQh2cQA', '2026-06-04 04:30:44'),
(446, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'ygvGBc5f2qeaoERLWGoudodnh3HWVbUG9zQh2cQA', '2026-06-04 04:36:54'),
(447, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'ygvGBc5f2qeaoERLWGoudodnh3HWVbUG9zQh2cQA', '2026-06-04 04:36:54'),
(448, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '9VEgVV7ucYPi1sEAS5jyn4B8ROu8hbvItevgF3lh', '2026-06-04 04:36:59'),
(449, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '9VEgVV7ucYPi1sEAS5jyn4B8ROu8hbvItevgF3lh', '2026-06-04 04:37:14'),
(450, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '9VEgVV7ucYPi1sEAS5jyn4B8ROu8hbvItevgF3lh', '2026-06-04 04:37:14'),
(451, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.16\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'F5OoZ1VSpJmTc27uEA29cXdTxopTt1rsYXgmy1lT', '2026-06-04 04:37:21'),
(452, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'F5OoZ1VSpJmTc27uEA29cXdTxopTt1rsYXgmy1lT', '2026-06-04 04:37:38'),
(453, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'F5OoZ1VSpJmTc27uEA29cXdTxopTt1rsYXgmy1lT', '2026-06-04 04:37:38'),
(454, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'lNivozxZB4bbGemCnXlCYfHYDKxmUUHuArObNEhk', '2026-06-04 04:37:44'),
(455, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'lNivozxZB4bbGemCnXlCYfHYDKxmUUHuArObNEhk', '2026-06-04 04:43:32'),
(456, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'lNivozxZB4bbGemCnXlCYfHYDKxmUUHuArObNEhk', '2026-06-04 04:43:33'),
(457, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.16\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'CQJraAT8lrk7VaC0Ok7aBIv1yLV77EkoD8StT4lx', '2026-06-04 04:43:37'),
(458, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'CQJraAT8lrk7VaC0Ok7aBIv1yLV77EkoD8StT4lx', '2026-06-04 04:43:58'),
(459, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'CQJraAT8lrk7VaC0Ok7aBIv1yLV77EkoD8StT4lx', '2026-06-04 04:43:58'),
(460, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '7vDkmg6Mwafo7lvynM6WIJdvLgq6vPi7z3En2DTC', '2026-06-04 04:44:04'),
(461, 10, 'mahasiswa', 'skripsi.revision_uploaded', 'skripsi', 'App\\Models\\SkripsiSubmission', 4, '{\"notes\":null}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '7vDkmg6Mwafo7lvynM6WIJdvLgq6vPi7z3En2DTC', '2026-06-04 04:55:11'),
(462, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '7vDkmg6Mwafo7lvynM6WIJdvLgq6vPi7z3En2DTC', '2026-06-04 04:55:15'),
(463, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '7vDkmg6Mwafo7lvynM6WIJdvLgq6vPi7z3En2DTC', '2026-06-04 04:55:15'),
(464, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.16\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'OjvHuHPCWs4NK8hcft36AKwtTMAFzMoOTMFHMhnN', '2026-06-04 04:55:20'),
(465, 2, 'dosen', 'skripsi.revision_approved', 'skripsi', 'App\\Models\\SkripsiSubmission', 4, '{\"revision_id\":2,\"notes\":null}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'OjvHuHPCWs4NK8hcft36AKwtTMAFzMoOTMFHMhnN', '2026-06-04 04:55:34'),
(466, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'OjvHuHPCWs4NK8hcft36AKwtTMAFzMoOTMFHMhnN', '2026-06-04 04:55:36'),
(467, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'OjvHuHPCWs4NK8hcft36AKwtTMAFzMoOTMFHMhnN', '2026-06-04 04:55:36'),
(468, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'quNpmfyaeejtaWV9ZWEh3Soina65JRrvpc16Bwi0', '2026-06-04 04:55:45'),
(469, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'quNpmfyaeejtaWV9ZWEh3Soina65JRrvpc16Bwi0', '2026-06-04 04:56:54'),
(470, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'quNpmfyaeejtaWV9ZWEh3Soina65JRrvpc16Bwi0', '2026-06-04 04:56:54'),
(471, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'U1UD5eIegjUpLfVkQ4P01czVDnPlchdGSWMCPNQP', '2026-06-04 04:57:12'),
(472, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'U1UD5eIegjUpLfVkQ4P01czVDnPlchdGSWMCPNQP', '2026-06-04 04:57:20'),
(473, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'U1UD5eIegjUpLfVkQ4P01czVDnPlchdGSWMCPNQP', '2026-06-04 04:57:20'),
(474, 5, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 5, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '9UsGK9gdbjHSdRCBUreF1xqVyat1aCOgscYPgWzg', '2026-06-04 04:57:33'),
(475, 5, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 5, '{\"user_id\":5,\"user_email\":\"andipratama@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '9UsGK9gdbjHSdRCBUreF1xqVyat1aCOgscYPgWzg', '2026-06-04 04:57:38'),
(476, 5, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 5, '{\"user_id\":5,\"user_email\":\"andipratama@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '9UsGK9gdbjHSdRCBUreF1xqVyat1aCOgscYPgWzg', '2026-06-04 04:57:38'),
(477, 6, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 6, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '0KI4hcTScW3NeN7ykybrRzu0yixIrbBODWvxclRt', '2026-06-04 04:57:43'),
(478, 6, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 6, '{\"user_id\":6,\"user_email\":\"dewi.lestari@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '0KI4hcTScW3NeN7ykybrRzu0yixIrbBODWvxclRt', '2026-06-04 04:57:48');
INSERT INTO `audit_logs` (`id`, `actor_id`, `actor_role`, `action`, `module`, `auditable_type`, `auditable_id`, `meta`, `before`, `after`, `ip_address`, `user_agent`, `session_id`, `created_at`) VALUES
(479, 6, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 6, '{\"user_id\":6,\"user_email\":\"dewi.lestari@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '0KI4hcTScW3NeN7ykybrRzu0yixIrbBODWvxclRt', '2026-06-04 04:57:48'),
(480, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'iGVEEptPhnD4okdzt05ZXUt5fIt2zp7HBWLeTySk', '2026-06-04 04:59:02'),
(481, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'iGVEEptPhnD4okdzt05ZXUt5fIt2zp7HBWLeTySk', '2026-06-04 04:59:11'),
(482, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'iGVEEptPhnD4okdzt05ZXUt5fIt2zp7HBWLeTySk', '2026-06-04 04:59:11'),
(483, 9, 'keuangan', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.16\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'kzCYoX8AV2REZVILfvjePDmTGA9ym58LTAq02eMS', '2026-06-04 04:59:16'),
(484, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '9o6ho3XlLZccDWn5redvgh613dFlhpt8fZwj7YFF', '2026-06-04 05:00:58'),
(485, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '9o6ho3XlLZccDWn5redvgh613dFlhpt8fZwj7YFF', '2026-06-04 05:00:58'),
(486, 9, 'keuangan', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.7\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'e58Yh0ZM1xZ7cHo8igjpjy1fuKnvaFdVqIT3xmjI', '2026-06-04 05:01:04'),
(487, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'kzCYoX8AV2REZVILfvjePDmTGA9ym58LTAq02eMS', '2026-06-04 05:04:08'),
(488, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'kzCYoX8AV2REZVILfvjePDmTGA9ym58LTAq02eMS', '2026-06-04 05:04:08'),
(489, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'e58Yh0ZM1xZ7cHo8igjpjy1fuKnvaFdVqIT3xmjI', '2026-06-04 05:04:10'),
(490, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'e58Yh0ZM1xZ7cHo8igjpjy1fuKnvaFdVqIT3xmjI', '2026-06-04 05:04:10'),
(491, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'xBKXFkkga7EviKEKHSrsXU0UbHMImCqQAXDiH5CP', '2026-06-04 05:04:12'),
(492, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.7\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'hsIXu7dgmpVMwX3fDbmhgsRKMcec5Hp1gvAcbhkW', '2026-06-04 05:04:20'),
(493, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'hsIXu7dgmpVMwX3fDbmhgsRKMcec5Hp1gvAcbhkW', '2026-06-04 05:04:23'),
(494, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'hsIXu7dgmpVMwX3fDbmhgsRKMcec5Hp1gvAcbhkW', '2026-06-04 05:04:23'),
(495, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'NfwGFeNSArTblgJbOj028knpl053qFtEgTuZbJEP', '2026-06-04 05:04:32'),
(496, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'NfwGFeNSArTblgJbOj028knpl053qFtEgTuZbJEP', '2026-06-04 05:05:22'),
(497, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'NfwGFeNSArTblgJbOj028knpl053qFtEgTuZbJEP', '2026-06-04 05:05:22'),
(498, 9, 'keuangan', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.7\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '4tYhBeabnP0MvXFsPw096pd0amRpB1soCj65jplX', '2026-06-04 05:05:30'),
(499, 9, 'keuangan', 'invoice.completed', 'keuangan', 'App\\Models\\Invoice', 17, '{\"payment_id\":3}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '4tYhBeabnP0MvXFsPw096pd0amRpB1soCj65jplX', '2026-06-04 05:05:47'),
(500, 9, 'keuangan', 'payment_proof.approve', 'keuangan', 'App\\Models\\PaymentProof', 3, '{\"payment_id\":3,\"installment_id\":null,\"amount\":10000000,\"notes\":null}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '4tYhBeabnP0MvXFsPw096pd0amRpB1soCj65jplX', '2026-06-04 05:05:47'),
(501, 9, 'keuangan', 'invoice.completed', 'keuangan', 'App\\Models\\Invoice', 16, '{\"payment_id\":4}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '4tYhBeabnP0MvXFsPw096pd0amRpB1soCj65jplX', '2026-06-04 05:05:52'),
(502, 9, 'keuangan', 'payment_proof.approve', 'keuangan', 'App\\Models\\PaymentProof', 4, '{\"payment_id\":4,\"installment_id\":null,\"amount\":10000000,\"notes\":null}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '4tYhBeabnP0MvXFsPw096pd0amRpB1soCj65jplX', '2026-06-04 05:05:52'),
(503, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'xBKXFkkga7EviKEKHSrsXU0UbHMImCqQAXDiH5CP', '2026-06-04 05:06:15'),
(504, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'xBKXFkkga7EviKEKHSrsXU0UbHMImCqQAXDiH5CP', '2026-06-04 05:06:15'),
(505, 9, 'keuangan', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.16\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'tlI7LuMAALFyU2kzsAxqM5vrXPxOqR0JTdw3mObp', '2026-06-04 05:06:20'),
(506, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '4tYhBeabnP0MvXFsPw096pd0amRpB1soCj65jplX', '2026-06-04 05:06:37'),
(507, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '4tYhBeabnP0MvXFsPw096pd0amRpB1soCj65jplX', '2026-06-04 05:06:37'),
(508, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.7\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '9w9mBoVDndCP89SfEIKMqMoOYRVSMNJHCjIRQ3wk', '2026-06-04 05:06:46'),
(509, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'tlI7LuMAALFyU2kzsAxqM5vrXPxOqR0JTdw3mObp', '2026-06-04 05:06:57'),
(510, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'tlI7LuMAALFyU2kzsAxqM5vrXPxOqR0JTdw3mObp', '2026-06-04 05:06:57'),
(511, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'axu0WxjebAUchqv1p4aogwEKrJvnKUy3UIPUL8oZ', '2026-06-04 05:07:00'),
(512, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'axu0WxjebAUchqv1p4aogwEKrJvnKUy3UIPUL8oZ', '2026-06-04 05:07:32'),
(513, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'axu0WxjebAUchqv1p4aogwEKrJvnKUy3UIPUL8oZ', '2026-06-04 05:07:32'),
(514, 9, 'keuangan', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.16\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'INjscuHxANJaNHXGZHkpKoJ69VMPVAZ07lhLmTAc', '2026-06-04 05:07:36'),
(515, 9, 'keuangan', 'invoice.completed', 'keuangan', 'App\\Models\\Invoice', 19, '{\"payment_id\":5}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'INjscuHxANJaNHXGZHkpKoJ69VMPVAZ07lhLmTAc', '2026-06-04 05:07:49'),
(516, 9, 'keuangan', 'payment_proof.approve', 'keuangan', 'App\\Models\\PaymentProof', 5, '{\"payment_id\":5,\"installment_id\":null,\"amount\":10000000,\"notes\":null}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'INjscuHxANJaNHXGZHkpKoJ69VMPVAZ07lhLmTAc', '2026-06-04 05:07:49'),
(517, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'INjscuHxANJaNHXGZHkpKoJ69VMPVAZ07lhLmTAc', '2026-06-04 05:07:57'),
(518, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'INjscuHxANJaNHXGZHkpKoJ69VMPVAZ07lhLmTAc', '2026-06-04 05:07:57'),
(519, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'JNVSKFlnpUi0WjRIWSJqva8Up2YQAUqBjUxV6U3L', '2026-06-04 05:08:01'),
(520, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'JNVSKFlnpUi0WjRIWSJqva8Up2YQAUqBjUxV6U3L', '2026-06-04 05:08:25'),
(521, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'JNVSKFlnpUi0WjRIWSJqva8Up2YQAUqBjUxV6U3L', '2026-06-04 05:08:25'),
(522, 9, 'keuangan', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.16\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'G10TrizKnl7frjYobnjVbuB47xdfmtt2YtvWiDwV', '2026-06-04 05:08:30'),
(523, 9, 'keuangan', 'invoice.completed', 'keuangan', 'App\\Models\\Invoice', 18, '{\"payment_id\":6}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'G10TrizKnl7frjYobnjVbuB47xdfmtt2YtvWiDwV', '2026-06-04 05:08:38'),
(524, 9, 'keuangan', 'payment_proof.approve', 'keuangan', 'App\\Models\\PaymentProof', 6, '{\"payment_id\":6,\"installment_id\":null,\"amount\":10000000,\"notes\":null}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'G10TrizKnl7frjYobnjVbuB47xdfmtt2YtvWiDwV', '2026-06-04 05:08:38'),
(525, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'G10TrizKnl7frjYobnjVbuB47xdfmtt2YtvWiDwV', '2026-06-04 05:08:40'),
(526, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'G10TrizKnl7frjYobnjVbuB47xdfmtt2YtvWiDwV', '2026-06-04 05:08:40'),
(527, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2FYiicvfeSQj2QpxXmFyNd5QiA0injDgPDxgURut', '2026-06-04 05:08:44'),
(528, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2FYiicvfeSQj2QpxXmFyNd5QiA0injDgPDxgURut', '2026-06-04 05:38:27'),
(529, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2FYiicvfeSQj2QpxXmFyNd5QiA0injDgPDxgURut', '2026-06-04 05:38:27'),
(530, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.16\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'PrL9pwZ3QOLN1Dhzb5DTtLKJEhKZjZIVTDbht0wC', '2026-06-04 05:38:32'),
(531, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'PrL9pwZ3QOLN1Dhzb5DTtLKJEhKZjZIVTDbht0wC', '2026-06-04 05:38:55'),
(532, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'PrL9pwZ3QOLN1Dhzb5DTtLKJEhKZjZIVTDbht0wC', '2026-06-04 05:38:55'),
(533, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '5NASGjm0Wdvm4ksSBYOigKdE4hhCfD0iMCuPTQhV', '2026-06-04 05:39:00'),
(534, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '5NASGjm0Wdvm4ksSBYOigKdE4hhCfD0iMCuPTQhV', '2026-06-04 05:39:15'),
(535, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '5NASGjm0Wdvm4ksSBYOigKdE4hhCfD0iMCuPTQhV', '2026-06-04 05:39:15'),
(536, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.16\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '0T6tFFKcHDBNCRoOCDETHwEhXXyhu3sQsPt4CoIs', '2026-06-04 05:39:20'),
(537, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '0T6tFFKcHDBNCRoOCDETHwEhXXyhu3sQsPt4CoIs', '2026-06-04 05:39:24'),
(538, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '0T6tFFKcHDBNCRoOCDETHwEhXXyhu3sQsPt4CoIs', '2026-06-04 05:39:24'),
(539, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.16\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '3aXbbwTdwhR67fcu5ewUPK3Y6D1GdfIXeY9yCo2P', '2026-06-04 05:39:27'),
(540, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '3aXbbwTdwhR67fcu5ewUPK3Y6D1GdfIXeY9yCo2P', '2026-06-04 05:39:42'),
(541, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '3aXbbwTdwhR67fcu5ewUPK3Y6D1GdfIXeY9yCo2P', '2026-06-04 05:39:42'),
(542, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'SHuP3qflIrSkHKQ26Rilcu40vUFqgJN5obASBhiB', '2026-06-04 05:39:47'),
(543, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '9w9mBoVDndCP89SfEIKMqMoOYRVSMNJHCjIRQ3wk', '2026-06-04 05:54:19'),
(544, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.7\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '9w9mBoVDndCP89SfEIKMqMoOYRVSMNJHCjIRQ3wk', '2026-06-04 05:54:19'),
(545, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'thSvK232iY9tuBcOlahttscE10zd8PUFwb2vxXPP', '2026-06-04 05:54:22'),
(546, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'SHuP3qflIrSkHKQ26Rilcu40vUFqgJN5obASBhiB', '2026-06-04 05:57:01'),
(547, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'SHuP3qflIrSkHKQ26Rilcu40vUFqgJN5obASBhiB', '2026-06-04 05:57:02'),
(548, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.16\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '12nQkZwoMQOxIyiZSUFy7lsLhPJmcU2mAHDFztAn', '2026-06-04 05:57:05'),
(549, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '12nQkZwoMQOxIyiZSUFy7lsLhPJmcU2mAHDFztAn', '2026-06-04 05:58:25'),
(550, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '12nQkZwoMQOxIyiZSUFy7lsLhPJmcU2mAHDFztAn', '2026-06-04 05:58:25'),
(551, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'QeDlMIRcbiD6nLeD1n5nFOH33JHAkJATJr9h6mkF', '2026-06-04 05:58:28'),
(552, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'QeDlMIRcbiD6nLeD1n5nFOH33JHAkJATJr9h6mkF', '2026-06-04 06:11:08'),
(553, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'QeDlMIRcbiD6nLeD1n5nFOH33JHAkJATJr9h6mkF', '2026-06-04 06:11:08'),
(554, 9, 'keuangan', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.16\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'ghBIi8GL1wJyXnsD0wZLocf3pVzDYw86U1DiEMow', '2026-06-04 06:11:14'),
(555, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'ghBIi8GL1wJyXnsD0wZLocf3pVzDYw86U1DiEMow', '2026-06-04 06:11:34'),
(556, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'ghBIi8GL1wJyXnsD0wZLocf3pVzDYw86U1DiEMow', '2026-06-04 06:11:34'),
(557, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'kcSjKAf26zXK2AnBiIR4nw9qPNN3GFiFEAb8j4dC', '2026-06-04 06:11:41'),
(558, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'kcSjKAf26zXK2AnBiIR4nw9qPNN3GFiFEAb8j4dC', '2026-06-04 06:11:54'),
(559, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'kcSjKAf26zXK2AnBiIR4nw9qPNN3GFiFEAb8j4dC', '2026-06-04 06:11:54'),
(560, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '0dGidmNJvWsJbDv3d3SCTkb8EbB0QwmKol3UA1Cu', '2026-06-04 06:12:00'),
(561, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '0dGidmNJvWsJbDv3d3SCTkb8EbB0QwmKol3UA1Cu', '2026-06-04 06:12:04'),
(562, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '0dGidmNJvWsJbDv3d3SCTkb8EbB0QwmKol3UA1Cu', '2026-06-04 06:12:04'),
(563, 9, 'keuangan', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.16\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'A1Wuc98WWtEgmxSwxToRs7ug1IB37aJKdXhdT5ZT', '2026-06-04 06:12:11'),
(564, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'A1Wuc98WWtEgmxSwxToRs7ug1IB37aJKdXhdT5ZT', '2026-06-04 06:12:36'),
(565, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'A1Wuc98WWtEgmxSwxToRs7ug1IB37aJKdXhdT5ZT', '2026-06-04 06:12:36'),
(566, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2ZZqfjeGjWeF2jv95u7OEOUiUtCdEVwduqpXFC7p', '2026-06-04 06:12:41'),
(567, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2ZZqfjeGjWeF2jv95u7OEOUiUtCdEVwduqpXFC7p', '2026-06-04 06:12:55'),
(568, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.16\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2ZZqfjeGjWeF2jv95u7OEOUiUtCdEVwduqpXFC7p', '2026-06-04 06:12:55'),
(569, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.16\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.16', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'dbExRgXZ7AUoxQ7hdfruStXrSUBKE24eKbn86AiX', '2026-06-04 06:12:59'),
(570, 9, 'keuangan', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.28\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'zfmI72Gf7fgH6u19Y87nsKGUwU7VT4ipNJYq7fXM', '2026-06-10 05:01:32'),
(571, 9, 'keuangan', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.30\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.30', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'uLhzun6BHt6y2jahol8OJL8mdvrAmqu3HCxTkhq2', '2026-06-10 05:08:06'),
(572, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'zfmI72Gf7fgH6u19Y87nsKGUwU7VT4ipNJYq7fXM', '2026-06-10 05:11:42'),
(573, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'zfmI72Gf7fgH6u19Y87nsKGUwU7VT4ipNJYq7fXM', '2026-06-10 05:11:42'),
(574, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.28\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Aw8VSnLf4FDxMOuE22Qo6B7GadXoEEkZKDbCnJoN', '2026-06-10 05:11:51'),
(575, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Aw8VSnLf4FDxMOuE22Qo6B7GadXoEEkZKDbCnJoN', '2026-06-10 05:12:29'),
(576, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Aw8VSnLf4FDxMOuE22Qo6B7GadXoEEkZKDbCnJoN', '2026-06-10 05:12:29'),
(577, 9, 'keuangan', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.28\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'm3z9qe7jMcFzS7f7LbHd0GcNNMuNL4Xh065a0cw5', '2026-06-10 05:12:36'),
(578, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'm3z9qe7jMcFzS7f7LbHd0GcNNMuNL4Xh065a0cw5', '2026-06-10 05:17:51'),
(579, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'm3z9qe7jMcFzS7f7LbHd0GcNNMuNL4Xh065a0cw5', '2026-06-10 05:17:51'),
(580, 9, 'keuangan', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.28\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'BcMGDxtQfAzVOS2o0z8hPkH2HfGVDLnOUyom33pA', '2026-06-10 05:18:06'),
(581, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'BcMGDxtQfAzVOS2o0z8hPkH2HfGVDLnOUyom33pA', '2026-06-10 05:18:27'),
(582, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'BcMGDxtQfAzVOS2o0z8hPkH2HfGVDLnOUyom33pA', '2026-06-10 05:18:27'),
(583, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.28\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Cb5SpJTsj7XGFGGjyIhUoTCtAMOgRDxAVbijjU1l', '2026-06-10 05:18:31'),
(584, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Cb5SpJTsj7XGFGGjyIhUoTCtAMOgRDxAVbijjU1l', '2026-06-10 05:18:57'),
(585, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Cb5SpJTsj7XGFGGjyIhUoTCtAMOgRDxAVbijjU1l', '2026-06-10 05:18:57'),
(586, 9, 'keuangan', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.28\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'GU2PWdBAgkMAYm249RzUUx1BYJoe42nrzLTzEQ8C', '2026-06-10 05:19:06'),
(587, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.28\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'OAn0IQV0NvkwiD0Doul4d8CIcU0slrz2mx7slTn3', '2026-06-15 01:43:14'),
(588, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.28\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'GZhw5i3fB1yEfqFnAgMGBQ5mNT6iayPpVFBbjtT3', '2026-06-15 06:59:26'),
(589, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.28\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '3lonsIFwq68xoa5DXYTSbRd2tZKyJgrbRxrxHqda', '2026-06-18 03:25:56'),
(590, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.28\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'sOMfAuHV9uCHyWnK2pGsqIzLMEbflYx7H8WEoApW', '2026-06-22 02:32:16');
INSERT INTO `audit_logs` (`id`, `actor_id`, `actor_role`, `action`, `module`, `auditable_type`, `auditable_id`, `meta`, `before`, `after`, `ip_address`, `user_agent`, `session_id`, `created_at`) VALUES
(591, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'sOMfAuHV9uCHyWnK2pGsqIzLMEbflYx7H8WEoApW', '2026-06-22 02:53:19'),
(592, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'sOMfAuHV9uCHyWnK2pGsqIzLMEbflYx7H8WEoApW', '2026-06-22 02:53:19'),
(593, 5, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 5, '{\"ip\":\"192.168.1.28\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'wrZmi9MvzHL5tD0rIozEN5QiraOtTteIt06orsbT', '2026-06-22 02:53:23'),
(594, 5, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 5, '{\"user_id\":5,\"user_email\":\"andipratama@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'wrZmi9MvzHL5tD0rIozEN5QiraOtTteIt06orsbT', '2026-06-22 02:53:45'),
(595, 5, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 5, '{\"user_id\":5,\"user_email\":\"andipratama@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'wrZmi9MvzHL5tD0rIozEN5QiraOtTteIt06orsbT', '2026-06-22 02:53:45'),
(596, 10, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 10, '{\"ip\":\"192.168.1.28\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'nEzaq2kDmjUhy10KH5VHMZorQAHQUxMsXGMxDUBS', '2026-06-22 02:53:49'),
(597, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'nEzaq2kDmjUhy10KH5VHMZorQAHQUxMsXGMxDUBS', '2026-06-22 02:53:52'),
(598, 10, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 10, '{\"user_id\":10,\"user_email\":\"ahmadmahasiswa@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'nEzaq2kDmjUhy10KH5VHMZorQAHQUxMsXGMxDUBS', '2026-06-22 02:53:52'),
(599, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.28\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Yta1WLP0y0Cmeb7gDctXySWLot4MkYKHiHH8xay6', '2026-06-22 02:53:56'),
(600, 30, 'mahasiswa', 'internship.created', 'magang', 'App\\Models\\Internship', 1, NULL, NULL, '{\"internship_type_id\":1,\"instansi\":\"PT. SEMESTA NUSANTARA\",\"alamat_instansi\":\"Jl. Cawang jakarta\",\"posisi\":\"Legal Office Intern\",\"periode_mulai\":\"2026-07-11T17:00:00.000000Z\",\"periode_selesai\":\"2026-10-11T17:00:00.000000Z\",\"deskripsi\":\"magang legal\",\"pembimbing_lapangan_nama\":null,\"pembimbing_lapangan_phone\":null,\"semester_mahasiswa\":7,\"mahasiswa_id\":6,\"semester_id\":1,\"status\":\"draft\",\"id\":1}', '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Yta1WLP0y0Cmeb7gDctXySWLot4MkYKHiHH8xay6', '2026-06-22 02:56:35'),
(601, 30, 'mahasiswa', 'internship.updated', 'magang', 'App\\Models\\Internship', 1, NULL, '{\"status\":\"draft\"}', '{\"status\":\"submitted\"}', '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Yta1WLP0y0Cmeb7gDctXySWLot4MkYKHiHH8xay6', '2026-06-22 02:56:56'),
(602, 30, 'mahasiswa', 'internship.updated', 'magang', 'App\\Models\\Internship', 1, NULL, '{\"status\":\"submitted\"}', '{\"status\":\"waiting_request_letter\"}', '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Yta1WLP0y0Cmeb7gDctXySWLot4MkYKHiHH8xay6', '2026-06-22 02:56:56'),
(603, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Yta1WLP0y0Cmeb7gDctXySWLot4MkYKHiHH8xay6', '2026-06-22 02:57:01'),
(604, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Yta1WLP0y0Cmeb7gDctXySWLot4MkYKHiHH8xay6', '2026-06-22 02:57:01'),
(605, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.28\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'tE9O4IZlVSKsXDPwAnqvECJpibeIQyEEMj6nAUzT', '2026-06-22 02:57:07'),
(606, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'tE9O4IZlVSKsXDPwAnqvECJpibeIQyEEMj6nAUzT', '2026-06-22 02:57:36'),
(607, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'tE9O4IZlVSKsXDPwAnqvECJpibeIQyEEMj6nAUzT', '2026-06-22 02:57:36'),
(608, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.28\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'gLDthsXq49JnqthXOzj4TIZXzeYdYwnHQTe5Dpcl', '2026-06-22 02:57:42'),
(609, 30, 'mahasiswa', 'internship.updated', 'magang', 'App\\Models\\Internship', 1, NULL, '{\"request_letter_generated_path\":null}', '{\"request_letter_generated_path\":\"internship\\/request\\/JOJO_50421684\\/request_letter_1_1782097070.docx\"}', '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'gLDthsXq49JnqthXOzj4TIZXzeYdYwnHQTe5Dpcl', '2026-06-22 02:57:50'),
(610, 30, 'mahasiswa', 'internship.updated', 'magang', 'App\\Models\\Internship', 1, NULL, '{\"request_letter_signed_path\":null}', '{\"request_letter_signed_path\":\"internship\\/signed\\/JOJO_50421684\\/internship_request_signed_1_1782097111.pdf\"}', '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'gLDthsXq49JnqthXOzj4TIZXzeYdYwnHQTe5Dpcl', '2026-06-22 02:58:31'),
(611, 30, 'mahasiswa', 'internship.updated', 'magang', 'App\\Models\\Internship', 1, NULL, '{\"status\":\"waiting_request_letter\"}', '{\"status\":\"request_letter_uploaded\"}', '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'gLDthsXq49JnqthXOzj4TIZXzeYdYwnHQTe5Dpcl', '2026-06-22 02:58:31'),
(612, 30, 'mahasiswa', 'internship.updated', 'magang', 'App\\Models\\Internship', 1, NULL, '{\"status\":\"request_letter_uploaded\"}', '{\"status\":\"under_review\"}', '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'gLDthsXq49JnqthXOzj4TIZXzeYdYwnHQTe5Dpcl', '2026-06-22 02:58:35'),
(613, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'gLDthsXq49JnqthXOzj4TIZXzeYdYwnHQTe5Dpcl', '2026-06-22 02:58:38'),
(614, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'gLDthsXq49JnqthXOzj4TIZXzeYdYwnHQTe5Dpcl', '2026-06-22 02:58:38'),
(615, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.28\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'EhIf890xIRAAQ4ojzSONL20bvnGJ1DZEs8BZpyZC', '2026-06-22 02:58:43'),
(616, 1, 'akademik', 'internship.updated', 'magang', 'App\\Models\\Internship', 1, NULL, '{\"status\":\"under_review\"}', '{\"status\":\"approved\"}', '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'EhIf890xIRAAQ4ojzSONL20bvnGJ1DZEs8BZpyZC', '2026-06-22 02:59:01'),
(617, 1, 'akademik', 'internship.updated', 'magang', 'App\\Models\\Internship', 1, NULL, '{\"approved_by\":null,\"approved_at\":null}', '{\"approved_by\":1,\"approved_at\":\"2026-06-22 09:59:01\"}', '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'EhIf890xIRAAQ4ojzSONL20bvnGJ1DZEs8BZpyZC', '2026-06-22 02:59:01'),
(618, 1, 'akademik', 'internship.updated', 'magang', 'App\\Models\\Internship', 1, NULL, '{\"nomor_surat\":null}', '{\"nomor_surat\":\"099\\/SK\\/STIH\\/III\\/2026\"}', '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'EhIf890xIRAAQ4ojzSONL20bvnGJ1DZEs8BZpyZC', '2026-06-22 02:59:46'),
(619, 1, 'akademik', 'internship.updated', 'magang', 'App\\Models\\Internship', 1, NULL, '{\"admin_final_pdf_path\":null}', '{\"admin_final_pdf_path\":\"internship\\/admin_official\\/JOJO_50421684\\/official_1_1782097186.docx\"}', '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'EhIf890xIRAAQ4ojzSONL20bvnGJ1DZEs8BZpyZC', '2026-06-22 02:59:46'),
(620, 1, 'akademik', 'internship.updated', 'magang', 'App\\Models\\Internship', 1, NULL, '{\"admin_signed_pdf_path\":null}', '{\"admin_signed_pdf_path\":\"internship\\/admin_signed\\/JOJO_50421684\\/official_signed_1_1782097273.pdf\"}', '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'EhIf890xIRAAQ4ojzSONL20bvnGJ1DZEs8BZpyZC', '2026-06-22 03:01:14'),
(621, 1, 'akademik', 'internship.updated', 'magang', 'App\\Models\\Internship', 1, NULL, '{\"status\":\"approved\"}', '{\"status\":\"sent_to_student\"}', '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'EhIf890xIRAAQ4ojzSONL20bvnGJ1DZEs8BZpyZC', '2026-06-22 03:01:14'),
(622, 1, 'akademik', 'internship.updated', 'magang', 'App\\Models\\Internship', 1, NULL, '{\"sent_to_student_at\":null,\"sent_by\":null}', '{\"sent_to_student_at\":\"2026-06-22 10:01:14\",\"sent_by\":1}', '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'EhIf890xIRAAQ4ojzSONL20bvnGJ1DZEs8BZpyZC', '2026-06-22 03:01:14'),
(623, 1, 'akademik', 'internship.updated', 'magang', 'App\\Models\\Internship', 1, NULL, '{\"status\":\"sent_to_student\"}', '{\"status\":\"supervisor_assigned\"}', '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'EhIf890xIRAAQ4ojzSONL20bvnGJ1DZEs8BZpyZC', '2026-06-22 03:01:21'),
(624, 1, 'akademik', 'internship.updated', 'magang', 'App\\Models\\Internship', 1, NULL, '{\"supervisor_dosen_id\":null,\"supervisor_assigned_at\":null}', '{\"supervisor_dosen_id\":1,\"supervisor_assigned_at\":\"2026-06-22 10:01:21\"}', '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'EhIf890xIRAAQ4ojzSONL20bvnGJ1DZEs8BZpyZC', '2026-06-22 03:01:21'),
(625, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'EhIf890xIRAAQ4ojzSONL20bvnGJ1DZEs8BZpyZC', '2026-06-22 03:01:28'),
(626, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'EhIf890xIRAAQ4ojzSONL20bvnGJ1DZEs8BZpyZC', '2026-06-22 03:01:28'),
(627, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.28\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Dvx2kmFiBEN1XM6UMQLB2fSMu8D2ItLXzHO11H9i', '2026-06-22 03:01:35'),
(628, 30, 'mahasiswa', 'internship.updated', 'magang', 'App\\Models\\Internship', 1, NULL, '{\"acceptance_letter_path\":null}', '{\"acceptance_letter_path\":\"internship\\/acceptance\\/JOJO_50421684\\/acceptance_1_1782097312.pdf\"}', '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Dvx2kmFiBEN1XM6UMQLB2fSMu8D2ItLXzHO11H9i', '2026-06-22 03:01:52'),
(629, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Dvx2kmFiBEN1XM6UMQLB2fSMu8D2ItLXzHO11H9i', '2026-06-22 03:02:01'),
(630, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Dvx2kmFiBEN1XM6UMQLB2fSMu8D2ItLXzHO11H9i', '2026-06-22 03:02:01'),
(631, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.28\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'H08K8ErK0r8M7DDB1vbEe180pBTpRFD1UEoy2d9s', '2026-06-22 03:02:06'),
(632, 1, 'akademik', 'internship.updated', 'magang', 'App\\Models\\Internship', 1, NULL, '{\"status\":\"supervisor_assigned\"}', '{\"status\":\"acceptance_letter_ready\"}', '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'H08K8ErK0r8M7DDB1vbEe180pBTpRFD1UEoy2d9s', '2026-06-22 03:02:18'),
(633, 1, 'akademik', 'internship.updated', 'magang', 'App\\Models\\Internship', 1, NULL, '{\"status\":\"acceptance_letter_ready\"}', '{\"status\":\"ongoing\"}', '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'H08K8ErK0r8M7DDB1vbEe180pBTpRFD1UEoy2d9s', '2026-06-22 03:02:31'),
(634, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'H08K8ErK0r8M7DDB1vbEe180pBTpRFD1UEoy2d9s', '2026-06-22 03:02:36'),
(635, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'H08K8ErK0r8M7DDB1vbEe180pBTpRFD1UEoy2d9s', '2026-06-22 03:02:36'),
(636, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.28\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'W6Qh7fttIG5O2O2cIBl0OJ42R0Q2Bm375BGEQhm4', '2026-06-22 03:02:43'),
(637, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'W6Qh7fttIG5O2O2cIBl0OJ42R0Q2Bm375BGEQhm4', '2026-06-22 03:07:52'),
(638, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'W6Qh7fttIG5O2O2cIBl0OJ42R0Q2Bm375BGEQhm4', '2026-06-22 03:07:52'),
(639, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.28\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'IUWEl2HH4Zp8SHKHdMIIzEZcNwzpH9GxxTCnJdWU', '2026-06-22 03:07:58'),
(640, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'IUWEl2HH4Zp8SHKHdMIIzEZcNwzpH9GxxTCnJdWU', '2026-06-22 03:08:56'),
(641, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'IUWEl2HH4Zp8SHKHdMIIzEZcNwzpH9GxxTCnJdWU', '2026-06-22 03:08:56'),
(642, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.28\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'l4OdOZ6awCAw7zWTr5p70UlbIjbmD3ID4TOIrztw', '2026-06-22 03:09:03'),
(643, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'l4OdOZ6awCAw7zWTr5p70UlbIjbmD3ID4TOIrztw', '2026-06-22 03:21:14'),
(644, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'l4OdOZ6awCAw7zWTr5p70UlbIjbmD3ID4TOIrztw', '2026-06-22 03:21:14'),
(645, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.28\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'cSZoDft67qtLWW2QNggHMMMlYyRRePRvw6r5bdES', '2026-06-22 03:21:18'),
(646, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'cSZoDft67qtLWW2QNggHMMMlYyRRePRvw6r5bdES', '2026-06-22 03:24:47'),
(647, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'cSZoDft67qtLWW2QNggHMMMlYyRRePRvw6r5bdES', '2026-06-22 03:24:47'),
(648, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.28\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'jk0XK5sunx4gpU8PT68pnPZgB7YuzZjww09DS61b', '2026-06-22 03:24:51'),
(649, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'jk0XK5sunx4gpU8PT68pnPZgB7YuzZjww09DS61b', '2026-06-22 03:25:13'),
(650, 1, 'akademik', 'user.logout', 'auth', 'App\\Models\\User', 1, '{\"user_id\":1,\"user_email\":\"admin@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'jk0XK5sunx4gpU8PT68pnPZgB7YuzZjww09DS61b', '2026-06-22 03:25:13'),
(651, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.28\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'HsZYTiDjpz76f6FVeVAaTzRHtrZlKAqaEkN8qMX6', '2026-06-22 03:25:19'),
(652, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'HsZYTiDjpz76f6FVeVAaTzRHtrZlKAqaEkN8qMX6', '2026-06-22 04:16:50'),
(653, 2, 'dosen', 'user.logout', 'auth', 'App\\Models\\User', 2, '{\"user_id\":2,\"user_email\":\"ahmad.fauzi@stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'HsZYTiDjpz76f6FVeVAaTzRHtrZlKAqaEkN8qMX6', '2026-06-22 04:16:50'),
(654, 30, 'mahasiswa', 'user.login', 'auth', 'App\\Models\\User', 30, '{\"ip\":\"192.168.1.28\",\"role\":\"mahasiswa\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'I50FiYIXZ8bOy6rax5aiO5u16tIK8igQimVICGP4', '2026-06-22 04:16:53'),
(655, 30, 'mahasiswa', 'skripsi.proposal_submitted', 'skripsi', 'App\\Models\\SkripsiSubmission', 6, '{\"judul\":\"Perlindungan Hukum bagi Korban Penipuan Online\",\"supervisor_id\":\"1\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'I50FiYIXZ8bOy6rax5aiO5u16tIK8igQimVICGP4', '2026-06-22 04:18:23'),
(656, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'I50FiYIXZ8bOy6rax5aiO5u16tIK8igQimVICGP4', '2026-06-22 04:18:30'),
(657, 30, 'mahasiswa', 'user.logout', 'auth', 'App\\Models\\User', 30, '{\"user_id\":30,\"user_email\":\"jojo@student.stih.ac.id\",\"ip\":\"192.168.1.28\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'I50FiYIXZ8bOy6rax5aiO5u16tIK8igQimVICGP4', '2026-06-22 04:18:30'),
(658, 2, 'dosen', 'user.login', 'auth', 'App\\Models\\User', 2, '{\"ip\":\"192.168.1.28\",\"role\":\"dosen\"}', NULL, NULL, '192.168.1.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'FIdRBYpoOzRh3OCaMYdbk4QQ2nyKeL9V2uuBruiG', '2026-06-22 04:18:34'),
(659, 9, 'keuangan', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.27\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.27', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '01C9Okw1M0KyAJEwaEBtKKGqQqNhCmiNbTJEeQde', '2026-06-23 02:23:24'),
(660, 9, 'keuangan', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.27\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.27', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Ceh2HudL5cqcSq47zlFz4YaixFQweTS2iE2UVs7A', '2026-06-23 06:50:59'),
(661, 9, 'keuangan', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.27\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.27', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'kiSWEn9gu8K0gIByJ7ipjUQNi3TW2kTMVFPv7OKA', '2026-06-24 06:57:50'),
(662, 9, 'keuangan', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.27\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.27', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'ocRuIKzWWdsVF8oNy6dp1VsCW7lAaNDLl4wz8QRI', '2026-06-24 10:14:15'),
(663, 9, 'keuangan', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.27\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.27', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'd2iyCGZ6Wm3Z1PaLTYUo9ZjJDMj5R1JxZbsoTgyv', '2026-06-25 03:31:42'),
(664, 9, 'keuangan', 'user.login', 'auth', 'App\\Models\\User', 9, '{\"ip\":\"192.168.1.24\",\"role\":\"keuangan\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'hKiPIKA0elc85BE8RPgxk3pNm6TtaEZZfPmC6OUF', '2026-07-02 02:37:00'),
(665, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.24\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'hKiPIKA0elc85BE8RPgxk3pNm6TtaEZZfPmC6OUF', '2026-07-02 03:07:57'),
(666, 9, 'keuangan', 'user.logout', 'auth', 'App\\Models\\User', 9, '{\"user_id\":9,\"user_email\":\"keuangan@stih.ac.id\",\"ip\":\"192.168.1.24\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'hKiPIKA0elc85BE8RPgxk3pNm6TtaEZZfPmC6OUF', '2026-07-02 03:07:57'),
(667, 1, 'akademik', 'user.login', 'auth', 'App\\Models\\User', 1, '{\"ip\":\"192.168.1.7\",\"role\":\"admin\"}', NULL, NULL, '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'MchM8gZRNhTEDrooFAdOnb8SLyLc53o070vkK01D', '2026-07-02 03:15:10'),
(668, 1, 'akademik', 'user.created', 'system', 'App\\Models\\User', 32, NULL, NULL, '{\"name\":\"budi\",\"email\":\"2024010001@parent.stih.ac.id\",\"role\":\"parent\",\"id\":32}', '192.168.1.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'MchM8gZRNhTEDrooFAdOnb8SLyLc53o070vkK01D', '2026-07-02 03:15:45'),
(669, 32, 'parents', 'user.login', 'auth', 'App\\Models\\User', 32, '{\"ip\":\"192.168.1.24\",\"role\":\"parent\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'SKyc47Qw6ITnmDnkdprHtOZHlaEZHrGO2gcaE2y0', '2026-07-02 03:16:10'),
(670, 32, 'parents', 'parent.view_dashboard', 'system', 'App\\Models\\Mahasiswa', 1, '{\"student_name\":\"Andi Pratama\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'SKyc47Qw6ITnmDnkdprHtOZHlaEZHrGO2gcaE2y0', '2026-07-02 03:16:10'),
(671, 32, 'parents', 'user.logout', 'auth', 'App\\Models\\User', 32, '{\"user_id\":32,\"user_email\":\"2024010001@parent.stih.ac.id\",\"ip\":\"192.168.1.24\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'SKyc47Qw6ITnmDnkdprHtOZHlaEZHrGO2gcaE2y0', '2026-07-02 03:16:24'),
(672, 32, 'parents', 'user.logout', 'auth', 'App\\Models\\User', 32, '{\"user_id\":32,\"user_email\":\"2024010001@parent.stih.ac.id\",\"ip\":\"192.168.1.24\",\"user_agent\":\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/148.0.0.0 Safari\\/537.36\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'SKyc47Qw6ITnmDnkdprHtOZHlaEZHrGO2gcaE2y0', '2026-07-02 03:16:24'),
(673, 32, 'parents', 'user.login', 'auth', 'App\\Models\\User', 32, '{\"ip\":\"192.168.1.24\",\"role\":\"parent\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'z2Kw0zRgESqi2pFSzOCJdAAwlIDytCymPrYoivOR', '2026-07-02 03:16:33'),
(674, 32, 'parents', 'parent.view_dashboard', 'system', 'App\\Models\\Mahasiswa', 1, '{\"student_name\":\"Andi Pratama\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'z2Kw0zRgESqi2pFSzOCJdAAwlIDytCymPrYoivOR', '2026-07-02 03:16:33'),
(675, 32, 'parents', 'parent.view_grades', 'akademik', 'App\\Models\\Mahasiswa', 1, '{\"student_name\":\"Andi Pratama\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'z2Kw0zRgESqi2pFSzOCJdAAwlIDytCymPrYoivOR', '2026-07-02 03:46:07'),
(676, 32, 'parents', 'parent.view_schedule', 'system', 'App\\Models\\Mahasiswa', 1, '{\"student_name\":\"Andi Pratama\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'z2Kw0zRgESqi2pFSzOCJdAAwlIDytCymPrYoivOR', '2026-07-02 03:46:08'),
(677, 32, 'parents', 'parent.view_attendance', 'system', 'App\\Models\\Mahasiswa', 1, '{\"student_name\":\"Andi Pratama\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'z2Kw0zRgESqi2pFSzOCJdAAwlIDytCymPrYoivOR', '2026-07-02 03:46:10'),
(678, 32, 'parents', 'parent.view_payments', 'keuangan', 'App\\Models\\Mahasiswa', 1, '{\"student_name\":\"Andi Pratama\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'z2Kw0zRgESqi2pFSzOCJdAAwlIDytCymPrYoivOR', '2026-07-02 03:46:15'),
(679, 32, 'parents', 'user.login', 'auth', 'App\\Models\\User', 32, '{\"ip\":\"192.168.1.24\",\"role\":\"parent\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '5FjALjS8X5rSixZzsvzp27woGHtQK6chQklKXgUi', '2026-07-03 03:27:15'),
(680, 32, 'parents', 'parent.view_dashboard', 'system', 'App\\Models\\Mahasiswa', 1, '{\"student_name\":\"Andi Pratama\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '5FjALjS8X5rSixZzsvzp27woGHtQK6chQklKXgUi', '2026-07-03 03:27:15'),
(681, 32, 'parents', 'parent.view_grades', 'akademik', 'App\\Models\\Mahasiswa', 1, '{\"student_name\":\"Andi Pratama\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '5FjALjS8X5rSixZzsvzp27woGHtQK6chQklKXgUi', '2026-07-03 03:47:57'),
(682, 32, 'parents', 'parent.view_schedule', 'system', 'App\\Models\\Mahasiswa', 1, '{\"student_name\":\"Andi Pratama\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '5FjALjS8X5rSixZzsvzp27woGHtQK6chQklKXgUi', '2026-07-03 03:49:07'),
(683, 32, 'parents', 'parent.view_attendance', 'system', 'App\\Models\\Mahasiswa', 1, '{\"student_name\":\"Andi Pratama\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '5FjALjS8X5rSixZzsvzp27woGHtQK6chQklKXgUi', '2026-07-03 03:49:09'),
(684, 32, 'parents', 'parent.view_schedule', 'system', 'App\\Models\\Mahasiswa', 1, '{\"student_name\":\"Andi Pratama\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '5FjALjS8X5rSixZzsvzp27woGHtQK6chQklKXgUi', '2026-07-03 03:49:11'),
(685, 32, 'parents', 'parent.view_grades', 'akademik', 'App\\Models\\Mahasiswa', 1, '{\"student_name\":\"Andi Pratama\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '5FjALjS8X5rSixZzsvzp27woGHtQK6chQklKXgUi', '2026-07-03 03:49:12'),
(686, 32, 'parents', 'user.login', 'auth', 'App\\Models\\User', 32, '{\"ip\":\"192.168.1.24\",\"role\":\"parent\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '81tmMHi1Sa7oZnuX0guiKqnWA9rQ3mpuSOu2sCJo', '2026-07-07 03:37:02'),
(687, 32, 'parents', 'parent.view_dashboard', 'system', 'App\\Models\\Mahasiswa', 1, '{\"student_name\":\"Andi Pratama\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '81tmMHi1Sa7oZnuX0guiKqnWA9rQ3mpuSOu2sCJo', '2026-07-07 03:37:02'),
(688, 32, 'parents', 'parent.view_grades', 'akademik', 'App\\Models\\Mahasiswa', 1, '{\"student_name\":\"Andi Pratama\"}', NULL, NULL, '192.168.1.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '81tmMHi1Sa7oZnuX0guiKqnWA9rQ3mpuSOu2sCJo', '2026-07-07 03:37:30');

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
(1, 2, 25.00, 25.00, 5.00, 5.00, 20.00, 20.00, 1, '2026-06-15 03:37:38', 2, '2026-06-15 03:36:57', '2026-06-15 03:37:38');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('academic_period_current_types:1:2026-06-10', 'a:0:{}', 1781068831),
('academic_period_current_types:1:2026-06-22', 'a:0:{}', 1782101934),
('academic_period_current_types:1:2026-07-02', 'a:0:{}', 1782966700),
('academic_periods:1', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:14:{i:0;O:24:\"App\\Models\\AcademicEvent\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"academic_events\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:51;s:5:\"title\";s:18:\"Bimbingan akademik\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:7:\"lainnya\";s:10:\"start_date\";s:10:\"2025-09-15\";s:8:\"end_date\";s:10:\"2025-09-17\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#6b7280\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:51;s:5:\"title\";s:18:\"Bimbingan akademik\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:7:\"lainnya\";s:10:\"start_date\";s:10:\"2025-09-15\";s:8:\"end_date\";s:10:\"2025-09-17\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#6b7280\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:10:\"start_date\";s:4:\"date\";s:8:\"end_date\";s:4:\"date\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:5:\"title\";i:1;s:11:\"description\";i:2;s:10:\"event_type\";i:3;s:10:\"start_date\";i:4;s:8:\"end_date\";i:5;s:11:\"semester_id\";i:6;s:5:\"color\";i:7;s:9:\"is_active\";i:8;s:10:\"created_by\";i:9;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:24:\"App\\Models\\AcademicEvent\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"academic_events\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:52;s:5:\"title\";s:20:\"KRS online mahasiswa\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:3:\"krs\";s:10:\"start_date\";s:10:\"2025-09-15\";s:8:\"end_date\";s:10:\"2025-09-17\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#10b981\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:52;s:5:\"title\";s:20:\"KRS online mahasiswa\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:3:\"krs\";s:10:\"start_date\";s:10:\"2025-09-15\";s:8:\"end_date\";s:10:\"2025-09-17\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#10b981\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:10:\"start_date\";s:4:\"date\";s:8:\"end_date\";s:4:\"date\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:5:\"title\";i:1;s:11:\"description\";i:2;s:10:\"event_type\";i:3;s:10:\"start_date\";i:4;s:8:\"end_date\";i:5;s:11:\"semester_id\";i:6;s:5:\"color\";i:7;s:9:\"is_active\";i:8;s:10:\"created_by\";i:9;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:24:\"App\\Models\\AcademicEvent\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"academic_events\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:53;s:5:\"title\";s:35:\"Batas waktu pengajuan Judul Skripsi\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:7:\"lainnya\";s:10:\"start_date\";s:10:\"2025-09-18\";s:8:\"end_date\";s:10:\"2025-09-19\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#6b7280\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:53;s:5:\"title\";s:35:\"Batas waktu pengajuan Judul Skripsi\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:7:\"lainnya\";s:10:\"start_date\";s:10:\"2025-09-18\";s:8:\"end_date\";s:10:\"2025-09-19\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#6b7280\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:10:\"start_date\";s:4:\"date\";s:8:\"end_date\";s:4:\"date\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:5:\"title\";i:1;s:11:\"description\";i:2;s:10:\"event_type\";i:3;s:10:\"start_date\";i:4;s:8:\"end_date\";i:5;s:11:\"semester_id\";i:6;s:5:\"color\";i:7;s:9:\"is_active\";i:8;s:10:\"created_by\";i:9;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:3;O:24:\"App\\Models\\AcademicEvent\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"academic_events\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:54;s:5:\"title\";s:85:\"Perkenalan Kehidupan Kampus Bagi Mahasiswa Baru (PKKMB) dan Pelantikan Mahasiswa baru\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:7:\"lainnya\";s:10:\"start_date\";s:10:\"2025-10-02\";s:8:\"end_date\";s:10:\"2025-10-03\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#6b7280\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:54;s:5:\"title\";s:85:\"Perkenalan Kehidupan Kampus Bagi Mahasiswa Baru (PKKMB) dan Pelantikan Mahasiswa baru\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:7:\"lainnya\";s:10:\"start_date\";s:10:\"2025-10-02\";s:8:\"end_date\";s:10:\"2025-10-03\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#6b7280\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:10:\"start_date\";s:4:\"date\";s:8:\"end_date\";s:4:\"date\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:5:\"title\";i:1;s:11:\"description\";i:2;s:10:\"event_type\";i:3;s:10:\"start_date\";i:4;s:8:\"end_date\";i:5;s:11:\"semester_id\";i:6;s:5:\"color\";i:7;s:9:\"is_active\";i:8;s:10:\"created_by\";i:9;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:4;O:24:\"App\\Models\\AcademicEvent\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"academic_events\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:55;s:5:\"title\";s:17:\"Awal Perkuliahaan\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:11:\"perkuliahan\";s:10:\"start_date\";s:10:\"2025-10-06\";s:8:\"end_date\";s:10:\"2025-10-06\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#3b82f6\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:55;s:5:\"title\";s:17:\"Awal Perkuliahaan\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:11:\"perkuliahan\";s:10:\"start_date\";s:10:\"2025-10-06\";s:8:\"end_date\";s:10:\"2025-10-06\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#3b82f6\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:10:\"start_date\";s:4:\"date\";s:8:\"end_date\";s:4:\"date\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:5:\"title\";i:1;s:11:\"description\";i:2;s:10:\"event_type\";i:3;s:10:\"start_date\";i:4;s:8:\"end_date\";i:5;s:11:\"semester_id\";i:6;s:5:\"color\";i:7;s:9:\"is_active\";i:8;s:10:\"created_by\";i:9;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:5;O:24:\"App\\Models\\AcademicEvent\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"academic_events\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:56;s:5:\"title\";s:52:\"Masa Perkuliahaan efektif sebelum UTS (7x pertemuan)\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:3:\"uts\";s:10:\"start_date\";s:10:\"2025-10-06\";s:8:\"end_date\";s:10:\"2025-11-21\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#f59e0b\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:56;s:5:\"title\";s:52:\"Masa Perkuliahaan efektif sebelum UTS (7x pertemuan)\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:3:\"uts\";s:10:\"start_date\";s:10:\"2025-10-06\";s:8:\"end_date\";s:10:\"2025-11-21\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#f59e0b\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:10:\"start_date\";s:4:\"date\";s:8:\"end_date\";s:4:\"date\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:5:\"title\";i:1;s:11:\"description\";i:2;s:10:\"event_type\";i:3;s:10:\"start_date\";i:4;s:8:\"end_date\";i:5;s:11:\"semester_id\";i:6;s:5:\"color\";i:7;s:9:\"is_active\";i:8;s:10:\"created_by\";i:9;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:6;O:24:\"App\\Models\\AcademicEvent\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"academic_events\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:57;s:5:\"title\";s:27:\"Ujian Tengah Semester (UTS)\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:3:\"uts\";s:10:\"start_date\";s:10:\"2025-11-24\";s:8:\"end_date\";s:10:\"2025-11-28\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#f59e0b\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:57;s:5:\"title\";s:27:\"Ujian Tengah Semester (UTS)\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:3:\"uts\";s:10:\"start_date\";s:10:\"2025-11-24\";s:8:\"end_date\";s:10:\"2025-11-28\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#f59e0b\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:10:\"start_date\";s:4:\"date\";s:8:\"end_date\";s:4:\"date\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:5:\"title\";i:1;s:11:\"description\";i:2;s:10:\"event_type\";i:3;s:10:\"start_date\";i:4;s:8:\"end_date\";i:5;s:11:\"semester_id\";i:6;s:5:\"color\";i:7;s:9:\"is_active\";i:8;s:10:\"created_by\";i:9;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:7;O:24:\"App\\Models\\AcademicEvent\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"academic_events\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:61;s:5:\"title\";s:31:\"Masa Pendaftaran Sidang Skripsi\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:7:\"lainnya\";s:10:\"start_date\";s:10:\"2025-11-24\";s:8:\"end_date\";s:10:\"2025-02-13\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#6b7280\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:61;s:5:\"title\";s:31:\"Masa Pendaftaran Sidang Skripsi\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:7:\"lainnya\";s:10:\"start_date\";s:10:\"2025-11-24\";s:8:\"end_date\";s:10:\"2025-02-13\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#6b7280\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:10:\"start_date\";s:4:\"date\";s:8:\"end_date\";s:4:\"date\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:5:\"title\";i:1;s:11:\"description\";i:2;s:10:\"event_type\";i:3;s:10:\"start_date\";i:4;s:8:\"end_date\";i:5;s:11:\"semester_id\";i:6;s:5:\"color\";i:7;s:9:\"is_active\";i:8;s:10:\"created_by\";i:9;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:8;O:24:\"App\\Models\\AcademicEvent\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"academic_events\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:58;s:5:\"title\";s:52:\"Masa Perkuliahaan efektif setelah UTS (7x pertemuan)\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:3:\"uts\";s:10:\"start_date\";s:10:\"2025-12-01\";s:8:\"end_date\";s:10:\"2026-02-06\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#f59e0b\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:58;s:5:\"title\";s:52:\"Masa Perkuliahaan efektif setelah UTS (7x pertemuan)\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:3:\"uts\";s:10:\"start_date\";s:10:\"2025-12-01\";s:8:\"end_date\";s:10:\"2026-02-06\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#f59e0b\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:10:\"start_date\";s:4:\"date\";s:8:\"end_date\";s:4:\"date\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:5:\"title\";i:1;s:11:\"description\";i:2;s:10:\"event_type\";i:3;s:10:\"start_date\";i:4;s:8:\"end_date\";i:5;s:11:\"semester_id\";i:6;s:5:\"color\";i:7;s:9:\"is_active\";i:8;s:10:\"created_by\";i:9;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:9;O:24:\"App\\Models\\AcademicEvent\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"academic_events\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:59;s:5:\"title\";s:46:\"Libur Hari Raya Natal dan Tahun Baru 2025/2026\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:14:\"libur_akademik\";s:10:\"start_date\";s:10:\"2025-12-22\";s:8:\"end_date\";s:10:\"2026-01-06\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#ef4444\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:59;s:5:\"title\";s:46:\"Libur Hari Raya Natal dan Tahun Baru 2025/2026\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:14:\"libur_akademik\";s:10:\"start_date\";s:10:\"2025-12-22\";s:8:\"end_date\";s:10:\"2026-01-06\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#ef4444\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:10:\"start_date\";s:4:\"date\";s:8:\"end_date\";s:4:\"date\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:5:\"title\";i:1;s:11:\"description\";i:2;s:10:\"event_type\";i:3;s:10:\"start_date\";i:4;s:8:\"end_date\";i:5;s:11:\"semester_id\";i:6;s:5:\"color\";i:7;s:9:\"is_active\";i:8;s:10:\"created_by\";i:9;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:10;O:24:\"App\\Models\\AcademicEvent\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"academic_events\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:60;s:5:\"title\";s:67:\"Perkuliahaan Setelah Libur Hari Raya Natal dan Tahun Baru 2025/2026\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:14:\"libur_akademik\";s:10:\"start_date\";s:10:\"2026-01-09\";s:8:\"end_date\";s:10:\"2026-02-13\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#ef4444\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:60;s:5:\"title\";s:67:\"Perkuliahaan Setelah Libur Hari Raya Natal dan Tahun Baru 2025/2026\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:14:\"libur_akademik\";s:10:\"start_date\";s:10:\"2026-01-09\";s:8:\"end_date\";s:10:\"2026-02-13\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#ef4444\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:10:\"start_date\";s:4:\"date\";s:8:\"end_date\";s:4:\"date\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:5:\"title\";i:1;s:11:\"description\";i:2;s:10:\"event_type\";i:3;s:10:\"start_date\";i:4;s:8:\"end_date\";i:5;s:11:\"semester_id\";i:6;s:5:\"color\";i:7;s:9:\"is_active\";i:8;s:10:\"created_by\";i:9;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:11;O:24:\"App\\Models\\AcademicEvent\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"academic_events\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:62;s:5:\"title\";s:26:\"Ujian Akhir Semester (UAS)\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:3:\"uas\";s:10:\"start_date\";s:10:\"2026-02-09\";s:8:\"end_date\";s:10:\"2026-02-13\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#d97706\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:62;s:5:\"title\";s:26:\"Ujian Akhir Semester (UAS)\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:3:\"uas\";s:10:\"start_date\";s:10:\"2026-02-09\";s:8:\"end_date\";s:10:\"2026-02-13\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#d97706\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:10:\"start_date\";s:4:\"date\";s:8:\"end_date\";s:4:\"date\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:5:\"title\";i:1;s:11:\"description\";i:2;s:10:\"event_type\";i:3;s:10:\"start_date\";i:4;s:8:\"end_date\";i:5;s:11:\"semester_id\";i:6;s:5:\"color\";i:7;s:9:\"is_active\";i:8;s:10:\"created_by\";i:9;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:12;O:24:\"App\\Models\\AcademicEvent\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"academic_events\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:63;s:5:\"title\";s:27:\"Penginputan Nilai Mahasiswa\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:7:\"lainnya\";s:10:\"start_date\";s:10:\"2026-02-16\";s:8:\"end_date\";s:10:\"2026-02-20\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#6b7280\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:63;s:5:\"title\";s:27:\"Penginputan Nilai Mahasiswa\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:7:\"lainnya\";s:10:\"start_date\";s:10:\"2026-02-16\";s:8:\"end_date\";s:10:\"2026-02-20\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#6b7280\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:10:\"start_date\";s:4:\"date\";s:8:\"end_date\";s:4:\"date\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:5:\"title\";i:1;s:11:\"description\";i:2;s:10:\"event_type\";i:3;s:10:\"start_date\";i:4;s:8:\"end_date\";i:5;s:11:\"semester_id\";i:6;s:5:\"color\";i:7;s:9:\"is_active\";i:8;s:10:\"created_by\";i:9;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:13;O:24:\"App\\Models\\AcademicEvent\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"academic_events\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:64;s:5:\"title\";s:29:\"Cetak Kartu Hasil Studi (KHS)\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:7:\"lainnya\";s:10:\"start_date\";s:10:\"2026-02-27\";s:8:\"end_date\";s:10:\"2026-02-27\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#6b7280\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:11:\"\0*\0original\";a:13:{s:2:\"id\";i:64;s:5:\"title\";s:29:\"Cetak Kartu Hasil Studi (KHS)\";s:11:\"description\";s:17:\"Imported from PDF\";s:10:\"event_type\";s:7:\"lainnya\";s:10:\"start_date\";s:10:\"2026-02-27\";s:8:\"end_date\";s:10:\"2026-02-27\";s:11:\"semester_id\";i:1;s:5:\"color\";s:7:\"#6b7280\";s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"updated_by\";i:1;s:10:\"created_at\";s:19:\"2026-05-25 09:30:55\";s:10:\"updated_at\";s:19:\"2026-05-25 09:30:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:10:\"start_date\";s:4:\"date\";s:8:\"end_date\";s:4:\"date\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:10:{i:0;s:5:\"title\";i:1;s:11:\"description\";i:2;s:10:\"event_type\";i:3;s:10:\"start_date\";i:4;s:8:\"end_date\";i:5;s:11:\"semester_id\";i:6;s:5:\"color\";i:7;s:9:\"is_active\";i:8;s:10:\"created_by\";i:9;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1782967180),
('active_semester', 'O:19:\"App\\Models\\Semester\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:9:\"semesters\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:18:{s:2:\"id\";i:1;s:13:\"nama_semester\";s:6:\"Ganjil\";s:17:\"nama_semester_old\";N;s:12:\"tahun_ajaran\";s:9:\"2025/2026\";s:6:\"status\";s:5:\"aktif\";s:9:\"is_active\";i:1;s:9:\"is_locked\";i:0;s:9:\"locked_at\";N;s:9:\"locked_by\";N;s:15:\"krs_dapat_diisi\";i:1;s:14:\"max_sks_rendah\";i:20;s:14:\"max_sks_tinggi\";i:24;s:9:\"krs_mulai\";s:10:\"2025-09-15\";s:11:\"krs_selesai\";s:10:\"2025-09-17\";s:13:\"tanggal_mulai\";s:10:\"2026-05-21\";s:15:\"tanggal_selesai\";s:10:\"2026-11-21\";s:10:\"created_at\";s:19:\"2026-05-21 16:16:40\";s:10:\"updated_at\";s:19:\"2026-06-02 09:09:16\";}s:11:\"\0*\0original\";a:18:{s:2:\"id\";i:1;s:13:\"nama_semester\";s:6:\"Ganjil\";s:17:\"nama_semester_old\";N;s:12:\"tahun_ajaran\";s:9:\"2025/2026\";s:6:\"status\";s:5:\"aktif\";s:9:\"is_active\";i:1;s:9:\"is_locked\";i:0;s:9:\"locked_at\";N;s:9:\"locked_by\";N;s:15:\"krs_dapat_diisi\";i:1;s:14:\"max_sks_rendah\";i:20;s:14:\"max_sks_tinggi\";i:24;s:9:\"krs_mulai\";s:10:\"2025-09-15\";s:11:\"krs_selesai\";s:10:\"2025-09-17\";s:13:\"tanggal_mulai\";s:10:\"2026-05-21\";s:15:\"tanggal_selesai\";s:10:\"2026-11-21\";s:10:\"created_at\";s:19:\"2026-05-21 16:16:40\";s:10:\"updated_at\";s:19:\"2026-06-02 09:09:16\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:8:{s:13:\"tanggal_mulai\";s:4:\"date\";s:15:\"tanggal_selesai\";s:4:\"date\";s:9:\"is_active\";s:7:\"boolean\";s:9:\"is_locked\";s:7:\"boolean\";s:9:\"locked_at\";s:8:\"datetime\";s:15:\"krs_dapat_diisi\";s:7:\"boolean\";s:9:\"krs_mulai\";s:4:\"date\";s:11:\"krs_selesai\";s:4:\"date\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:12:{i:0;s:13:\"nama_semester\";i:1;s:12:\"tahun_ajaran\";i:2;s:6:\"status\";i:3;s:13:\"tanggal_mulai\";i:4;s:15:\"tanggal_selesai\";i:5;s:9:\"is_active\";i:6;s:9:\"is_locked\";i:7;s:9:\"locked_at\";i:8;s:9:\"locked_by\";i:9;s:15:\"krs_dapat_diisi\";i:10;s:9:\"krs_mulai\";i:11;s:11:\"krs_selesai\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1782966880),
('spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:18:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:12:\"manage-users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:15:\"manage-students\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:16:\"manage-lecturers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:14:\"manage-courses\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:10:\"manage-krs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:10:\"manage-khs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:13:\"manage-grades\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:18:\"manage-internships\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:13:\"manage-thesis\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:17:\"manage-graduation\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:14:\"manage-finance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:13:\"manage-system\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:15:\"manage-settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:18:\"manage-permissions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:14:\"view-audit-log\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:16:\"impersonate-user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:22:\"override-academic-data\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:23:\"override-financial-data\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:3:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"super_admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:8:\"akademik\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:8:\"keuangan\";s:1:\"c\";s:3:\"web\";}}}', 1782285567);

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

-- --------------------------------------------------------

--
-- Table structure for table `dosens`
--

CREATE TABLE `dosens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `fakultas_id` bigint(20) UNSIGNED DEFAULT NULL,
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
  `kuota` int(11) NOT NULL DEFAULT 6,
  `absen_password_hash` varchar(255) DEFAULT NULL COMMENT 'Bcrypt hash for dosen QR attendance activation password'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dosens`
--

INSERT INTO `dosens` (`id`, `user_id`, `fakultas_id`, `nidn`, `pendidikan_terakhir`, `universitas`, `dosen_tetap`, `jabatan_fungsional`, `pendidikan`, `prodi`, `phone`, `address`, `mata_kuliah_ids`, `status`, `created_at`, `updated_at`, `kuota`, `absen_password_hash`) VALUES
(1, 2, 1, '0101018501', '[\"S3\"]', '[\"Universitas Gunadarma\"]', 1, '[\"Asisten Ahli\"]', 'S3', '[\"Ilmu Hukum\"]', '081234567891', 'Jl. Dosen No. 1', '[5,6,7]', 'aktif', '2026-05-21 09:13:22', '2026-05-21 10:26:50', 6, NULL),
(2, 3, NULL, '0102028601', NULL, NULL, 0, NULL, NULL, '\"Hukum Bisnis\"', '081234567892', 'Jl. Dosen No. 2', NULL, 'aktif', '2026-05-21 09:13:22', '2026-05-21 09:13:22', 6, NULL),
(3, 4, NULL, '0103038701', NULL, NULL, 0, NULL, NULL, '\"Hukum Pidana\"', '081234567893', 'Jl. Dosen No. 3', NULL, 'aktif', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 6, NULL),
(4, 12, NULL, '417017906', '[\"S1\",\"S2\",\"S3\"]', '[\"Universitas Pancasila\",\"Magister Ilmu Hukum Universitas Trisakti\",\"Doktor Ilmu Hukum Universitas Airlangga\"]', 1, '[\"Lektor\"]', 'S3', '[\"ilmu hukum\"]', '', '', NULL, 'aktif', '2026-05-21 09:16:13', '2026-05-21 09:16:13', 6, NULL),
(5, 13, NULL, '301067501', '[\"S1\",\"S2\",\"S3\"]', '[\"Universitas Muhammadiyah Jakarta\",\"Universitas Muhammadiyah Jakarta\",\"Universitas Islam Bandung\"]', 1, '[\"Lektor\"]', 'S3', '[\"ilmu hukum\"]', '', '', NULL, 'aktif', '2026-05-21 09:16:13', '2026-05-21 09:16:13', 6, NULL),
(6, 14, NULL, '3146747648130140', '[\"S1\",\"S2\",\"S3\"]', '[\"Universitas Andalas\",\"Universitas Andalas\",\"Universitas Jayabaya\"]', 0, '[\"Lektor\"]', 'S3', '[\"ilmu hukum\"]', '', '', NULL, 'aktif', '2026-05-21 09:16:13', '2026-05-21 09:16:13', 6, NULL),
(7, 15, NULL, '714076601', '[\"S1\",\"S2\",\"S3\"]', '[\"Universitas Bhayangkara Surabaya\",\"Universitas Airlangga\",\"Universitas Brawijaya\"]', 1, '[\"Lektor\"]', 'S3', '[\"ilmu hukum\"]', '', '', NULL, 'aktif', '2026-05-21 09:16:13', '2026-05-21 09:16:13', 6, NULL),
(8, 16, NULL, '302129701', '[\"S1\",\"S2\"]', '[\"Universitas Pancasila\",\"Magister Ilmu Hukum Universitas Indonesia\"]', 1, '[\"Lektor\"]', 'S2', '[\"ilmu hukum\"]', '', '', NULL, 'aktif', '2026-05-21 09:16:14', '2026-05-21 09:16:14', 6, NULL),
(9, 17, NULL, '302059501', '[\"S1\",\"S2\"]', '[\"Universitas Indonesia\",\"Master of Laws Lancaster University\"]', 1, '[\"Lektor\"]', 'S2', '[\"ilmu hukum\"]', '', '', '[4,8]', 'aktif', '2026-05-21 09:16:14', '2026-05-21 09:59:28', 6, NULL),
(10, 18, NULL, '313089202', '[\"S1\",\"S2\"]', '[\"Universitas Indonesia\",\"Master of Laws Vrije Universiteit Amsterdam\"]', 1, '[\"Lektor\"]', 'S2', '[\"ilmu hukum\"]', '', '', '[1,2,3]', 'aktif', '2026-05-21 09:16:14', '2026-05-21 09:58:56', 6, NULL),
(11, 19, NULL, '307089005', '[\"S1\",\"S2\"]', '[\"Universitas Indonesia\",\"Master of Laws University of Basque Country\"]', 1, '[\"Lektor\"]', 'S2', '[\"ilmu hukum\"]', '', '', NULL, 'aktif', '2026-05-21 09:16:14', '2026-05-21 09:16:14', 6, NULL),
(12, 20, NULL, '8918290024', '[\"S1\",\"S2\",\"S3\"]', '[\"Universitas Sebelas Maret\",\"Universitas Padjajaran\",\"Universitas Hasanuddin Makassar\"]', 0, '[\"Tenaga Pengajar\"]', 'S3', '[\"ilmu hukum\"]', '', '', NULL, 'aktif', '2026-05-21 09:16:15', '2026-05-21 09:16:15', 6, NULL),
(13, 21, NULL, '3860765666130310', '[\"S1\",\"S2\"]', '[\"Universitas Muhammadiyah Aceh Banda Aceh\",\"Universitas Pembangunan Nasional Veteran Jakarta\"]', 1, '[\"Tenaga Pengajar\"]', 'S2', '[\"ilmu hukum\"]', '', '', NULL, 'aktif', '2026-05-21 09:16:15', '2026-05-21 09:16:15', 6, NULL),
(14, 22, NULL, '1956751652130120', '[\"S1\",\"S2\"]', '[\"Universitas Islam Attahiriyah\",\"Universitas Pancasila\"]', 1, '[\"Tenaga Pengajar\"]', 'S2', '[\"ilmu hukum\"]', '', '', NULL, 'aktif', '2026-05-21 09:16:15', '2026-05-21 09:16:15', 6, NULL),
(15, 23, NULL, '3345774675130210', '[\"S1\",\"S2\"]', '[\"Universitas Indonesia\",\"Universitas Indonesia\"]', 1, '[\"Tenaga Pengajar\"]', 'S2', '[\"ilmu hukum\"]', '', '', NULL, 'aktif', '2026-05-21 09:16:15', '2026-05-21 09:16:15', 6, NULL),
(16, 24, NULL, '4434778679130070', '[\"S1\",\"S2\"]', '[\"Universitas Indonesia\",\"Universitas Malaya\"]', 1, '[\"Asisten Ahli\"]', 'S2', '[\"ilmu hukum\"]', '', '', NULL, 'aktif', '2026-05-21 09:16:15', '2026-05-21 09:16:15', 6, NULL),
(17, 25, NULL, '7641763664130240', '[\"S1\",\"S2\"]', '[\"Universitas Wiraswasta Indonesia\",\"Universitas Al Azhar Indonesia\"]', 1, '[\"Asisten Ahli\"]', 'S2', '[\"ilmu hukum\"]', '', '', NULL, 'aktif', '2026-05-21 09:16:16', '2026-05-21 09:16:16', 6, NULL),
(18, 26, NULL, '2150767668137030', '[\"S1\",\"S2\"]', '[\"Universitas Islam Indonesia\",\"Universitas Al Azhar Indonesia\"]', 1, '[\"Asisten Ahli\"]', 'S2', '[\"ilmu hukum\"]', '', '', NULL, 'aktif', '2026-05-21 09:16:16', '2026-05-21 09:16:16', 6, NULL),
(19, 27, NULL, '3454762663130160', '[\"S1\",\"S2\"]', '[\"Universitas Al-Azhar Indonesia\",\"Universitas Indonesia\"]', 1, '[\"Tenaga Pengajar\"]', 'S2', '[\"ilmu hukum\"]', '', '', NULL, 'aktif', '2026-05-21 09:16:16', '2026-05-21 09:16:16', 6, NULL),
(20, 28, NULL, '3204070406910000', '[\"S1\",\"S2\",\"S3\"]', '[\"Universitas Pasundan\",\"Universitas Padjajaran\",\"Universitas Indonesia\"]', 0, '[\"Tenaga Pengajar\"]', 'S3', '[\"ilmu hukum\"]', '', '', NULL, 'aktif', '2026-05-21 09:16:16', '2026-05-21 09:16:16', 6, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dosen_attendances`
--

CREATE TABLE `dosen_attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dosen_id` bigint(20) UNSIGNED NOT NULL,
  `kelas_mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `pertemuan_id` bigint(20) UNSIGNED DEFAULT NULL,
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

--
-- Dumping data for table `dosen_availabilities`
--

INSERT INTO `dosen_availabilities` (`id`, `dosen_id`, `semester_id`, `hari`, `jam_perkuliahan_id`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 10, 1, 'Senin', 1, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(2, 10, 1, 'Selasa', 1, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(3, 10, 1, 'Senin', 2, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(4, 10, 1, 'Selasa', 2, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(5, 10, 1, 'Senin', 3, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(6, 10, 1, 'Selasa', 3, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(7, 10, 1, 'Senin', 4, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(8, 10, 1, 'Selasa', 4, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(9, 10, 1, 'Senin', 5, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(10, 10, 1, 'Selasa', 5, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(11, 10, 1, 'Senin', 6, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(12, 10, 1, 'Selasa', 6, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(13, 10, 1, 'Senin', 7, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(14, 10, 1, 'Selasa', 7, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(15, 10, 1, 'Senin', 8, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(16, 10, 1, 'Selasa', 8, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(17, 10, 1, 'Senin', 9, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(18, 10, 1, 'Selasa', 9, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(19, 10, 1, 'Senin', 10, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(20, 10, 1, 'Selasa', 10, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(21, 10, 1, 'Senin', 11, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(22, 10, 1, 'Selasa', 11, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(23, 10, 1, 'Senin', 12, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(24, 10, 1, 'Selasa', 12, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(25, 10, 1, 'Senin', 13, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(26, 10, 1, 'Selasa', 13, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(27, 10, 1, 'Senin', 14, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(28, 10, 1, 'Selasa', 14, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(29, 10, 1, 'Senin', 15, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(30, 10, 1, 'Selasa', 15, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(31, 10, 1, 'Senin', 16, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(32, 10, 1, 'Selasa', 16, 'available', NULL, '2026-05-21 10:00:07', '2026-05-21 10:00:07'),
(65, 9, 1, 'Jumat', 1, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(66, 9, 1, 'Sabtu', 1, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(67, 9, 1, 'Jumat', 2, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(68, 9, 1, 'Sabtu', 2, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(69, 9, 1, 'Jumat', 3, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(70, 9, 1, 'Sabtu', 3, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(71, 9, 1, 'Jumat', 4, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(72, 9, 1, 'Sabtu', 4, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(73, 9, 1, 'Jumat', 5, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(74, 9, 1, 'Sabtu', 5, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(75, 9, 1, 'Jumat', 6, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(76, 9, 1, 'Sabtu', 6, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(77, 9, 1, 'Jumat', 7, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(78, 9, 1, 'Sabtu', 7, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(79, 9, 1, 'Jumat', 8, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(80, 9, 1, 'Sabtu', 8, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(81, 9, 1, 'Jumat', 9, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(82, 9, 1, 'Sabtu', 9, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(83, 9, 1, 'Jumat', 10, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(84, 9, 1, 'Sabtu', 10, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(85, 9, 1, 'Jumat', 11, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(86, 9, 1, 'Sabtu', 11, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(87, 9, 1, 'Jumat', 12, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(88, 9, 1, 'Sabtu', 12, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(89, 9, 1, 'Jumat', 13, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(90, 9, 1, 'Sabtu', 13, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(91, 9, 1, 'Jumat', 14, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(92, 9, 1, 'Sabtu', 14, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(93, 9, 1, 'Jumat', 15, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(94, 9, 1, 'Sabtu', 15, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(95, 9, 1, 'Jumat', 16, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(96, 9, 1, 'Sabtu', 16, 'available', NULL, '2026-05-21 10:00:42', '2026-05-21 10:00:42'),
(134, 1, 1, 'Kamis', 1, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(135, 1, 1, 'Kamis', 2, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(136, 1, 1, 'Senin', 3, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(137, 1, 1, 'Rabu', 3, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(138, 1, 1, 'Kamis', 4, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(139, 1, 1, 'Senin', 5, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(140, 1, 1, 'Rabu', 5, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(141, 1, 1, 'Kamis', 5, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(142, 1, 1, 'Selasa', 6, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(143, 1, 1, 'Rabu', 6, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(144, 1, 1, 'Selasa', 7, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(145, 1, 1, 'Rabu', 7, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(146, 1, 1, 'Kamis', 7, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(147, 1, 1, 'Kamis', 8, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(148, 1, 1, 'Senin', 9, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(149, 1, 1, 'Rabu', 9, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(150, 1, 1, 'Kamis', 9, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(151, 1, 1, 'Rabu', 10, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(152, 1, 1, 'Kamis', 10, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(153, 1, 1, 'Rabu', 11, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(154, 1, 1, 'Kamis', 11, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(155, 1, 1, 'Rabu', 12, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(156, 1, 1, 'Kamis', 12, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(157, 1, 1, 'Rabu', 13, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(158, 1, 1, 'Kamis', 13, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(159, 1, 1, 'Rabu', 14, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44'),
(160, 1, 1, 'Kamis', 14, 'available', NULL, '2026-06-15 07:06:44', '2026-06-15 07:06:44');

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
-- Table structure for table `dosen_mata_kuliah`
--

CREATE TABLE `dosen_mata_kuliah` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dosen_id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `semester_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dosen_mata_kuliah`
--

INSERT INTO `dosen_mata_kuliah` (`id`, `dosen_id`, `mata_kuliah_id`, `semester_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 10, 1, 1, 1, '2026-05-21 09:58:56', '2026-05-21 09:58:56'),
(2, 10, 2, 1, 1, '2026-05-21 09:58:56', '2026-05-21 09:58:56'),
(3, 10, 3, 1, 1, '2026-05-21 09:58:56', '2026-05-21 09:58:56'),
(4, 1, 5, 1, 1, '2026-05-21 09:59:19', '2026-05-21 09:59:19'),
(5, 1, 6, 1, 1, '2026-05-21 09:59:19', '2026-05-21 09:59:19'),
(6, 1, 7, 1, 1, '2026-05-21 09:59:19', '2026-05-21 09:59:19'),
(7, 9, 4, 1, 1, '2026-05-21 09:59:28', '2026-05-21 09:59:28'),
(8, 9, 8, 1, 1, '2026-05-21 09:59:28', '2026-05-21 09:59:28');

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

--
-- Dumping data for table `dosen_pa`
--

INSERT INTO `dosen_pa` (`id`, `dosen_id`, `mahasiswa_id`, `created_at`, `updated_at`) VALUES
(1, 1, 4, '2026-05-21 10:26:58', '2026-05-21 10:26:58');

-- --------------------------------------------------------

--
-- Table structure for table `email_blast_logs`
--

CREATE TABLE `email_blast_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `batch_id` varchar(50) NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `email_sent_to` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT 0,
  `error_message` text DEFAULT NULL,
  `recipient_type` enum('student','parent') NOT NULL DEFAULT 'student',
  `credential_type` enum('none','student','parents','both') NOT NULL DEFAULT 'none',
  `sent_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_blast_logs`
--

INSERT INTO `email_blast_logs` (`id`, `batch_id`, `mahasiswa_id`, `email_sent_to`, `subject`, `success`, `error_message`, `recipient_type`, `credential_type`, `sent_by`, `created_at`, `updated_at`) VALUES
(1, 'credentials_blast_6a0fc0bbcfe84', 6, 'gregoriusjoel28@gmail.com', 'Akun Login SIAKAD - Email dan Password Kampus Anda', 1, NULL, 'student', 'none', 1, '2026-05-22 02:34:40', '2026-05-22 02:34:40'),
(2, 'wisuda_notif_6a0fdc10ac71a', 6, 'jojo@student.stih.ac.id', 'Jadwal Wisuda Anda', 1, NULL, 'student', 'none', NULL, '2026-05-22 04:33:00', NULL),
(3, 'wisuda_notif_6a0fe27757f25', 6, 'gregoriusjoel28@gmail.com', 'Jadwal Wisuda Anda', 1, NULL, 'student', 'none', NULL, '2026-05-22 04:58:50', NULL),
(4, 'wisuda_notif_6a0fe3070544e', 6, 'gregoriusjoel28@gmail.com', 'Jadwal Wisuda Anda', 1, NULL, 'student', 'none', NULL, '2026-05-22 05:01:03', NULL),
(5, 'wisuda_notif_6a0fe4e3f177c', 6, 'gregoriusjoel28@gmail.com', 'Jadwal Wisuda Anda', 1, NULL, 'student', 'none', NULL, '2026-05-22 05:09:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `email_outboxes`
--

CREATE TABLE `email_outboxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `batch_id` varchar(255) DEFAULT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `target_email` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `greeting` varchar(255) DEFAULT NULL,
  `message_body` text DEFAULT NULL,
  `is_credentials_mode` tinyint(1) NOT NULL DEFAULT 0,
  `credential_type` enum('none','student','parents','both') NOT NULL DEFAULT 'none',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_outboxes`
--

INSERT INTO `email_outboxes` (`id`, `batch_id`, `mahasiswa_id`, `target_email`, `subject`, `greeting`, `message_body`, `is_credentials_mode`, `credential_type`, `status`, `scheduled_at`, `sent_at`, `error_message`, `created_at`, `updated_at`) VALUES
(1, 'wisuda_notif_6a0fdc10ac71a', 6, 'jojo@student.stih.ac.id', 'Jadwal Wisuda Anda', 'Yth. Jojo', 'Berikut jadwal wisuda Anda:\n\nBatch: Wisuda 22 Mei 2026\nTanggal: Jumat, 22 Mei 2026\nWaktu: 08:00 WIB\nLokasi: JICC\n', 0, 'none', 'sent', '2026-05-22 04:31:12', '2026-05-22 04:33:00', NULL, '2026-05-22 04:31:12', '2026-05-22 04:33:00'),
(2, 'wisuda_notif_6a0fe27757f25', 6, 'gregoriusjoel28@gmail.com', 'Jadwal Wisuda Anda', 'Yth. Jojo', 'Berikut jadwal wisuda Anda:\n\nBatch: Wisuda 22 Mei 2026\nTanggal: Jumat, 22 Mei 2026\nWaktu: 08:00 WIB\nLokasi: JICC\n', 0, 'none', 'sent', '2026-05-22 04:58:31', '2026-05-22 04:58:50', NULL, '2026-05-22 04:58:31', '2026-05-22 04:58:50'),
(3, 'wisuda_notif_6a0fe3070544e', 6, 'gregoriusjoel28@gmail.com', 'Jadwal Wisuda Anda', 'Yth. Jojo', 'Berikut jadwal wisuda Anda:\n\nBatch: Wisuda 22 Mei 2026\nTanggal: Jumat, 22 Mei 2026\nWaktu: 08:00 WIB\nLokasi: JICC\n', 0, 'none', 'sent', '2026-05-22 05:00:55', '2026-05-22 05:01:03', NULL, '2026-05-22 05:00:55', '2026-05-22 05:01:03'),
(4, 'wisuda_notif_6a0fe4e3f177c', 6, 'gregoriusjoel28@gmail.com', 'Jadwal Wisuda Anda', 'Yth. Jojo', 'Berikut jadwal wisuda Anda:\n\nBatch: Wisuda 22 Mei 2026\nTanggal: Jumat, 22 Mei 2026\nWaktu: 08:00 WIB\nLokasi: JICC\n', 0, 'none', 'sent', '2026-05-22 05:08:51', '2026-05-22 05:09:04', NULL, '2026-05-22 05:08:51', '2026-05-22 05:09:04'),
(5, 'wisuda_notif_6a2113fa44e40', 4, 'ahmadmahasiswa@student.stih.ac.id', 'Jadwal Wisuda Anda', 'Yth. Ahmad Mahasiswa', 'Berikut jadwal wisuda Anda:\n\nBatch: Wisuda 22 Mei 2026\nTanggal: Jumat, 22 Mei 2026\nWaktu: 08:00 WIB\nLokasi: JICC\n', 0, 'none', 'pending', '2026-06-04 05:58:18', NULL, NULL, '2026-06-04 05:58:18', '2026-06-04 05:58:18');

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
(1, 'FH', 'Fakultas Hukum', 'aktif', '2026-05-21 09:13:16', '2026-05-21 09:13:16'),
(2, 'FTI', 'Fakultas Teknik Informatika', 'aktif', '2026-05-21 09:51:20', '2026-05-21 09:51:20');

-- --------------------------------------------------------

--
-- Table structure for table `impersonation_logs`
--

CREATE TABLE `impersonation_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `impersonator_id` bigint(20) UNSIGNED NOT NULL,
  `target_user_id` bigint(20) UNSIGNED NOT NULL,
  `target_role` varchar(50) DEFAULT NULL COMMENT 'Role of the target user at time of impersonation',
  `reason` text DEFAULT NULL COMMENT 'Reason provided by Super Admin for impersonation',
  `started_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'When impersonation session began',
  `ended_at` timestamp NULL DEFAULT NULL COMMENT 'When impersonation session ended (null if still active)',
  `duration_seconds` int(10) UNSIGNED DEFAULT NULL COMMENT 'Duration in seconds (calculated on stop)',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

--
-- Dumping data for table `import_logs`
--

INSERT INTO `import_logs` (`id`, `user_id`, `type`, `filename`, `total_rows`, `success_count`, `failed_count`, `skipped_count`, `details`, `imported_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'ruangan', 'template_ruangan (1).xlsx', 7, 7, 0, 0, '\"{\\\"success\\\":[5,6,7,8,9,10,11],\\\"failed\\\":[],\\\"skipped\\\":[]}\"', '2026-05-21 09:53:47', '2026-05-21 09:53:47', '2026-05-21 09:53:47');

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
(1, 20, 4, 4, NULL, 'ada masalah ekonomi', 'SUBMITTED', NULL, NULL, NULL, '2026-06-10 05:12:26', '2026-06-10 05:12:26');

-- --------------------------------------------------------

--
-- Table structure for table `internships`
--

CREATE TABLE `internships` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `internship_type_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `semester_id` bigint(20) UNSIGNED NOT NULL,
  `semester_mahasiswa` int(11) DEFAULT NULL COMMENT 'Semester mahasiswa saat mendaftar magang',
  `instansi` varchar(255) NOT NULL,
  `alamat_instansi` text NOT NULL,
  `posisi` varchar(255) DEFAULT NULL,
  `periode_mulai` date NOT NULL,
  `periode_selesai` date NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `pembimbing_lapangan_nama` varchar(255) DEFAULT NULL,
  `pembimbing_lapangan_email` varchar(255) DEFAULT NULL,
  `pembimbing_lapangan_phone` varchar(255) DEFAULT NULL,
  `dokumen_pendukung_path` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `supervisor_dosen_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supervisor_assigned_at` timestamp NULL DEFAULT NULL,
  `converted_sks` tinyint(3) UNSIGNED NOT NULL DEFAULT 16,
  `final_score` decimal(5,2) DEFAULT NULL,
  `final_grade` varchar(3) DEFAULT NULL,
  `request_letter_generated_path` varchar(255) DEFAULT NULL,
  `request_letter_signed_path` varchar(255) DEFAULT NULL,
  `acceptance_letter_path` varchar(255) DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_reason` text DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `revision_no` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `admin_note` text DEFAULT NULL,
  `nomor_surat` varchar(255) DEFAULT NULL,
  `admin_final_pdf_path` varchar(255) DEFAULT NULL,
  `admin_signed_pdf_path` varchar(255) DEFAULT NULL,
  `sent_to_student_at` timestamp NULL DEFAULT NULL,
  `sent_by` bigint(20) UNSIGNED DEFAULT NULL,
  `date_changed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `date_changed_at` timestamp NULL DEFAULT NULL,
  `date_change_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `internships`
--

INSERT INTO `internships` (`id`, `mahasiswa_id`, `internship_type_id`, `semester_id`, `semester_mahasiswa`, `instansi`, `alamat_instansi`, `posisi`, `periode_mulai`, `periode_selesai`, `deskripsi`, `pembimbing_lapangan_nama`, `pembimbing_lapangan_email`, `pembimbing_lapangan_phone`, `dokumen_pendukung_path`, `status`, `supervisor_dosen_id`, `supervisor_assigned_at`, `converted_sks`, `final_score`, `final_grade`, `request_letter_generated_path`, `request_letter_signed_path`, `acceptance_letter_path`, `approved_by`, `approved_at`, `rejected_reason`, `rejected_at`, `revision_no`, `admin_note`, `nomor_surat`, `admin_final_pdf_path`, `admin_signed_pdf_path`, `sent_to_student_at`, `sent_by`, `date_changed_by`, `date_changed_at`, `date_change_reason`, `created_at`, `updated_at`) VALUES
(1, 6, 1, 1, 7, 'PT. SEMESTA NUSANTARA', 'Jl. Cawang jakarta', 'Legal Office Intern', '2026-07-12', '2026-10-12', 'magang legal', NULL, NULL, NULL, NULL, 'ongoing', 1, '2026-06-22 03:01:21', 16, NULL, NULL, 'internship/request/JOJO_50421684/request_letter_1_1782097070.docx', 'internship/signed/JOJO_50421684/internship_request_signed_1_1782097111.pdf', 'internship/acceptance/JOJO_50421684/acceptance_1_1782097312.pdf', 1, '2026-06-22 02:59:01', NULL, NULL, 0, NULL, '099/SK/STIH/III/2026', 'internship/admin_official/JOJO_50421684/official_1_1782097186.docx', 'internship/admin_signed/JOJO_50421684/official_signed_1_1782097273.pdf', '2026-06-22 03:01:14', 1, NULL, NULL, NULL, '2026-06-22 02:56:35', '2026-06-22 03:02:31');

-- --------------------------------------------------------

--
-- Table structure for table `internship_course_mappings`
--

CREATE TABLE `internship_course_mappings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `internship_id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `sks` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internship_logbooks`
--

CREATE TABLE `internship_logbooks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `internship_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `kegiatan` text NOT NULL,
  `catatan_dosen` text DEFAULT NULL,
  `created_by_role` varchar(255) NOT NULL DEFAULT 'mahasiswa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `internship_logbooks`
--

INSERT INTO `internship_logbooks` (`id`, `internship_id`, `tanggal`, `kegiatan`, `catatan_dosen`, `created_by_role`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-07-13', 'review judul magang', NULL, 'mahasiswa', '2026-06-22 03:08:52', '2026-06-22 03:08:52');

-- --------------------------------------------------------

--
-- Table structure for table `internship_revisions`
--

CREATE TABLE `internship_revisions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `internship_id` bigint(20) UNSIGNED NOT NULL,
  `revision_no` smallint(5) UNSIGNED NOT NULL,
  `request_letter_signed_path` varchar(255) DEFAULT NULL,
  `note_from_admin` text DEFAULT NULL,
  `note_from_mahasiswa` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internship_types`
--

CREATE TABLE `internship_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_conversion` tinyint(1) NOT NULL DEFAULT 0,
  `max_conversion_sks` tinyint(3) UNSIGNED NOT NULL DEFAULT 16,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `internship_types`
--

INSERT INTO `internship_types` (`id`, `code`, `name`, `description`, `is_conversion`, `max_conversion_sks`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'BERDAMPAK', 'Magang Berdampak (MBKM)', 'Program Magang MBKM dengan konversi SKS mata kuliah (maksimal 20 SKS).', 1, 20, 1, '2026-06-02 08:28:29', '2026-06-02 08:28:29'),
(2, 'MANDIRI', 'Magang Mandiri', 'Program Magang Mandiri pencarian mandiri tanpa konversi SKS.', 0, 0, 1, '2026-06-02 08:28:29', '2026-06-02 08:28:29');

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
  `auto_generated_from_krs` tinyint(1) NOT NULL DEFAULT 0,
  `allow_partial` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `bank_name` varchar(50) DEFAULT NULL,
  `va_number` varchar(50) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `student_id`, `semester`, `tahun_ajaran`, `sks_ambil`, `paket_sks_bayar`, `total_tagihan`, `status`, `auto_generated_from_krs`, `allow_partial`, `notes`, `bank_name`, `va_number`, `created_by`, `published_at`, `created_at`, `updated_at`) VALUES
(11, 1, 1, '2024/2025', 18, 18, 4500000, 'LUNAS', 0, 1, 'Pembayaran uang kuliah Semester 1', NULL, NULL, 1, '2024-09-01 08:00:00', '2026-05-29 04:39:46', '2026-05-29 04:39:46'),
(12, 1, 2, '2024/2025', 20, 20, 5000000, 'LUNAS', 0, 1, 'Pembayaran uang kuliah Semester 2', NULL, NULL, 1, '2025-02-01 08:00:00', '2026-05-29 04:39:46', '2026-05-29 04:39:46'),
(16, 4, 3, '2025/2026', 24, 24, 10000000, 'LUNAS', 0, 1, NULL, NULL, NULL, 9, '2026-06-04 12:04:03', '2026-06-04 05:03:38', '2026-06-04 05:05:52'),
(17, 4, 3, '2025/2026', 24, 24, 10000000, 'LUNAS', 0, 0, NULL, NULL, NULL, 9, '2026-06-04 12:04:06', '2026-06-04 05:04:00', '2026-06-04 05:05:47'),
(18, 4, 1, '2025/2026', 24, 24, 10000000, 'LUNAS', 0, 0, NULL, NULL, NULL, 9, '2026-06-04 12:06:38', '2026-06-04 05:06:34', '2026-06-04 05:08:38'),
(19, 4, 2, '2025/2026', 24, 24, 10000000, 'LUNAS', 0, 0, NULL, NULL, NULL, 9, '2026-06-04 12:06:54', '2026-06-04 05:06:52', '2026-06-04 05:07:49'),
(20, 4, 4, '2025/2026', 24, 24, 10000000, 'PUBLISHED', 0, 0, NULL, NULL, NULL, 9, '2026-06-04 13:11:31', '2026-06-04 06:11:27', '2026-06-04 06:11:31'),
(21, 6, 6, '2025/2026', 22, 22, 8000000, 'DRAFT', 0, 0, NULL, NULL, NULL, 9, NULL, '2026-06-04 06:12:32', '2026-06-04 06:12:32'),
(22, 6, 7, '2025/2026', 22, 22, 10000000, 'PUBLISHED', 0, 0, NULL, NULL, NULL, 9, '2026-06-10 12:18:26', '2026-06-10 05:18:22', '2026-06-10 05:18:26');

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
  `is_outside_availability` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'True jika jadwal dibuat di luar ketersediaan waktu dosen',
  `outside_reason` varchar(255) DEFAULT NULL COMMENT 'Alasan jadwal di luar availability: tidak mengisi / tidak cukup / bentrok',
  `kelas_perkuliahan_id` bigint(20) UNSIGNED DEFAULT NULL,
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

INSERT INTO `jadwals` (`id`, `kelas_id`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`, `is_outside_availability`, `outside_reason`, `kelas_perkuliahan_id`, `ruangan_id`, `status`, `catatan_dosen`, `catatan_admin`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Selasa', '13:45:00', '16:15:00', 'RI 1', 0, NULL, NULL, NULL, 'active', NULL, NULL, 18, '2026-05-21 10:01:11', '2026-05-21 10:01:11', '2026-05-21 10:01:11'),
(2, 5, 'Selasa', '20:40:00', '22:10:00', 'R 1', 0, NULL, NULL, NULL, 'active', NULL, NULL, 18, '2026-05-21 10:01:12', '2026-05-21 10:01:12', '2026-05-21 10:01:12'),
(3, 8, 'Senin', '10:30:00', '12:00:00', 'R 1', 0, NULL, NULL, NULL, 'active', NULL, NULL, 18, '2026-05-21 10:01:13', '2026-05-21 10:01:13', '2026-05-21 10:01:13'),
(4, 2, 'Kamis', '13:45:00', '16:15:00', 'R 4', 0, NULL, NULL, NULL, 'active', NULL, NULL, 2, '2026-05-21 10:01:26', '2026-05-21 10:01:26', '2026-05-21 10:01:26'),
(5, 3, 'Kamis', '19:55:00', '22:10:00', 'RI 1', 0, NULL, NULL, NULL, 'active', NULL, NULL, 2, '2026-05-21 10:01:28', '2026-05-21 10:01:28', '2026-05-21 10:01:28'),
(6, 4, 'Rabu', '16:15:00', '18:30:00', 'R 2', 0, NULL, NULL, NULL, 'active', NULL, NULL, 2, '2026-05-21 10:01:29', '2026-05-21 10:01:29', '2026-05-21 10:01:29'),
(7, 6, 'Jumat', '15:30:00', '16:55:00', 'R 2', 0, NULL, NULL, NULL, 'active', NULL, NULL, 17, '2026-05-21 10:01:38', '2026-05-21 10:01:38', '2026-05-21 10:01:38'),
(8, 7, 'Jumat', '20:40:00', '22:10:00', 'R 1', 0, NULL, NULL, NULL, 'active', NULL, NULL, 17, '2026-05-21 10:01:39', '2026-05-21 10:01:39', '2026-05-21 10:01:39');

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
(1, 1, 18, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-05-21 10:01:11', '2026-05-21 10:01:11', '2026-05-21 10:01:11'),
(2, 5, 18, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-05-21 10:01:12', '2026-05-21 10:01:12', '2026-05-21 10:01:12'),
(3, 8, 18, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-05-21 10:01:13', '2026-05-21 10:01:13', '2026-05-21 10:01:13'),
(4, 2, 2, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-05-21 10:01:26', '2026-05-21 10:01:26', '2026-05-21 10:01:26'),
(5, 3, 2, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-05-21 10:01:28', '2026-05-21 10:01:28', '2026-05-21 10:01:28'),
(6, 4, 2, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-05-21 10:01:29', '2026-05-21 10:01:29', '2026-05-21 10:01:29'),
(7, 6, 17, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-05-21 10:01:38', '2026-05-21 10:01:38', '2026-05-21 10:01:38'),
(8, 7, 17, 'dosen', 'approve', NULL, NULL, NULL, NULL, NULL, '2026-05-21 10:01:39', '2026-05-21 10:01:39', '2026-05-21 10:01:39');

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
(1, 1, 8, 0, NULL, 'completed', NULL, '2026-05-21 10:00:55', '2026-05-21 10:00:55');

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
  `is_outside_availability` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'True jika jadwal dibuat di luar ketersediaan waktu dosen',
  `outside_reason` varchar(255) DEFAULT NULL COMMENT 'Alasan jadwal di luar availability: tidak mengisi / tidak cukup / bentrok',
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

INSERT INTO `jadwal_proposals` (`id`, `mata_kuliah_id`, `kelas_id`, `dosen_id`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`, `is_outside_availability`, `outside_reason`, `ruangan_id`, `status`, `catatan_generate`, `generated_by`, `generated_at`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 10, 'Selasa', '13:45:00', '16:15:00', 'RI 1', 0, NULL, NULL, 'approved_admin', 'Auto generated (sesuai ketersediaan dosen)', 1, '2026-05-21 10:00:55', '2026-05-21 10:00:55', '2026-05-21 10:01:11'),
(2, 6, 2, 1, 'Kamis', '13:45:00', '16:15:00', 'R 4', 0, NULL, NULL, 'approved_admin', 'Auto generated (sesuai ketersediaan dosen)', 1, '2026-05-21 10:00:55', '2026-05-21 10:00:55', '2026-05-21 10:01:26'),
(3, 7, 3, 1, 'Kamis', '19:55:00', '22:10:00', 'RI 1', 0, NULL, NULL, 'approved_admin', 'Auto generated (sesuai ketersediaan dosen)', 1, '2026-05-21 10:00:55', '2026-05-21 10:00:55', '2026-05-21 10:01:28'),
(4, 5, 4, 1, 'Rabu', '16:15:00', '18:30:00', 'R 2', 0, NULL, NULL, 'approved_admin', 'Auto generated (sesuai ketersediaan dosen)', 1, '2026-05-21 10:00:55', '2026-05-21 10:00:55', '2026-05-21 10:01:29'),
(5, 2, 5, 10, 'Selasa', '20:40:00', '22:10:00', 'R 1', 0, NULL, NULL, 'approved_admin', 'Auto generated (sesuai ketersediaan dosen)', 1, '2026-05-21 10:00:55', '2026-05-21 10:00:55', '2026-05-21 10:01:12'),
(6, 8, 6, 9, 'Jumat', '15:30:00', '16:55:00', 'R 2', 0, NULL, NULL, 'approved_admin', 'Auto generated (sesuai ketersediaan dosen)', 1, '2026-05-21 10:00:55', '2026-05-21 10:00:55', '2026-05-21 10:01:38'),
(7, 4, 7, 9, 'Jumat', '20:40:00', '22:10:00', 'R 1', 0, NULL, NULL, 'approved_admin', 'Auto generated (sesuai ketersediaan dosen)', 1, '2026-05-21 10:00:55', '2026-05-21 10:00:55', '2026-05-21 10:01:39'),
(8, 1, 8, 10, 'Senin', '10:30:00', '12:00:00', 'R 1', 0, NULL, NULL, 'approved_admin', 'Auto generated (sesuai ketersediaan dosen)', 1, '2026-05-21 10:00:55', '2026-05-21 10:00:55', '2026-05-21 10:01:13');

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
(1, 1, '09:00:00', '09:45:00', 1, NULL, NULL),
(2, 2, '09:45:00', '10:30:00', 1, NULL, NULL),
(3, 3, '10:30:00', '11:15:00', 1, NULL, NULL),
(4, 4, '11:15:00', '12:00:00', 1, NULL, NULL),
(5, 5, '13:00:00', '13:45:00', 1, NULL, NULL),
(6, 6, '13:45:00', '14:30:00', 1, NULL, NULL),
(7, 7, '14:30:00', '15:15:00', 1, NULL, NULL),
(8, 8, '15:30:00', '16:15:00', 1, NULL, NULL),
(9, 9, '16:15:00', '16:55:00', 1, NULL, NULL),
(10, 10, '16:55:00', '17:45:00', 1, NULL, NULL),
(11, 11, '17:45:00', '18:30:00', 1, NULL, NULL),
(12, 12, '18:30:00', '19:15:00', 1, NULL, NULL),
(13, 13, '19:15:00', '19:55:00', 1, NULL, NULL),
(14, 14, '19:55:00', '20:40:00', 1, NULL, NULL),
(15, 15, '20:40:00', '21:25:00', 1, NULL, NULL),
(16, 16, '21:25:00', '22:10:00', 1, NULL, NULL);

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
-- Table structure for table `kategori_ruangans`
--

CREATE TABLE `kategori_ruangans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kategori` varchar(50) NOT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `warna_badge` varchar(20) NOT NULL DEFAULT 'gray' COMMENT 'Warna untuk badge di UI (blue, yellow, purple, green, gray)',
  `urutan` int(11) NOT NULL DEFAULT 0,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_ruangans`
--

INSERT INTO `kategori_ruangans` (`id`, `nama_kategori`, `deskripsi`, `warna_badge`, `urutan`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Kelas', 'Ruangan untuk pembelajaran teori di kelas', 'blue', 1, 'aktif', '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(2, 'Praktikum', 'Ruangan untuk praktikum dan latihan keterampilan', 'yellow', 2, 'aktif', '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(3, 'Sidang', 'Ruangan untuk sidang dan ujian skripsi', 'purple', 3, 'aktif', '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(4, 'Laboratorium', 'Ruangan untuk laboratorium dan penelitian', 'green', 4, 'aktif', '2026-05-21 09:13:24', '2026-05-21 09:13:24');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `dosen_id` bigint(20) UNSIGNED NOT NULL,
  `kapasitas` int(11) NOT NULL DEFAULT 40,
  `tahun_ajaran` varchar(20) NOT NULL,
  `semester_type` enum('Ganjil','Genap') NOT NULL DEFAULT 'Ganjil',
  `kelas_perkuliahan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `mata_kuliah_id`, `dosen_id`, `kapasitas`, `tahun_ajaran`, `semester_type`, `kelas_perkuliahan_id`, `created_at`, `updated_at`) VALUES
(1, 3, 10, 40, '2025/2026', 'Ganjil', 1, '2026-05-21 10:00:55', '2026-05-21 10:00:55'),
(2, 6, 1, 40, '2025/2026', 'Ganjil', 1, '2026-05-21 10:00:55', '2026-05-21 10:00:55'),
(3, 7, 1, 40, '2025/2026', 'Ganjil', 1, '2026-05-21 10:00:55', '2026-05-21 10:00:55'),
(4, 5, 1, 40, '2025/2026', 'Ganjil', 1, '2026-05-21 10:00:55', '2026-05-21 10:00:55'),
(5, 2, 10, 40, '2025/2026', 'Ganjil', 1, '2026-05-21 10:00:55', '2026-05-21 10:00:55'),
(6, 8, 9, 40, '2025/2026', 'Ganjil', 1, '2026-05-21 10:00:55', '2026-05-21 10:00:55'),
(7, 4, 9, 40, '2025/2026', 'Ganjil', 1, '2026-05-21 10:00:55', '2026-05-21 10:00:55'),
(8, 1, 10, 40, '2025/2026', 'Ganjil', 1, '2026-05-21 10:00:55', '2026-05-21 10:00:55');

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
  `online_meeting_link` varchar(255) DEFAULT NULL,
  `online_link` varchar(255) DEFAULT NULL,
  `asynchronous_tugas` text DEFAULT NULL,
  `asynchronous_file` varchar(255) DEFAULT NULL,
  `qr_token` varchar(255) DEFAULT NULL,
  `qr_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `qr_current_pertemuan` int(11) DEFAULT NULL,
  `kelas_perkuliahan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `qr_expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas_mata_kuliahs`
--

INSERT INTO `kelas_mata_kuliahs` (`id`, `mata_kuliah_id`, `dosen_id`, `semester_id`, `kode_kelas`, `kapasitas`, `ruang`, `ruangan_id`, `hari`, `jam_mulai`, `jam_selesai`, `metode_pengajaran`, `online_meeting_link`, `online_link`, `asynchronous_tugas`, `asynchronous_file`, `qr_token`, `qr_enabled`, `qr_current_pertemuan`, `kelas_perkuliahan_id`, `qr_expires_at`, `created_at`, `updated_at`) VALUES
(1, 3, 10, 1, '01', 40, 'RI 1', NULL, 'Selasa', '13:45:00', '16:15:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL, '2026-05-21 10:01:11', '2026-05-21 10:01:11'),
(2, 2, 10, 1, '01', 40, 'R 1', NULL, 'Selasa', '20:40:00', '22:10:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL, '2026-05-21 10:01:12', '2026-05-21 10:01:12'),
(3, 1, 10, 1, '01', 40, 'R 1', NULL, 'Senin', '10:30:00', '12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL, '2026-05-21 10:01:13', '2026-05-21 10:01:13'),
(4, 6, 1, 1, '01', 40, 'R 4', NULL, 'Kamis', '13:45:00', '16:15:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL, '2026-05-21 10:01:26', '2026-05-21 10:01:26'),
(5, 7, 1, 1, '01', 40, 'RI 1', NULL, 'Kamis', '19:55:00', '22:10:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL, '2026-05-21 10:01:28', '2026-05-21 10:01:28'),
(6, 5, 1, 1, '01', 40, 'R 2', NULL, 'Rabu', '16:15:00', '18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL, '2026-05-21 10:01:29', '2026-05-21 10:01:29'),
(7, 8, 9, 1, '01', 40, 'R 2', NULL, 'Jumat', '15:30:00', '16:55:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL, '2026-05-21 10:01:38', '2026-05-21 10:01:38'),
(8, 4, 9, 1, '01', 40, 'R 1', NULL, 'Jumat', '20:40:00', '22:10:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, NULL, '2026-05-21 10:01:39', '2026-05-21 10:01:39');

-- --------------------------------------------------------

--
-- Table structure for table `kelas_perkuliahans`
--

CREATE TABLE `kelas_perkuliahans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kelas` varchar(20) NOT NULL,
  `tingkat` tinyint(3) UNSIGNED NOT NULL,
  `angkatan` varchar(4) NOT NULL,
  `kode_prodi` varchar(10) NOT NULL,
  `kode_kelas` varchar(5) NOT NULL,
  `prodi_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tahun_akademik_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas_perkuliahans`
--

INSERT INTO `kelas_perkuliahans` (`id`, `nama_kelas`, `tingkat`, `angkatan`, `kode_prodi`, `kode_kelas`, `prodi_id`, `tahun_akademik_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '26HK01', 0, '2026', 'HK', '01', 1, 1, NULL, '2026-05-21 09:54:34', '2026-05-21 09:54:34'),
(2, '26HK02', 0, '2026', 'HK', '02', 1, 1, NULL, '2026-05-21 09:54:34', '2026-05-21 09:54:34'),
(3, '26HK03', 0, '2026', 'HK', '03', 1, 1, NULL, '2026-05-21 09:54:34', '2026-05-21 09:54:34'),
(4, '26HK04', 0, '2026', 'HK', '04', 1, 1, NULL, '2026-05-21 09:54:34', '2026-05-21 09:54:34');

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

-- --------------------------------------------------------

--
-- Table structure for table `krs`
--

CREATE TABLE `krs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kelas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tahun_ajaran` varchar(9) DEFAULT NULL,
  `status` enum('draft','sudah submit','approved','rejected') NOT NULL DEFAULT 'draft',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ambil_mk` enum('ya','tidak') NOT NULL DEFAULT 'ya',
  `internship_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_internship_conversion` tinyint(1) NOT NULL DEFAULT 0,
  `kelas_mata_kuliah_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `krs`
--

INSERT INTO `krs` (`id`, `mahasiswa_id`, `mata_kuliah_id`, `kelas_id`, `tahun_ajaran`, `status`, `keterangan`, `created_at`, `updated_at`, `ambil_mk`, `internship_id`, `is_internship_conversion`, `kelas_mata_kuliah_id`) VALUES
(1, 4, 1, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(2, 4, 2, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(3, 4, 3, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(4, 4, 4, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(5, 4, 5, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(6, 4, 6, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(7, 4, 7, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(8, 4, 8, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(9, 4, 9, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(10, 4, 10, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(11, 4, 11, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(12, 4, 12, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(13, 4, 13, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(14, 4, 14, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(15, 4, 15, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(16, 4, 16, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(17, 4, 17, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(18, 4, 18, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(19, 4, 19, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(20, 4, 20, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(21, 4, 21, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(22, 4, 22, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(23, 4, 23, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(24, 4, 24, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(25, 4, 25, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(26, 4, 26, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(27, 4, 27, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(28, 4, 28, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(29, 4, 29, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(30, 4, 30, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(31, 4, 31, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(32, 4, 32, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(33, 4, 33, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(34, 4, 34, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(35, 4, 35, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(36, 4, 36, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(37, 4, 37, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(38, 4, 38, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(39, 4, 39, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(40, 4, 40, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(41, 4, 41, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(42, 4, 42, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(43, 4, 43, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(44, 4, 44, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(45, 4, 45, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(46, 4, 46, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(47, 4, 47, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(48, 4, 48, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(49, 4, 49, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(50, 4, 50, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(51, 4, 51, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(52, 4, 52, NULL, NULL, 'approved', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24', 'ya', NULL, 0, NULL),
(53, 6, 2, 5, '2026/2027', 'approved', NULL, '2026-05-22 02:44:54', '2026-05-22 04:05:18', 'ya', NULL, 0, 2),
(54, 6, 3, 1, '2026/2027', 'approved', NULL, '2026-05-22 02:44:54', '2026-05-22 04:05:18', 'ya', NULL, 0, 1),
(55, 6, 1, 8, '2026/2027', 'approved', NULL, '2026-05-22 02:44:54', '2026-05-22 04:05:18', 'ya', NULL, 0, 3),
(56, 6, 5, 4, '2026/2027', 'approved', NULL, '2026-05-22 02:44:54', '2026-05-22 04:05:18', 'ya', NULL, 0, 6),
(57, 6, 6, 2, '2026/2027', 'approved', NULL, '2026-05-22 02:44:54', '2026-05-22 04:05:18', 'ya', NULL, 0, 4),
(58, 6, 7, 3, '2026/2027', 'approved', NULL, '2026-05-22 02:44:54', '2026-05-22 04:05:18', 'ya', NULL, 0, 5),
(59, 6, 8, 6, '2026/2027', 'approved', NULL, '2026-05-22 02:44:54', '2026-05-22 04:05:18', 'ya', NULL, 0, 7),
(60, 6, 4, 7, '2026/2027', 'approved', NULL, '2026-05-22 02:44:54', '2026-05-22 04:05:18', 'ya', NULL, 0, 8),
(61, 6, 10, NULL, '2026/2027', 'approved', NULL, '2026-05-22 02:46:10', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(62, 6, 15, NULL, '2026/2027', 'approved', NULL, '2026-05-22 02:46:10', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(63, 6, 11, NULL, '2026/2027', 'approved', NULL, '2026-05-22 02:46:10', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(64, 6, 12, NULL, '2026/2027', 'approved', NULL, '2026-05-22 02:46:10', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(65, 6, 13, NULL, '2026/2027', 'approved', NULL, '2026-05-22 02:46:10', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(66, 6, 14, NULL, '2026/2027', 'approved', NULL, '2026-05-22 02:46:10', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(67, 6, 9, NULL, '2026/2027', 'approved', NULL, '2026-05-22 02:46:10', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(74, 6, 16, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:45:36', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(75, 6, 20, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:45:36', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(76, 6, 25, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:45:36', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(77, 6, 19, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:45:36', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(78, 6, 17, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:45:36', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(79, 6, 18, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:45:36', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(80, 6, 21, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:45:36', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(81, 6, 22, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:45:37', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(82, 6, 23, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:45:37', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(83, 6, 24, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:45:37', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(84, 6, 36, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:46:11', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(85, 6, 30, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:46:11', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(86, 6, 28, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:46:11', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(87, 6, 27, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:46:11', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(88, 6, 35, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:46:11', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(89, 6, 34, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:46:11', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(90, 6, 31, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:46:11', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(91, 6, 33, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:46:11', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(92, 6, 29, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:46:11', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(93, 6, 32, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:46:11', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(94, 6, 26, NULL, '2027/2028', 'approved', NULL, '2026-05-22 03:46:11', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(95, 6, 45, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:31', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(96, 6, 41, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:31', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(97, 6, 39, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:31', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(98, 6, 47, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:31', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(99, 6, 40, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:31', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(100, 6, 37, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:31', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(101, 6, 46, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:31', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(102, 6, 44, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:31', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(103, 6, 43, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:31', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(104, 6, 48, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:31', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(105, 6, 38, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:31', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(106, 6, 42, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:31', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(107, 6, 57, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:45', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(108, 6, 58, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:45', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(109, 6, 59, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:45', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(110, 6, 49, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:45', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(111, 6, 50, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:45', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(112, 6, 51, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:45', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(113, 6, 52, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:45', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(114, 6, 53, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:45', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(115, 6, 55, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:45', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(116, 6, 56, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:45', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(117, 6, 60, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:45', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(118, 6, 54, NULL, '2028/2029', 'approved', NULL, '2026-05-22 03:46:45', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(119, 6, 63, NULL, '2029/2030', 'approved', NULL, '2026-05-22 03:47:27', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(120, 6, 61, NULL, '2029/2030', 'approved', NULL, '2026-05-22 03:47:27', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(121, 6, 62, NULL, '2029/2030', 'approved', NULL, '2026-05-22 03:47:27', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(122, 6, 66, NULL, '2029/2030', 'approved', NULL, '2026-05-22 03:47:27', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(123, 6, 68, NULL, '2029/2030', 'approved', NULL, '2026-05-22 03:47:27', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(124, 6, 69, NULL, '2029/2030', 'approved', NULL, '2026-05-22 03:47:27', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(125, 6, 67, NULL, '2029/2030', 'approved', NULL, '2026-05-22 03:47:27', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(126, 6, 65, NULL, '2029/2030', 'approved', NULL, '2026-05-22 03:47:27', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(127, 6, 70, NULL, '2029/2030', 'approved', NULL, '2026-05-22 03:47:27', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(128, 6, 64, NULL, '2029/2030', 'approved', NULL, '2026-05-22 03:47:27', '2026-05-22 04:05:18', 'ya', NULL, 0, NULL),
(129, 1, 2, 5, '2026/2027', 'sudah submit', NULL, '2026-05-22 07:49:05', '2026-05-22 07:49:05', 'ya', NULL, 0, 2),
(130, 1, 3, 1, '2026/2027', 'sudah submit', NULL, '2026-05-22 07:49:05', '2026-05-22 07:49:05', 'ya', NULL, 0, 1),
(131, 1, 1, 8, '2026/2027', 'sudah submit', NULL, '2026-05-22 07:49:05', '2026-05-22 07:49:05', 'ya', NULL, 0, 3),
(132, 1, 5, 4, '2026/2027', 'sudah submit', NULL, '2026-05-22 07:49:05', '2026-05-22 07:49:05', 'ya', NULL, 0, 6),
(133, 1, 6, 2, '2026/2027', 'sudah submit', NULL, '2026-05-22 07:49:05', '2026-05-22 07:49:05', 'ya', NULL, 0, 4),
(134, 1, 7, 3, '2026/2027', 'sudah submit', NULL, '2026-05-22 07:49:05', '2026-05-22 07:49:05', 'ya', NULL, 0, 5),
(135, 1, 8, 6, '2026/2027', 'sudah submit', NULL, '2026-05-22 07:49:05', '2026-05-22 07:49:05', 'ya', NULL, 0, 7),
(136, 1, 4, 7, '2026/2027', 'sudah submit', NULL, '2026-05-22 07:49:05', '2026-05-22 07:49:05', 'ya', NULL, 0, 8);

-- --------------------------------------------------------

--
-- Table structure for table `kuesioner_aktivasi`
--

CREATE TABLE `kuesioner_aktivasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `semester_id` bigint(20) UNSIGNED DEFAULT NULL,
  `semester_mahasiswa` int(11) DEFAULT NULL COMMENT 'Semester level of the student when filling the questionnaire',
  `fasilitas_kampus` int(11) NOT NULL COMMENT '1-5',
  `sistem_akademik` int(11) NOT NULL COMMENT '1-5',
  `kualitas_dosen` int(11) NOT NULL COMMENT '1-5',
  `layanan_administrasi` int(11) NOT NULL COMMENT '1-5',
  `kepuasan_keseluruhan` int(11) NOT NULL COMMENT '1-5',
  `saran` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kuesioner_aktivasi`
--

INSERT INTO `kuesioner_aktivasi` (`id`, `mahasiswa_id`, `semester_id`, `semester_mahasiswa`, `fasilitas_kampus`, `sistem_akademik`, `kualitas_dosen`, `layanan_administrasi`, `kepuasan_keseluruhan`, `saran`, `created_at`, `updated_at`) VALUES
(1, 6, 1, 7, 5, 5, 5, 5, 5, 'cukup', '2026-05-22 07:45:56', '2026-05-22 07:45:56'),
(2, 4, 1, 3, 5, 5, 5, 5, 5, 'Cukup', '2026-05-22 07:46:50', '2026-05-22 07:46:50'),
(4, 1, 1, 3, 5, 5, 5, 5, 5, NULL, '2026-06-02 06:54:28', '2026-06-02 06:54:28'),
(5, 1, 1, 4, 5, 5, 5, 5, 5, 'Mantap', '2026-06-02 06:55:26', '2026-06-02 06:55:26');

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
(1, 4, 'ahmadmahasiswa@student.stih.ac.id', 'Ilmu Hukum', NULL, 2026, 4, 4, 4, 4, 4, 4, 4, 'cukup', NULL, '2026-05-21 10:03:32', '2026-05-21 10:03:32'),
(2, 6, 'jojo@student.stih.ac.id', 'Ilmu Hukum', 'Laki-Laki', 2026, 4, 4, 4, 4, 4, 4, 4, 'tidak ada, cukup', NULL, '2026-05-22 02:35:26', '2026-05-22 02:35:26'),
(3, 1, 'andipratama@student.stih.ac.id', 'Ilmu Hukum', NULL, 2026, 4, 4, 4, 4, 4, 4, 4, 'Mantap', NULL, '2026-05-22 07:47:42', '2026-05-22 07:47:42');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswas`
--

CREATE TABLE `mahasiswas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nim` varchar(255) NOT NULL,
  `prodi` varchar(255) NOT NULL,
  `prodi_id` bigint(20) UNSIGNED DEFAULT NULL,
  `angkatan` varchar(255) NOT NULL,
  `semester` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `tahun_akademik_id` bigint(20) UNSIGNED DEFAULT NULL,
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
  `is_dokumen_unlocked` tinyint(1) NOT NULL DEFAULT 0,
  `kelas_perkuliahan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `new_survey_completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `file_ijazah` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`file_ijazah`)),
  `file_transkrip` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`file_transkrip`)),
  `file_kk` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`file_kk`)),
  `file_ktp` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`file_ktp`)),
  `email_pribadi` varchar(255) DEFAULT NULL COMMENT 'Email pribadi mahasiswa untuk login & notifikasi alternatif',
  `email_kampus` varchar(255) DEFAULT NULL COMMENT 'Email kampus otomatis: [nama_tanpa_spasi]@student.stih.ac.id',
  `email_aktif` enum('pribadi','kampus') NOT NULL DEFAULT 'pribadi' COMMENT 'Email aktif untuk login & notifikasi: pribadi | kampus',
  `email_pribadi_verified_at` timestamp NULL DEFAULT NULL COMMENT 'Timestamp saat email pribadi diverifikasi',
  `password_reset_token` varchar(255) DEFAULT NULL COMMENT 'Token untuk force reset password (opsional)',
  `is_default_password` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'true = password masih default (NIM), false = sudah diganti',
  `account_automation_at` timestamp NULL DEFAULT NULL COMMENT 'Timestamp saat akun otomasi dijalankan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mahasiswas`
--

INSERT INTO `mahasiswas` (`id`, `user_id`, `nim`, `prodi`, `prodi_id`, `angkatan`, `semester`, `tahun_akademik_id`, `last_semester_id`, `phone`, `no_hp`, `address`, `alamat`, `rt`, `rw`, `kota`, `kecamatan`, `desa`, `alamat_ktp`, `rt_ktp`, `rw_ktp`, `provinsi_ktp`, `kota_ktp`, `kecamatan_ktp`, `desa_ktp`, `provinsi`, `kabupaten`, `jenis_sekolah`, `jurusan_sekolah`, `tahun_lulus`, `nilai_kelulusan`, `foto`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `status_sipil`, `status`, `status_akun`, `is_dokumen_unlocked`, `kelas_perkuliahan_id`, `new_survey_completed`, `created_at`, `updated_at`, `file_ijazah`, `file_transkrip`, `file_kk`, `file_ktp`, `email_pribadi`, `email_kampus`, `email_aktif`, `email_pribadi_verified_at`, `password_reset_token`, `is_default_password`, `account_automation_at`) VALUES
(1, 5, '2024010001', 'Ilmu Hukum', 1, '2026', 4, 1, NULL, '081234567894', '1231231231231', 'Jl. Mahasiswa No. 1', '123', '12', '12', 'KAB. ACEH BARAT DAYA', 'LEMBAH SABIL', 'LADANG TUHA I', '123', '12', '12', 'ACEH', 'KAB. ACEH BARAT DAYA', 'LEMBAH SABIL', 'LADANG TUHA I', 'ACEH', NULL, '1 - Umum', 'SMA', '2026', 100.00, NULL, 'Jakarta', '2000-10-10', 'Laki-Laki', 'Katolik', 'Belum Menikah', 'aktif', 'aktif', 0, 1, 1, '2026-05-21 09:13:23', '2026-06-02 06:55:07', '[\"documents\\/mahasiswa\\/ANDI_PRATAMA_2024010001\\/f54c30e6-eb0c-40fe-a7d9-0dd9aef01121.pdf\"]', '[\"documents\\/mahasiswa\\/ANDI_PRATAMA_2024010001\\/2525e546-e150-40f7-aa01-8f9c8af46f36.pdf\"]', '[\"documents\\/mahasiswa\\/ANDI_PRATAMA_2024010001\\/bb91228c-cb56-48c5-8800-624cb58d1c97.pdf\"]', '[\"documents\\/mahasiswa\\/ANDI_PRATAMA_2024010001\\/8ead5f91-9f2c-4d98-bcc6-814b14d6e7ac.pdf\"]', NULL, 'andipratama@student.stih.ac.id', 'kampus', NULL, NULL, 1, NULL),
(2, 6, '2024010002', 'Hukum Bisnis', NULL, '2024', 1, NULL, NULL, '081234567895', NULL, 'Jl. Mahasiswa No. 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'aktif', 'baru', 0, NULL, 0, '2026-05-21 09:13:23', '2026-05-21 09:13:23', NULL, NULL, NULL, NULL, NULL, NULL, 'pribadi', NULL, NULL, 1, NULL),
(3, 7, '2024010003', 'Hukum Pidana', NULL, '2024', 1, NULL, NULL, '081234567896', NULL, 'Jl. Mahasiswa No. 3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'aktif', 'baru', 0, NULL, 0, '2026-05-21 09:13:23', '2026-05-21 09:13:23', NULL, NULL, NULL, NULL, NULL, NULL, 'pribadi', NULL, NULL, 1, NULL),
(4, 10, '2024001', 'Ilmu Hukum', 1, '2026', 3, 1, NULL, NULL, '1231231231231', NULL, 'Jakarta', '12', '12', 'KAB. ACEH BARAT', 'ARONGAN LAMBALEK', 'ALUE SUNDAK', 'Jakarta', '12', '12', 'ACEH', 'KAB. ACEH BARAT', 'ARONGAN LAMBALEK', 'ALUE SUNDAK', 'ACEH', NULL, '1 - Umum', 'SMA', '2026', 100.00, NULL, 'Jakarta', '2000-10-10', 'Laki-Laki', 'Buddha', 'Belum Menikah', 'aktif', 'baru', 0, 1, 1, '2026-05-21 09:13:24', '2026-06-02 02:03:09', '[\"documents\\/mahasiswa\\/AHMAD_MAHASISWA_2024001\\/592bd5c7-f8e9-47a8-bf72-006c67b1f252.pdf\"]', '[\"documents\\/mahasiswa\\/AHMAD_MAHASISWA_2024001\\/0bc5dd24-4912-4018-863d-6a46d30e5294.pdf\"]', '[\"documents\\/mahasiswa\\/AHMAD_MAHASISWA_2024001\\/10ebf57a-ba43-4832-8bb9-ed944738a5be.pdf\"]', '[\"documents\\/mahasiswa\\/AHMAD_MAHASISWA_2024001\\/d5af8e70-0bb6-42bc-920f-db7472864937.pdf\"]', NULL, 'ahmadmahasiswa@student.stih.ac.id', 'kampus', NULL, NULL, 1, NULL),
(5, 11, '2024002', 'Hukum Bisnis', NULL, '2024', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'aktif', 'baru', 0, NULL, 0, '2026-05-21 09:13:24', '2026-05-21 09:13:24', NULL, NULL, NULL, NULL, NULL, NULL, 'pribadi', NULL, NULL, 1, NULL),
(6, 30, '50421684', 'Ilmu Hukum', 1, '2026', 7, 1, NULL, NULL, '82282228222', NULL, 'Jakarta', '12', '12', 'KOTA ADM. JAKARTA PUSAT', 'CEMPAKA PUTIH', 'CEMPAKA PUTIH BARAT', 'Jakarta', '12', '12', 'DKI JAKARTA', 'KOTA ADM. JAKARTA PUSAT', 'CEMPAKA PUTIH', 'CEMPAKA PUTIH BARAT', 'DKI JAKARTA', NULL, '1 - Umum', 'SMA', '2026', 100.00, 'images/mahasiswa/foto/JOJO_50421684/b617b89b-5e19-4338-8996-ec8e168371df.png', 'Jakarta', '2000-02-28', 'Laki-Laki', 'Katolik', 'Belum Menikah', 'aktif', 'baru', 0, 1, 1, '2026-05-22 02:34:13', '2026-05-22 03:46:54', '[\"documents\\/mahasiswa\\/JOJO_50421684\\/5cd84b5e-7cbc-4022-9f90-19218678b38a.pdf\"]', '[\"documents\\/mahasiswa\\/JOJO_50421684\\/00ad7ed6-9b7f-4cff-94c9-06c1344d4047.pdf\"]', '[\"documents\\/mahasiswa\\/JOJO_50421684\\/cbd00a11-75c7-46c8-993b-2f4180cba5f3.pdf\"]', '[\"documents\\/mahasiswa\\/JOJO_50421684\\/7318a893-fae4-4f36-9860-c173a4bac7e1.pdf\"]', 'gregoriusjoel28@gmail.com', 'jojo@student.stih.ac.id', 'kampus', NULL, NULL, 1, NULL);

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
  `tipe` enum('teori','praktikum','sidang','lab') NOT NULL DEFAULT 'teori' COMMENT 'Jenis mata kuliah: teori, praktikum, sidang, atau lab',
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

INSERT INTO `mata_kuliahs` (`id`, `kode_mk`, `kode_id`, `nama_mk`, `praktikum`, `tipe`, `sks`, `semester`, `jenis`, `deskripsi`, `created_at`, `updated_at`, `prodi_id`, `fakultas_id`) VALUES
(1, 'ADH10010', 'sms1', 'Ilmu Agama', NULL, 'teori', 2, 1, 'wajib_nasional', 'Mata Kuliah Wajib Nasional', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(2, 'ADH10006', 'sms1', 'Bahasa Indonesia Hukum', NULL, 'teori', 2, 1, 'wajib_nasional', 'Mata Kuliah Wajib Nasional', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(3, 'ADH10007', 'sms1', 'Pancasila & Kewargakabupatenan', NULL, 'teori', 3, 1, 'wajib_nasional', 'Mata Kuliah Wajib Nasional', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(4, 'ADH30001', 'sms1', 'Ekonomi Pembangunan', NULL, 'teori', 2, 1, 'pilihan', 'Mata Kuliah Pilihan', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(5, 'ADH20001', 'sms1', 'Ilmu Kabupaten', NULL, 'teori', 3, 1, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(6, 'ADH20002', 'sms1', 'Pengantar Ilmu Hukum', NULL, 'teori', 3, 1, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(7, 'ADH20003', 'sms1', 'Pengantar Hukum Indonesia', NULL, 'teori', 3, 1, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(8, 'ADH20004', 'sms1', 'Hukum & Hak Asasi Manusia', NULL, 'teori', 2, 1, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(9, 'ADH20050', 'sms2', 'Hukum Perdata', NULL, 'teori', 3, 2, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(10, 'ADH20006', 'sms2', 'Hukum Pidana', NULL, 'teori', 3, 2, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(11, 'ADH20009', 'sms2', 'Hukum Adat', NULL, 'teori', 4, 2, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(12, 'ADH20010', 'sms2', 'Hukum Islam', NULL, 'teori', 2, 2, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(13, 'ADH20012', 'sms2', 'Ilmu Perundang-undangan', 1, 'teori', 2, 2, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 1', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(14, 'ADH20014', 'sms2', 'Hukum Internasional', NULL, 'teori', 3, 2, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(15, 'ADH20007', 'sms2', 'Hukum Tata Kabupaten', NULL, 'teori', 3, 2, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(16, 'ADH20005', 'sms3', 'Hukum Benda & Orang', NULL, 'teori', 2, 3, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(17, 'ADH20015', 'sms3', 'Hukum Dagang', NULL, 'teori', 3, 3, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(18, 'ADH20018', 'sms3', 'Hukum Acara Pidana', 1, 'teori', 3, 3, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 1', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(19, 'ADH20013', 'sms3', 'Hukum Acara Perdata', 1, 'teori', 3, 3, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 1', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(20, 'ADH20008', 'sms3', 'Hukum Administrasi Kabupaten', NULL, 'teori', 3, 3, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(21, 'ADH20020', 'sms3', 'Kejaksaan dan Badan Peradilan di Indonesia', 1, 'teori', 2, 3, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 1', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(22, 'ADH20040', 'sms3', 'Hukum Sanksi', 1, 'teori', 2, 3, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 1', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(23, 'ADH20051', 'sms3', 'Penerapan Asas-Asas Hukum Pidana', NULL, 'teori', 2, 3, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(24, 'ADH30011', 'sms3', 'Lembaga Kabupaten Indonesia', NULL, 'teori', 2, 3, 'pilihan', 'Mata Kuliah Pilihan', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(25, 'ADH20011', 'sms3', 'Hukum Perikatan', NULL, 'teori', 2, 3, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(26, 'ADH30002', 'sms4', 'Legal English', 1, 'teori', 2, 4, 'pilihan', 'Mata Kuliah Pilihan • Praktikum: 1', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(27, 'ADH20019', 'sms4', 'Hukum Acara Tata Usaha Kabupaten', 1, 'teori', 3, 4, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 1', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(28, 'ADH20017', 'sms4', 'Hukum Agraria', NULL, 'teori', 2, 4, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(29, 'ADH20047', 'sms4', 'Hukum Perdata Internasional', NULL, 'teori', 2, 4, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(30, 'ADH20016', 'sms4', 'Hukum Ketenagakerjaan', NULL, 'teori', 2, 4, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(31, 'ADH20026', 'sms4', 'Hukum Kekayaan Interlektual', 1, 'teori', 2, 4, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 1', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(32, 'ADH20052', 'sms4', 'Kejaksaan Dalam Sistem Peradilan Pidana', 1, 'teori', 2, 4, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 1', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(33, 'ADH20036', 'sms4', 'Praktik Pembuktian Pidana', 2, 'teori', 3, 4, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 2', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(34, 'ADH20023', 'sms4', 'Hukum Lingkungan', NULL, 'teori', 2, 4, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(35, 'ADH20022', 'sms4', 'Hukum Pajak', NULL, 'teori', 2, 4, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(36, 'ADH10009', 'sms4', 'Logika Hukum', NULL, 'teori', 2, 4, 'wajib_nasional', 'Mata Kuliah Wajib Nasional', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(37, 'ADH20033', 'sms5', 'Metode Penelitian Hukum & Penulisan Jurnal Ilmiah', 1, 'teori', 2, 5, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 1', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(38, 'ADH30005', 'sms5', 'Hukum Pidana Khusus', NULL, 'teori', 2, 5, 'pilihan', 'Mata Kuliah Pilihan', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(39, 'ADH20027', 'sms5', 'Hukum Perlindungan Anak', NULL, 'teori', 2, 5, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(40, 'ADH20029', 'sms5', 'Hukum Pidana Internasional', NULL, 'teori', 2, 5, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(41, 'ADH20025', 'sms5', 'Hukum Acara Mahkamah Konstitusi', 1, 'teori', 2, 5, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 1', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(42, 'ADH30007', 'sms5', 'Hukum Siber', NULL, 'teori', 2, 5, 'pilihan', 'Mata Kuliah Pilihan', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(43, 'ADH20048', 'sms5', 'Hukum Humaniter', NULL, 'teori', 2, 5, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(44, 'ADH20035', 'sms5', 'Hukum Kepailitan', 1, 'teori', 2, 5, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 1', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(45, 'ADH20021', 'sms5', 'Hukum Perdata Islam', 1, 'teori', 2, 5, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 1', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(46, 'ADH20034', 'sms5', 'Pencucian Uang, Penyitaan & Pemulihan Aset', NULL, 'teori', 2, 5, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(47, 'ADH20028', 'sms5', 'Hukum Antar Tata Hukum', NULL, 'teori', 2, 5, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:23', '2026-05-21 09:13:23', 1, 1),
(48, 'ADH20053', 'sms5', 'Kejaksaan Dalam Bidang Perdata & Tata Usaha Kabupaten', 1, 'teori', 2, 5, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 1', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(49, 'ADH20037', 'sms6', 'Etika, Tanggung Jawab & Profesi Hukum', NULL, 'teori', 2, 6, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(50, 'ADH20038', 'sms6', 'Filsafat Hukum', NULL, 'teori', 2, 6, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(51, 'ADH20039', 'sms6', 'Legal Drafting', 2, 'teori', 2, 6, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 2', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(52, 'ADH20041', 'sms6', 'Praktik Hukum Perdata', 1, 'teori', 2, 6, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 1', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(53, 'ADH20042', 'sms6', 'Praktik Hukum Tata Usaha Kabupaten', 1, 'teori', 2, 6, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 1', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(54, 'ADH40004', 'sms6', 'Kriminologi & Viktimologi', NULL, 'teori', 2, 6, 'peminatan', 'Mata Kuliah Peminatan', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(55, 'ADH20044', 'sms6', 'Arbitrase & Alternative Dispute Resolution', 1, 'teori', 2, 6, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 1', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(56, 'ADH30004', 'sms6', 'Hukum Perusahaan, Persaingan Usaha & Jaminan', NULL, 'teori', 2, 6, 'pilihan', 'Mata Kuliah Pilihan', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(57, 'ADH20030', 'sms6', 'Hukum Perbankan & Surat Berharga', NULL, 'teori', 2, 6, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(58, 'ADH20031', 'sms6', 'Penyelesaian Sengketa Industrial', NULL, 'teori', 2, 6, 'wajib_prodi', 'Mata Kuliah Wajib Prodi', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(59, 'ADH20032', 'sms6', 'Perbandingan Hukum Pidana', 1, 'teori', 2, 6, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 1', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(60, 'ADH30010', 'sms6', 'Hukum Organisasi Internasional', NULL, 'teori', 2, 6, 'pilihan', 'Mata Kuliah Pilihan', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(61, 'ADH20024', 'sms7', 'Hukum Administrasi Kabupaten Sektoral', 1, 'teori', 2, 7, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 1', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(62, 'ADH30003', 'sms7', 'Legal Enterpreneurship (Kewirausahaan)', 1, 'teori', 2, 7, 'pilihan', 'Mata Kuliah Pilihan • Praktikum: 1', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(63, 'ADD20049', 'sms7', 'Penulisan Skripsi/ Penulisan Jurnal Ilmiah', 3, 'teori', 4, 7, 'wajib_prodi', 'Mata Kuliah Wajib Prodi • Praktikum: 3', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(64, 'ADH40001', 'sms7', 'Hukum Laut', NULL, 'teori', 2, 7, 'peminatan', 'Mata Kuliah Peminatan', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(65, 'ADH30014', 'sms7', 'Hukum Perlindungan Konsumen', NULL, 'teori', 2, 7, 'pilihan', 'Mata Kuliah Pilihan', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(66, 'ADH30006', 'sms7', 'Hukum Jaminan', 1, 'teori', 2, 7, 'pilihan', 'Mata Kuliah Pilihan • Praktikum: 1', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(67, 'ADH30012', 'sms7', 'Hukum Kesehatan & Medikolegal', NULL, 'teori', 2, 7, 'pilihan', 'Mata Kuliah Pilihan', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(68, 'ADH30008', 'sms7', 'Hukum Investasi', NULL, 'teori', 2, 7, 'pilihan', 'Mata Kuliah Pilihan', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(69, 'ADH30009', 'sms7', 'Hukum Perjanjian Internasional', NULL, 'teori', 2, 7, 'pilihan', 'Mata Kuliah Pilihan', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1),
(70, 'ADH30015', 'sms7', 'Kapita Selekta Hukum Acara Pidana', 1, 'teori', 2, 7, 'pilihan', 'Mata Kuliah Pilihan • Praktikum: 1', '2026-05-21 09:13:24', '2026-05-21 09:13:24', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mata_kuliah_semesters`
--

CREATE TABLE `mata_kuliah_semesters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `semester_id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('active','history','archived') NOT NULL DEFAULT 'active',
  `source_semester_id` bigint(20) UNSIGNED DEFAULT NULL,
  `activated_at` datetime DEFAULT NULL,
  `deactivated_at` datetime DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mata_kuliah_semesters`
--

INSERT INTO `mata_kuliah_semesters` (`id`, `semester_id`, `mata_kuliah_id`, `status`, `source_semester_id`, `activated_at`, `deactivated_at`, `meta`, `created_at`, `updated_at`) VALUES
(1, 1, 55, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(2, 1, 2, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(3, 1, 4, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(4, 1, 49, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(5, 1, 50, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(6, 1, 8, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(7, 1, 41, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(8, 1, 19, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(9, 1, 18, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(10, 1, 27, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(11, 1, 11, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(12, 1, 20, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(13, 1, 61, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(14, 1, 28, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(15, 1, 47, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(16, 1, 16, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(17, 1, 17, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(18, 1, 43, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(19, 1, 14, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(20, 1, 68, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(21, 1, 12, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(22, 1, 66, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(23, 1, 31, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(24, 1, 44, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(25, 1, 67, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(26, 1, 30, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(27, 1, 64, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(28, 1, 34, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(29, 1, 60, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(30, 1, 35, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(31, 1, 57, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(32, 1, 9, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(33, 1, 29, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(34, 1, 45, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(35, 1, 25, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(36, 1, 69, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(37, 1, 39, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(38, 1, 65, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(39, 1, 56, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(40, 1, 10, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(41, 1, 40, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(42, 1, 38, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(43, 1, 22, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(44, 1, 42, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(45, 1, 15, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(46, 1, 1, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(47, 1, 5, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(48, 1, 13, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(49, 1, 70, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(50, 1, 48, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(51, 1, 32, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(52, 1, 21, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(53, 1, 54, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(54, 1, 51, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(55, 1, 26, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(56, 1, 62, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(57, 1, 24, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(58, 1, 36, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(59, 1, 37, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(60, 1, 3, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(61, 1, 46, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(62, 1, 23, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(63, 1, 7, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(64, 1, 6, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(65, 1, 63, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(66, 1, 58, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(67, 1, 59, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(68, 1, 52, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(69, 1, 53, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45'),
(70, 1, 33, 'active', NULL, '2026-05-21 16:51:45', NULL, NULL, '2026-05-21 09:51:45', '2026-05-21 09:51:45');

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
(98, '2026_02_11_000001_create_pertemuans_table', 1),
(99, '2026_02_11_033628_add_pertemuan_to_presensis_table', 1),
(100, '2026_02_11_034943_create_dokumen_kelas_table', 1),
(101, '2026_02_11_035309_add_asynchronous_file_to_kelas_mata_kuliahs', 1),
(102, '2026_02_12_041345_add_approval_fields_to_pengajuans_table', 1),
(103, '2026_02_12_080836_add_metode_columns_to_kelas_reschedules_table', 1),
(104, '2026_02_13_000001_add_semester_transition_fields', 1),
(105, '2026_02_18_000000_fix_invoices_student_foreign_to_mahasiswas', 1),
(106, '2026_02_18_000001_add_role_to_users_table', 1),
(107, '2026_02_18_000001_fix_installment_requests_student_foreign_to_mahasiswas', 1),
(108, '2026_02_18_000002_create_students_table', 1),
(109, '2026_02_18_000003_create_invoices_table', 1),
(110, '2026_02_18_000004_create_installment_requests_table', 1),
(111, '2026_02_18_000005_create_installments_table', 1),
(112, '2026_02_18_000006_create_payment_proofs_table', 1),
(113, '2026_02_18_000007_create_payments_table', 1),
(114, '2026_02_18_000008_create_audit_logs_table', 1),
(115, '2026_02_18_021638_make_user_id_nullable_in_parents_table', 1),
(116, '2026_02_18_022023_make_mahasiswa_id_nullable_in_parents_table', 1),
(117, '2026_02_19_000001_drop_absen_password_hash_from_dosens', 1),
(118, '2026_02_19_050352_add_kuota_to_dosens_table', 1),
(119, '2026_02_19_065019_add_online_meeting_link_to_pertemuans_table', 1),
(120, '2026_02_20_000001_add_metode_pengajaran_to_pertemuans', 1),
(121, '2026_02_20_000002_add_absen_password_hash_to_dosens', 1),
(122, '2026_02_20_000003_create_dosen_attendances_table', 1),
(123, '2026_02_23_100000_add_location_fields_to_presensis_table', 1),
(124, '2026_02_25_022611_add_submission_type_to_tugas_table', 1),
(125, '2026_02_25_022640_add_text_submission_to_tugas_submissions_table', 1),
(126, '2026_02_25_024708_drop_assignments_tables', 1),
(127, '2026_02_25_080555_add_availability_tracking_to_jadwal_proposals_table', 1),
(128, '2026_02_25_080641_add_availability_tracking_to_jadwals_table', 1),
(129, '2026_02_26_033215_make_schedule_fields_nullable_in_kelas_mata_kuliahs_table', 1),
(130, '2026_02_27_000001_create_mata_kuliah_semesters_table', 1),
(131, '2026_02_27_000002_add_lock_fields_to_semesters_table', 1),
(132, '2026_02_27_000003_add_audit_fields_to_audit_logs_table', 1),
(133, '2026_02_28_000001_add_tipe_pertemuan_to_pertemuans_table', 1),
(134, '2026_02_28_100000_add_indexes_and_audit_to_academic_events', 1),
(135, '2026_02_28_200000_create_dosen_mata_kuliah_table', 1),
(136, '2026_03_03_000001_upgrade_pengajuans_workflow', 1),
(137, '2026_03_03_000002_create_pengajuan_revisions_table', 1),
(138, '2026_03_03_035729_add_is_dokumen_unlocked_to_mahasiswas_table', 1),
(139, '2026_03_04_000001_create_internships_table', 1),
(140, '2026_03_05_063714_add_semester_mahasiswa_to_internships_table', 1),
(141, '2026_03_06_000001_update_internships_for_full_workflow', 1),
(142, '2026_03_11_073140_drop_unused_columns_from_parents_table', 1),
(143, '2026_03_12_000001_create_thesis_submissions_table', 1),
(144, '2026_03_12_000002_create_thesis_guidances_table', 1),
(145, '2026_03_12_000003_create_thesis_sidang_tables', 1),
(146, '2026_03_12_000004_create_thesis_sidang_schedules_table', 1),
(147, '2026_03_12_000005_create_thesis_revisions_table', 1),
(148, '2026_03_31_040610_create_uploads_table', 1),
(149, '2026_03_31_100000_add_logbook_file_to_thesis_submissions', 1),
(150, '2026_04_01_035237_rename_thesis_to_skripsi_tables', 1),
(151, '2026_04_13_120000_add_fakultas_id_to_dosens_table', 1),
(152, '2026_04_15_000000_fix_installment_requests_foreign_key_properly', 1),
(153, '2026_04_15_034718_drop_fakultas_id_from_prodis_table', 1),
(154, '2026_04_15_add_online_meeting_link_to_kelas_mata_kuliahs', 1),
(155, '2026_04_16_000001_create_kategori_ruangans_table', 1),
(156, '2026_04_16_000002_add_kategori_id_to_ruangans_table', 1),
(157, '2026_04_16_062753_update_krs_status_enum_to_sudah_submit', 1),
(158, '2026_04_16_add_tipe_to_mata_kuliahs_table', 1),
(159, '2026_04_17_070255_restore_fakultas_id_to_prodis_table', 1),
(160, '2026_04_20_000001_create_kelas_perkuliahans_table', 1),
(161, '2026_04_20_000002_add_kelas_perkuliahan_id_to_related_tables', 1),
(162, '2026_04_20_000003_add_email_columns_to_mahasiswas_table', 1),
(163, '2026_04_20_000004_create_email_blast_logs_table', 1),
(164, '2026_04_20_050133_drop_kapasitas_from_kelas_perkuliahans_table', 1),
(165, '2026_04_22_033524_create_email_outboxes_table', 1),
(166, '2026_04_24_150000_add_va_fields_to_invoices_table', 1),
(167, '2026_04_29_140500_add_auto_generated_from_krs_to_invoices_table', 1),
(168, '2026_04_30_115430_add_tahun_ajaran_to_krs_table', 1),
(169, '2026_04_30_115938_drop_semester_id_from_krs_table', 1),
(170, '2026_04_30_140000_add_mahasiswa_class_assignment_fields', 1),
(171, '2026_05_04_000001_refactor_kelas_perkuliahan_to_angkatan', 1),
(172, '2026_05_05_000001_add_credential_recipient_type_to_email_blast_logs', 1),
(173, '2026_05_05_000002_add_credential_type_to_email_outboxes', 1),
(174, '2026_05_06_remove_section_from_kelas', 1),
(175, '2026_05_08_000001_create_prestasi_tables', 1),
(176, '2026_05_21_000001_create_wisuda_tables', 1),
(177, '2026_06_02_115159_add_semester_mahasiswa_to_kuesioner_aktivasi_table', 2),
(178, '2026_06_02_152544_create_internship_types_table_and_alter_internships', 3),
(179, '2026_06_02_164047_create_permission_tables', 4),
(180, '2026_06_03_000001_enhance_audit_logs_table', 5),
(181, '2026_06_03_000002_create_impersonation_logs_table', 5),
(182, '2026_06_04_000001_fix_invoices_foreign_key_properly', 6);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 31),
(2, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 9),
(4, 'App\\Models\\User', 2),
(4, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 4),
(4, 'App\\Models\\User', 12),
(4, 'App\\Models\\User', 13),
(4, 'App\\Models\\User', 14),
(4, 'App\\Models\\User', 15),
(4, 'App\\Models\\User', 16),
(4, 'App\\Models\\User', 17),
(4, 'App\\Models\\User', 18),
(4, 'App\\Models\\User', 19),
(4, 'App\\Models\\User', 20),
(4, 'App\\Models\\User', 21),
(4, 'App\\Models\\User', 22),
(4, 'App\\Models\\User', 23),
(4, 'App\\Models\\User', 24),
(4, 'App\\Models\\User', 25),
(4, 'App\\Models\\User', 26),
(4, 'App\\Models\\User', 27),
(4, 'App\\Models\\User', 28),
(5, 'App\\Models\\User', 8),
(5, 'App\\Models\\User', 29),
(6, 'App\\Models\\User', 5),
(6, 'App\\Models\\User', 6),
(6, 'App\\Models\\User', 7),
(6, 'App\\Models\\User', 10),
(6, 'App\\Models\\User', 11),
(6, 'App\\Models\\User', 30);

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
(1, 1, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(2, 2, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(3, 3, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(4, 4, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(5, 5, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(6, 6, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(7, 7, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(8, 8, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(9, 9, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(10, 10, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(11, 11, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(12, 12, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(13, 13, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(14, 14, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(15, 15, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(16, 16, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(17, 17, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(18, 18, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(19, 19, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(20, 20, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(21, 21, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(22, 22, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(23, 23, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(24, 24, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(25, 25, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(26, 26, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(27, 27, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(28, 28, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(29, 29, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(30, 30, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(31, 31, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(32, 32, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(33, 33, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(34, 34, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(35, 35, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(36, 36, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(37, 37, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(38, 38, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(39, 39, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(40, 40, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(41, 41, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(42, 42, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(43, 43, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(44, 44, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(45, 45, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(46, 46, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(47, 47, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(48, 48, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(49, 49, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(50, 50, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(51, 51, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(52, 52, NULL, 90.00, 90.00, 88.00, 90.00, 85.00, 90.00, 90.00, 'A', 4.00, 1, '2026-05-21 09:13:24', NULL, '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(53, 53, 5, 93.27, 92.73, 72.33, 87.69, 94.81, 87.97, 90.00, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-06-03 04:58:22'),
(54, 54, 1, 97.18, 84.11, 95.95, 96.54, 77.71, 92.24, 90.62, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(55, 55, 8, 86.67, 92.68, 78.50, 87.88, 74.47, 87.00, 84.53, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(56, 56, 4, 94.40, 96.42, 78.61, 82.00, 83.90, 91.53, 87.81, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(57, 57, 2, 82.32, 86.19, 79.52, 75.46, 88.64, 92.78, 84.15, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(58, 58, 3, 90.11, 91.60, 75.04, 77.85, 94.77, 92.15, 86.92, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(59, 59, 6, 77.51, 81.71, 77.70, 84.72, 71.13, 87.83, 80.10, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(60, 60, 7, 87.22, 95.21, 80.72, 81.40, 70.63, 71.91, 81.18, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(61, 61, 1, 97.37, 95.17, 91.65, 77.33, 87.66, 93.02, 90.37, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(62, 62, 1, 75.20, 94.01, 94.39, 86.35, 91.77, 71.44, 85.53, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(63, 63, 1, 86.07, 78.57, 79.89, 88.34, 95.57, 71.16, 83.27, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(64, 64, 1, 77.33, 88.18, 77.38, 84.82, 89.58, 87.87, 84.19, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(65, 65, 1, 85.25, 76.04, 82.91, 85.37, 95.71, 85.74, 85.17, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(66, 66, 1, 95.29, 88.75, 74.73, 87.83, 91.19, 81.14, 86.49, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(67, 67, 1, 77.32, 94.71, 79.60, 91.89, 86.91, 77.65, 84.68, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(68, 74, 1, 75.09, 81.36, 74.17, 92.28, 88.80, 81.28, 82.16, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(69, 75, 1, 76.49, 93.77, 89.25, 97.78, 83.89, 92.47, 88.94, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(70, 76, 1, 76.63, 75.76, 90.70, 79.55, 90.53, 83.16, 82.72, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(71, 77, 1, 90.14, 90.62, 88.34, 85.03, 70.09, 80.82, 84.17, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(72, 78, 1, 77.43, 90.55, 73.10, 96.63, 89.88, 73.04, 83.44, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(73, 79, 1, 81.68, 83.83, 80.50, 81.52, 79.18, 74.07, 80.13, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(74, 80, 1, 81.79, 88.80, 74.85, 76.30, 93.63, 81.12, 82.75, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(75, 81, 1, 93.48, 88.44, 85.57, 93.61, 81.91, 94.02, 89.51, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(76, 82, 1, 77.06, 97.44, 74.29, 82.71, 76.33, 89.74, 82.93, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(77, 83, 1, 91.79, 81.17, 87.78, 80.82, 80.14, 79.21, 83.49, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(78, 84, 1, 78.55, 78.28, 80.16, 79.99, 81.14, 87.27, 80.90, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(79, 85, 1, 78.34, 96.06, 95.80, 76.07, 90.22, 88.37, 87.48, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(80, 86, 1, 96.54, 97.33, 90.93, 93.80, 79.44, 78.63, 89.45, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(81, 87, 1, 77.96, 82.10, 92.84, 92.97, 70.22, 92.21, 84.72, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(82, 88, 1, 90.53, 80.84, 97.30, 81.29, 81.20, 93.56, 87.45, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(83, 89, 1, 76.49, 93.72, 93.52, 90.70, 76.38, 77.42, 84.71, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(84, 90, 1, 92.28, 92.24, 83.93, 78.70, 95.39, 88.69, 88.54, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(85, 91, 1, 79.40, 92.89, 85.39, 94.30, 85.64, 71.56, 84.86, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(86, 92, 1, 91.60, 76.44, 96.72, 75.76, 86.96, 88.33, 85.97, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(87, 93, 1, 95.75, 90.68, 84.69, 91.26, 78.89, 92.79, 89.01, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(88, 94, 1, 82.79, 79.49, 94.99, 78.40, 73.88, 75.37, 80.82, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(89, 95, 1, 75.61, 82.14, 97.40, 75.59, 89.54, 85.29, 84.26, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(90, 96, 1, 82.05, 79.62, 85.48, 93.21, 90.84, 78.34, 84.92, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(91, 97, 1, 83.76, 84.77, 94.09, 93.60, 87.42, 81.66, 87.55, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(92, 98, 1, 86.02, 88.39, 89.46, 82.94, 92.35, 79.84, 86.50, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(93, 99, 1, 82.07, 76.05, 81.35, 97.47, 81.98, 71.81, 81.79, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(94, 100, 1, 83.00, 91.89, 70.81, 94.47, 78.85, 91.07, 85.02, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(95, 101, 1, 89.90, 80.24, 88.72, 90.64, 85.05, 86.32, 86.81, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(96, 102, 1, 80.01, 89.54, 83.57, 93.86, 81.73, 72.10, 83.47, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(97, 103, 1, 76.08, 80.94, 70.24, 89.84, 74.89, 94.24, 81.04, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(98, 104, 1, 78.08, 89.86, 73.51, 93.26, 71.43, 80.86, 81.17, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(99, 105, 1, 94.46, 96.46, 96.23, 96.63, 82.69, 80.40, 91.15, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(100, 106, 1, 92.23, 93.91, 85.87, 82.54, 81.61, 75.30, 85.24, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(101, 107, 1, 93.08, 95.36, 70.37, 88.65, 79.66, 86.30, 85.57, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(102, 108, 1, 80.36, 84.12, 72.37, 80.75, 79.52, 93.23, 81.73, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(103, 109, 1, 79.92, 88.84, 83.09, 96.44, 93.03, 78.75, 86.68, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(104, 110, 1, 83.00, 77.31, 80.99, 80.25, 74.52, 74.11, 78.36, 'A-', 3.67, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(105, 111, 1, 86.22, 81.46, 75.93, 93.89, 73.54, 88.26, 83.22, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(106, 112, 1, 94.38, 83.32, 83.66, 83.23, 88.80, 80.42, 85.64, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(107, 113, 1, 83.05, 76.94, 70.72, 95.90, 90.38, 86.34, 83.89, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(108, 114, 1, 79.70, 84.05, 88.69, 85.59, 74.03, 82.32, 82.40, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(109, 115, 1, 89.78, 89.06, 86.15, 89.75, 87.20, 74.50, 86.07, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(110, 116, 1, 79.07, 91.08, 80.45, 94.90, 71.62, 80.56, 82.95, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(111, 117, 1, 91.00, 86.35, 86.47, 85.48, 87.58, 95.91, 88.80, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(112, 118, 1, 85.99, 87.50, 97.04, 84.85, 87.57, 78.21, 86.86, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(113, 119, 1, 78.50, 75.72, 81.70, 95.64, 72.93, 89.36, 82.31, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(114, 120, 1, 89.15, 78.55, 90.51, 92.78, 85.17, 79.49, 85.94, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(115, 121, 1, 91.83, 81.53, 76.44, 78.57, 75.40, 94.18, 82.99, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(116, 122, 1, 90.25, 77.29, 83.58, 92.56, 82.64, 92.73, 86.51, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(117, 123, 1, 90.29, 93.36, 88.76, 89.96, 94.57, 84.11, 90.18, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(118, 124, 1, 78.94, 83.11, 75.83, 75.72, 74.44, 74.34, 77.06, 'A-', 3.67, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(119, 125, 1, 82.31, 87.98, 76.39, 79.80, 83.65, 82.40, 82.09, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(120, 126, 1, 83.26, 90.54, 95.19, 89.97, 86.67, 80.98, 87.77, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(121, 127, 1, 80.26, 85.62, 89.45, 82.82, 83.69, 76.34, 83.03, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06'),
(122, 128, 1, 96.07, 87.47, 83.20, 82.84, 72.68, 83.84, 84.35, 'A', 4.00, 1, '2026-05-22 04:06:06', 1, '2026-05-22 04:06:06', '2026-05-22 04:06:06');

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hubungan` varchar(255) DEFAULT NULL,
  `pekerjaan` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
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
  `handphone_ibu` varchar(20) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `nama_wali` varchar(255) DEFAULT NULL,
  `hubungan_wali` varchar(255) DEFAULT NULL,
  `pendidikan_wali` varchar(255) DEFAULT NULL,
  `pekerjaan_wali` varchar(255) DEFAULT NULL,
  `agama_wali` varchar(255) DEFAULT NULL,
  `alamat_wali` text DEFAULT NULL,
  `kota_wali` varchar(255) DEFAULT NULL,
  `kecamatan_wali` varchar(255) DEFAULT NULL,
  `provinsi_wali` varchar(255) DEFAULT NULL,
  `handphone_wali` varchar(20) DEFAULT NULL,
  `keluarga` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`keluarga`)),
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `desa_wali` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`id`, `hubungan`, `pekerjaan`, `user_id`, `mahasiswa_id`, `tipe_wali`, `nama_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `agama_ayah`, `alamat_ayah`, `kota_ayah`, `kecamatan_ayah`, `propinsi_ayah`, `desa_ayah`, `handphone_ayah`, `nama_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `agama_ibu`, `alamat_ibu`, `kota_ibu`, `kecamatan_ibu`, `propinsi_ibu`, `desa_ibu`, `handphone_ibu`, `phone`, `nama_wali`, `hubungan_wali`, `pendidikan_wali`, `pekerjaan_wali`, `agama_wali`, `alamat_wali`, `kota_wali`, `kecamatan_wali`, `provinsi_wali`, `handphone_wali`, `keluarga`, `address`, `created_at`, `updated_at`, `desa_wali`) VALUES
(1, 'wali', 'Tentara Nasional Indonesia', 32, 1, 'wali', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '822222222222', 'budi', 'Lainnya', 'Tamat SD', 'Tentara Nasional Indonesia', 'Protestan', 'jakarta', 'KAB. SERANG', 'BANDUNG', 'BANTEN', '822222222222', NULL, 'jakarta', '2026-05-21 09:13:23', '2026-07-02 03:15:45', 'BANDUNG'),
(2, 'wali', 'Mengurus Rumah Tangga', 29, 4, 'wali', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '822222222222', 'Akbar', 'Paman', 'Tidak Sekolah', 'Mengurus Rumah Tangga', 'Protestan', 'Jakarta', 'KAB. SERANG', 'BAROS', 'BANTEN', '822222222222', NULL, 'Jakarta', '2026-05-21 10:06:25', '2026-05-21 10:25:44', 'PADASUKA'),
(3, NULL, NULL, 30, 6, 'orang_tua', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Akbar', 'Paman', 'Tamat SMTA', 'Kepolisian RI', 'Katolik', 'Jakarta', 'KOTA ADM. JAKARTA PUSAT', 'CEMPAKA PUTIH', 'DKI JAKARTA', '822222222222', NULL, NULL, '2026-05-22 02:44:10', '2026-05-22 02:44:10', 'CEMPAKA PUTIH BARAT');

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
(1, 11, NULL, 1, 4500000, '2024-09-06', '2024-09-05', 1, '2026-05-29 04:39:46', '2026-05-29 04:39:46'),
(2, 12, NULL, 2, 5000000, '2025-02-06', '2025-02-05', 1, '2026-05-29 04:39:46', '2026-05-29 04:39:46'),
(3, 17, NULL, 3, 10000000, '2026-06-04', '2026-06-04', 9, '2026-06-04 05:05:47', '2026-06-04 05:05:47'),
(4, 16, NULL, 4, 10000000, '2026-06-04', '2026-06-04', 9, '2026-06-04 05:05:52', '2026-06-04 05:05:52'),
(5, 19, NULL, 5, 10000000, '2026-06-04', '2026-06-04', 9, '2026-06-04 05:07:49', '2026-06-04 05:07:49'),
(6, 18, NULL, 6, 10000000, '2026-06-04', '2026-06-04', 9, '2026-06-04 05:08:38', '2026-06-04 05:08:38');

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
(1, 11, NULL, 5, '2024-09-05', 4500000, 'Transfer Bank', 'proofs/dummy_proof.jpg', 'APPROVED', NULL, 1, '2024-09-06 08:00:00', NULL, 'Lunas untuk semester 1', '2026-05-29 04:39:46', '2026-05-29 04:39:46'),
(2, 12, NULL, 5, '2025-02-05', 5000000, 'Transfer Bank', 'proofs/dummy_proof.jpg', 'APPROVED', NULL, 1, '2025-02-06 08:00:00', NULL, 'Lunas untuk semester 2', '2026-05-29 04:39:46', '2026-05-29 04:39:46'),
(3, 17, NULL, 10, '2026-06-04', 10000000, 'Transfer Bank', 'documents/payment-proofs/833f321a-2d6b-4aff-b586-abf83f8c8902.jpeg', 'APPROVED', NULL, 9, '2026-06-04 12:05:47', NULL, NULL, '2026-06-04 05:04:49', '2026-06-04 05:05:47'),
(4, 16, NULL, 10, '2026-06-04', 10000000, 'Tunai', 'documents/payment-proofs/a2b95f21-1fff-4b33-aaa1-29f963b81d69.jpeg', 'APPROVED', NULL, 9, '2026-06-04 12:05:52', NULL, NULL, '2026-06-04 05:05:13', '2026-06-04 05:05:52'),
(5, 19, NULL, 10, '2026-06-04', 10000000, 'VA', 'documents/payment-proofs/73e4676b-f02c-4c37-9c0d-b3173c6c2d67.pdf', 'APPROVED', NULL, 9, '2026-06-04 12:07:49', NULL, NULL, '2026-06-04 05:07:17', '2026-06-04 05:07:49'),
(6, 18, NULL, 10, '2026-06-04', 10000000, 'E-Wallet', 'documents/payment-proofs/4b0bbf28-8835-4eea-b76a-facabe63e8d1.pdf', 'APPROVED', NULL, 9, '2026-06-04 12:08:38', NULL, NULL, '2026-06-04 05:08:16', '2026-06-04 05:08:38'),
(7, 22, NULL, 30, '2026-06-10', 10000000, 'Transfer Bank', 'documents/payment-proofs/0f85b8d9-d648-411e-8940-47a66b0629c8.jpg', 'UPLOADED', NULL, NULL, NULL, NULL, NULL, '2026-06-10 05:18:53', '2026-06-10 05:18:53');

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
  `payload_template` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload_template`)),
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `file_path` varchar(255) DEFAULT NULL,
  `generated_doc_path` varchar(255) DEFAULT NULL,
  `signed_doc_path` varchar(255) DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `rejected_reason` text DEFAULT NULL,
  `revision_no` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `nomor_surat` varchar(255) DEFAULT NULL,
  `file_surat` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengajuans`
--

INSERT INTO `pengajuans` (`id`, `mahasiswa_id`, `jenis`, `keterangan`, `payload_template`, `status`, `file_path`, `generated_doc_path`, `signed_doc_path`, `admin_note`, `rejected_reason`, `revision_no`, `approved_by`, `approved_at`, `submitted_at`, `rejected_at`, `nomor_surat`, `file_surat`, `created_at`, `updated_at`) VALUES
(1, 1, 'bebas_keuangan', 'Surat Keterangan Bebas Keuangan', '[]', 'approved', NULL, 'pengajuan/generated/ANDI_PRATAMA_2024010001/generated_1_1780029852.docx', 'pengajuan/signed/ANDI_PRATAMA_2024010001/f1ad098b-b63c-4562-923c-956a1362630c.docx', NULL, NULL, 0, 9, '2026-05-29 04:58:03', '2026-05-29 04:46:34', NULL, '001/STIH-ADH/SK/05/2026', 'pengajuan/approved/ANDI_PRATAMA_2024010001/19b70193-286a-4c39-ba1f-0d922fd99764.pdf', '2026-05-29 04:44:11', '2026-05-29 04:58:05');

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_revisions`
--

CREATE TABLE `pengajuan_revisions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pengajuan_id` bigint(20) UNSIGNED NOT NULL,
  `revision_no` smallint(5) UNSIGNED NOT NULL,
  `signed_doc_path` varchar(255) NOT NULL,
  `note_from_admin` text DEFAULT NULL,
  `note_from_mahasiswa` text DEFAULT NULL,
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

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'manage-users', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(2, 'manage-students', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(3, 'manage-lecturers', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(4, 'manage-courses', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(5, 'manage-krs', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(6, 'manage-khs', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(7, 'manage-grades', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(8, 'manage-internships', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(9, 'manage-thesis', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(10, 'manage-graduation', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(11, 'manage-finance', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(12, 'manage-system', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(13, 'manage-settings', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(14, 'manage-permissions', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(15, 'view-audit-log', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(16, 'impersonate-user', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(17, 'override-academic-data', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(18, 'override-financial-data', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23');

-- --------------------------------------------------------

--
-- Table structure for table `pertemuans`
--

CREATE TABLE `pertemuans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kelas_mata_kuliah_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nomor_pertemuan` int(10) UNSIGNED NOT NULL,
  `tipe_pertemuan` enum('kuliah','uts','uas') NOT NULL DEFAULT 'kuliah' COMMENT 'Meeting type: kuliah (regular), uts (midterm), uas (final)',
  `tanggal` date DEFAULT NULL,
  `topik` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `metode_pengajaran` enum('offline','online','asynchronous') NOT NULL DEFAULT 'offline',
  `online_meeting_link` varchar(255) DEFAULT NULL,
  `qr_token` varchar(100) DEFAULT NULL,
  `qr_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `qr_expires_at` datetime DEFAULT NULL,
  `qr_generated_at` datetime DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `presensis`
--

CREATE TABLE `presensis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kelas_mata_kuliah_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pertemuan` int(10) UNSIGNED DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `kontak` varchar(255) DEFAULT NULL,
  `waktu` timestamp NULL DEFAULT NULL,
  `krs_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('hadir','izin','sakit','alpa') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `student_lat` decimal(10,7) DEFAULT NULL,
  `student_lng` decimal(10,7) DEFAULT NULL,
  `distance_meters` int(11) DEFAULT NULL,
  `presence_mode` enum('offline','online') DEFAULT NULL,
  `reason_category` varchar(255) DEFAULT NULL,
  `reason_detail` text DEFAULT NULL,
  `campus_lat` decimal(10,7) NOT NULL DEFAULT -6.3112520,
  `campus_lng` decimal(10,7) NOT NULL DEFAULT 106.8111740,
  `radius_meters` int(11) NOT NULL DEFAULT 100,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prestasis`
--

CREATE TABLE `prestasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipe` enum('pengajuan','pelaporan') NOT NULL DEFAULT 'pengajuan',
  `pengaju_type` varchar(255) NOT NULL,
  `pengaju_id` bigint(20) UNSIGNED NOT NULL,
  `nama_kegiatan` varchar(255) NOT NULL,
  `jenis_kegiatan` varchar(255) NOT NULL DEFAULT 'akademik',
  `tingkat_kegiatan` enum('internal','regional','nasional','internasional') NOT NULL DEFAULT 'nasional',
  `tempat_kegiatan` varchar(255) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `penyelenggara` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `dosen_pendamping_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jenis_prestasi` varchar(255) DEFAULT NULL,
  `nomor_sertifikat` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_reason` text DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `external_ref` varchar(255) DEFAULT NULL,
  `hash_kegiatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prestasis`
--

INSERT INTO `prestasis` (`id`, `tipe`, `pengaju_type`, `pengaju_id`, `nama_kegiatan`, `jenis_kegiatan`, `tingkat_kegiatan`, `tempat_kegiatan`, `tanggal_mulai`, `tanggal_selesai`, `penyelenggara`, `deskripsi`, `dosen_pendamping_id`, `jenis_prestasi`, `nomor_sertifikat`, `keterangan`, `status`, `approved_by`, `approved_at`, `rejected_reason`, `rejected_at`, `admin_note`, `tags`, `external_ref`, `hash_kegiatan`, `created_at`, `updated_at`) VALUES
(1, 'pengajuan', 'App\\Models\\Dosen', 1, 'Seminar Legal Internal 2026', 'akademik', 'internal', 'Jakarta', '2026-06-23', '2026-06-23', 'Universitas adhyaksa', 'Seminar', NULL, 'Pembicara', NULL, NULL, 'diajukan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'bf3e9b17abecb8fb7a7d72a1df04e5c6', '2026-06-22 03:20:46', '2026-06-22 03:20:53'),
(2, 'pengajuan', 'App\\Models\\Mahasiswa', 6, 'Lomba Debat Regional', 'akademik', 'regional', 'Jakarta', '2026-06-29', '2026-06-29', 'Universitas Jakarta', 'Lomba', 1, 'Peserta', NULL, NULL, 'diproses_admin', 1, '2026-06-22 03:25:03', NULL, NULL, NULL, NULL, NULL, '108046140acc1d4d292b7a70c22cf164', '2026-06-22 03:24:39', '2026-06-22 03:25:03');

-- --------------------------------------------------------

--
-- Table structure for table `prestasi_dokumens`
--

CREATE TABLE `prestasi_dokumens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prestasi_id` bigint(20) UNSIGNED NOT NULL,
  `jenis` enum('sertifikat','dokumentasi','surat_tugas_lama','pendukung') NOT NULL DEFAULT 'sertifikat',
  `file_path` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `size` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `uploaded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prestasi_logs`
--

CREATE TABLE `prestasi_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prestasi_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `from_status` varchar(255) DEFAULT NULL,
  `to_status` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prestasi_logs`
--

INSERT INTO `prestasi_logs` (`id`, `prestasi_id`, `action`, `from_status`, `to_status`, `user_id`, `metadata`, `created_at`) VALUES
(1, 1, 'created', NULL, 'draft', 2, NULL, '2026-06-22 03:20:46'),
(2, 1, 'submitted', 'draft', 'diajukan', 2, NULL, '2026-06-22 03:20:53'),
(3, 2, 'created', NULL, 'draft', 30, NULL, '2026-06-22 03:24:39'),
(4, 2, 'submitted', 'draft', 'diajukan', 30, NULL, '2026-06-22 03:24:43'),
(5, 2, 'approved', 'diproses_admin', 'diproses_admin', 1, '{\"admin_note\":null}', '2026-06-22 03:25:03');

-- --------------------------------------------------------

--
-- Table structure for table `prestasi_surats`
--

CREATE TABLE `prestasi_surats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prestasi_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_surat` varchar(255) NOT NULL,
  `nomor_surat` varchar(255) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `penandatangan_nama` varchar(255) NOT NULL,
  `penandatangan_jabatan` varchar(255) NOT NULL,
  `penandatangan_nip` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `is_backdate` tinyint(1) NOT NULL DEFAULT 0,
  `generated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prestasi_surat_settings`
--

CREATE TABLE `prestasi_surat_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jenis_surat` varchar(255) NOT NULL,
  `format_nomor` varchar(255) NOT NULL,
  `last_counter` int(11) NOT NULL DEFAULT 0,
  `reset_year` year(4) NOT NULL DEFAULT 2026,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prestasi_surat_settings`
--

INSERT INTO `prestasi_surat_settings` (`id`, `jenis_surat`, `format_nomor`, `last_counter`, `reset_year`, `created_at`, `updated_at`) VALUES
(1, 'tugas', '{counter}/STIH/ST/{month}/{year}', 0, '2026', '2026-05-21 09:13:22', '2026-05-21 09:13:22'),
(2, 'rekomendasi', '{counter}/STIH/SR/{month}/{year}', 0, '2026', '2026-05-21 09:13:22', '2026-05-21 09:13:22'),
(3, 'keterangan', '{counter}/STIH/SKP/{month}/{year}', 0, '2026', '2026-05-21 09:13:22', '2026-05-21 09:13:22'),
(4, 'penghargaan', '{counter}/STIH/PP/{month}/{year}', 0, '2026', '2026-05-21 09:13:22', '2026-05-21 09:13:22');

-- --------------------------------------------------------

--
-- Table structure for table `prodis`
--

CREATE TABLE `prodis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_prodi` varchar(10) NOT NULL,
  `fakultas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_prodi` varchar(255) NOT NULL,
  `jenjang` enum('D3','S1','S2','S3') NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prodis`
--

INSERT INTO `prodis` (`id`, `kode_prodi`, `fakultas_id`, `nama_prodi`, `jenjang`, `status`, `created_at`, `updated_at`) VALUES
(1, 'HK', 1, 'Ilmu Hukum', 'S1', 'aktif', '2026-05-21 09:13:16', '2026-05-21 09:50:53'),
(2, 'INF', 2, 'Informatika', 'S1', 'aktif', '2026-05-21 09:13:23', '2026-05-21 09:51:31');

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
(1, '1001001', 'Islam', '2026-05-21 09:13:23', '2026-05-21 09:13:23'),
(2, '1001002', 'Protestan', '2026-05-21 09:13:23', '2026-05-21 09:13:23'),
(3, '1001003', 'Hindu', '2026-05-21 09:13:23', '2026-05-21 09:13:23'),
(4, '1001004', 'Buddha', '2026-05-21 09:13:23', '2026-05-21 09:13:23'),
(5, '1001005', 'Katolik', '2026-05-21 09:13:23', '2026-05-21 09:13:23'),
(6, '1001006', 'Khonghucu', '2026-05-21 09:13:23', '2026-05-21 09:13:23'),
(7, '1001007', 'Penganut Kepercayaan Lainnya', '2026-05-21 09:13:23', '2026-05-21 09:13:23');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(2, 'akademik', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(3, 'keuangan', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(4, 'dosen', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(5, 'parents', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(6, 'mahasiswa', 'web', '2026-06-02 09:41:23', '2026-06-02 09:41:23');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(4, 1),
(4, 2),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(7, 1),
(7, 2),
(8, 1),
(8, 2),
(9, 1),
(9, 2),
(10, 1),
(10, 2),
(11, 1),
(11, 3),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1);

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
  `kategori_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ruangans`
--

INSERT INTO `ruangans` (`id`, `kode_ruangan`, `nama_ruangan`, `gedung`, `lantai`, `kapasitas`, `status`, `kategori_id`, `created_at`, `updated_at`) VALUES
(15, 'RI 1', 'Ruang Kelas Internasional 1', 'STIH Adhyaksa', 1, 50, 'aktif', 3, '2026-05-21 09:53:47', '2026-05-25 06:39:05'),
(16, 'RI 2', 'Ruang Kelas Internasional 2', 'STIH Adhyaksa', 1, 30, 'aktif', NULL, '2026-05-21 09:53:47', '2026-05-21 09:53:47'),
(17, 'RI 3', 'Ruang Kelas Internasional 3', 'STIH Adhyaksa', 1, 50, 'aktif', NULL, '2026-05-21 09:53:47', '2026-05-21 09:53:47'),
(18, 'R 1', 'Ruang Kelas R 1', 'STIH Adhyaksa', 2, 50, 'aktif', 1, '2026-05-21 09:53:47', '2026-05-25 06:38:22'),
(19, 'R 2', 'Ruang Kelas R 2', 'STIH Adhyaksa', 2, 50, 'aktif', 1, '2026-05-21 09:53:47', '2026-05-25 06:38:30'),
(20, 'R 3', 'Ruang Kelas R 3', 'STIH Adhyaksa', 2, 50, 'aktif', 1, '2026-05-21 09:53:47', '2026-05-25 06:38:37'),
(21, 'R 4', 'Ruang Kelas R 4', 'STIH Adhyaksa', 2, 50, 'aktif', 1, '2026-05-21 09:53:47', '2026-05-25 06:38:48');

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
  `is_locked` tinyint(1) NOT NULL DEFAULT 0,
  `locked_at` datetime DEFAULT NULL,
  `locked_by` bigint(20) UNSIGNED DEFAULT NULL,
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

INSERT INTO `semesters` (`id`, `nama_semester`, `nama_semester_old`, `tahun_ajaran`, `status`, `is_active`, `is_locked`, `locked_at`, `locked_by`, `krs_dapat_diisi`, `max_sks_rendah`, `max_sks_tinggi`, `krs_mulai`, `krs_selesai`, `tanggal_mulai`, `tanggal_selesai`, `created_at`, `updated_at`) VALUES
(1, 'Ganjil', NULL, '2025/2026', 'aktif', 1, 0, NULL, NULL, 1, 20, 24, '2025-09-15', '2025-09-17', '2026-05-21', '2026-11-21', '2026-05-21 09:16:40', '2026-06-02 02:09:16'),
(2, 'Genap', NULL, '2025/2026', 'non-aktif', 0, 0, NULL, NULL, 0, 20, 24, '2026-03-09', '2026-03-11', '2026-11-22', '2027-05-22', '2026-05-21 09:16:50', '2026-05-25 02:28:55'),
(3, 'Ganjil', NULL, '2026/2027', 'non-aktif', 0, 0, NULL, NULL, 0, 20, 24, NULL, NULL, '2027-05-23', '2027-11-23', '2026-05-22 03:34:45', '2026-05-22 03:34:57'),
(4, 'Genap', NULL, '2026/2027', 'non-aktif', 0, 0, NULL, NULL, 0, 20, 24, NULL, NULL, '2027-11-24', '2028-05-24', '2026-05-22 03:35:05', '2026-05-22 03:35:05'),
(5, 'Ganjil', NULL, '2027/2028', 'non-aktif', 0, 0, NULL, NULL, 0, 20, 24, NULL, NULL, '2028-05-25', '2028-11-25', '2026-05-22 03:35:17', '2026-05-22 03:35:17'),
(6, 'Genap', NULL, '2027/2028', 'non-aktif', 0, 0, NULL, NULL, 0, 20, 24, NULL, NULL, '2028-11-26', '2029-05-26', '2026-05-22 03:35:28', '2026-05-22 03:35:28'),
(7, 'Ganjil', NULL, '2028/2029', 'non-aktif', 0, 0, NULL, NULL, 0, 20, 24, NULL, NULL, '2029-05-27', '2029-11-27', '2026-05-22 03:35:42', '2026-05-22 03:35:42'),
(8, 'Genap', NULL, '2028/2029', 'non-aktif', 0, 0, NULL, NULL, 0, 20, 24, NULL, NULL, '2029-11-28', '2030-05-28', '2026-05-22 03:35:54', '2026-05-22 03:35:54');

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
-- Table structure for table `skripsi_guidances`
--

CREATE TABLE `skripsi_guidances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `skripsi_submission_id` bigint(20) UNSIGNED NOT NULL,
  `dosen_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_bimbingan` date NOT NULL,
  `catatan` text NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `catatan_dosen` text DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skripsi_guidances`
--

INSERT INTO `skripsi_guidances` (`id`, `skripsi_submission_id`, `dosen_id`, `tanggal_bimbingan`, `catatan`, `file_path`, `status`, `catatan_dosen`, `reviewed_at`, `created_at`, `updated_at`) VALUES
(1, 4, 1, '2026-05-27', 'test ini bimbingan 1', 'skripsi/4/bimbingan/VvGeiVuVPKNvshweaqGUWRrFmYHoFiocwQhpMXdu.pdf', 'approved', NULL, '2026-06-04 04:07:48', '2026-06-04 04:06:44', '2026-06-04 04:07:48');

-- --------------------------------------------------------

--
-- Table structure for table `skripsi_revisions`
--

CREATE TABLE `skripsi_revisions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `skripsi_submission_id` bigint(20) UNSIGNED NOT NULL,
  `revision_file_path` varchar(255) NOT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `dosen_notes` text DEFAULT NULL,
  `approved_by_dosen_id` bigint(20) UNSIGNED DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skripsi_revisions`
--

INSERT INTO `skripsi_revisions` (`id`, `skripsi_submission_id`, `revision_file_path`, `original_name`, `notes`, `dosen_notes`, `approved_by_dosen_id`, `uploaded_at`, `approved_at`, `created_at`, `updated_at`) VALUES
(2, 4, 'skripsi/4/revisi/xNfxQs0puPsWdUpWOpEeO4ToGvjKMpij2awcssdW.pdf', 'Surat_Tugas_012_STIH_ST_5_2026.pdf', NULL, NULL, 1, '2026-06-04 04:55:11', '2026-06-04 04:55:34', '2026-06-04 04:55:11', '2026-06-04 04:55:34');

-- --------------------------------------------------------

--
-- Table structure for table `skripsi_sidang_files`
--

CREATE TABLE `skripsi_sidang_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sidang_registration_id` bigint(20) UNSIGNED NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skripsi_sidang_files`
--

INSERT INTO `skripsi_sidang_files` (`id`, `sidang_registration_id`, `file_type`, `file_path`, `original_name`, `file_size`, `created_at`, `updated_at`) VALUES
(7, 2, 'form_sidang', 'skripsi/4/sidang/form_sidang/StmbaKu110OTu5pANoClwjN4mvB83o1dyVSG7Gqp.pdf', 'Surat_Tugas_012_STIH_ST_5_2026.pdf', 27282, '2026-06-04 04:27:20', '2026-06-04 04:27:20'),
(8, 2, 'transkrip', 'skripsi/4/sidang/transkrip/qy8gVORoDoO3IX3SWER4vJHS32krc6oEX41Yf7vN.pdf', 'Surat_Tugas_012_STIH_ST_5_2026.pdf', 27282, '2026-06-04 04:27:28', '2026-06-04 04:27:28'),
(9, 2, 'file_skripsi', 'skripsi/4/sidang/file_skripsi/HjjOtHvrhc1KecNnfAsoEEQSqv3fTujW9w3bLMNh.pdf', 'Surat_Tugas_012_STIH_ST_5_2026.pdf', 27282, '2026-06-04 04:27:35', '2026-06-04 04:27:35'),
(10, 2, 'lainnya', 'skripsi/4/sidang/lainnya/uzi2MIfwaUGsBhJ3LenIVtpLAsEPG7yqTF0lNCNG.pdf', 'Surat_Tugas_012_STIH_ST_5_2026.pdf', 27282, '2026-06-04 04:27:42', '2026-06-04 04:27:42'),
(11, 2, 'bebas_pustaka', 'skripsi/4/sidang/bebas_pustaka/Cebz48PDpbJ8vw8KYffp7lZ11ER0RnyXGrAZ6vIf.pdf', 'Surat_Tugas_012_STIH_ST_5_2026.pdf', 27282, '2026-06-04 04:27:51', '2026-06-04 04:27:51'),
(12, 2, 'file_ppt', 'skripsi/4/sidang/file_ppt/nHwTCIwNDqpGUCjPXgqhS3CCX79KlsfCg4dcIdrE.pdf', 'Surat_Tugas_012_STIH_ST_5_2026.pdf', 27282, '2026-06-04 04:28:07', '2026-06-04 04:28:07');

-- --------------------------------------------------------

--
-- Table structure for table `skripsi_sidang_registrations`
--

CREATE TABLE `skripsi_sidang_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `skripsi_submission_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skripsi_sidang_registrations`
--

INSERT INTO `skripsi_sidang_registrations` (`id`, `skripsi_submission_id`, `status`, `notes`, `admin_note`, `verified_by`, `submitted_at`, `verified_at`, `rejected_at`, `created_at`, `updated_at`) VALUES
(2, 4, 'verified', NULL, NULL, 1, '2026-06-04 04:28:30', '2026-06-04 04:29:41', NULL, '2026-06-04 04:24:14', '2026-06-04 04:29:41');

-- --------------------------------------------------------

--
-- Table structure for table `skripsi_sidang_schedules`
--

CREATE TABLE `skripsi_sidang_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `skripsi_submission_id` bigint(20) UNSIGNED NOT NULL,
  `sidang_registration_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tanggal` date NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `ruangan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ruangan_manual` varchar(255) DEFAULT NULL,
  `pembimbing_id` bigint(20) UNSIGNED NOT NULL,
  `penguji_1_id` bigint(20) UNSIGNED NOT NULL,
  `penguji_2_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skripsi_sidang_schedules`
--

INSERT INTO `skripsi_sidang_schedules` (`id`, `skripsi_submission_id`, `sidang_registration_id`, `tanggal`, `waktu_mulai`, `waktu_selesai`, `ruangan_id`, `ruangan_manual`, `pembimbing_id`, `penguji_1_id`, `penguji_2_id`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 4, 2, '2026-06-30', '10:00:00', NULL, 18, NULL, 1, 10, NULL, NULL, 1, '2026-06-04 04:30:29', '2026-06-04 04:30:29');

-- --------------------------------------------------------

--
-- Table structure for table `skripsi_submissions`
--

CREATE TABLE `skripsi_submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `semester_id` bigint(20) UNSIGNED DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi_proposal` text DEFAULT NULL,
  `proposal_file_path` varchar(255) DEFAULT NULL,
  `requested_supervisor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_supervisor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'PROPOSAL_DRAFT',
  `total_bimbingan` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `logbook_file_path` varchar(255) DEFAULT NULL,
  `logbook_original_name` varchar(255) DEFAULT NULL,
  `logbook_uploaded_at` timestamp NULL DEFAULT NULL,
  `eligible_for_sidang_at` timestamp NULL DEFAULT NULL,
  `revision_approved_at` timestamp NULL DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skripsi_submissions`
--

INSERT INTO `skripsi_submissions` (`id`, `mahasiswa_id`, `semester_id`, `judul`, `deskripsi_proposal`, `proposal_file_path`, `requested_supervisor_id`, `approved_supervisor_id`, `status`, `total_bimbingan`, `logbook_file_path`, `logbook_original_name`, `logbook_uploaded_at`, `eligible_for_sidang_at`, `revision_approved_at`, `admin_note`, `reviewed_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 4, NULL, 'Sentimen DOSEN RESEK', NULL, 'skripsi/4/proposal/etz39giyeo8OfH6HR5M82PlFTkvNXSBD5mSpUzx0.pdf', 1, 1, 'THESIS_COMPLETED', 1, 'skripsi/4/logbook/PQ26obGKInOLo1TRnFbTde1ECRE3cNjJJtndgZXe.pdf', 'Surat_Tugas_012_STIH_ST_5_2026.pdf', '2026-06-04 04:07:02', '2026-06-04 04:07:02', '2026-06-04 04:55:34', NULL, 1, '2026-06-04 03:20:57', '2026-06-04 04:55:34', NULL),
(6, 6, NULL, 'Perlindungan Hukum bagi Korban Penipuan Online', NULL, NULL, 1, NULL, 'PROPOSAL_PENDING_SUPERVISOR', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-22 04:18:23', '2026-06-22 04:18:23', NULL);

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

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `npm`, `nama`, `prodi`, `angkatan`, `created_at`, `updated_at`) VALUES
(1, 5, '2024010001', 'Andi Pratama', 'Ilmu Hukum', '2026', '2026-05-29 04:39:46', '2026-05-29 04:39:46');

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
  `submission_type` enum('pdf','word','excel','text','any') NOT NULL DEFAULT 'any',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tugas_submissions`
--

CREATE TABLE `tugas_submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tugas_id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `text_submission` text DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `graded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `graded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uploadable_type` varchar(255) DEFAULT NULL,
  `uploadable_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) NOT NULL,
  `extension` varchar(20) NOT NULL,
  `folder` varchar(50) NOT NULL,
  `size` bigint(20) UNSIGNED NOT NULL,
  `disk` varchar(20) NOT NULL DEFAULT 's3',
  `label` varchar(255) DEFAULT NULL,
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
(1, 'Admin STIH', 'admin@stih.ac.id', NULL, '$2y$12$q6K1WOZ2vx1YfzAeD7O1H.z4stFmwd53gDwUfbJFvGt99BB0LP9bC', NULL, 'admin', '2026-05-21 09:13:22', '2026-06-02 09:51:18'),
(2, 'Dr. Ahmad Fauzi, S.H., M.H.', 'ahmad.fauzi@stih.ac.id', NULL, '$2y$12$JRh9gIOW4h9GKPBVXd.K0Oi4n2f.5RG5cJBZB4JZl3rAQ0hkkH.Fq', NULL, 'dosen', '2026-05-21 09:13:22', '2026-05-21 09:13:22'),
(3, 'Prof. Dr. Siti Nurjanah, S.H., M.H.', 'siti.nurjanah@stih.ac.id', NULL, '$2y$12$rZccE/s3cXPB1wUuWXaHK.qsPEODw//XzBJ2efYPttYokhj81unNy', NULL, 'dosen', '2026-05-21 09:13:22', '2026-05-21 09:13:22'),
(4, 'Dr. Budi Santoso, S.H., M.H.', 'budi.santoso@stih.ac.id', NULL, '$2y$12$B7/74U1QdY5QAOZXtiPIcOjRANZ2R/lCUJDWTnC/fHTuoyGIcmZKy', NULL, 'dosen', '2026-05-21 09:13:23', '2026-05-21 09:13:23'),
(5, 'Andi Pratama', 'andipratama@student.stih.ac.id', NULL, '$2y$12$oaybSqdoWTHRqAMY4v8SvevXoSPe9X9j9E1/b8tabDRvA85ZlPI9G', NULL, 'mahasiswa', '2026-05-21 09:13:23', '2026-05-22 07:47:25'),
(6, 'Dewi Lestari', 'dewi.lestari@student.stih.ac.id', NULL, '$2y$12$Ik5Osq/fpUwURC5MtoIN1.MpqHhSaneP3mnR1E6C0zCjYLsSb83zS', NULL, 'mahasiswa', '2026-05-21 09:13:23', '2026-05-21 09:13:23'),
(7, 'Rizki Firmansyah', 'rizki.firmansyah@student.stih.ac.id', NULL, '$2y$12$MJ70HOcHIyYaELGpeKGMxOxHZeptTFhN0L1VQ8clhS7urkhEj0ohO', NULL, 'mahasiswa', '2026-05-21 09:13:23', '2026-05-21 09:13:23'),
(8, 'Bapak Pratama', 'parent.pratama@stih.ac.id', NULL, '$2y$12$Cl1zxHZVfsOaA6tn8DlN.uhHXlJmSa1FEUxe9QPKGoFpByz2eZVx2', NULL, 'parent', '2026-05-21 09:13:23', '2026-05-21 09:13:23'),
(9, 'Staf Keuangan', 'keuangan@stih.ac.id', '2026-05-21 09:13:24', '$2y$12$Zy/j5B/Y2.XsQwHIOFuD..3gV0UcoD.zIGIUvEodLUjnm6KF8ITyC', NULL, 'keuangan', '2026-05-21 09:13:24', '2026-05-29 04:47:29'),
(10, 'Ahmad Mahasiswa', 'ahmadmahasiswa@student.stih.ac.id', '2026-05-21 09:13:24', '$2y$12$QK4AObUgSTRkbN/J3e68xuO.0sOuKrFfHmLwLvOOXaHYzjh6ZjILu', NULL, 'mahasiswa', '2026-05-21 09:13:24', '2026-05-21 10:03:10'),
(11, 'Siti Mahasiswi', 'student2@stih.ac.id', '2026-05-21 09:13:24', '$2y$12$TmgztbJcRfDA6zENe.i87eewl6dCsKP2.fVFPqSOMJljK4f18s6bO', NULL, 'student', '2026-05-21 09:13:24', '2026-05-21 09:13:24'),
(12, 'Dr. R Muhamad Ibnu Mazjah, S.H., M.H.', '417017906@stihadhyaksa.ac.id', NULL, '$2y$12$WKMUmpmLAvM65h2ibzyllONYBMecsSKLlnOB1jZ2RURcfOEKDyt5G', NULL, 'dosen', '2026-05-21 09:16:13', '2026-05-21 09:16:13'),
(13, 'Dr. Armansyah, S.H., M.H.', '301067501@stihadhyaksa.ac.id', NULL, '$2y$12$Z5Md0T/dfo3ZnHqv2XVLjOAS.Sic8yPnFeQGtF6zt7zeGbTVekzF6', NULL, 'dosen', '2026-05-21 09:16:13', '2026-05-21 09:16:13'),
(14, 'Dr. Mukhlis, S.H., M.H.', '3146747648130140@stihadhyaksa.ac.id', NULL, '$2y$12$1FnLZQqKuqA6dNPX2XiiHeaag3t1bjxfcmv3rKWBBkwg8UjYb4Ylm', NULL, 'dosen', '2026-05-21 09:16:13', '2026-05-21 09:16:13'),
(15, 'Dr. Joko Cahyono, SH., MH.', '714076601@stihadhyaksa.ac.id', NULL, '$2y$12$0ivQ4kWDRl6ZK9s6fFpZC.p7hUxBvwtAZF4le5aU9KiFHHyUzBIgC', NULL, 'dosen', '2026-05-21 09:16:13', '2026-05-21 09:16:13'),
(16, 'Sandi Yudha Prayoga, S.H., M.H.', '302129701@stihadhyaksa.ac.id', NULL, '$2y$12$3GPq3C503kYJ15JYVDNDP.Qv2jeBURd.B4Eim1NRadDlFc7UMgEmi', NULL, 'dosen', '2026-05-21 09:16:14', '2026-05-21 09:16:14'),
(17, 'Adilla Meytiara Intan, S.H., LL.M.', '302059501@stihadhyaksa.ac.id', NULL, '$2y$12$M/GXHGAvpDj0GGwY.L0D5OnMG3uOFDCzSGxsliscyK.VOBuTHeZEK', NULL, 'dosen', '2026-05-21 09:16:14', '2026-05-21 09:16:14'),
(18, 'Adery Ardhan Saputro, S.H., LL.M.', '313089202@stihadhyaksa.ac.id', NULL, '$2y$12$ApVA5RDVAPwgcJDXePc9g.H/6XV4OW6m5BW4lJ.sVZb0cVfX3nXrC', NULL, 'dosen', '2026-05-21 09:16:14', '2026-05-21 09:16:14'),
(19, 'Dio Ashar Wicaksana, S.H., M.A.', '307089005@stihadhyaksa.ac.id', NULL, '$2y$12$C04i/niaFs.0A2120IRZYOKK/GbqvD6Ko.oA/AdxQVCi/6JfNqh4G', NULL, 'dosen', '2026-05-21 09:16:14', '2026-05-21 09:16:14'),
(20, 'Prof. Dr. Bambang Sugeng Rukmono, S.H.,M.M., M.H.', '8918290024@stihadhyaksa.ac.id', NULL, '$2y$12$.1BIzpn0kEcHHaQzLm4BD.8L0RFi/EDlmXeBY.2Q7F5PwkiHr9qma', NULL, 'dosen', '2026-05-21 09:16:15', '2026-05-21 09:16:15'),
(21, 'Maydika Ramadani, S.H., M.H.', '3860765666130310@stihadhyaksa.ac.id', NULL, '$2y$12$FhpT93m4HtEwPpQFMIVdcuNIzjk3TSCL2idzbqBbJFcejYW0HXZzy', NULL, 'dosen', '2026-05-21 09:16:15', '2026-05-21 09:16:15'),
(22, 'Raul Gindo cahyono, S.H., M.H.', '1956751652130120@stihadhyaksa.ac.id', NULL, '$2y$12$dMe6/u3WHZCQAL6IdrgBoukiz36Lxnaqt8VpY7B0nv7KwbephyWge', NULL, 'dosen', '2026-05-21 09:16:15', '2026-05-21 09:16:15'),
(23, 'Muhammad Arbani, S.H., M.Kn.', '3345774675130210@stihadhyaksa.ac.id', NULL, '$2y$12$Ew0R2cU8tvXgQfIGaRP6/ODKPfpkIxrk4bdTcWD155WhK1LH5.K1u', NULL, 'dosen', '2026-05-21 09:16:15', '2026-05-21 09:16:15'),
(24, 'Muhammad Rizqi Alfarizi, S.H., LL.M.', '4434778679130070@stihadhyaksa.ac.id', NULL, '$2y$12$2lViImnfakDuqTLoUdXZfergOVcqF9n1Kef3gg7UqFnLz2UTZHp3.', NULL, 'dosen', '2026-05-21 09:16:15', '2026-05-21 09:16:15'),
(25, 'Amir Firmansyah, S.H. M.H', '7641763664130240@stihadhyaksa.ac.id', NULL, '$2y$12$JCIOTTrWnbJjGVdkRrIpX.GwxEKayR9h9.Jbn8Km.2vMJfdDC2/RS', NULL, 'dosen', '2026-05-21 09:16:16', '2026-05-21 09:16:16'),
(26, 'Akhmad Ikraam, S.H., M.H.', '2150767668137030@stihadhyaksa.ac.id', NULL, '$2y$12$ZZ5YGqavRkVGBP.wKSbs0OVqz4xgWiORRpWzQq7Jw3a7CdNQc.coy', NULL, 'dosen', '2026-05-21 09:16:16', '2026-05-21 09:16:16'),
(27, 'Zul Karnen, S.S., M.Si.', '3454762663130160@stihadhyaksa.ac.id', NULL, '$2y$12$vl1Fdhmcvs2/4WGTAVKLXOVGpo4Yk8pIt9zOmpzGhUv7adMJSVq5O', NULL, 'dosen', '2026-05-21 09:16:16', '2026-05-21 09:16:16'),
(28, 'Dr. Rudi Pradisetia Sudirdja., S.H., M.H.', '3204070406910000@stihadhyaksa.ac.id', NULL, '$2y$12$fFy8UE2ECoKLF9JLqPwbH.43zyUc6IniwlA3Xe4ueYp6KVV2gxzbi', NULL, 'dosen', '2026-05-21 09:16:16', '2026-05-21 09:16:16'),
(29, 'Akbar', '2024001@parent.stih.ac.id', NULL, '$2y$12$nxE9KQ.ShxHni1pXz8DyXOgszhQgA5LUtoIV/AgITGDCssuWToLUW', NULL, 'parent', '2026-05-21 10:25:44', '2026-05-21 10:25:44'),
(30, 'Jojo', 'jojo@student.stih.ac.id', NULL, '$2y$12$rDiq1F/ybcBqJb8ok7q3uexFmZqalkcryvzF3uQKVXjoDX.dJKVxq', NULL, 'mahasiswa', '2026-05-22 02:34:13', '2026-05-22 02:44:20'),
(31, 'Super Admin STIH', 'superadmin@stih.ac.id', NULL, '$2y$12$4aaAguc2/vtiegaDSPKq3.Xtvq1uMQmlKp1YRC/eVud9YQ48ypHhS', NULL, 'super_admin', '2026-06-02 09:41:23', '2026-06-02 09:41:23'),
(32, 'budi', '2024010001@parent.stih.ac.id', NULL, '$2y$12$kvYffezRsaalv8wlfaHEbetJOttsJPCgQ2Mnk92RjjA3pEJPZjoZq', NULL, 'parent', '2026-07-02 03:15:45', '2026-07-02 03:15:45');

-- --------------------------------------------------------

--
-- Table structure for table `wisuda_batches`
--

CREATE TABLE `wisuda_batches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_batch` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_mulai` time NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `catatan` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wisuda_batches`
--

INSERT INTO `wisuda_batches` (`id`, `nama_batch`, `tanggal`, `waktu_mulai`, `lokasi`, `catatan`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Wisuda 22 Mei 2026', '2026-05-22', '08:00:00', 'JICC', NULL, 1, '2026-05-22 02:23:32', '2026-05-22 02:23:32');

-- --------------------------------------------------------

--
-- Table structure for table `wisuda_documents`
--

CREATE TABLE `wisuda_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wisuda_registration_id` bigint(20) UNSIGNED NOT NULL,
  `file_type` enum('surat_penyerahan_skripsi','penyerahan_buku','keterangan_turnitin','pas_foto') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wisuda_registrations`
--

CREATE TABLE `wisuda_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `skripsi_submission_id` bigint(20) UNSIGNED NOT NULL,
  `wisuda_batch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `email_aktif` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected','scheduled') NOT NULL DEFAULT 'pending',
  `rejection_note` text DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wisuda_registrations`
--

INSERT INTO `wisuda_registrations` (`id`, `mahasiswa_id`, `skripsi_submission_id`, `wisuda_batch_id`, `no_hp`, `email_aktif`, `status`, `rejection_note`, `submitted_at`, `reviewed_at`, `reviewed_by`, `created_at`, `updated_at`) VALUES
(2, 4, 4, 1, '1231231231231', 'ahmadmahasiswa@student.stih.ac.id', 'scheduled', NULL, '2026-06-04 05:38:09', '2026-06-04 05:38:49', 1, '2026-06-04 05:38:09', '2026-06-04 05:58:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_events`
--
ALTER TABLE `academic_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ae_active_type_dates_idx` (`is_active`,`event_type`,`start_date`,`end_date`),
  ADD KEY `ae_semester_type_idx` (`semester_id`,`event_type`);

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
  ADD KEY `audit_logs_action_index` (`action`),
  ADD KEY `idx_audit_created_at` (`created_at`),
  ADD KEY `idx_audit_actor_action` (`actor_id`,`action`),
  ADD KEY `idx_audit_module` (`module`),
  ADD KEY `idx_audit_actor_role` (`actor_role`);

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
  ADD KEY `dosens_user_id_foreign` (`user_id`),
  ADD KEY `dosens_fakultas_id_foreign` (`fakultas_id`);

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
-- Indexes for table `dosen_mata_kuliah`
--
ALTER TABLE `dosen_mata_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dmk_unique` (`dosen_id`,`mata_kuliah_id`,`semester_id`),
  ADD KEY `dosen_mata_kuliah_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  ADD KEY `dosen_mata_kuliah_semester_id_foreign` (`semester_id`),
  ADD KEY `dosen_mata_kuliah_created_by_foreign` (`created_by`),
  ADD KEY `dmk_dosen_semester` (`dosen_id`,`semester_id`);

--
-- Indexes for table `dosen_pa`
--
ALTER TABLE `dosen_pa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dosen_pa_mahasiswa_id_unique` (`mahasiswa_id`),
  ADD KEY `dosen_pa_dosen_id_foreign` (`dosen_id`);

--
-- Indexes for table `email_blast_logs`
--
ALTER TABLE `email_blast_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_blast_logs_batch_id_index` (`batch_id`),
  ADD KEY `email_blast_logs_mahasiswa_id_index` (`mahasiswa_id`),
  ADD KEY `email_blast_logs_success_index` (`success`),
  ADD KEY `email_blast_logs_created_at_index` (`created_at`);

--
-- Indexes for table `email_outboxes`
--
ALTER TABLE `email_outboxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_outboxes_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `email_outboxes_batch_id_index` (`batch_id`),
  ADD KEY `email_outboxes_status_index` (`status`),
  ADD KEY `email_outboxes_scheduled_at_index` (`scheduled_at`);

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
-- Indexes for table `impersonation_logs`
--
ALTER TABLE `impersonation_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `impersonation_logs_impersonator_id_index` (`impersonator_id`),
  ADD KEY `impersonation_logs_target_user_id_index` (`target_user_id`),
  ADD KEY `impersonation_logs_started_at_index` (`started_at`),
  ADD KEY `impersonation_logs_ended_at_started_at_index` (`ended_at`,`started_at`);

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
-- Indexes for table `internships`
--
ALTER TABLE `internships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `internships_semester_id_foreign` (`semester_id`),
  ADD KEY `internships_approved_by_foreign` (`approved_by`),
  ADD KEY `internships_mahasiswa_id_semester_id_index` (`mahasiswa_id`,`semester_id`),
  ADD KEY `internships_supervisor_dosen_id_index` (`supervisor_dosen_id`),
  ADD KEY `internships_status_index` (`status`),
  ADD KEY `internships_sent_by_foreign` (`sent_by`),
  ADD KEY `internships_date_changed_by_foreign` (`date_changed_by`),
  ADD KEY `internships_internship_type_id_foreign` (`internship_type_id`);

--
-- Indexes for table `internship_course_mappings`
--
ALTER TABLE `internship_course_mappings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `internship_course_mappings_internship_id_mata_kuliah_id_unique` (`internship_id`,`mata_kuliah_id`),
  ADD KEY `internship_course_mappings_mata_kuliah_id_foreign` (`mata_kuliah_id`);

--
-- Indexes for table `internship_logbooks`
--
ALTER TABLE `internship_logbooks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `internship_logbooks_internship_id_index` (`internship_id`);

--
-- Indexes for table `internship_revisions`
--
ALTER TABLE `internship_revisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `internship_revisions_internship_id_index` (`internship_id`);

--
-- Indexes for table `internship_types`
--
ALTER TABLE `internship_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `internship_types_code_unique` (`code`);

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
  ADD KEY `jadwals_ruangan_id_index` (`ruangan_id`),
  ADD KEY `jadwals_kelas_perkuliahan_id_foreign` (`kelas_perkuliahan_id`);

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
-- Indexes for table `kategori_ruangans`
--
ALTER TABLE `kategori_ruangans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kategori_ruangans_nama_kategori_unique` (`nama_kategori`),
  ADD KEY `kategori_ruangans_urutan_index` (`urutan`),
  ADD KEY `kategori_ruangans_status_index` (`status`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  ADD KEY `kelas_dosen_id_foreign` (`dosen_id`),
  ADD KEY `kelas_kelas_perkuliahan_id_foreign` (`kelas_perkuliahan_id`);

--
-- Indexes for table `kelas_mata_kuliahs`
--
ALTER TABLE `kelas_mata_kuliahs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kelas_mata_kuliahs_qr_token_unique` (`qr_token`),
  ADD KEY `kelas_mata_kuliahs_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  ADD KEY `kelas_mata_kuliahs_dosen_id_foreign` (`dosen_id`),
  ADD KEY `kelas_mata_kuliahs_semester_id_foreign` (`semester_id`),
  ADD KEY `kelas_mata_kuliahs_ruangan_id_index` (`ruangan_id`),
  ADD KEY `kelas_mata_kuliahs_kelas_perkuliahan_id_foreign` (`kelas_perkuliahan_id`);

--
-- Indexes for table `kelas_perkuliahans`
--
ALTER TABLE `kelas_perkuliahans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kp_unique_angkatan_combo` (`angkatan`,`prodi_id`,`kode_kelas`,`tahun_akademik_id`),
  ADD KEY `kelas_perkuliahans_tahun_akademik_id_foreign` (`tahun_akademik_id`),
  ADD KEY `idx_kelas_angkatan` (`angkatan`),
  ADD KEY `idx_kelas_prodi` (`prodi_id`),
  ADD KEY `idx_kelas_kode` (`kode_kelas`),
  ADD KEY `idx_kelas_angkatan_prodi_kode` (`angkatan`,`prodi_id`,`kode_kelas`);

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
  ADD KEY `krs_kelas_mata_kuliah_id_foreign` (`kelas_mata_kuliah_id`),
  ADD KEY `krs_internship_id_foreign` (`internship_id`);

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
  ADD UNIQUE KEY `mahasiswas_email_kampus_unique` (`email_kampus`),
  ADD KEY `mahasiswas_user_id_foreign` (`user_id`),
  ADD KEY `mahasiswas_last_semester_id_foreign` (`last_semester_id`),
  ADD KEY `mahasiswas_email_pribadi_index` (`email_pribadi`),
  ADD KEY `mahasiswas_kelas_perkuliahan_id_idx` (`kelas_perkuliahan_id`),
  ADD KEY `mahasiswas_prodi_id_idx` (`prodi_id`),
  ADD KEY `mahasiswas_tahun_akademik_id_idx` (`tahun_akademik_id`),
  ADD KEY `mahasiswas_semester_idx` (`semester`);

--
-- Indexes for table `mata_kuliahs`
--
ALTER TABLE `mata_kuliahs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mata_kuliahs_kode_mk_unique` (`kode_mk`),
  ADD KEY `mata_kuliahs_kode_id_index` (`kode_id`),
  ADD KEY `mata_kuliahs_prodi_id_foreign` (`prodi_id`),
  ADD KEY `mata_kuliahs_fakultas_id_foreign` (`fakultas_id`),
  ADD KEY `mata_kuliahs_tipe_index` (`tipe`);

--
-- Indexes for table `mata_kuliah_semesters`
--
ALTER TABLE `mata_kuliah_semesters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mk_semester_unique` (`semester_id`,`mata_kuliah_id`),
  ADD KEY `mata_kuliah_semesters_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  ADD KEY `mata_kuliah_semesters_status_index` (`status`),
  ADD KEY `mata_kuliah_semesters_source_semester_id_index` (`source_semester_id`);

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
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

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
  ADD KEY `pengajuans_approved_by_foreign` (`approved_by`),
  ADD KEY `idx_pengajuans_mhs_status` (`mahasiswa_id`,`status`),
  ADD KEY `idx_pengajuans_jenis` (`jenis`);

--
-- Indexes for table `pengajuan_revisions`
--
ALTER TABLE `pengajuan_revisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengajuan_revisions_pengajuan_id_revision_no_index` (`pengajuan_id`,`revision_no`);

--
-- Indexes for table `pengumumans`
--
ALTER TABLE `pengumumans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `pertemuans`
--
ALTER TABLE `pertemuans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pertemuans_qr_token_unique` (`qr_token`),
  ADD KEY `pertemuans_kelas_mata_kuliah_id_index` (`kelas_mata_kuliah_id`),
  ADD KEY `pertemuans_kelas_mata_kuliah_id_nomor_pertemuan_index` (`kelas_mata_kuliah_id`,`nomor_pertemuan`),
  ADD KEY `pertemuans_kmk_tipe_nomor_index` (`kelas_mata_kuliah_id`,`tipe_pertemuan`,`nomor_pertemuan`);

--
-- Indexes for table `presensis`
--
ALTER TABLE `presensis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `presensis_krs_id_foreign` (`krs_id`),
  ADD KEY `presensis_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `presensis_kelas_mata_kuliah_id_foreign` (`kelas_mata_kuliah_id`);

--
-- Indexes for table `prestasis`
--
ALTER TABLE `prestasis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prestasis_approved_by_foreign` (`approved_by`),
  ADD KEY `prestasis_pengaju_type_pengaju_id_index` (`pengaju_type`,`pengaju_id`),
  ADD KEY `prestasis_dosen_pendamping_id_index` (`dosen_pendamping_id`),
  ADD KEY `prestasis_tingkat_kegiatan_index` (`tingkat_kegiatan`),
  ADD KEY `prestasis_status_index` (`status`),
  ADD KEY `prestasis_hash_kegiatan_index` (`hash_kegiatan`);

--
-- Indexes for table `prestasi_dokumens`
--
ALTER TABLE `prestasi_dokumens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prestasi_dokumens_uploaded_by_foreign` (`uploaded_by`),
  ADD KEY `prestasi_dokumens_prestasi_id_index` (`prestasi_id`);

--
-- Indexes for table `prestasi_logs`
--
ALTER TABLE `prestasi_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prestasi_logs_user_id_foreign` (`user_id`),
  ADD KEY `prestasi_logs_prestasi_id_index` (`prestasi_id`),
  ADD KEY `prestasi_logs_action_index` (`action`);

--
-- Indexes for table `prestasi_surats`
--
ALTER TABLE `prestasi_surats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prestasi_surats_nomor_surat_unique` (`nomor_surat`),
  ADD KEY `prestasi_surats_generated_by_foreign` (`generated_by`),
  ADD KEY `prestasi_surats_prestasi_id_index` (`prestasi_id`),
  ADD KEY `prestasi_surats_jenis_surat_index` (`jenis_surat`);

--
-- Indexes for table `prestasi_surat_settings`
--
ALTER TABLE `prestasi_surat_settings`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `ruangans`
--
ALTER TABLE `ruangans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ruangans_kode_ruangan_unique` (`kode_ruangan`),
  ADD KEY `ruangans_kategori_id_foreign` (`kategori_id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `semesters_nama_tahun_tanggal_unique` (`nama_semester`,`tahun_ajaran`,`tanggal_mulai`),
  ADD KEY `semesters_locked_by_foreign` (`locked_by`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `skripsi_guidances`
--
ALTER TABLE `skripsi_guidances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thesis_guidances_dosen_id_foreign` (`dosen_id`),
  ADD KEY `skripsi_guidances_skripsi_submission_id_foreign` (`skripsi_submission_id`);

--
-- Indexes for table `skripsi_revisions`
--
ALTER TABLE `skripsi_revisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thesis_revisions_approved_by_dosen_id_foreign` (`approved_by_dosen_id`),
  ADD KEY `skripsi_revisions_skripsi_submission_id_foreign` (`skripsi_submission_id`);

--
-- Indexes for table `skripsi_sidang_files`
--
ALTER TABLE `skripsi_sidang_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thesis_sidang_files_sidang_registration_id_foreign` (`sidang_registration_id`);

--
-- Indexes for table `skripsi_sidang_registrations`
--
ALTER TABLE `skripsi_sidang_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thesis_sidang_registrations_verified_by_foreign` (`verified_by`),
  ADD KEY `skripsi_sidang_registrations_skripsi_submission_id_foreign` (`skripsi_submission_id`);

--
-- Indexes for table `skripsi_sidang_schedules`
--
ALTER TABLE `skripsi_sidang_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thesis_sidang_schedules_sidang_registration_id_foreign` (`sidang_registration_id`),
  ADD KEY `thesis_sidang_schedules_ruangan_id_foreign` (`ruangan_id`),
  ADD KEY `thesis_sidang_schedules_pembimbing_id_foreign` (`pembimbing_id`),
  ADD KEY `thesis_sidang_schedules_penguji_1_id_foreign` (`penguji_1_id`),
  ADD KEY `thesis_sidang_schedules_penguji_2_id_foreign` (`penguji_2_id`),
  ADD KEY `thesis_sidang_schedules_created_by_foreign` (`created_by`),
  ADD KEY `skripsi_sidang_schedules_skripsi_submission_id_foreign` (`skripsi_submission_id`);

--
-- Indexes for table `skripsi_submissions`
--
ALTER TABLE `skripsi_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thesis_submissions_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `thesis_submissions_semester_id_foreign` (`semester_id`),
  ADD KEY `thesis_submissions_requested_supervisor_id_foreign` (`requested_supervisor_id`),
  ADD KEY `thesis_submissions_approved_supervisor_id_foreign` (`approved_supervisor_id`),
  ADD KEY `thesis_submissions_reviewed_by_foreign` (`reviewed_by`);

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
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploads_uploadable_type_uploadable_id_index` (`uploadable_type`,`uploadable_id`),
  ADD KEY `uploads_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `wisuda_batches`
--
ALTER TABLE `wisuda_batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wisuda_batches_created_by_foreign` (`created_by`);

--
-- Indexes for table `wisuda_documents`
--
ALTER TABLE `wisuda_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wisuda_documents_wisuda_registration_id_foreign` (`wisuda_registration_id`);

--
-- Indexes for table `wisuda_registrations`
--
ALTER TABLE `wisuda_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wisuda_registrations_skripsi_submission_id_foreign` (`skripsi_submission_id`),
  ADD KEY `wisuda_registrations_wisuda_batch_id_foreign` (`wisuda_batch_id`),
  ADD KEY `wisuda_registrations_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `wisuda_registrations_mahasiswa_id_status_index` (`mahasiswa_id`,`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_events`
--
ALTER TABLE `academic_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=689;

--
-- AUTO_INCREMENT for table `bobot_penilaian`
--
ALTER TABLE `bobot_penilaian`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dokumen_kelas`
--
ALTER TABLE `dokumen_kelas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dosens`
--
ALTER TABLE `dosens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `dosen_attendances`
--
ALTER TABLE `dosen_attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dosen_availabilities`
--
ALTER TABLE `dosen_availabilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT for table `dosen_availability_checks`
--
ALTER TABLE `dosen_availability_checks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dosen_mata_kuliah`
--
ALTER TABLE `dosen_mata_kuliah`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dosen_pa`
--
ALTER TABLE `dosen_pa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `email_blast_logs`
--
ALTER TABLE `email_blast_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `email_outboxes`
--
ALTER TABLE `email_outboxes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fakultas`
--
ALTER TABLE `fakultas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `impersonation_logs`
--
ALTER TABLE `impersonation_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `import_logs`
--
ALTER TABLE `import_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `installments`
--
ALTER TABLE `installments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `installment_requests`
--
ALTER TABLE `installment_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `internships`
--
ALTER TABLE `internships`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `internship_course_mappings`
--
ALTER TABLE `internship_course_mappings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internship_logbooks`
--
ALTER TABLE `internship_logbooks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `internship_revisions`
--
ALTER TABLE `internship_revisions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internship_types`
--
ALTER TABLE `internship_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `jadwals`
--
ALTER TABLE `jadwals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jadwal_approvals`
--
ALTER TABLE `jadwal_approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jadwal_exceptions`
--
ALTER TABLE `jadwal_exceptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_generate_logs`
--
ALTER TABLE `jadwal_generate_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jadwal_proposals`
--
ALTER TABLE `jadwal_proposals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jadwal_reschedules`
--
ALTER TABLE `jadwal_reschedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jam_perkuliahan`
--
ALTER TABLE `jam_perkuliahan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori_ruangans`
--
ALTER TABLE `kategori_ruangans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `kelas_mata_kuliahs`
--
ALTER TABLE `kelas_mata_kuliahs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `kelas_perkuliahans`
--
ALTER TABLE `kelas_perkuliahans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kelas_reschedules`
--
ALTER TABLE `kelas_reschedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `krs`
--
ALTER TABLE `krs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `kuesioner_aktivasi`
--
ALTER TABLE `kuesioner_aktivasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kuesioner_mahasiswa_baru`
--
ALTER TABLE `kuesioner_mahasiswa_baru`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mahasiswas`
--
ALTER TABLE `mahasiswas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `mata_kuliahs`
--
ALTER TABLE `mata_kuliahs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `mata_kuliah_semesters`
--
ALTER TABLE `mata_kuliah_semesters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `materis`
--
ALTER TABLE `materis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment_proofs`
--
ALTER TABLE `payment_proofs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengajuans`
--
ALTER TABLE `pengajuans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pengajuan_revisions`
--
ALTER TABLE `pengajuan_revisions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengumumans`
--
ALTER TABLE `pengumumans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `pertemuans`
--
ALTER TABLE `pertemuans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `presensis`
--
ALTER TABLE `presensis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prestasis`
--
ALTER TABLE `prestasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `prestasi_dokumens`
--
ALTER TABLE `prestasi_dokumens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prestasi_logs`
--
ALTER TABLE `prestasi_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `prestasi_surats`
--
ALTER TABLE `prestasi_surats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prestasi_surat_settings`
--
ALTER TABLE `prestasi_surat_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `prodis`
--
ALTER TABLE `prodis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `religions`
--
ALTER TABLE `religions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ruangans`
--
ALTER TABLE `ruangans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `skripsi_guidances`
--
ALTER TABLE `skripsi_guidances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `skripsi_revisions`
--
ALTER TABLE `skripsi_revisions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `skripsi_sidang_files`
--
ALTER TABLE `skripsi_sidang_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `skripsi_sidang_registrations`
--
ALTER TABLE `skripsi_sidang_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `skripsi_sidang_schedules`
--
ALTER TABLE `skripsi_sidang_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `skripsi_submissions`
--
ALTER TABLE `skripsi_submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tugas_submissions`
--
ALTER TABLE `tugas_submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `wisuda_batches`
--
ALTER TABLE `wisuda_batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wisuda_documents`
--
ALTER TABLE `wisuda_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wisuda_registrations`
--
ALTER TABLE `wisuda_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  ADD CONSTRAINT `dosens_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`) ON DELETE SET NULL,
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
-- Constraints for table `dosen_mata_kuliah`
--
ALTER TABLE `dosen_mata_kuliah`
  ADD CONSTRAINT `dosen_mata_kuliah_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `dosen_mata_kuliah_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dosen_mata_kuliah_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dosen_mata_kuliah_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dosen_pa`
--
ALTER TABLE `dosen_pa`
  ADD CONSTRAINT `dosen_pa_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dosen_pa_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `email_blast_logs`
--
ALTER TABLE `email_blast_logs`
  ADD CONSTRAINT `email_blast_logs_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `email_outboxes`
--
ALTER TABLE `email_outboxes`
  ADD CONSTRAINT `email_outboxes_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `impersonation_logs`
--
ALTER TABLE `impersonation_logs`
  ADD CONSTRAINT `impersonation_logs_impersonator_id_foreign` FOREIGN KEY (`impersonator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `impersonation_logs_target_user_id_foreign` FOREIGN KEY (`target_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `internships`
--
ALTER TABLE `internships`
  ADD CONSTRAINT `internships_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `internships_date_changed_by_foreign` FOREIGN KEY (`date_changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `internships_internship_type_id_foreign` FOREIGN KEY (`internship_type_id`) REFERENCES `internship_types` (`id`),
  ADD CONSTRAINT `internships_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `internships_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `internships_sent_by_foreign` FOREIGN KEY (`sent_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `internships_supervisor_dosen_id_foreign` FOREIGN KEY (`supervisor_dosen_id`) REFERENCES `dosens` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `internship_course_mappings`
--
ALTER TABLE `internship_course_mappings`
  ADD CONSTRAINT `internship_course_mappings_internship_id_foreign` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `internship_course_mappings_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `internship_logbooks`
--
ALTER TABLE `internship_logbooks`
  ADD CONSTRAINT `internship_logbooks_internship_id_foreign` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `internship_revisions`
--
ALTER TABLE `internship_revisions`
  ADD CONSTRAINT `internship_revisions_internship_id_foreign` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `jadwals_kelas_perkuliahan_id_foreign` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahans` (`id`) ON DELETE SET NULL,
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
  ADD CONSTRAINT `kelas_kelas_perkuliahan_id_foreign` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kelas_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kelas_mata_kuliahs`
--
ALTER TABLE `kelas_mata_kuliahs`
  ADD CONSTRAINT `kelas_mata_kuliahs_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelas_mata_kuliahs_kelas_perkuliahan_id_foreign` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kelas_mata_kuliahs_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelas_mata_kuliahs_ruangan_id_foreign` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kelas_mata_kuliahs_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kelas_perkuliahans`
--
ALTER TABLE `kelas_perkuliahans`
  ADD CONSTRAINT `kelas_perkuliahans_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodis` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kelas_perkuliahans_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `semesters` (`id`) ON DELETE SET NULL;

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
  ADD CONSTRAINT `krs_internship_id_foreign` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE SET NULL,
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
  ADD CONSTRAINT `mahasiswas_kelas_perkuliahan_id_foreign` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `mahasiswas_last_semester_id_foreign` FOREIGN KEY (`last_semester_id`) REFERENCES `semesters` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `mahasiswas_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodis` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `mahasiswas_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `semesters` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `mahasiswas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mata_kuliahs`
--
ALTER TABLE `mata_kuliahs`
  ADD CONSTRAINT `mata_kuliahs_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`),
  ADD CONSTRAINT `mata_kuliahs_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodis` (`id`);

--
-- Constraints for table `mata_kuliah_semesters`
--
ALTER TABLE `mata_kuliah_semesters`
  ADD CONSTRAINT `mata_kuliah_semesters_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mata_kuliah_semesters_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mata_kuliah_semesters_source_semester_id_foreign` FOREIGN KEY (`source_semester_id`) REFERENCES `semesters` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `pengajuan_revisions`
--
ALTER TABLE `pengajuan_revisions`
  ADD CONSTRAINT `pengajuan_revisions_pengajuan_id_foreign` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pertemuans`
--
ALTER TABLE `pertemuans`
  ADD CONSTRAINT `pertemuans_kelas_mata_kuliah_id_foreign` FOREIGN KEY (`kelas_mata_kuliah_id`) REFERENCES `kelas_mata_kuliahs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `presensis`
--
ALTER TABLE `presensis`
  ADD CONSTRAINT `presensis_kelas_mata_kuliah_id_foreign` FOREIGN KEY (`kelas_mata_kuliah_id`) REFERENCES `kelas_mata_kuliahs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `presensis_krs_id_foreign` FOREIGN KEY (`krs_id`) REFERENCES `krs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `presensis_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `prestasis`
--
ALTER TABLE `prestasis`
  ADD CONSTRAINT `prestasis_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `prestasis_dosen_pendamping_id_foreign` FOREIGN KEY (`dosen_pendamping_id`) REFERENCES `dosens` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `prestasi_dokumens`
--
ALTER TABLE `prestasi_dokumens`
  ADD CONSTRAINT `prestasi_dokumens_prestasi_id_foreign` FOREIGN KEY (`prestasi_id`) REFERENCES `prestasis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prestasi_dokumens_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `prestasi_logs`
--
ALTER TABLE `prestasi_logs`
  ADD CONSTRAINT `prestasi_logs_prestasi_id_foreign` FOREIGN KEY (`prestasi_id`) REFERENCES `prestasis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prestasi_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `prestasi_surats`
--
ALTER TABLE `prestasi_surats`
  ADD CONSTRAINT `prestasi_surats_generated_by_foreign` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `prestasi_surats_prestasi_id_foreign` FOREIGN KEY (`prestasi_id`) REFERENCES `prestasis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prodis`
--
ALTER TABLE `prodis`
  ADD CONSTRAINT `prodis_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`);

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ruangans`
--
ALTER TABLE `ruangans`
  ADD CONSTRAINT `ruangans_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_ruangans` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `semesters`
--
ALTER TABLE `semesters`
  ADD CONSTRAINT `semesters_locked_by_foreign` FOREIGN KEY (`locked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `skripsi_guidances`
--
ALTER TABLE `skripsi_guidances`
  ADD CONSTRAINT `skripsi_guidances_skripsi_submission_id_foreign` FOREIGN KEY (`skripsi_submission_id`) REFERENCES `skripsi_submissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `thesis_guidances_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`);

--
-- Constraints for table `skripsi_revisions`
--
ALTER TABLE `skripsi_revisions`
  ADD CONSTRAINT `skripsi_revisions_skripsi_submission_id_foreign` FOREIGN KEY (`skripsi_submission_id`) REFERENCES `skripsi_submissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `thesis_revisions_approved_by_dosen_id_foreign` FOREIGN KEY (`approved_by_dosen_id`) REFERENCES `dosens` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `skripsi_sidang_files`
--
ALTER TABLE `skripsi_sidang_files`
  ADD CONSTRAINT `thesis_sidang_files_sidang_registration_id_foreign` FOREIGN KEY (`sidang_registration_id`) REFERENCES `skripsi_sidang_registrations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `skripsi_sidang_registrations`
--
ALTER TABLE `skripsi_sidang_registrations`
  ADD CONSTRAINT `skripsi_sidang_registrations_skripsi_submission_id_foreign` FOREIGN KEY (`skripsi_submission_id`) REFERENCES `skripsi_submissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `thesis_sidang_registrations_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `skripsi_sidang_schedules`
--
ALTER TABLE `skripsi_sidang_schedules`
  ADD CONSTRAINT `skripsi_sidang_schedules_skripsi_submission_id_foreign` FOREIGN KEY (`skripsi_submission_id`) REFERENCES `skripsi_submissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `thesis_sidang_schedules_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `thesis_sidang_schedules_pembimbing_id_foreign` FOREIGN KEY (`pembimbing_id`) REFERENCES `dosens` (`id`),
  ADD CONSTRAINT `thesis_sidang_schedules_penguji_1_id_foreign` FOREIGN KEY (`penguji_1_id`) REFERENCES `dosens` (`id`),
  ADD CONSTRAINT `thesis_sidang_schedules_penguji_2_id_foreign` FOREIGN KEY (`penguji_2_id`) REFERENCES `dosens` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `thesis_sidang_schedules_ruangan_id_foreign` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `thesis_sidang_schedules_sidang_registration_id_foreign` FOREIGN KEY (`sidang_registration_id`) REFERENCES `skripsi_sidang_registrations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `skripsi_submissions`
--
ALTER TABLE `skripsi_submissions`
  ADD CONSTRAINT `thesis_submissions_approved_supervisor_id_foreign` FOREIGN KEY (`approved_supervisor_id`) REFERENCES `dosens` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `thesis_submissions_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `thesis_submissions_requested_supervisor_id_foreign` FOREIGN KEY (`requested_supervisor_id`) REFERENCES `dosens` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `thesis_submissions_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `thesis_submissions_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `uploads`
--
ALTER TABLE `uploads`
  ADD CONSTRAINT `uploads_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `wisuda_batches`
--
ALTER TABLE `wisuda_batches`
  ADD CONSTRAINT `wisuda_batches_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `wisuda_documents`
--
ALTER TABLE `wisuda_documents`
  ADD CONSTRAINT `wisuda_documents_wisuda_registration_id_foreign` FOREIGN KEY (`wisuda_registration_id`) REFERENCES `wisuda_registrations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wisuda_registrations`
--
ALTER TABLE `wisuda_registrations`
  ADD CONSTRAINT `wisuda_registrations_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wisuda_registrations_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `wisuda_registrations_skripsi_submission_id_foreign` FOREIGN KEY (`skripsi_submission_id`) REFERENCES `skripsi_submissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wisuda_registrations_wisuda_batch_id_foreign` FOREIGN KEY (`wisuda_batch_id`) REFERENCES `wisuda_batches` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
