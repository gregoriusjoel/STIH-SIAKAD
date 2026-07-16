/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `academic_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academic_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `event_type` enum('krs','krs_perubahan','perkuliahan','uts','uas','libur_akademik','lainnya') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lainnya',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `semester_id` bigint unsigned DEFAULT NULL,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3788d8',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ae_active_type_dates_idx` (`is_active`,`event_type`,`start_date`,`end_date`),
  KEY `ae_semester_type_idx` (`semester_id`,`event_type`),
  CONSTRAINT `academic_events_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `activity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_nip_unique` (`nip`),
  KEY `admins_user_id_foreign` (`user_id`),
  CONSTRAINT `admins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `actor_id` bigint unsigned DEFAULT NULL,
  `actor_role` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Feature module: akademik, keuangan, magang, skripsi, wisuda, system, auth',
  `auditable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` bigint unsigned NOT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `before` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `after` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Laravel session ID for correlating events within one login session',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `audit_logs_actor_id_index` (`actor_id`),
  KEY `audit_logs_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  KEY `audit_logs_action_index` (`action`),
  KEY `idx_audit_created_at` (`created_at`),
  KEY `idx_audit_actor_action` (`actor_id`,`action`),
  KEY `idx_audit_module` (`module`),
  KEY `idx_audit_actor_role` (`actor_role`),
  CONSTRAINT `audit_logs_actor_id_foreign` FOREIGN KEY (`actor_id`) REFERENCES `users` (`id`),
  CONSTRAINT `audit_logs_chk_1` CHECK (json_valid(`meta`)),
  CONSTRAINT `audit_logs_chk_2` CHECK (json_valid(`before`)),
  CONSTRAINT `audit_logs_chk_3` CHECK (json_valid(`after`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bobot_penilaian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bobot_penilaian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_id` bigint unsigned NOT NULL,
  `bobot_partisipatif` decimal(5,2) NOT NULL DEFAULT '25.00',
  `bobot_proyek` decimal(5,2) NOT NULL DEFAULT '25.00',
  `bobot_quiz` decimal(5,2) NOT NULL DEFAULT '5.00',
  `bobot_tugas` decimal(5,2) NOT NULL DEFAULT '5.00',
  `bobot_uts` decimal(5,2) NOT NULL DEFAULT '20.00',
  `bobot_uas` decimal(5,2) NOT NULL DEFAULT '20.00',
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `locked_at` timestamp NULL DEFAULT NULL,
  `locked_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bobot_penilaian_kelas_id_unique` (`kelas_id`),
  KEY `bobot_penilaian_locked_by_foreign` (`locked_by`),
  CONSTRAINT `bobot_penilaian_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bobot_penilaian_locked_by_foreign` FOREIGN KEY (`locked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `dokumen_kelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dokumen_kelas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_id` bigint unsigned NOT NULL,
  `tipe_dokumen` enum('silabus','rps') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uploaded_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dokumen_kelas_kelas_id_tipe_dokumen_unique` (`kelas_id`,`tipe_dokumen`),
  KEY `dokumen_kelas_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `dokumen_kelas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dokumen_kelas_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `dosen_attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dosen_attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dosen_id` bigint unsigned NOT NULL,
  `kelas_mata_kuliah_id` bigint unsigned NOT NULL,
  `pertemuan_id` bigint unsigned DEFAULT NULL,
  `metode_pengajaran` enum('offline','online','asynchronous') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'offline',
  `jam_kelas_mulai` time DEFAULT NULL COMMENT 'Scheduled class start time',
  `jam_kelas_selesai` time DEFAULT NULL COMMENT 'Scheduled class end time',
  `jam_absen_dosen` datetime NOT NULL COMMENT 'When dosen tapped activate QR',
  `lokasi_dosen` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'GPS coords or address',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dosen_attendance_unique` (`dosen_id`,`pertemuan_id`),
  KEY `dosen_attendances_kelas_mata_kuliah_id_foreign` (`kelas_mata_kuliah_id`),
  KEY `dosen_attendances_pertemuan_id_foreign` (`pertemuan_id`),
  CONSTRAINT `dosen_attendances_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dosen_attendances_kelas_mata_kuliah_id_foreign` FOREIGN KEY (`kelas_mata_kuliah_id`) REFERENCES `kelas_mata_kuliahs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dosen_attendances_pertemuan_id_foreign` FOREIGN KEY (`pertemuan_id`) REFERENCES `pertemuans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `dosen_availabilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dosen_availabilities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dosen_id` bigint unsigned NOT NULL,
  `semester_id` bigint unsigned NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hari tersedia',
  `jam_perkuliahan_id` bigint unsigned NOT NULL,
  `status` enum('available','booked','blocked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available' COMMENT 'Status ketersediaan',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Catatan dari dosen',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_dosen_slot` (`dosen_id`,`semester_id`,`hari`,`jam_perkuliahan_id`),
  KEY `dosen_availabilities_semester_id_foreign` (`semester_id`),
  KEY `dosen_availabilities_jam_perkuliahan_id_foreign` (`jam_perkuliahan_id`),
  CONSTRAINT `dosen_availabilities_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dosen_availabilities_jam_perkuliahan_id_foreign` FOREIGN KEY (`jam_perkuliahan_id`) REFERENCES `jam_perkuliahan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dosen_availabilities_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `dosen_availability_checks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dosen_availability_checks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dosen_id` bigint unsigned NOT NULL,
  `mata_kuliah_id` bigint unsigned NOT NULL,
  `hari` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dosen_availability_checks_dosen_id_foreign` (`dosen_id`),
  KEY `dosen_availability_checks_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  CONSTRAINT `dosen_availability_checks_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dosen_availability_checks_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `dosen_mata_kuliah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dosen_mata_kuliah` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dosen_id` bigint unsigned NOT NULL,
  `mata_kuliah_id` bigint unsigned NOT NULL,
  `semester_id` bigint unsigned NOT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dmk_unique` (`dosen_id`,`mata_kuliah_id`,`semester_id`),
  KEY `dosen_mata_kuliah_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  KEY `dosen_mata_kuliah_semester_id_foreign` (`semester_id`),
  KEY `dosen_mata_kuliah_created_by_foreign` (`created_by`),
  KEY `dmk_dosen_semester` (`dosen_id`,`semester_id`),
  CONSTRAINT `dosen_mata_kuliah_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `dosen_mata_kuliah_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dosen_mata_kuliah_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dosen_mata_kuliah_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `dosen_pa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dosen_pa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dosen_id` bigint unsigned NOT NULL,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dosen_pa_mahasiswa_id_unique` (`mahasiswa_id`),
  KEY `dosen_pa_dosen_id_foreign` (`dosen_id`),
  CONSTRAINT `dosen_pa_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dosen_pa_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `dosens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dosens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `fakultas_id` bigint unsigned DEFAULT NULL,
  `nidn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pendidikan_terakhir` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin COMMENT 'Multiple education levels: S1, S2, S3',
  `universitas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin COMMENT 'Array of universities for each education level',
  `dosen_tetap` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is permanent lecturer',
  `jabatan_fungsional` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin COMMENT 'Functional positions',
  `pendidikan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prodi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `status` enum('aktif','non-aktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kuota` int NOT NULL DEFAULT '6',
  `absen_password_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Bcrypt hash for dosen QR attendance activation password',
  PRIMARY KEY (`id`),
  UNIQUE KEY `dosens_nidn_unique` (`nidn`),
  KEY `dosens_user_id_foreign` (`user_id`),
  KEY `dosens_fakultas_id_foreign` (`fakultas_id`),
  CONSTRAINT `dosens_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `dosens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dosens_chk_1` CHECK (json_valid(`pendidikan_terakhir`)),
  CONSTRAINT `dosens_chk_2` CHECK (json_valid(`universitas`)),
  CONSTRAINT `dosens_chk_3` CHECK (json_valid(`jabatan_fungsional`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `email_blast_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_blast_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `email_sent_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT '0',
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `recipient_type` enum('student','parent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'student',
  `credential_type` enum('none','student','parents','both') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `sent_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email_blast_logs_batch_id_index` (`batch_id`),
  KEY `email_blast_logs_mahasiswa_id_index` (`mahasiswa_id`),
  KEY `email_blast_logs_success_index` (`success`),
  KEY `email_blast_logs_created_at_index` (`created_at`),
  CONSTRAINT `email_blast_logs_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `email_outboxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_outboxes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `target_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `greeting` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message_body` text COLLATE utf8mb4_unicode_ci,
  `is_credentials_mode` tinyint(1) NOT NULL DEFAULT '0',
  `credential_type` enum('none','student','parents','both') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email_outboxes_mahasiswa_id_foreign` (`mahasiswa_id`),
  KEY `email_outboxes_batch_id_index` (`batch_id`),
  KEY `email_outboxes_status_index` (`status`),
  KEY `email_outboxes_scheduled_at_index` (`scheduled_at`),
  CONSTRAINT `email_outboxes_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `fakultas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fakultas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_fakultas` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_fakultas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fakultas_kode_fakultas_unique` (`kode_fakultas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `impersonation_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `impersonation_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `impersonator_id` bigint unsigned NOT NULL,
  `target_user_id` bigint unsigned NOT NULL,
  `target_role` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Role of the target user at time of impersonation',
  `reason` text COLLATE utf8mb4_unicode_ci COMMENT 'Reason provided by Super Admin for impersonation',
  `started_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'When impersonation session began',
  `ended_at` timestamp NULL DEFAULT NULL COMMENT 'When impersonation session ended (null if still active)',
  `duration_seconds` int unsigned DEFAULT NULL COMMENT 'Duration in seconds (calculated on stop)',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `impersonation_logs_impersonator_id_index` (`impersonator_id`),
  KEY `impersonation_logs_target_user_id_index` (`target_user_id`),
  KEY `impersonation_logs_started_at_index` (`started_at`),
  KEY `impersonation_logs_ended_at_started_at_index` (`ended_at`,`started_at`),
  CONSTRAINT `impersonation_logs_impersonator_id_foreign` FOREIGN KEY (`impersonator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `impersonation_logs_target_user_id_foreign` FOREIGN KEY (`target_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `import_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_rows` int NOT NULL DEFAULT '0',
  `success_count` int NOT NULL DEFAULT '0',
  `failed_count` int NOT NULL DEFAULT '0',
  `skipped_count` int NOT NULL DEFAULT '0',
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `imported_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `import_logs_user_id_foreign` (`user_id`),
  KEY `import_logs_type_index` (`type`),
  KEY `import_logs_created_at_index` (`created_at`),
  CONSTRAINT `import_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `import_logs_chk_1` CHECK (json_valid(`details`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `installment_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `installment_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `requested_terms` int NOT NULL,
  `approved_terms` int DEFAULT NULL,
  `alasan` text COLLATE utf8mb4_unicode_ci,
  `status` enum('SUBMITTED','APPROVED','REJECTED','CANCELLED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SUBMITTED',
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `installment_requests_reviewed_by_foreign` (`reviewed_by`),
  KEY `installment_requests_invoice_id_status_index` (`invoice_id`,`status`),
  KEY `installment_requests_status_index` (`status`),
  KEY `installment_requests_student_id_foreign` (`student_id`),
  CONSTRAINT `installment_requests_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `installment_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`),
  CONSTRAINT `installment_requests_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `installments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `installments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `installment_no` int NOT NULL,
  `amount` bigint unsigned NOT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('UNPAID','WAITING_VERIFICATION','PAID','REJECTED_PAYMENT') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UNPAID',
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `installments_invoice_id_installment_no_unique` (`invoice_id`,`installment_no`),
  KEY `installments_invoice_id_status_index` (`invoice_id`,`status`),
  CONSTRAINT `installments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `internship_course_mappings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `internship_course_mappings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `internship_id` bigint unsigned NOT NULL,
  `mata_kuliah_id` bigint unsigned NOT NULL,
  `sks` tinyint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `internship_course_mappings_internship_id_mata_kuliah_id_unique` (`internship_id`,`mata_kuliah_id`),
  KEY `internship_course_mappings_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  CONSTRAINT `internship_course_mappings_internship_id_foreign` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE CASCADE,
  CONSTRAINT `internship_course_mappings_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `internship_logbooks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `internship_logbooks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `internship_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `kegiatan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan_dosen` text COLLATE utf8mb4_unicode_ci,
  `created_by_role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mahasiswa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `internship_logbooks_internship_id_index` (`internship_id`),
  CONSTRAINT `internship_logbooks_internship_id_foreign` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `internship_revisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `internship_revisions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `internship_id` bigint unsigned NOT NULL,
  `revision_no` smallint unsigned NOT NULL,
  `request_letter_signed_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note_from_admin` text COLLATE utf8mb4_unicode_ci,
  `note_from_mahasiswa` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `internship_revisions_internship_id_index` (`internship_id`),
  CONSTRAINT `internship_revisions_internship_id_foreign` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `internship_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `internship_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_conversion` tinyint(1) NOT NULL DEFAULT '0',
  `max_conversion_sks` tinyint unsigned NOT NULL DEFAULT '16',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `internship_types_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `internships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `internships` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `internship_type_id` bigint unsigned NOT NULL DEFAULT '1',
  `semester_id` bigint unsigned NOT NULL,
  `semester_mahasiswa` int DEFAULT NULL COMMENT 'Semester mahasiswa saat mendaftar magang',
  `instansi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_instansi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `posisi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `periode_mulai` date NOT NULL,
  `periode_selesai` date NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `pembimbing_lapangan_nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pembimbing_lapangan_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pembimbing_lapangan_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dokumen_pendukung_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `supervisor_dosen_id` bigint unsigned DEFAULT NULL,
  `supervisor_assigned_at` timestamp NULL DEFAULT NULL,
  `converted_sks` tinyint unsigned NOT NULL DEFAULT '16',
  `final_score` decimal(5,2) DEFAULT NULL,
  `final_grade` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_letter_generated_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_letter_signed_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acceptance_letter_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_reason` text COLLATE utf8mb4_unicode_ci,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `revision_no` smallint unsigned NOT NULL DEFAULT '0',
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `nomor_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_final_pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_signed_pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent_to_student_at` timestamp NULL DEFAULT NULL,
  `sent_by` bigint unsigned DEFAULT NULL,
  `date_changed_by` bigint unsigned DEFAULT NULL,
  `date_changed_at` timestamp NULL DEFAULT NULL,
  `date_change_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `internships_semester_id_foreign` (`semester_id`),
  KEY `internships_approved_by_foreign` (`approved_by`),
  KEY `internships_mahasiswa_id_semester_id_index` (`mahasiswa_id`,`semester_id`),
  KEY `internships_supervisor_dosen_id_index` (`supervisor_dosen_id`),
  KEY `internships_status_index` (`status`),
  KEY `internships_sent_by_foreign` (`sent_by`),
  KEY `internships_date_changed_by_foreign` (`date_changed_by`),
  KEY `internships_internship_type_id_foreign` (`internship_type_id`),
  CONSTRAINT `internships_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `internships_date_changed_by_foreign` FOREIGN KEY (`date_changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `internships_internship_type_id_foreign` FOREIGN KEY (`internship_type_id`) REFERENCES `internship_types` (`id`),
  CONSTRAINT `internships_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `internships_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `internships_sent_by_foreign` FOREIGN KEY (`sent_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `internships_supervisor_dosen_id_foreign` FOREIGN KEY (`supervisor_dosen_id`) REFERENCES `dosens` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `semester` int NOT NULL,
  `tahun_ajaran` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sks_ambil` int DEFAULT NULL,
  `paket_sks_bayar` int DEFAULT NULL,
  `total_tagihan` bigint unsigned NOT NULL,
  `status` enum('DRAFT','PUBLISHED','IN_INSTALLMENT','LUNAS','CANCELLED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `auto_generated_from_krs` tinyint(1) NOT NULL DEFAULT '0',
  `allow_partial` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `bank_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `va_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint unsigned NOT NULL,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_created_by_foreign` (`created_by`),
  KEY `invoices_student_id_status_index` (`student_id`,`status`),
  KEY `invoices_tahun_ajaran_index` (`tahun_ajaran`),
  CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `invoices_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jadwal_approvals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jadwal_approvals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jadwal_proposal_id` bigint unsigned NOT NULL,
  `approved_by` bigint unsigned NOT NULL,
  `role` enum('dosen','admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` enum('approve','reject') COLLATE utf8mb4_unicode_ci NOT NULL,
  `alasan_penolakan` text COLLATE utf8mb4_unicode_ci,
  `hari_pengganti` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jam_mulai_pengganti` time DEFAULT NULL,
  `jam_selesai_pengganti` time DEFAULT NULL,
  `ruangan_pengganti` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jadwal_approvals_jadwal_proposal_id_role_index` (`jadwal_proposal_id`,`role`),
  KEY `jadwal_approvals_approved_by_action_index` (`approved_by`,`action`),
  CONSTRAINT `jadwal_approvals_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `jadwal_approvals_jadwal_proposal_id_foreign` FOREIGN KEY (`jadwal_proposal_id`) REFERENCES `jadwal_proposals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jadwal_exceptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jadwal_exceptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jadwal_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `hari` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jadwal_exceptions_jadwal_id_foreign` (`jadwal_id`),
  CONSTRAINT `jadwal_exceptions_jadwal_id_foreign` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jadwal_generate_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jadwal_generate_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `total_generated` int NOT NULL DEFAULT '0',
  `total_failed` int NOT NULL DEFAULT '0',
  `failed_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jadwal_generate_logs_user_id_foreign` (`user_id`),
  KEY `jadwal_generate_logs_created_at_index` (`created_at`),
  CONSTRAINT `jadwal_generate_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwal_generate_logs_chk_1` CHECK (json_valid(`failed_items`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jadwal_proposals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jadwal_proposals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mata_kuliah_id` bigint unsigned NOT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `dosen_id` bigint unsigned NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruangan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_outside_availability` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True jika jadwal dibuat di luar ketersediaan waktu dosen',
  `outside_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Alasan jadwal di luar availability: tidak mengisi / tidak cukup / bentrok',
  `ruangan_id` bigint unsigned DEFAULT NULL,
  `status` enum('pending_dosen','approved_dosen','rejected_dosen','pending_admin','approved_admin','rejected_admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_dosen',
  `catatan_generate` text COLLATE utf8mb4_unicode_ci COMMENT 'Catatan dari sistem auto generate',
  `generated_by` bigint unsigned NOT NULL,
  `generated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jadwal_proposals_kelas_id_foreign` (`kelas_id`),
  KEY `jadwal_proposals_dosen_id_foreign` (`dosen_id`),
  KEY `jadwal_proposals_generated_by_foreign` (`generated_by`),
  KEY `jadwal_proposals_status_dosen_id_index` (`status`,`dosen_id`),
  KEY `jadwal_proposals_mata_kuliah_id_hari_jam_mulai_index` (`mata_kuliah_id`,`hari`,`jam_mulai`),
  KEY `jadwal_proposals_ruangan_id_index` (`ruangan_id`),
  CONSTRAINT `jadwal_proposals_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwal_proposals_generated_by_foreign` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`),
  CONSTRAINT `jadwal_proposals_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwal_proposals_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwal_proposals_ruangan_id_foreign` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangans` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jadwal_reschedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jadwal_reschedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jadwal_id` bigint unsigned NOT NULL,
  `dosen_id` bigint unsigned NOT NULL,
  `old_hari` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_jam_mulai` time DEFAULT NULL,
  `old_jam_selesai` time DEFAULT NULL,
  `new_hari` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_jam_mulai` time NOT NULL,
  `new_jam_selesai` time NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `apply_date` date DEFAULT NULL,
  `one_week_only` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jadwal_reschedules_jadwal_id_foreign` (`jadwal_id`),
  KEY `jadwal_reschedules_dosen_id_foreign` (`dosen_id`),
  CONSTRAINT `jadwal_reschedules_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwal_reschedules_jadwal_id_foreign` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jadwals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jadwals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_id` bigint unsigned NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruangan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_outside_availability` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True jika jadwal dibuat di luar ketersediaan waktu dosen',
  `outside_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Alasan jadwal di luar availability: tidak mengisi / tidak cukup / bentrok',
  `kelas_perkuliahan_id` bigint unsigned DEFAULT NULL,
  `ruangan_id` bigint unsigned DEFAULT NULL,
  `status` enum('pending','approved','rejected','active') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `catatan_dosen` text COLLATE utf8mb4_unicode_ci,
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jadwals_kelas_id_foreign` (`kelas_id`),
  KEY `jadwals_approved_by_foreign` (`approved_by`),
  KEY `jadwals_ruangan_id_index` (`ruangan_id`),
  KEY `jadwals_kelas_perkuliahan_id_foreign` (`kelas_perkuliahan_id`),
  CONSTRAINT `jadwals_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `jadwals_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwals_kelas_perkuliahan_id_foreign` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `jadwals_ruangan_id_foreign` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangans` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jam_perkuliahan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jam_perkuliahan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jam_ke` int NOT NULL COMMENT 'Jam ke berapa (1-14)',
  `jam_mulai` time NOT NULL COMMENT 'Waktu mulai',
  `jam_selesai` time NOT NULL COMMENT 'Waktu selesai',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Status aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jam_perkuliahan_jam_ke_unique` (`jam_ke`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `kategori_ruangans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategori_ruangans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warna_badge` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'gray' COMMENT 'Warna untuk badge di UI (blue, yellow, purple, green, gray)',
  `urutan` int NOT NULL DEFAULT '0',
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kategori_ruangans_nama_kategori_unique` (`nama_kategori`),
  KEY `kategori_ruangans_urutan_index` (`urutan`),
  KEY `kategori_ruangans_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `kelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mata_kuliah_id` bigint unsigned NOT NULL,
  `dosen_id` bigint unsigned NOT NULL,
  `kapasitas` int NOT NULL DEFAULT '40',
  `tahun_ajaran` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester_type` enum('Ganjil','Genap') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ganjil',
  `kelas_perkuliahan_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kelas_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  KEY `kelas_dosen_id_foreign` (`dosen_id`),
  KEY `kelas_kelas_perkuliahan_id_foreign` (`kelas_perkuliahan_id`),
  CONSTRAINT `kelas_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kelas_kelas_perkuliahan_id_foreign` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `kelas_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `kelas_mata_kuliahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas_mata_kuliahs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mata_kuliah_id` bigint unsigned NOT NULL,
  `dosen_id` bigint unsigned NOT NULL,
  `semester_id` bigint unsigned NOT NULL,
  `kode_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kapasitas` int NOT NULL,
  `ruang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruangan_id` bigint unsigned DEFAULT NULL,
  `hari` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `metode_pengajaran` enum('offline','online','asynchronous') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `online_meeting_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `online_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `asynchronous_tugas` text COLLATE utf8mb4_unicode_ci,
  `asynchronous_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `qr_current_pertemuan` int DEFAULT NULL,
  `kelas_perkuliahan_id` bigint unsigned DEFAULT NULL,
  `qr_expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kelas_mata_kuliahs_qr_token_unique` (`qr_token`),
  KEY `kelas_mata_kuliahs_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  KEY `kelas_mata_kuliahs_dosen_id_foreign` (`dosen_id`),
  KEY `kelas_mata_kuliahs_semester_id_foreign` (`semester_id`),
  KEY `kelas_mata_kuliahs_ruangan_id_index` (`ruangan_id`),
  KEY `kelas_mata_kuliahs_kelas_perkuliahan_id_foreign` (`kelas_perkuliahan_id`),
  CONSTRAINT `kelas_mata_kuliahs_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kelas_mata_kuliahs_kelas_perkuliahan_id_foreign` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `kelas_mata_kuliahs_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kelas_mata_kuliahs_ruangan_id_foreign` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `kelas_mata_kuliahs_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `kelas_perkuliahans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas_perkuliahans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tingkat` tinyint unsigned NOT NULL,
  `angkatan` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_prodi` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_kelas` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prodi_id` bigint unsigned DEFAULT NULL,
  `tahun_akademik_id` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kp_unique_angkatan_combo` (`angkatan`,`prodi_id`,`kode_kelas`,`tahun_akademik_id`),
  KEY `kelas_perkuliahans_tahun_akademik_id_foreign` (`tahun_akademik_id`),
  KEY `idx_kelas_angkatan` (`angkatan`),
  KEY `idx_kelas_prodi` (`prodi_id`),
  KEY `idx_kelas_kode` (`kode_kelas`),
  KEY `idx_kelas_angkatan_prodi_kode` (`angkatan`,`prodi_id`,`kode_kelas`),
  CONSTRAINT `kelas_perkuliahans_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodis` (`id`) ON DELETE SET NULL,
  CONSTRAINT `kelas_perkuliahans_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `semesters` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `kelas_reschedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas_reschedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_mata_kuliah_id` bigint unsigned NOT NULL,
  `dosen_id` bigint unsigned NOT NULL,
  `old_hari` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_jam_mulai` time DEFAULT NULL,
  `old_jam_selesai` time DEFAULT NULL,
  `new_hari` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_jam_mulai` time NOT NULL,
  `new_jam_selesai` time NOT NULL,
  `new_ruang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_kelas` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metode_pengajaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `online_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `asynchronous_tugas` text COLLATE utf8mb4_unicode_ci,
  `asynchronous_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `week_start` date NOT NULL,
  `week_end` date NOT NULL,
  `status` enum('pending','approved','room_assigned','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `catatan_dosen` text COLLATE utf8mb4_unicode_ci,
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kelas_reschedules_kelas_mata_kuliah_id_foreign` (`kelas_mata_kuliah_id`),
  KEY `kelas_reschedules_dosen_id_foreign` (`dosen_id`),
  KEY `kelas_reschedules_approved_by_foreign` (`approved_by`),
  CONSTRAINT `kelas_reschedules_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `kelas_reschedules_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kelas_reschedules_kelas_mata_kuliah_id_foreign` FOREIGN KEY (`kelas_mata_kuliah_id`) REFERENCES `kelas_mata_kuliahs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `krs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `krs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `mata_kuliah_id` bigint unsigned DEFAULT NULL,
  `kelas_id` bigint unsigned DEFAULT NULL,
  `tahun_ajaran` varchar(9) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','sudah submit','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ambil_mk` enum('ya','tidak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ya',
  `internship_id` bigint unsigned DEFAULT NULL,
  `is_internship_conversion` tinyint(1) NOT NULL DEFAULT '0',
  `kelas_mata_kuliah_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `krs_mahasiswa_id_foreign` (`mahasiswa_id`),
  KEY `krs_kelas_id_foreign` (`kelas_id`),
  KEY `krs_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  KEY `krs_kelas_mata_kuliah_id_foreign` (`kelas_mata_kuliah_id`),
  KEY `krs_internship_id_foreign` (`internship_id`),
  CONSTRAINT `krs_internship_id_foreign` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE SET NULL,
  CONSTRAINT `krs_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `krs_kelas_mata_kuliah_id_foreign` FOREIGN KEY (`kelas_mata_kuliah_id`) REFERENCES `kelas_mata_kuliahs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `krs_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `krs_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `kuesioner_aktivasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kuesioner_aktivasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `semester_id` bigint unsigned DEFAULT NULL,
  `semester_mahasiswa` int DEFAULT NULL COMMENT 'Semester level of the student when filling the questionnaire',
  `fasilitas_kampus` int NOT NULL COMMENT '1-5',
  `sistem_akademik` int NOT NULL COMMENT '1-5',
  `kualitas_dosen` int NOT NULL COMMENT '1-5',
  `layanan_administrasi` int NOT NULL COMMENT '1-5',
  `kepuasan_keseluruhan` int NOT NULL COMMENT '1-5',
  `saran` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kuesioner_aktivasi_mahasiswa_id_foreign` (`mahasiswa_id`),
  KEY `kuesioner_aktivasi_semester_id_foreign` (`semester_id`),
  CONSTRAINT `kuesioner_aktivasi_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kuesioner_aktivasi_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `kuesioner_mahasiswa_baru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kuesioner_mahasiswa_baru` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prodi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `angkatan` smallint DEFAULT NULL,
  `q1` tinyint DEFAULT NULL,
  `q2` tinyint DEFAULT NULL,
  `q3` tinyint DEFAULT NULL,
  `q4` tinyint DEFAULT NULL,
  `q5` tinyint DEFAULT NULL,
  `q6` tinyint DEFAULT NULL,
  `q7` tinyint DEFAULT NULL,
  `saran` text COLLATE utf8mb4_unicode_ci,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kuesioner_mahasiswa_baru_mahasiswa_id_index` (`mahasiswa_id`),
  CONSTRAINT `kuesioner_mahasiswa_baru_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kuesioner_mahasiswa_baru_chk_1` CHECK (json_valid(`answers`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `mahasiswas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mahasiswas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `nim` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prodi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prodi_id` bigint unsigned DEFAULT NULL,
  `angkatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` tinyint unsigned NOT NULL DEFAULT '1',
  `tahun_akademik_id` bigint unsigned DEFAULT NULL,
  `last_semester_id` bigint unsigned DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `rt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rw` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kota` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_ktp` text COLLATE utf8mb4_unicode_ci,
  `rt_ktp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rw_ktp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provinsi_ktp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kota_ktp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan_ktp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desa_ktp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provinsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kabupaten` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_sekolah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jurusan_sekolah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_lulus` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nilai_kelulusan` decimal(5,2) DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_sipil` enum('Belum Menikah','Menikah','Cerai') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('aktif','cuti','lulus','do') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `status_akun` enum('baru','aktif','tidak_aktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'baru',
  `is_dokumen_unlocked` tinyint(1) NOT NULL DEFAULT '0',
  `kelas_perkuliahan_id` bigint unsigned DEFAULT NULL,
  `new_survey_completed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `file_ijazah` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `file_transkrip` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `file_kk` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `file_ktp` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `email_pribadi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email pribadi mahasiswa untuk login & notifikasi alternatif',
  `email_kampus` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email kampus otomatis: [nama_tanpa_spasi]@student.stih.ac.id',
  `email_aktif` enum('pribadi','kampus') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pribadi' COMMENT 'Email aktif untuk login & notifikasi: pribadi | kampus',
  `email_pribadi_verified_at` timestamp NULL DEFAULT NULL COMMENT 'Timestamp saat email pribadi diverifikasi',
  `password_reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Token untuk force reset password (opsional)',
  `is_default_password` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'true = password masih default (NIM), false = sudah diganti',
  `account_automation_at` timestamp NULL DEFAULT NULL COMMENT 'Timestamp saat akun otomasi dijalankan',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mahasiswas_nim_unique` (`nim`),
  UNIQUE KEY `mahasiswas_email_kampus_unique` (`email_kampus`),
  KEY `mahasiswas_user_id_foreign` (`user_id`),
  KEY `mahasiswas_last_semester_id_foreign` (`last_semester_id`),
  KEY `mahasiswas_email_pribadi_index` (`email_pribadi`),
  KEY `mahasiswas_kelas_perkuliahan_id_idx` (`kelas_perkuliahan_id`),
  KEY `mahasiswas_prodi_id_idx` (`prodi_id`),
  KEY `mahasiswas_tahun_akademik_id_idx` (`tahun_akademik_id`),
  KEY `mahasiswas_semester_idx` (`semester`),
  CONSTRAINT `mahasiswas_kelas_perkuliahan_id_foreign` FOREIGN KEY (`kelas_perkuliahan_id`) REFERENCES `kelas_perkuliahans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `mahasiswas_last_semester_id_foreign` FOREIGN KEY (`last_semester_id`) REFERENCES `semesters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `mahasiswas_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodis` (`id`) ON DELETE SET NULL,
  CONSTRAINT `mahasiswas_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `semesters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `mahasiswas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mahasiswas_chk_1` CHECK (json_valid(`file_ijazah`)),
  CONSTRAINT `mahasiswas_chk_2` CHECK (json_valid(`file_transkrip`)),
  CONSTRAINT `mahasiswas_chk_3` CHECK (json_valid(`file_kk`)),
  CONSTRAINT `mahasiswas_chk_4` CHECK (json_valid(`file_ktp`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `mata_kuliah_semesters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mata_kuliah_semesters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `semester_id` bigint unsigned NOT NULL,
  `mata_kuliah_id` bigint unsigned NOT NULL,
  `status` enum('active','history','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `source_semester_id` bigint unsigned DEFAULT NULL,
  `activated_at` datetime DEFAULT NULL,
  `deactivated_at` datetime DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mk_semester_unique` (`semester_id`,`mata_kuliah_id`),
  KEY `mata_kuliah_semesters_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  KEY `mata_kuliah_semesters_status_index` (`status`),
  KEY `mata_kuliah_semesters_source_semester_id_index` (`source_semester_id`),
  CONSTRAINT `mata_kuliah_semesters_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliahs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mata_kuliah_semesters_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mata_kuliah_semesters_source_semester_id_foreign` FOREIGN KEY (`source_semester_id`) REFERENCES `semesters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `mata_kuliah_semesters_chk_1` CHECK (json_valid(`meta`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `mata_kuliahs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mata_kuliahs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_mk` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'master kode like sms1, sms2',
  `nama_mk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `praktikum` tinyint DEFAULT NULL COMMENT 'jumlah sks praktikum',
  `tipe` enum('teori','praktikum','sidang','lab') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'teori' COMMENT 'Jenis mata kuliah: teori, praktikum, sidang, atau lab',
  `sks` int NOT NULL,
  `semester` int NOT NULL,
  `jenis` enum('wajib_nasional','wajib_prodi','pilihan','peminatan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `prodi_id` bigint unsigned DEFAULT NULL,
  `fakultas_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mata_kuliahs_kode_mk_unique` (`kode_mk`),
  KEY `mata_kuliahs_kode_id_index` (`kode_id`),
  KEY `mata_kuliahs_prodi_id_foreign` (`prodi_id`),
  KEY `mata_kuliahs_fakultas_id_foreign` (`fakultas_id`),
  KEY `mata_kuliahs_tipe_index` (`tipe`),
  CONSTRAINT `mata_kuliahs_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`),
  CONSTRAINT `mata_kuliahs_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodis` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `materis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mata_kuliah_id` bigint unsigned NOT NULL,
  `dosen_id` bigint unsigned NOT NULL,
  `pertemuan` int NOT NULL DEFAULT '1',
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `materis_mata_kuliah_id_pertemuan_index` (`mata_kuliah_id`,`pertemuan`),
  KEY `materis_dosen_id_index` (`dosen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `nilai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `krs_id` bigint unsigned NOT NULL,
  `kelas_id` bigint unsigned DEFAULT NULL,
  `nilai_partisipatif` decimal(5,2) DEFAULT NULL,
  `nilai_proyek` decimal(5,2) DEFAULT NULL,
  `nilai_quiz` decimal(5,2) DEFAULT NULL,
  `nilai_tugas` decimal(5,2) DEFAULT NULL,
  `nilai_uts` decimal(5,2) DEFAULT NULL,
  `nilai_uas` decimal(5,2) DEFAULT NULL,
  `nilai_akhir` decimal(5,2) DEFAULT NULL,
  `grade` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bobot` decimal(4,2) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `published_at` timestamp NULL DEFAULT NULL,
  `published_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nilai_krs_id_foreign` (`krs_id`),
  KEY `nilai_kelas_id_foreign` (`kelas_id`),
  KEY `nilai_published_by_foreign` (`published_by`),
  CONSTRAINT `nilai_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nilai_krs_id_foreign` FOREIGN KEY (`krs_id`) REFERENCES `krs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nilai_published_by_foreign` FOREIGN KEY (`published_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `parents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `hubungan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `mahasiswa_id` bigint unsigned DEFAULT NULL,
  `tipe_wali` enum('orang_tua','wali') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'orang_tua',
  `nama_ayah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pendidikan_ayah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan_ayah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agama_ayah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_ayah` text COLLATE utf8mb4_unicode_ci,
  `kota_ayah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan_ayah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `propinsi_ayah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desa_ayah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `handphone_ayah` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_ibu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pendidikan_ibu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan_ibu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agama_ibu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_ibu` text COLLATE utf8mb4_unicode_ci,
  `kota_ibu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan_ibu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `propinsi_ibu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desa_ibu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `handphone_ibu` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_wali` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hubungan_wali` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pendidikan_wali` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan_wali` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agama_wali` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_wali` text COLLATE utf8mb4_unicode_ci,
  `kota_wali` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan_wali` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provinsi_wali` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `handphone_wali` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keluarga` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `desa_wali` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parents_user_id_foreign` (`user_id`),
  KEY `parents_mahasiswa_id_foreign` (`mahasiswa_id`),
  CONSTRAINT `parents_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `parents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `parents_chk_1` CHECK (json_valid(`keluarga`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `payment_proofs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_proofs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned DEFAULT NULL,
  `installment_id` bigint unsigned DEFAULT NULL,
  `uploaded_by` bigint unsigned NOT NULL,
  `transfer_date` date NOT NULL,
  `amount_submitted` bigint unsigned NOT NULL,
  `method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('UPLOADED','APPROVED','REJECTED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UPLOADED',
  `finance_notes` text COLLATE utf8mb4_unicode_ci,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `rejected_at` datetime DEFAULT NULL,
  `student_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_proofs_uploaded_by_foreign` (`uploaded_by`),
  KEY `payment_proofs_approved_by_foreign` (`approved_by`),
  KEY `payment_proofs_status_index` (`status`),
  KEY `payment_proofs_installment_id_status_index` (`installment_id`,`status`),
  KEY `payment_proofs_invoice_id_status_index` (`invoice_id`,`status`),
  CONSTRAINT `payment_proofs_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `payment_proofs_installment_id_foreign` FOREIGN KEY (`installment_id`) REFERENCES `installments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_proofs_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_proofs_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `installment_id` bigint unsigned DEFAULT NULL,
  `proof_id` bigint unsigned NOT NULL,
  `amount_approved` bigint unsigned NOT NULL,
  `paid_date` date NOT NULL,
  `transfer_date` date NOT NULL,
  `approved_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_proof_id_unique` (`proof_id`),
  KEY `payments_approved_by_foreign` (`approved_by`),
  KEY `payments_invoice_id_paid_date_index` (`invoice_id`,`paid_date`),
  KEY `payments_installment_id_index` (`installment_id`),
  CONSTRAINT `payments_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `payments_installment_id_foreign` FOREIGN KEY (`installment_id`) REFERENCES `installments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_proof_id_foreign` FOREIGN KEY (`proof_id`) REFERENCES `payment_proofs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pembayaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembayaran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `semester_id` bigint unsigned DEFAULT NULL,
  `jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `dibayar` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('belum_bayar','sebagian','lunas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_bayar',
  `tanggal_bayar` date DEFAULT NULL,
  `bukti_bayar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pembayaran_mahasiswa_id_foreign` (`mahasiswa_id`),
  KEY `pembayaran_semester_id_foreign` (`semester_id`),
  CONSTRAINT `pembayaran_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pembayaran_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pengajuan_revisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengajuan_revisions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pengajuan_id` bigint unsigned NOT NULL,
  `revision_no` smallint unsigned NOT NULL,
  `signed_doc_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note_from_admin` text COLLATE utf8mb4_unicode_ci,
  `note_from_mahasiswa` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengajuan_revisions_pengajuan_id_revision_no_index` (`pengajuan_id`,`revision_no`),
  CONSTRAINT `pengajuan_revisions_pengajuan_id_foreign` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pengajuans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengajuans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `payload_template` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `generated_doc_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signed_doc_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `rejected_reason` text COLLATE utf8mb4_unicode_ci,
  `revision_no` smallint unsigned NOT NULL DEFAULT '0',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `nomor_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengajuans_approved_by_foreign` (`approved_by`),
  KEY `idx_pengajuans_mhs_status` (`mahasiswa_id`,`status`),
  KEY `idx_pengajuans_jenis` (`jenis`),
  CONSTRAINT `pengajuans_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pengajuans_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengajuans_chk_1` CHECK (json_valid(`payload_template`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pengumumans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengumumans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `target` enum('semua','dosen','mahasiswa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'semua',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pertemuans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pertemuans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_mata_kuliah_id` bigint unsigned DEFAULT NULL,
  `nomor_pertemuan` int unsigned NOT NULL,
  `tipe_pertemuan` enum('kuliah','uts','uas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kuliah' COMMENT 'Meeting type: kuliah (regular), uts (midterm), uas (final)',
  `tanggal` date DEFAULT NULL,
  `topik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `metode_pengajaran` enum('offline','online','asynchronous') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'offline',
  `online_meeting_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `qr_expires_at` datetime DEFAULT NULL,
  `qr_generated_at` datetime DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pertemuans_qr_token_unique` (`qr_token`),
  KEY `pertemuans_kelas_mata_kuliah_id_index` (`kelas_mata_kuliah_id`),
  KEY `pertemuans_kelas_mata_kuliah_id_nomor_pertemuan_index` (`kelas_mata_kuliah_id`,`nomor_pertemuan`),
  KEY `pertemuans_kmk_tipe_nomor_index` (`kelas_mata_kuliah_id`,`tipe_pertemuan`,`nomor_pertemuan`),
  CONSTRAINT `pertemuans_kelas_mata_kuliah_id_foreign` FOREIGN KEY (`kelas_mata_kuliah_id`) REFERENCES `kelas_mata_kuliahs` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `presensis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `presensis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned DEFAULT NULL,
  `kelas_mata_kuliah_id` bigint unsigned DEFAULT NULL,
  `pertemuan` int unsigned DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kontak` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waktu` timestamp NULL DEFAULT NULL,
  `krs_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('hadir','izin','sakit','alpa') COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `student_lat` decimal(10,7) DEFAULT NULL,
  `student_lng` decimal(10,7) DEFAULT NULL,
  `distance_meters` int DEFAULT NULL,
  `presence_mode` enum('offline','online') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason_category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason_detail` text COLLATE utf8mb4_unicode_ci,
  `campus_lat` decimal(10,7) NOT NULL DEFAULT '-6.3112520',
  `campus_lng` decimal(10,7) NOT NULL DEFAULT '106.8111740',
  `radius_meters` int NOT NULL DEFAULT '100',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `presensis_krs_id_foreign` (`krs_id`),
  KEY `presensis_mahasiswa_id_foreign` (`mahasiswa_id`),
  KEY `presensis_kelas_mata_kuliah_id_foreign` (`kelas_mata_kuliah_id`),
  CONSTRAINT `presensis_kelas_mata_kuliah_id_foreign` FOREIGN KEY (`kelas_mata_kuliah_id`) REFERENCES `kelas_mata_kuliahs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `presensis_krs_id_foreign` FOREIGN KEY (`krs_id`) REFERENCES `krs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `presensis_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `prestasi_dokumens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prestasi_dokumens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `prestasi_id` bigint unsigned NOT NULL,
  `jenis` enum('sertifikat','dokumentasi','surat_tugas_lama','pendukung') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sertifikat',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned NOT NULL DEFAULT '0',
  `uploaded_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prestasi_dokumens_uploaded_by_foreign` (`uploaded_by`),
  KEY `prestasi_dokumens_prestasi_id_index` (`prestasi_id`),
  CONSTRAINT `prestasi_dokumens_prestasi_id_foreign` FOREIGN KEY (`prestasi_id`) REFERENCES `prestasis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prestasi_dokumens_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `prestasi_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prestasi_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `prestasi_id` bigint unsigned NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `prestasi_logs_user_id_foreign` (`user_id`),
  KEY `prestasi_logs_prestasi_id_index` (`prestasi_id`),
  KEY `prestasi_logs_action_index` (`action`),
  CONSTRAINT `prestasi_logs_prestasi_id_foreign` FOREIGN KEY (`prestasi_id`) REFERENCES `prestasis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prestasi_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prestasi_logs_chk_1` CHECK (json_valid(`metadata`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `prestasi_surat_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prestasi_surat_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jenis_surat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `format_nomor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_counter` int NOT NULL DEFAULT '0',
  `reset_year` year NOT NULL DEFAULT '2026',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `prestasi_surats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prestasi_surats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `prestasi_id` bigint unsigned NOT NULL,
  `jenis_surat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_surat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_surat` date NOT NULL,
  `penandatangan_nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penandatangan_jabatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penandatangan_nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_backdate` tinyint(1) NOT NULL DEFAULT '0',
  `generated_by` bigint unsigned DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prestasi_surats_nomor_surat_unique` (`nomor_surat`),
  KEY `prestasi_surats_generated_by_foreign` (`generated_by`),
  KEY `prestasi_surats_prestasi_id_index` (`prestasi_id`),
  KEY `prestasi_surats_jenis_surat_index` (`jenis_surat`),
  CONSTRAINT `prestasi_surats_generated_by_foreign` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prestasi_surats_prestasi_id_foreign` FOREIGN KEY (`prestasi_id`) REFERENCES `prestasis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prestasi_surats_chk_1` CHECK (json_valid(`metadata`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `prestasis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prestasis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tipe` enum('pengajuan','pelaporan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pengajuan',
  `pengaju_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pengaju_id` bigint unsigned NOT NULL,
  `nama_kegiatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kegiatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'akademik',
  `tingkat_kegiatan` enum('internal','regional','nasional','internasional') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nasional',
  `tempat_kegiatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `penyelenggara` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `dosen_pendamping_id` bigint unsigned DEFAULT NULL,
  `jenis_prestasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_sertifikat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_reason` text COLLATE utf8mb4_unicode_ci,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `external_ref` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hash_kegiatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prestasis_approved_by_foreign` (`approved_by`),
  KEY `prestasis_pengaju_type_pengaju_id_index` (`pengaju_type`,`pengaju_id`),
  KEY `prestasis_dosen_pendamping_id_index` (`dosen_pendamping_id`),
  KEY `prestasis_tingkat_kegiatan_index` (`tingkat_kegiatan`),
  KEY `prestasis_status_index` (`status`),
  KEY `prestasis_hash_kegiatan_index` (`hash_kegiatan`),
  CONSTRAINT `prestasis_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prestasis_dosen_pendamping_id_foreign` FOREIGN KEY (`dosen_pendamping_id`) REFERENCES `dosens` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prestasis_chk_1` CHECK (json_valid(`tags`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `prodis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prodis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_prodi` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fakultas_id` bigint unsigned DEFAULT NULL,
  `nama_prodi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenjang` enum('D3','S1','S2','S3') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prodis_kode_prodi_unique` (`kode_prodi`),
  KEY `prodis_fakultas_id_foreign` (`fakultas_id`),
  CONSTRAINT `prodis_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `religions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `religions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `religions_code_index` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ruangans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ruangans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_ruangan` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_ruangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gedung` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lantai` int DEFAULT NULL,
  `kapasitas` int NOT NULL DEFAULT '30',
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `kategori_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ruangans_kode_ruangan_unique` (`kode_ruangan`),
  KEY `ruangans_kategori_id_foreign` (`kategori_id`),
  CONSTRAINT `ruangans_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_ruangans` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `semesters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `semesters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_semester` enum('Ganjil','Genap') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_semester_old` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_ajaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('aktif','non-aktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'non-aktif',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `locked_at` datetime DEFAULT NULL,
  `locked_by` bigint unsigned DEFAULT NULL,
  `krs_dapat_diisi` tinyint(1) NOT NULL DEFAULT '0',
  `max_sks_rendah` int NOT NULL DEFAULT '20' COMMENT 'Max SKS untuk IPK < 3.0',
  `max_sks_tinggi` int NOT NULL DEFAULT '24' COMMENT 'Max SKS untuk IPK >= 3.0',
  `krs_mulai` date DEFAULT NULL,
  `krs_selesai` date DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `semesters_nama_tahun_tanggal_unique` (`nama_semester`,`tahun_ajaran`,`tanggal_mulai`),
  KEY `semesters_locked_by_foreign` (`locked_by`),
  CONSTRAINT `semesters_locked_by_foreign` FOREIGN KEY (`locked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `skripsi_guidances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `skripsi_guidances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `skripsi_submission_id` bigint unsigned NOT NULL,
  `dosen_id` bigint unsigned NOT NULL,
  `tanggal_bimbingan` date NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `catatan_dosen` text COLLATE utf8mb4_unicode_ci,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `thesis_guidances_dosen_id_foreign` (`dosen_id`),
  KEY `skripsi_guidances_skripsi_submission_id_foreign` (`skripsi_submission_id`),
  CONSTRAINT `skripsi_guidances_skripsi_submission_id_foreign` FOREIGN KEY (`skripsi_submission_id`) REFERENCES `skripsi_submissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `thesis_guidances_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosens` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `skripsi_revisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `skripsi_revisions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `skripsi_submission_id` bigint unsigned NOT NULL,
  `revision_file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `dosen_notes` text COLLATE utf8mb4_unicode_ci,
  `approved_by_dosen_id` bigint unsigned DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `thesis_revisions_approved_by_dosen_id_foreign` (`approved_by_dosen_id`),
  KEY `skripsi_revisions_skripsi_submission_id_foreign` (`skripsi_submission_id`),
  CONSTRAINT `skripsi_revisions_skripsi_submission_id_foreign` FOREIGN KEY (`skripsi_submission_id`) REFERENCES `skripsi_submissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `thesis_revisions_approved_by_dosen_id_foreign` FOREIGN KEY (`approved_by_dosen_id`) REFERENCES `dosens` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `skripsi_sidang_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `skripsi_sidang_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sidang_registration_id` bigint unsigned NOT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `thesis_sidang_files_sidang_registration_id_foreign` (`sidang_registration_id`),
  CONSTRAINT `thesis_sidang_files_sidang_registration_id_foreign` FOREIGN KEY (`sidang_registration_id`) REFERENCES `skripsi_sidang_registrations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `skripsi_sidang_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `skripsi_sidang_registrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `skripsi_submission_id` bigint unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `verified_by` bigint unsigned DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `thesis_sidang_registrations_verified_by_foreign` (`verified_by`),
  KEY `skripsi_sidang_registrations_skripsi_submission_id_foreign` (`skripsi_submission_id`),
  CONSTRAINT `skripsi_sidang_registrations_skripsi_submission_id_foreign` FOREIGN KEY (`skripsi_submission_id`) REFERENCES `skripsi_submissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `thesis_sidang_registrations_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `skripsi_sidang_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `skripsi_sidang_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `skripsi_submission_id` bigint unsigned NOT NULL,
  `sidang_registration_id` bigint unsigned DEFAULT NULL,
  `tanggal` date NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `ruangan_id` bigint unsigned DEFAULT NULL,
  `ruangan_manual` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pembimbing_id` bigint unsigned NOT NULL,
  `penguji_1_id` bigint unsigned NOT NULL,
  `penguji_2_id` bigint unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `thesis_sidang_schedules_sidang_registration_id_foreign` (`sidang_registration_id`),
  KEY `thesis_sidang_schedules_ruangan_id_foreign` (`ruangan_id`),
  KEY `thesis_sidang_schedules_pembimbing_id_foreign` (`pembimbing_id`),
  KEY `thesis_sidang_schedules_penguji_1_id_foreign` (`penguji_1_id`),
  KEY `thesis_sidang_schedules_penguji_2_id_foreign` (`penguji_2_id`),
  KEY `thesis_sidang_schedules_created_by_foreign` (`created_by`),
  KEY `skripsi_sidang_schedules_skripsi_submission_id_foreign` (`skripsi_submission_id`),
  CONSTRAINT `skripsi_sidang_schedules_skripsi_submission_id_foreign` FOREIGN KEY (`skripsi_submission_id`) REFERENCES `skripsi_submissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `thesis_sidang_schedules_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `thesis_sidang_schedules_pembimbing_id_foreign` FOREIGN KEY (`pembimbing_id`) REFERENCES `dosens` (`id`),
  CONSTRAINT `thesis_sidang_schedules_penguji_1_id_foreign` FOREIGN KEY (`penguji_1_id`) REFERENCES `dosens` (`id`),
  CONSTRAINT `thesis_sidang_schedules_penguji_2_id_foreign` FOREIGN KEY (`penguji_2_id`) REFERENCES `dosens` (`id`) ON DELETE SET NULL,
  CONSTRAINT `thesis_sidang_schedules_ruangan_id_foreign` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `thesis_sidang_schedules_sidang_registration_id_foreign` FOREIGN KEY (`sidang_registration_id`) REFERENCES `skripsi_sidang_registrations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `skripsi_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `skripsi_submissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `semester_id` bigint unsigned DEFAULT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_proposal` text COLLATE utf8mb4_unicode_ci,
  `proposal_file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requested_supervisor_id` bigint unsigned DEFAULT NULL,
  `approved_supervisor_id` bigint unsigned DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PROPOSAL_DRAFT',
  `total_bimbingan` int unsigned NOT NULL DEFAULT '0',
  `logbook_file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logbook_original_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logbook_uploaded_at` timestamp NULL DEFAULT NULL,
  `eligible_for_sidang_at` timestamp NULL DEFAULT NULL,
  `revision_approved_at` timestamp NULL DEFAULT NULL,
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `thesis_submissions_mahasiswa_id_foreign` (`mahasiswa_id`),
  KEY `thesis_submissions_semester_id_foreign` (`semester_id`),
  KEY `thesis_submissions_requested_supervisor_id_foreign` (`requested_supervisor_id`),
  KEY `thesis_submissions_approved_supervisor_id_foreign` (`approved_supervisor_id`),
  KEY `thesis_submissions_reviewed_by_foreign` (`reviewed_by`),
  CONSTRAINT `thesis_submissions_approved_supervisor_id_foreign` FOREIGN KEY (`approved_supervisor_id`) REFERENCES `dosens` (`id`) ON DELETE SET NULL,
  CONSTRAINT `thesis_submissions_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `thesis_submissions_requested_supervisor_id_foreign` FOREIGN KEY (`requested_supervisor_id`) REFERENCES `dosens` (`id`) ON DELETE SET NULL,
  CONSTRAINT `thesis_submissions_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `thesis_submissions_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `npm` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prodi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `angkatan` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_npm_unique` (`npm`),
  KEY `students_user_id_foreign` (`user_id`),
  KEY `students_npm_index` (`npm`),
  CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `system_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tugas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tugas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mata_kuliah_id` bigint unsigned DEFAULT NULL,
  `kelas_id` bigint unsigned DEFAULT NULL,
  `pertemuan` int NOT NULL DEFAULT '1',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `due_date` datetime DEFAULT NULL,
  `dosen_id` bigint unsigned DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_score` int DEFAULT NULL,
  `submission_type` enum('pdf','word','excel','text','any') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'any',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tugas_kelas_id_pertemuan_index` (`kelas_id`,`pertemuan`),
  KEY `tugas_mata_kuliah_id_index` (`mata_kuliah_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tugas_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tugas_submissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tugas_id` bigint unsigned NOT NULL,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text_submission` text COLLATE utf8mb4_unicode_ci,
  `comments` text COLLATE utf8mb4_unicode_ci,
  `score` int DEFAULT NULL,
  `graded_by` bigint unsigned DEFAULT NULL,
  `graded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tugas_submissions_tugas_id_mahasiswa_id_index` (`tugas_id`,`mahasiswa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `uploads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `uploads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uploadable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploadable_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `folder` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` bigint unsigned NOT NULL,
  `disk` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 's3',
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uploads_uploadable_type_uploadable_id_index` (`uploadable_type`,`uploadable_id`),
  KEY `uploads_user_id_foreign` (`user_id`),
  CONSTRAINT `uploads_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mahasiswa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `wisuda_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wisuda_batches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_batch` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_mulai` time NOT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wisuda_batches_created_by_foreign` (`created_by`),
  CONSTRAINT `wisuda_batches_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `wisuda_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wisuda_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `wisuda_registration_id` bigint unsigned NOT NULL,
  `file_type` enum('surat_penyerahan_skripsi','penyerahan_buku','keterangan_turnitin','pas_foto') COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wisuda_documents_wisuda_registration_id_foreign` (`wisuda_registration_id`),
  CONSTRAINT `wisuda_documents_wisuda_registration_id_foreign` FOREIGN KEY (`wisuda_registration_id`) REFERENCES `wisuda_registrations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `wisuda_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wisuda_registrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` bigint unsigned NOT NULL,
  `skripsi_submission_id` bigint unsigned NOT NULL,
  `wisuda_batch_id` bigint unsigned DEFAULT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_aktif` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected','scheduled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `rejection_note` text COLLATE utf8mb4_unicode_ci,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wisuda_registrations_skripsi_submission_id_foreign` (`skripsi_submission_id`),
  KEY `wisuda_registrations_wisuda_batch_id_foreign` (`wisuda_batch_id`),
  KEY `wisuda_registrations_reviewed_by_foreign` (`reviewed_by`),
  KEY `wisuda_registrations_mahasiswa_id_status_index` (`mahasiswa_id`,`status`),
  CONSTRAINT `wisuda_registrations_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wisuda_registrations_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `wisuda_registrations_skripsi_submission_id_foreign` FOREIGN KEY (`skripsi_submission_id`) REFERENCES `skripsi_submissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wisuda_registrations_wisuda_batch_id_foreign` FOREIGN KEY (`wisuda_batch_id`) REFERENCES `wisuda_batches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2026_01_13_091431_add_role_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2026_01_15_000001_create_mata_kuliahs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2026_01_15_000002_create_kelas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2026_01_15_000003_create_jadwals_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2026_01_15_030141_create_admins_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2026_01_15_030149_create_dosens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2026_01_15_030150_create_mahasiswas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2026_01_15_030151_create_parents_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2026_01_15_030152_create_activity_logs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2026_01_15_030153_create_semesters_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2026_01_15_030153_create_system_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2026_01_15_030154_create_kelas_mata_kuliahs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2026_01_15_030156_create_krs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2026_01_15_030157_create_nilai_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2026_01_15_030158_create_presensis_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2026_01_19_000001_create_jadwal_reschedules_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2026_01_19_000002_add_apply_date_to_jadwal_reschedules_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2026_01_19_000003_create_jadwal_exceptions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2026_01_19_040244_create_kuesioner_aktivasi_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2026_01_19_040252_create_pembayaran_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2026_01_19_040301_add_status_to_mahasiswas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2026_01_19_040556_add_ambil_mk_to_krs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2026_01_19_042201_add_is_active_to_semesters_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2026_01_19_044316_add_krs_settings_to_semesters_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2026_01_19_130000_add_qr_to_kelas_mata_kuliahs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2026_01_20_024300_add_fields_to_presensis_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2026_01_20_080000_add_semester_to_mahasiswas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2026_01_20_090000_add_mata_kuliah_ids_to_dosens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2026_01_20_100000_update_jenis_mata_kuliah_enum',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2026_01_20_101500_add_pendidikan_to_dosens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2026_01_21_000001_add_kode_id_to_mata_kuliahs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2026_01_21_000002_add_praktikum_to_mata_kuliahs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2026_01_21_032900_add_hari_to_kelas_mata_kuliahs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2026_01_21_033208_create_kelas_reschedules_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2026_01_21_033546_add_new_kelas_to_kelas_reschedules_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2026_01_21_033845_add_mata_kuliah_id_to_krs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2026_01_21_050000_add_times_to_kelas_mata_kuliahs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2026_01_21_115547_create_dosen_pa_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2026_01_21_120000_update_krs_table_to_current_system',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2026_01_22_000002_add_hubungan_pekerjaan_to_parents',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2026_01_22_082913_add_data_pribadi_to_mahasiswas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2026_01_22_084657_add_orang_tua_to_parents_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2026_01_22_084707_add_asal_sekolah_to_mahasiswas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2026_01_23_041401_create_academic_events_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2026_01_23_065016_align_academic_event_types',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2026_01_23_120000_add_pertemuan_to_presensis_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2026_01_26_000001_add_new_survey_flag_to_mahasiswas',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2026_01_26_000002_create_kuesioner_mahasiswa_baru_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2026_01_26_000003_add_q_columns_to_kuesioner_mahasiswa_baru',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2026_01_26_000004_add_meta_columns_to_kuesioner_mahasiswa_baru',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2026_01_28_000001_create_tugas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2026_01_28_000002_create_tugas_submissions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2026_01_29_000000_rename_npm_to_nim_in_mahasiswas',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2026_01_29_010000_convert_nama_semester_to_enum',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2026_01_29_020000_add_unique_index_semesters',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2026_01_30_000002_create_religions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (60,'2026_01_30_071940_add_desa_provinsi_to_mahasiswas',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2026_01_30_072052_drop_propinsi_from_mahasiswas',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (62,'2026_01_30_081140_add_document_fields_to_mahasiswas',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2026_01_30_084701_add_wali_fields_to_parents',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (64,'2026_01_30_092328_add_keluarga_to_parents',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2026_01_30_110441_add_ktp_address_to_mahasiswas',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (66,'2026_01_30_120000_add_metode_pengajaran_to_kelas_mata_kuliahs',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (67,'2026_01_30_130000_create_pengumumans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (68,'2026_02_03_020911_create_prodis_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (69,'2026_02_03_020920_create_fakultas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (70,'2026_02_03_023543_update_mata_kuliahs_add_prodi_fakultas_relations',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (71,'2026_02_03_024544_add_foreign_key_to_prodis_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (72,'2026_02_03_033934_add_qr_current_pertemuan_to_kelas_mata_kuliahs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (73,'2026_02_03_040919_create_jadwal_proposals_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (74,'2026_02_03_040928_create_jadwal_approvals_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (75,'2026_02_03_044000_add_fields_to_dosens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (76,'2026_02_03_045000_create_jadwal_generate_logs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (77,'2026_02_03_082432_add_target_to_pengumumans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (78,'2026_02_03_085058_create_ruangans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (79,'2026_02_03_091447_add_ruangan_id_to_jadwals_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (80,'2026_02_03_091505_add_ruangan_id_to_jadwal_proposals_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (81,'2026_02_03_091540_add_ruangan_id_to_kelas_mata_kuliahs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (82,'2026_02_04_034520_add_desa_columns_to_parents_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (83,'2026_02_04_041443_create_jam_perkuliahan_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (84,'2026_02_04_042815_split_parent_address_columns',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (85,'2026_02_04_083453_add_universitas_to_dosens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (86,'2026_02_05_100000_create_materis_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (87,'2026_02_05_100001_update_tugas_table_for_sharing',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (88,'2026_02_05_164000_add_columns_to_ruangans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (89,'2026_02_05_164500_fix_kelas_dosen_foreign_key',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (90,'2026_02_06_064041_create_dosen_availabilities_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (91,'2026_02_06_070353_add_kecamatan_columns_to_mahasiswas_and_parents_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (92,'2026_02_06_093010_create_dosen_availability_checks_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (93,'2026_02_09_000001_create_import_logs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (94,'2026_02_09_000001_update_nilai_table_add_components',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (95,'2026_02_09_000002_create_bobot_penilaian_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (96,'2026_02_09_000003_add_published_status_to_nilai_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (97,'2026_02_10_070112_create_pengajuans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (98,'2026_02_11_000001_create_pertemuans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (99,'2026_02_11_033628_add_pertemuan_to_presensis_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (100,'2026_02_11_034943_create_dokumen_kelas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (101,'2026_02_11_035309_add_asynchronous_file_to_kelas_mata_kuliahs',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (102,'2026_02_12_041345_add_approval_fields_to_pengajuans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (103,'2026_02_12_080836_add_metode_columns_to_kelas_reschedules_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (104,'2026_02_13_000001_add_semester_transition_fields',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (105,'2026_02_18_000000_fix_invoices_student_foreign_to_mahasiswas',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (106,'2026_02_18_000001_add_role_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (107,'2026_02_18_000001_fix_installment_requests_student_foreign_to_mahasiswas',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (108,'2026_02_18_000002_create_students_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (109,'2026_02_18_000003_create_invoices_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (110,'2026_02_18_000004_create_installment_requests_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (111,'2026_02_18_000005_create_installments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (112,'2026_02_18_000006_create_payment_proofs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (113,'2026_02_18_000007_create_payments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (114,'2026_02_18_000008_create_audit_logs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (115,'2026_02_18_021638_make_user_id_nullable_in_parents_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (116,'2026_02_18_022023_make_mahasiswa_id_nullable_in_parents_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (117,'2026_02_19_000001_drop_absen_password_hash_from_dosens',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (118,'2026_02_19_050352_add_kuota_to_dosens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (119,'2026_02_19_065019_add_online_meeting_link_to_pertemuans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (120,'2026_02_20_000001_add_metode_pengajaran_to_pertemuans',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (121,'2026_02_20_000002_add_absen_password_hash_to_dosens',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (122,'2026_02_20_000003_create_dosen_attendances_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (123,'2026_02_23_100000_add_location_fields_to_presensis_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (124,'2026_02_25_022611_add_submission_type_to_tugas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (125,'2026_02_25_022640_add_text_submission_to_tugas_submissions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (126,'2026_02_25_024708_drop_assignments_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (127,'2026_02_25_080555_add_availability_tracking_to_jadwal_proposals_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (128,'2026_02_25_080641_add_availability_tracking_to_jadwals_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (129,'2026_02_26_033215_make_schedule_fields_nullable_in_kelas_mata_kuliahs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (130,'2026_02_27_000001_create_mata_kuliah_semesters_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (131,'2026_02_27_000002_add_lock_fields_to_semesters_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (132,'2026_02_27_000003_add_audit_fields_to_audit_logs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (133,'2026_02_28_000001_add_tipe_pertemuan_to_pertemuans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (134,'2026_02_28_100000_add_indexes_and_audit_to_academic_events',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (135,'2026_02_28_200000_create_dosen_mata_kuliah_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (136,'2026_03_03_000001_upgrade_pengajuans_workflow',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (137,'2026_03_03_000002_create_pengajuan_revisions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (138,'2026_03_03_035729_add_is_dokumen_unlocked_to_mahasiswas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (139,'2026_03_04_000001_create_internships_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (140,'2026_03_05_063714_add_semester_mahasiswa_to_internships_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (141,'2026_03_06_000001_update_internships_for_full_workflow',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (142,'2026_03_11_073140_drop_unused_columns_from_parents_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (143,'2026_03_12_000001_create_thesis_submissions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (144,'2026_03_12_000002_create_thesis_guidances_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (145,'2026_03_12_000003_create_thesis_sidang_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (146,'2026_03_12_000004_create_thesis_sidang_schedules_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (147,'2026_03_12_000005_create_thesis_revisions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (148,'2026_03_31_040610_create_uploads_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (149,'2026_03_31_100000_add_logbook_file_to_thesis_submissions',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (150,'2026_04_01_035237_rename_thesis_to_skripsi_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (151,'2026_04_13_120000_add_fakultas_id_to_dosens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (152,'2026_04_15_000000_fix_installment_requests_foreign_key_properly',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (153,'2026_04_15_034718_drop_fakultas_id_from_prodis_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (154,'2026_04_15_add_online_meeting_link_to_kelas_mata_kuliahs',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (155,'2026_04_16_000001_create_kategori_ruangans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (156,'2026_04_16_000002_add_kategori_id_to_ruangans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (157,'2026_04_16_062753_update_krs_status_enum_to_sudah_submit',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (158,'2026_04_16_add_tipe_to_mata_kuliahs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (159,'2026_04_17_070255_restore_fakultas_id_to_prodis_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (160,'2026_04_20_000001_create_kelas_perkuliahans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (161,'2026_04_20_000002_add_kelas_perkuliahan_id_to_related_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (162,'2026_04_20_000003_add_email_columns_to_mahasiswas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (163,'2026_04_20_000004_create_email_blast_logs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (164,'2026_04_20_050133_drop_kapasitas_from_kelas_perkuliahans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (165,'2026_04_22_033524_create_email_outboxes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (166,'2026_04_24_150000_add_va_fields_to_invoices_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (167,'2026_04_29_140500_add_auto_generated_from_krs_to_invoices_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (168,'2026_04_30_115430_add_tahun_ajaran_to_krs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (169,'2026_04_30_115938_drop_semester_id_from_krs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (170,'2026_04_30_140000_add_mahasiswa_class_assignment_fields',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (171,'2026_05_04_000001_refactor_kelas_perkuliahan_to_angkatan',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (172,'2026_05_05_000001_add_credential_recipient_type_to_email_blast_logs',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (173,'2026_05_05_000002_add_credential_type_to_email_outboxes',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (174,'2026_05_06_remove_section_from_kelas',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (175,'2026_05_08_000001_create_prestasi_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (176,'2026_05_21_000001_create_wisuda_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (177,'2026_06_02_115159_add_semester_mahasiswa_to_kuesioner_aktivasi_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (178,'2026_06_02_152544_create_internship_types_table_and_alter_internships',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (179,'2026_06_02_164047_create_permission_tables',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (180,'2026_06_03_000001_enhance_audit_logs_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (181,'2026_06_03_000002_create_impersonation_logs_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (182,'2026_06_04_000001_fix_invoices_foreign_key_properly',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (183,'2026_07_14_162151_drop_mata_kuliah_ids_from_dosens_table',7);
