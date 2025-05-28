-- MariaDB dump 10.19  Distrib 10.11.10-MariaDB, for debian-linux-gnu (aarch64)
--
-- Host: localhost    Database: db
-- ------------------------------------------------------
-- Server version	10.11.10-MariaDB-ubu2204-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES
('laravel_cache_356a192b7913b04c54574d18c28d46e6395428ab','i:1;',1748355214),
('laravel_cache_356a192b7913b04c54574d18c28d46e6395428ab:timer','i:1748355214;',1748355214),
('laravel_cache_livewire-rate-limiter:39ca9ec65b16f4d05e61d1a56f54ade350eee77b','i:1;',1748376267),
('laravel_cache_livewire-rate-limiter:39ca9ec65b16f4d05e61d1a56f54ade350eee77b:timer','i:1748376267;',1748376267);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_user_id_foreign` (`user_id`),
  KEY `comments_post_id_foreign` (`post_id`),
  CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `follows`
--

DROP TABLE IF EXISTS `follows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `follows` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `followable_type` varchar(255) NOT NULL,
  `followable_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `follows_user_id_foreign` (`user_id`),
  KEY `follows_followable_type_followable_id_index` (`followable_type`,`followable_id`),
  CONSTRAINT `follows_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `follows`
--

LOCK TABLES `follows` WRITE;
/*!40000 ALTER TABLE `follows` DISABLE KEYS */;
/*!40000 ALTER TABLE `follows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `friendships`
--

DROP TABLE IF EXISTS `friendships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `friendships` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `friend_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `friendships_user_id_friend_id_unique` (`user_id`,`friend_id`),
  KEY `friendships_friend_id_foreign` (`friend_id`),
  CONSTRAINT `friendships_friend_id_foreign` FOREIGN KEY (`friend_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `friendships_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `friendships`
--

LOCK TABLES `friendships` WRITE;
/*!40000 ALTER TABLE `friendships` DISABLE KEYS */;
/*!40000 ALTER TABLE `friendships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `games` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `stadium_id` bigint(20) unsigned NOT NULL,
  `home_team_id` bigint(20) unsigned NOT NULL,
  `away_team_id` bigint(20) unsigned NOT NULL,
  `home_score` int(11) DEFAULT NULL,
  `away_score` int(11) DEFAULT NULL,
  `match_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `games_stadium_id_foreign` (`stadium_id`),
  KEY `games_home_team_id_foreign` (`home_team_id`),
  KEY `games_away_team_id_foreign` (`away_team_id`),
  CONSTRAINT `games_away_team_id_foreign` FOREIGN KEY (`away_team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `games_home_team_id_foreign` FOREIGN KEY (`home_team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `games_stadium_id_foreign` FOREIGN KEY (`stadium_id`) REFERENCES `stadia` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `games`
--

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
INSERT INTO `games` VALUES
(1,1,1,2,2,0,'2025-05-14','2025-05-14 13:25:29','2025-05-14 13:25:29');
/*!40000 ALTER TABLE `games` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `likes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `likes_user_id_foreign` (`user_id`),
  KEY `likes_post_id_foreign` (`post_id`),
  CONSTRAINT `likes_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes`
--

LOCK TABLES `likes` WRITE;
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
/*!40000 ALTER TABLE `likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2025_05_14_091747_create_stadia_table',1),
(5,'2025_05_14_091758_create_teams_table',1),
(6,'2025_05_14_091834_create_games_table',1),
(7,'2025_05_14_091845_create_posts_table',1),
(8,'2025_05_14_091850_create_comments_table',1),
(9,'2025_05_14_091857_create_likes_table',1),
(10,'2025_05_14_091860_create_visits_table',1),
(11,'2025_05_14_091901_create_friendships_table',1),
(12,'2025_05_14_091905_create_notifications_table',1),
(13,'2025_05_14_093409_create_personal_access_tokens_table',1),
(14,'2025_05_14_111701_add_role_to_users_table',2),
(15,'2025_05_14_123605_add_profile_image_to_users_table',3),
(16,'2025_05_14_123625_add_image_to_stadiums_table',4),
(17,'2025_05_14_123640_add_image_to_posts_table',4),
(18,'2025_05_14_123657_add_logo_url_to_teams_table',4),
(19,'2025_05_14_131015_add_location_to_stadia_table',5),
(20,'2025_05_21_114815_create_follows_table',6),
(21,'2025_05_21_115221_add_images_to_teams_table',6),
(23,'2025_05_21_115331_add_images_to_stadia_table',7),
(24,'2025_05_21_115517_add_username_and_banner_to_users_table',8),
(25,'2025_05_21_120012_add_unique_index_to_usernames',9);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `sender_id` bigint(20) unsigned NOT NULL,
  `type` enum('visit','comment') NOT NULL,
  `game_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  KEY `notifications_sender_id_foreign` (`sender_id`),
  KEY `notifications_game_id_foreign` (`game_id`),
  KEY `notifications_post_id_foreign` (`post_id`),
  CONSTRAINT `notifications_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES
(1,'App\\Models\\User',1,'api-token','85231501dc7414872950ac4416b0b9a1261439eb62e0dd1a6b6bb99a1a5f5279','[\"*\"]','2025-05-27 12:12:42',NULL,'2025-05-14 07:49:59','2025-05-27 12:12:42');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `game_id` bigint(20) unsigned NOT NULL,
  `content` text NOT NULL,
  `image` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `posts_user_id_foreign` (`user_id`),
  KEY `posts_game_id_foreign` (`game_id`),
  CONSTRAINT `posts_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES
('fOs0LiejEzwldBAWEQT4kfCGIEcgZzK2jKZmU4TM',1,'172.18.0.5','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 OPR/119.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiOW5mZllBQ0YxbUZ2VmFWd0ZoeGptVE1NbHFNeUpDNGNCZXBTR3VGMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDQ6Imh0dHBzOi8vYmFja2VuZC5kZGV2LnNpdGUvYWRtaW4vdXNlcnMvaW52aXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiR3azE3U0xncEd5QTRzSnBIUlo1T2l1cjNBaHRLSEYwSWExT0djbGx2VnBNNnRubGsxOEQ1eSI7fQ==',1748376218),
('vA8BZ301L4hVeJ9DKPVXbEOIrNCyI8pRP7MVHNgV',1,'172.18.0.5','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 OPR/119.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoidUNjOVJVUnNJSlpIU1RWaXZTbFRsZ3pRbm1DeXVETm1pUjNpNUM3YSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vYmFja2VuZC5kZGV2LnNpdGUvYWRtaW4vcG9zdHMiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkd2sxN1NMZ3BHeUE0c0pwSFJaNU9pdXIzQWh0S0hGMElhMU9HY2xsdlZwTTZ0bmxrMThENXkiO3M6ODoiZmlsYW1lbnQiO2E6MDp7fX0=',1748356041);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stadia`
--

DROP TABLE IF EXISTS `stadia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stadia` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `image` varchar(191) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `location` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`location`)),
  `banner_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stadia`
--

LOCK TABLES `stadia` WRITE;
/*!40000 ALTER TABLE `stadia` DISABLE KEYS */;
INSERT INTO `stadia` VALUES
(1,'Test-Arena','Test',NULL,10000,'2025-05-14 13:20:32','2025-05-14 13:20:32','{\"lat\":\"0\",\"lng\":\"0\"}',NULL);
/*!40000 ALTER TABLE `stadia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `league` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
INSERT INTO `teams` VALUES
(1,'KAA Gent','teams/01JW91D2855ZQW6KVP348C1QZP.png','Belgian Pro Legue','2025-05-14 13:22:00','2025-05-27 12:12:36',NULL),
(2,'Racing Genk','teams/01JV7PC7VF07AF0HTY59YEC84D.png','Belgian Pro League','2025-05-14 13:24:58','2025-05-14 13:24:58',NULL);
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `profile_image` varchar(191) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=9864401867 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'SUPER_ADMIN','super-admin','test2@example.com','users/profile-image/MWHd4ZHOxi6O2SPcKoDazsjhXOeWUzd4QPMJxJKD.png','users/banner-image/iSB04xGqttsszPUFVI29NAbVxDiQzqkw0Gg6CWbM.png','2025-05-21 09:31:10','$2y$12$wk17SLgpGyA4sJpHRZ5Oiur3AhtKHF0Ia1OGcllvVpM6tnlk18D5y','qmRxb5MqIlyUM1BmBFqGZX0ejwHbPfhYnVD1sdN3VoZLBYjTF6u68QbUCevl','2025-05-14 07:49:58','2025-05-27 09:51:46','super_admin'),
(3,'ADMIN',NULL,'test@example.com','',NULL,NULL,'$2y$12$ay6SgdpLtXQno5/WPbVIj.jxO9uPgtgYHHUadZq5RFHEkmlbYuVXG',NULL,'2025-05-14 09:57:12','2025-05-14 11:35:33','admin'),
(1029938812,'Quentin Rath','test','sophia.kautzer@example.com',NULL,NULL,NULL,'$2y$12$8/EC6DM1nV38W5Bk2lz5f.AFYdiFL0XkyriYi6bOM/KWfJejzhCgi',NULL,'2025-05-14 11:58:43','2025-05-14 11:58:43','user'),
(1146858053,'Lucie Wuckert',NULL,'breitenberg.raina@example.org',NULL,NULL,NULL,'$2y$12$ULAytOaigb5zcgDuAw2.quyGtq4oYqfleuHeUQk6Dx9YqX8ZjqtfO',NULL,'2025-05-14 11:58:36','2025-05-14 11:58:36','user'),
(1217274709,'Mr. Vladimir Lowe',NULL,'anna84@example.org',NULL,NULL,NULL,'$2y$12$7DVgG1PmbZ0OYoU17zzUYOa9vWz9g7Ax2HHbD0Q3LPTuI2zfr745u',NULL,'2025-05-14 11:58:39','2025-05-14 11:58:39','user'),
(1270954019,'Dr. Isabelle Wisozk III',NULL,'cabshire@example.org',NULL,NULL,NULL,'$2y$12$9E.tvfLMK.XtUUhVgilZJef166B13Gujl1BBpr69UV2eA0CNd11Ae',NULL,'2025-05-14 11:58:37','2025-05-14 11:58:37','user'),
(1411147901,'Gustave Douglas V',NULL,'casper.sandra@example.org',NULL,NULL,NULL,'$2y$12$MqMFUA80x8rudXb8DqaRlernMpWDpPQCWe.kn0kjZlynNyBhPsutC',NULL,'2025-05-14 11:58:38','2025-05-14 11:58:38','user'),
(1495507633,'Dr. Savannah O\'Kon',NULL,'fmann@example.net',NULL,NULL,NULL,'$2y$12$YWZ7pKASjcc9nRSaPrOPKe.s2N9fFmXi.zcDNniFM7ZTbxBKFr7Ju',NULL,'2025-05-14 11:58:38','2025-05-14 11:58:38','user'),
(1916577810,'Dovie Kuvalis',NULL,'bernice.turcotte@example.com',NULL,NULL,NULL,'$2y$12$FpG7w/1Cl7d4Gnv4rvJ7Guy6.qNYlI78Sll.OMDt3yMLd2M5djwOa',NULL,'2025-05-14 11:58:38','2025-05-14 11:58:38','user'),
(2090943087,'Corine Hamill',NULL,'korey66@example.com',NULL,NULL,NULL,'$2y$12$9NNxQX75j2CJFrp2rYuwSOj3mlDarsYqPwRzJl54dZ3u/Hrcxc4ZG',NULL,'2025-05-14 11:58:40','2025-05-14 11:58:40','user'),
(2201101170,'Stella Wuckert',NULL,'lang.manley@example.net',NULL,NULL,NULL,'$2y$12$.8o7ReFz56Cugvh.JWCYG.Z3ykkV.HpmZD.0ZieXTdtXRYEYyEYdy',NULL,'2025-05-14 11:58:41','2025-05-14 11:58:41','user'),
(2276801054,'Skylar Marks',NULL,'rogahn.sylvia@example.org',NULL,NULL,NULL,'$2y$12$Na5j7A.iFfiuQVMUR0W3L.CEv3bz7L31K6kONIFnRIx4aFxCfWG8e',NULL,'2025-05-14 11:58:36','2025-05-14 11:58:36','user'),
(2704707876,'Reymundo Heidenreich II',NULL,'maddison.nolan@example.net',NULL,NULL,NULL,'$2y$12$shJXz0D0vQVENvgI7zNHGu5VdrElIJqQvpfibhYeVaDp8g4BwApeS',NULL,'2025-05-14 11:58:37','2025-05-14 11:58:37','user'),
(3089374936,'Ms. Adah Herzog II',NULL,'hamill.missouri@example.net',NULL,NULL,NULL,'$2y$12$i96TbujhoGBtA6hvdfxf7OWVRhja62Hzr55PxCDG9NYU9we4vKEkK',NULL,'2025-05-14 11:58:41','2025-05-14 11:58:41','user'),
(3143962197,'Fausto Yundt',NULL,'jfahey@example.com',NULL,NULL,NULL,'$2y$12$BuhSGqQXPBsYuF3rRkiDleHN7aJ79eX0ZDDinhyRhrSWCMm1GMk0S',NULL,'2025-05-14 11:58:44','2025-05-14 11:58:44','user'),
(3219449671,'Yolanda Ondricka II',NULL,'charlotte92@example.org',NULL,NULL,NULL,'$2y$12$RNPiocndLj/EsSg7auDMaeB3ZCdZNbuAmEBfrBFlsns2H0QijYvRe',NULL,'2025-05-14 11:58:39','2025-05-14 11:58:39','user'),
(3306163824,'Horacio Armstrong',NULL,'heffertz@example.org',NULL,NULL,NULL,'$2y$12$x8ke1B12p9K8O.Vz704Zl.C7ne9BF0Sei5Nd3VJ7nstJ0WEcyNmqu',NULL,'2025-05-14 11:58:43','2025-05-14 11:58:43','user'),
(3333551298,'Josh Powlowski',NULL,'bart.schmeler@example.net',NULL,NULL,NULL,'$2y$12$ynSVSy6QFL8vK.eQti/SFOKYtz/bVv8cXZmjEF2P0y0G4Lxwop3Iq',NULL,'2025-05-14 11:58:40','2025-05-14 11:58:40','user'),
(3449008604,'Forest Fisher',NULL,'isaias38@example.com',NULL,NULL,NULL,'$2y$12$d3PkugQNAVmss9VMtZvbqunInqlgOnf8PEwasMrVs5ioPnuc2Awum',NULL,'2025-05-14 11:58:41','2025-05-14 11:58:41','user'),
(3542991636,'Dr. Thomas Cassin II',NULL,'yarmstrong@example.com',NULL,NULL,NULL,'$2y$12$wPlIDKXtG0R/w12rw9BLi.97AhWdITRme4VZDgt6y5TOjajU1aZVW',NULL,'2025-05-14 11:58:43','2025-05-14 11:58:43','user'),
(3931547058,'Prof. Marshall Hirthe II',NULL,'vern.beahan@example.com',NULL,NULL,NULL,'$2y$12$tbuBwxSDVqYI4qbKbKU2JeyvpsRPBXM8f0JozI3OlivIgdpHnz7cG',NULL,'2025-05-14 11:58:37','2025-05-14 11:58:37','user'),
(4078219653,'Prof. Thomas Feil PhD',NULL,'blanca90@example.net',NULL,NULL,NULL,'$2y$12$4vefAio3uV2dxbmnszh7T.QyGEcR8rNT04CK4lViCNf9KFLyhvhLi',NULL,'2025-05-14 11:58:39','2025-05-14 11:58:39','user'),
(4083227866,'Hulda Jacobi Sr.',NULL,'danial99@example.org',NULL,NULL,NULL,'$2y$12$GGfkZxOFYh53TvTT6ClZvOjZO4dBExedzPTVnfu1ToPpGVA97.5b6',NULL,'2025-05-14 11:58:40','2025-05-14 11:58:40','user'),
(4401501976,'Karelle Krajcik',NULL,'ekassulke@example.org',NULL,NULL,NULL,'$2y$12$UtTbTTHkFqER3t1LUrjUCeIeFnUVPtKgHfb2OySTr6NI762HFiZpW',NULL,'2025-05-14 11:58:43','2025-05-14 11:58:43','user'),
(4444726676,'Mara Reynolds',NULL,'von.rhea@example.com',NULL,NULL,NULL,'$2y$12$cUagr3F8mJVSTfYigTjVNeKDAxrMI0nDLNDmick5.vilxX/82dihK',NULL,'2025-05-14 11:58:42','2025-05-14 11:58:42','user'),
(4899924255,'Adele Marquardt',NULL,'shaun23@example.net',NULL,NULL,NULL,'$2y$12$w/wj9FqUyKOgzOHMjtj/EO7ttkr.Z2QAB0O.3spYBi08kTBayhFra',NULL,'2025-05-14 11:58:44','2025-05-14 11:58:44','user'),
(5141072182,'Anabelle Hodkiewicz',NULL,'blick.remington@example.net',NULL,NULL,NULL,'$2y$12$IHjRorT6fMR6WzuYNTD.FepsBWzuNhNyc3K2eZS.PXWtH/sWdCiXC',NULL,'2025-05-14 11:58:43','2025-05-14 11:58:43','user'),
(5226405061,'Mr. Wellington Emard',NULL,'odickens@example.com',NULL,NULL,NULL,'$2y$12$iwREMEyt/6re19hv93QTeeDpVXYi5wPIT//nG7ILLcumbbAyCHQTC',NULL,'2025-05-14 11:58:40','2025-05-14 11:58:40','user'),
(5360620557,'Lyda Lowe',NULL,'reichel.meda@example.net',NULL,NULL,NULL,'$2y$12$jbyOv/Aj2pGaxXFWTNoCyuZN8dua30ybtM0TZZkydRIOifm4ExEJW',NULL,'2025-05-14 11:58:41','2025-05-14 11:58:41','user'),
(5504306255,'Mrs. Georgiana Kling',NULL,'rory56@example.com',NULL,NULL,NULL,'$2y$12$lCSrbk3n5Ko5yI/EdZ32iObceY9alNRr7zEfsXnTtW8mUiFT18b5y',NULL,'2025-05-14 11:58:39','2025-05-14 11:58:39','user'),
(5690236951,'Dr. Gillian Kirlin',NULL,'jkulas@example.org',NULL,NULL,NULL,'$2y$12$AC30N/3djAWaVE.2CLTy.eWSVhaLSJgIIiPHAxZt8TIlC78FmnZ72',NULL,'2025-05-14 11:58:43','2025-05-14 11:58:43','user'),
(5712017307,'Ernestina Barrows',NULL,'tod55@example.org',NULL,NULL,NULL,'$2y$12$0bHOYUqx6Y8Pw45C89IUgOtPUAPLcaP7cfaw7yerOCMYD74NdWuri',NULL,'2025-05-14 11:58:38','2025-05-14 11:58:38','user'),
(6007678880,'Jaleel Swift',NULL,'qwilderman@example.net',NULL,NULL,NULL,'$2y$12$ApYKt0H8Yrzznlde5gLfXuXchygBR5JER.1H6oEHWtX3FpJojU0hW',NULL,'2025-05-14 11:58:36','2025-05-14 11:58:36','user'),
(6961019485,'Johan Swaniawski I',NULL,'kristopher39@example.net',NULL,NULL,NULL,'$2y$12$xv6XiGHumTdJAhgFlvf1wuPg2EoLLdUwFmhoUq7j1SpMydqkkxqxi',NULL,'2025-05-14 11:58:45','2025-05-14 11:58:45','user'),
(7155003031,'Prof. Ursula Hessel',NULL,'howell.ashtyn@example.com',NULL,NULL,NULL,'$2y$12$JueAFW6zY1SlKS.ti92VX.C2LS8oK7ccnukkT7wAGoh1kSmOf1MIm',NULL,'2025-05-14 11:58:40','2025-05-14 11:58:40','user'),
(7558104393,'Crystel Koepp DVM',NULL,'annamae.wolff@example.net',NULL,NULL,NULL,'$2y$12$OPTxbfJze6rzlY52zZnTAOD26IgyyCDoEW8tTNpxyx.OV1ze8Xj2m',NULL,'2025-05-14 11:58:44','2025-05-14 11:58:44','user'),
(7623197846,'Mrs. Joelle Quigley',NULL,'sandrine.kreiger@example.org',NULL,NULL,NULL,'$2y$12$T5zMiGqCNhT8nJ0IrPeG6ufvJVZjRzVEOWB9DjsSNOi9UiZxAoQgu',NULL,'2025-05-14 11:58:36','2025-05-14 11:58:36','user'),
(7740857237,'Harmony Witting',NULL,'oschimmel@example.net',NULL,NULL,NULL,'$2y$12$JGcE28y7xZG3RwiXHlj/ZeTTV7NdsRBXEN4uFm3txj1PDwVv3zS.W',NULL,'2025-05-14 11:58:45','2025-05-14 11:58:45','user'),
(7894648322,'Prof. Albert Renner',NULL,'qgaylord@example.net',NULL,NULL,NULL,'$2y$12$bhLAN8nGTRqxUnai4YAm/uUv7RAb/KX2zyLRXjRcXWuOEiz.5SsWC',NULL,'2025-05-14 11:58:39','2025-05-14 11:58:39','user'),
(7910397603,'Charley Dibbert',NULL,'chaya.nicolas@example.org',NULL,NULL,NULL,'$2y$12$awxbHrFtjthUsDzChb3wOeuACbgupAw1ZXjBCSETt7sK48/p79IEy',NULL,'2025-05-14 11:58:42','2025-05-14 11:58:42','user'),
(8216699340,'Ryder Reichert',NULL,'blaze.hoppe@example.com',NULL,NULL,NULL,'$2y$12$9YhIpzybYrCmyeR62Hq8VuJrYcakQaoerCSVDnSW.MX9TF4eWs7eu',NULL,'2025-05-14 11:58:42','2025-05-14 11:58:42','user'),
(8336664765,'Mckayla Hermann',NULL,'angel.aufderhar@example.net',NULL,NULL,NULL,'$2y$12$YkIEQSmLGV/1v/pYIFXTUuNhFYoc6jz23WSkHG5BKZrsr.RdjitCW',NULL,'2025-05-14 11:58:44','2025-05-14 11:58:44','user'),
(8361301760,'Prof. Felipe Yost',NULL,'adell96@example.net',NULL,NULL,NULL,'$2y$12$ZJf7yxzgJ3y/X2D9gM1AHeDrNaYGVIQV1Kk.Ns34us33m2wcoyTku',NULL,'2025-05-14 11:58:42','2025-05-14 11:58:42','user'),
(8489880794,'Ozella Gorczany',NULL,'raynor.gilda@example.com',NULL,NULL,NULL,'$2y$12$sb/vsyT3huXjMwYYM5gMwOKThwnUIYzwPKP1hsuPofPi0fNXvKtAm',NULL,'2025-05-14 11:58:36','2025-05-14 11:58:36','user'),
(8737501133,'Quentin Ebert',NULL,'wunsch.kay@example.net',NULL,NULL,NULL,'$2y$12$rtK5c/Xb/gcz/m7QUNJB8eKYI.BVKR9SGLdGixdHL4T1DlpHkyCt2',NULL,'2025-05-14 11:58:36','2025-05-14 11:58:36','user'),
(8917741097,'Dr. Tyshawn Frami',NULL,'isaias.howe@example.com',NULL,NULL,NULL,'$2y$12$lcQH9XUcO0uer.xVIz26NupgWtGqj0MESEU9gZ3nXSyaGUkssKHPG',NULL,'2025-05-14 11:58:41','2025-05-14 11:58:41','user'),
(9168384967,'Ms. Natasha Schaefer',NULL,'flegros@example.com',NULL,NULL,NULL,'$2y$12$2PkXoWl1oAsVlWQuKm5Mp.c1pJYgxOrwVV99ANcTx0Ntl1WFTSPLS',NULL,'2025-05-14 11:58:42','2025-05-14 11:58:42','user'),
(9278988697,'Kaya Zemlak',NULL,'kassulke.bryana@example.com',NULL,NULL,NULL,'$2y$12$H/DSx8/ArtegNwCNS7rWBOWMaibT5WRWTKvc1jAEBoFJsbzQ6BvOy',NULL,'2025-05-14 11:58:39','2025-05-14 11:58:39','user'),
(9327470197,'Wyman Pfannerstill',NULL,'schuster.michaela@example.org',NULL,NULL,NULL,'$2y$12$uFNcyg9i0YeM4CVYV4bktOT24RpwgXIJyn6cQ2tgeCJy9/18Obc6C',NULL,'2025-05-14 11:58:37','2025-05-14 11:58:37','user'),
(9446069643,'Miss Zora Mertz',NULL,'fannie05@example.com',NULL,NULL,NULL,'$2y$12$Vp9mPObbo1sG9K5VXpsJ8u7guG6qS27dC8KTDe/yEIvM42RjR7hLi',NULL,'2025-05-14 11:58:44','2025-05-14 11:58:44','user'),
(9577253619,'Delta Denesik',NULL,'hartmann.elijah@example.com',NULL,NULL,NULL,'$2y$12$C6WYLWwDWtN1A189ja0Mi.hokrgjQy1HiRIT.zFZj7M0MJH8f29eS',NULL,'2025-05-14 11:58:38','2025-05-14 11:58:38','user'),
(9864401866,'Mrs. Emie Braun MD',NULL,'xtorphy@example.com',NULL,NULL,NULL,'$2y$12$guS3x5sj6ipfR2l..Rkd2.j2/U494lZ8CGc5b16TvW7liwTOyhS92',NULL,'2025-05-14 11:58:37','2025-05-14 11:58:37','user');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visits`
--

DROP TABLE IF EXISTS `visits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `game_id` bigint(20) unsigned NOT NULL,
  `visited_at` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visits_user_id_foreign` (`user_id`),
  KEY `visits_game_id_foreign` (`game_id`),
  CONSTRAINT `visits_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  CONSTRAINT `visits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visits`
--

LOCK TABLES `visits` WRITE;
/*!40000 ALTER TABLE `visits` DISABLE KEYS */;
/*!40000 ALTER TABLE `visits` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-28 14:18:17
