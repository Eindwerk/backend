-- MySQL dump 10.13  Distrib 8.0.35, for macos12.7 (arm64)
--
-- Host: db-groundpass-do-user-22327236-0.f.db.ondigitalocean.com    Database: defaultdb
-- ------------------------------------------------------
-- Server version	8.0.35

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ 'a34c7b7f-3bbb-11f0-a123-261b7785fbfd:1-4785';

--
-- Table structure for table `api_keys`
--

DROP TABLE IF EXISTS `api_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `api_keys` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_keys_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `api_keys`
--

LOCK TABLES `api_keys` WRITE;
/*!40000 ALTER TABLE `api_keys` DISABLE KEYS */;
INSERT INTO `api_keys` VALUES (1,'ADMIN','HxNcXGIoQlyAmr8nwSpPAb60yGKBjoyq1Mz4pgSD','2025-06-11 18:34:44','2025-06-11 18:34:44');
/*!40000 ALTER TABLE `api_keys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

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

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel_cache_ca5b833524140d1605de91424f91840af93c3b6d','i:1;',1750175143),('laravel_cache_ca5b833524140d1605de91424f91840af93c3b6d:timer','i:1750175143;',1750175143);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `post_id` bigint unsigned NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `follows` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `followable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `followable_id` bigint unsigned NOT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `friendships` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `friend_id` bigint unsigned NOT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `games` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `stadium_id` bigint unsigned NOT NULL,
  `home_team_id` bigint unsigned NOT NULL,
  `away_team_id` bigint unsigned NOT NULL,
  `home_score` int DEFAULT NULL,
  `away_score` int DEFAULT NULL,
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
INSERT INTO `games` VALUES (1,70,15,12,2,0,'2025-06-13','2025-06-13 10:04:14','2025-06-13 10:04:14');
/*!40000 ALTER TABLE `games` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

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

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leagues`
--

DROP TABLE IF EXISTS `leagues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leagues` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `leagues_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leagues`
--

LOCK TABLES `leagues` WRITE;
/*!40000 ALTER TABLE `leagues` DISABLE KEYS */;
INSERT INTO `leagues` VALUES (1,'Bundesliga','2025-06-11 18:56:19','2025-06-11 18:56:19'),(2,'Bundesliga 2','2025-06-11 18:56:27','2025-06-11 18:56:27'),(3,'3. Liga','2025-06-11 18:56:38','2025-06-11 18:56:38');
/*!40000 ALTER TABLE `leagues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `likes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `post_id` bigint unsigned NOT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_05_14_091747_create_stadia_table',1),(5,'2025_05_14_091758_create_teams_table',1),(6,'2025_05_14_091834_create_games_table',1),(7,'2025_05_14_091845_create_posts_table',1),(8,'2025_05_14_091850_create_comments_table',1),(9,'2025_05_14_091857_create_likes_table',1),(10,'2025_05_14_091860_create_visits_table',1),(11,'2025_05_14_091901_create_friendships_table',1),(12,'2025_05_14_091905_create_notifications_table',1),(13,'2025_05_14_093409_create_personal_access_tokens_table',1),(14,'2025_05_14_111701_add_role_to_users_table',1),(15,'2025_05_14_123605_add_profile_image_to_users_table',1),(16,'2025_05_14_123625_add_image_to_stadiums_table',1),(17,'2025_05_14_123640_add_image_to_posts_table',1),(18,'2025_05_14_123657_add_logo_url_to_teams_table',1),(19,'2025_05_14_131015_add_location_to_stadia_table',1),(20,'2025_05_21_114815_create_follows_table',1),(21,'2025_05_21_115221_add_images_to_teams_table',1),(22,'2025_05_21_115331_add_images_to_stadia_table',1),(23,'2025_05_21_115517_add_username_and_banner_to_users_table',1),(24,'2025_05_21_120012_add_unique_index_to_usernames',1),(25,'2025_06_04_200308_create_api_keys_table',1),(26,'2025_06_06_120112_add_email_verification_token_to_users',1),(27,'2025_06_11_182750_create_leagues_table',1),(28,'2025_06_11_182921_update_teams_table_add_league_id',1),(29,'2025_06_12_084846_add_team_id_to_stadiums_table',2),(30,'2025_06_12_085404_update_table',3),(31,'2025_06_16_103710_make_content_nullable_on_posts_table',4),(32,'2025_06_16_103952_rename_logo_url_to_profile_image_in_teams_table',5),(34,'2025_06_16_115517_add_stadium_id_to_posts_table',6),(35,'2025_06_16_122403_update_posts_table_add_fields',6),(36,'2025_06_16_122617_drop_image_column_from_posts_table',7),(37,'2025_06_17_122355_split_location_in_stadia_table',8),(38,'2025_06_17_123731_remove_location',9);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `sender_id` bigint unsigned NOT NULL,
  `type` enum('visit','comment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `game_id` bigint unsigned NOT NULL,
  `post_id` bigint unsigned DEFAULT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User',4389546485,'api-token','54c7aa2587223ffe498ba7cfa956f47dedbdce86019245aaa08c0a36389daae9','[\"*\"]','2025-06-15 21:58:38',NULL,'2025-06-11 18:36:56','2025-06-15 21:58:38'),(2,'App\\Models\\User',4389546485,'api-token','c116bb062cd64bb1f8e9ca81673b03ce5d481254dac903944bcae77f6e1719d2','[\"*\"]',NULL,NULL,'2025-06-11 18:37:30','2025-06-11 18:37:30'),(3,'App\\Models\\User',4389546485,'api-token','9cbb6d404589bff03cf1cc599b90e2ed64b3226e1c89a6e9777eb05a7efc6985','[\"*\"]',NULL,NULL,'2025-06-11 18:41:30','2025-06-11 18:41:30'),(4,'App\\Models\\User',4389546485,'api-token','000e143c68d97044d8cfb78afda6d0899edc232d7421426af4fe96897652eac7','[\"*\"]','2025-06-13 10:23:22',NULL,'2025-06-11 18:46:02','2025-06-13 10:23:22'),(5,'App\\Models\\User',4389546485,'api-token','5fa910a6a34e8cae2949838312e11c64b1d531ea212bd865e2d0966a327687c4','[\"*\"]',NULL,NULL,'2025-06-13 08:38:12','2025-06-13 08:38:12'),(6,'App\\Models\\User',4389546485,'api-token','30062a40b7dac1302af42a49fe05b246683030f5de099bb2a66925f5109f695a','[\"*\"]',NULL,NULL,'2025-06-13 08:38:42','2025-06-13 08:38:42'),(7,'App\\Models\\User',4389546485,'api-token','41f853920834c4aa54ec554f228ad2bf5ddaadfe6deaeae20becf57df7e84dbf','[\"*\"]',NULL,NULL,'2025-06-13 08:39:06','2025-06-13 08:39:06'),(8,'App\\Models\\User',4389546485,'api-token','46e6ea057b794eda0b4c2143bd879698fa079af359da91f91c799fae91109ca7','[\"*\"]',NULL,NULL,'2025-06-13 08:39:48','2025-06-13 08:39:48'),(9,'App\\Models\\User',4389546485,'api-token','4431879480cc39d2dad992992e111f81e29c1082050dcb77b284bf7a8f0b56ed','[\"*\"]','2025-06-17 14:46:22',NULL,'2025-06-13 10:24:46','2025-06-17 14:46:22'),(10,'App\\Models\\User',4389546485,'api-token','c1fc9a01002eaec72e3efa466640789de8033d109d1b8c2ac1a0e9b81c21fd13','[\"*\"]','2025-06-17 17:51:25',NULL,'2025-06-13 13:39:41','2025-06-17 17:51:25'),(11,'App\\Models\\User',4389546485,'api-token','6e9acd099aecc6eea8177750ca2f759bc6132413039a2c57e0d93649b01a4381','[\"*\"]','2025-06-13 14:54:32',NULL,'2025-06-13 14:54:18','2025-06-13 14:54:32'),(12,'App\\Models\\User',4389546485,'api-token','e4a27e53c1e60892cc514c570bac08a7ec768636e13560332c56851610df09a5','[\"*\"]','2025-06-15 15:27:08',NULL,'2025-06-15 15:27:02','2025-06-15 15:27:08'),(13,'App\\Models\\User',4389546485,'api-token','bc13c84490043541ae2a3cd13cdada56d9e8155e2b93c7dae006b7dfd4e306ca','[\"*\"]','2025-06-17 14:36:46',NULL,'2025-06-16 11:48:30','2025-06-17 14:36:46');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `game_id` bigint unsigned NOT NULL,
  `stadium_id` bigint unsigned DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `posts_user_id_foreign` (`user_id`),
  KEY `posts_game_id_foreign` (`game_id`),
  KEY `posts_stadium_id_foreign` (`stadium_id`),
  CONSTRAINT `posts_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  CONSTRAINT `posts_stadium_id_foreign` FOREIGN KEY (`stadium_id`) REFERENCES `stadia` (`id`) ON DELETE SET NULL,
  CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (4,4389546485,1,70,NULL,'uploads/posts/fM87NweyJv2qwbnQbYngKYkKD5cqBQDCdBUX2Fg6.png','2025-06-17 09:30:58','2025-06-17 09:30:58'),(5,4389546485,1,70,NULL,'uploads/posts/xENst9H6oZbN06A2FU0K1WZLuXxjkf9EVzfwBSTP.png','2025-06-17 09:40:27','2025-06-17 09:40:27');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

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

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('4N7y0uYBfoqp9Ln2ZZpRaiQ22CXq3tChFjxZpOFQ',NULL,'172.71.146.79','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUmViWGRhcUs5eXJlOENZSFZXcm9yN3JpcmZPbXAzb1JkQzdjazZCdiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cHM6Ly9hZG1pbi5ncm91bmRwYXNzLmJlIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHBzOi8vYWRtaW4uZ3JvdW5kcGFzcy5iZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1750179423),('A4dMGGT36MTCdIyM3Rn7j2NPpZQnadEEUtgRHt45',NULL,'172.68.26.95','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNUN3QmRXU0lSanNiWFBZSk9GT0swcXR6dnVPMDdyYjgyTDMzaXhtQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vYWRtaW4uZ3JvdW5kcGFzcy5iZS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1750179425),('CuaD0uxnFxQDcEcdcZdJOC6xefxQToPqxIOBZQv0',4389546485,'172.18.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiN3pTazBZam5HR2RKd2NJOTNEcmRwS2RuZ2dPaWgzdG96cTBJNWVoTSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI1OiJodHRwczovL2JhY2tlbmQuZGRldi5zaXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDM4OTU0NjQ4NTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJExwVmh5Z1U2eFBabDVhS3JnYjZIa09uVURIMS96ZVRIcWRwcnNpajAva3h5cUdPdXlmMDlLIjtzOjg6ImZpbGFtZW50IjthOjA6e319',1750165645),('kvIcNfKIbZoIGLQMjcVddTsKWyfNFLkq8i7TurHI',4389546485,'104.23.172.102','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiejNXdEYzbGoyZGRSWVR3YlhiRWF6RkdIaW1DM0V3QnFVOFdLQ3B4WiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vYWRtaW4uZ3JvdW5kcGFzcy5iZS90ZWFtcy8xMi9lZGl0Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDM4OTU0NjQ4NTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJExwVmh5Z1U2eFBabDVhS3JnYjZIa09uVURIMS96ZVRIcWRwcnNpajAva3h5cUdPdXlmMDlLIjtzOjg6ImZpbGFtZW50IjthOjA6e319',1750175097),('RKslAQFthjTaIQFuTX8v39KdkoaIxcU9IAfKb2nn',NULL,'172.71.232.86','PostmanRuntime/7.44.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTU1lb3lNUjRMWDFtanA5QUJNVVRmVHFnb216YWJDbXhYQWp3clNPdiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cHM6Ly9hZG1pbi5ncm91bmRwYXNzLmJlIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vYWRtaW4uZ3JvdW5kcGFzcy5iZS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1750164545),('ZSKvLQbfpbL0RvwNDmV8RGLaByVETJbG0OCNgH1M',NULL,'172.68.26.95','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582','YTozOntzOjY6Il90b2tlbiI7czo0MDoielM4MVVnRXZBcG9lOHlrSWZlbUYwOEZ3QkFPZzhZdzZleEp6c0tpbiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vYWRtaW4uZ3JvdW5kcGFzcy5iZS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1750179424);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stadia`
--

DROP TABLE IF EXISTS `stadia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stadia` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `banner_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `team_id` bigint unsigned DEFAULT NULL,
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stadia_team_id_foreign` (`team_id`),
  CONSTRAINT `stadia_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stadia`
--

LOCK TABLES `stadia` WRITE;
/*!40000 ALTER TABLE `stadia` DISABLE KEYS */;
INSERT INTO `stadia` VALUES (56,'WWK Arena','2025-06-12 09:39:57','2025-06-17 12:50:42',48.3230600,10.8861100,NULL,56,'uploads/stadiums/profile-image/U8cKIZfiEXyJb3CNMJGjFG3vvKxJY74oiciTJ8f7.png'),(57,'Stadion An der Alten Försterei','2025-06-12 09:39:58','2025-06-12 09:39:58',52.4572200,13.5680600,NULL,2,NULL),(58,'Wohninvest Weserstadion','2025-06-12 09:39:58','2025-06-12 09:39:58',53.0668000,8.8379000,NULL,3,NULL),(59,'Signal Iduna Park','2025-06-12 09:39:58','2025-06-12 09:39:58',51.4926000,7.4519000,NULL,4,NULL),(60,'Deutsche Bank Park','2025-06-12 09:39:58','2025-06-12 09:39:58',50.0686000,8.6455000,NULL,5,NULL),(61,'Europa-Park Stadion','2025-06-12 09:39:58','2025-06-12 09:39:58',48.0216000,7.8297000,NULL,6,NULL),(62,'Volksparkstadion','2025-06-12 09:39:58','2025-06-12 09:39:58',53.5872000,9.8986000,NULL,7,NULL),(63,'Voith-Arena','2025-06-12 09:39:58','2025-06-12 09:39:58',48.6685000,10.1393000,NULL,8,NULL),(64,'PreZero Arena','2025-06-12 09:39:58','2025-06-12 09:39:58',50.2857200,18.6860300,NULL,9,NULL),(65,'RheinEnergieStadion','2025-06-12 09:39:58','2025-06-12 09:39:58',50.9335000,6.8752000,NULL,10,NULL),(66,'Red Bull Arena','2025-06-12 09:39:58','2025-06-12 09:39:58',51.3458000,12.3483000,NULL,11,NULL),(67,'BayArena','2025-06-12 09:39:58','2025-06-12 09:39:58',51.0382000,7.0023000,NULL,12,NULL),(68,'MEWA ARENA','2025-06-12 09:39:58','2025-06-12 09:39:58',49.9839000,8.2244000,NULL,13,NULL),(69,'BORUSSIA-PARK','2025-06-12 09:39:58','2025-06-12 09:39:58',51.1746000,6.3855000,NULL,14,NULL),(70,'Allianz Arena','2025-06-12 09:39:58','2025-06-12 09:39:58',48.2188000,11.6247000,NULL,15,NULL),(71,'Millerntor-Stadion','2025-06-12 09:39:58','2025-06-12 09:39:58',53.5546000,9.9678000,NULL,16,NULL),(72,'MHPArena','2025-06-12 09:39:58','2025-06-12 09:39:58',48.7923000,9.2321000,NULL,17,NULL),(73,'Volkswagen Arena','2025-06-12 09:39:58','2025-06-12 09:39:58',52.4327000,10.8038000,NULL,18,NULL);
/*!40000 ALTER TABLE `stadia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teams` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `banner_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `league_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `teams_league_id_foreign` (`league_id`),
  CONSTRAINT `teams_league_id_foreign` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
INSERT INTO `teams` VALUES (1,'FC Augsburg','','2025-06-11 19:09:44','2025-06-17 14:12:24',NULL,1),(2,'Union Berlin','','2025-06-11 19:09:44','2025-06-17 12:01:05',NULL,1),(3,'Werder Bremen','','2025-06-11 19:09:44','2025-06-17 12:03:47',NULL,1),(4,'Borussia Dortmund','','2025-06-11 19:09:44','2025-06-17 12:04:00',NULL,1),(5,'Eintracht Frankfurt','','2025-06-11 19:09:44','2025-06-17 13:43:08',NULL,1),(6,'SC Freiburg','','2025-06-11 19:09:44','2025-06-12 08:36:04',NULL,1),(7,'Hamburger SV','','2025-06-11 19:09:44','2025-06-12 08:36:23',NULL,1),(8,'1. FC Heidenheim','','2025-06-11 19:09:44','2025-06-12 08:36:36',NULL,1),(9,'TSG Hoffenheim','','2025-06-11 19:09:45','2025-06-12 08:36:54',NULL,1),(10,'1. FC Köln','','2025-06-11 19:09:45','2025-06-12 08:37:14',NULL,1),(11,'RB Leipzig','','2025-06-11 19:09:45','2025-06-12 08:37:40',NULL,1),(12,'Bayer Leverkusen','uploads/teams/profile-image/ypeojWifknRbjxm9F8o7Ru8mGtN0p9X8El5SsWvf.png','2025-06-11 19:09:45','2025-06-17 14:36:46',NULL,1),(13,'Mainz 05','','2025-06-11 19:09:45','2025-06-12 08:38:06',NULL,1),(14,'Borussia Mönchengladbach','','2025-06-11 19:09:45','2025-06-12 08:38:19',NULL,1),(15,'Bayern Munich','','2025-06-11 19:09:45','2025-06-12 08:38:38',NULL,1),(16,'FC St. Pauli','','2025-06-11 19:09:45','2025-06-12 08:39:01',NULL,1),(17,'VfB Stuttgart','','2025-06-11 19:09:45','2025-06-12 08:39:19',NULL,1),(18,'VfL Wolfsburg','','2025-06-11 19:09:45','2025-06-12 08:39:33',NULL,1),(19,'Hertha BSC',NULL,'2025-06-11 19:13:07','2025-06-11 19:13:07',NULL,2),(20,'Arminia Bielefeld',NULL,'2025-06-11 19:13:13','2025-06-11 19:13:13',NULL,2),(21,'VfL Bochum',NULL,'2025-06-11 19:13:13','2025-06-11 19:13:13',NULL,2),(22,'Eintracht Braunschweig',NULL,'2025-06-11 19:13:13','2025-06-11 19:13:13',NULL,2),(23,'Darmstadt 98',NULL,'2025-06-11 19:13:13','2025-06-11 19:13:13',NULL,2),(24,'Dynamo Dresden',NULL,'2025-06-11 19:13:13','2025-06-11 19:13:13',NULL,2),(25,'Fortuna Düsseldorf',NULL,'2025-06-11 19:13:13','2025-06-11 19:13:13',NULL,2),(26,'SV Elversberg',NULL,'2025-06-11 19:13:13','2025-06-11 19:13:13',NULL,2),(27,'Greuther Fürth',NULL,'2025-06-11 19:13:13','2025-06-11 19:13:13',NULL,2),(28,'Hannover 96',NULL,'2025-06-11 19:13:13','2025-06-11 19:13:13',NULL,2),(29,'1. FC Kaiserslautern',NULL,'2025-06-11 19:13:13','2025-06-11 19:13:13',NULL,2),(30,'Karlsruher SC',NULL,'2025-06-11 19:13:13','2025-06-11 19:13:13',NULL,2),(31,'Holstein Kiel',NULL,'2025-06-11 19:13:13','2025-06-11 19:13:13',NULL,2),(32,'1. FC Magdeburg',NULL,'2025-06-11 19:13:13','2025-06-11 19:13:13',NULL,2),(33,'Preußen Münster',NULL,'2025-06-11 19:13:13','2025-06-11 19:13:13',NULL,2),(34,'1. FC Nürnberg',NULL,'2025-06-11 19:13:13','2025-06-11 19:13:13',NULL,2),(35,'SC Paderborn',NULL,'2025-06-11 19:13:14','2025-06-11 19:13:14',NULL,2),(36,'Schalke 04','uploads/teams/profile-image/0TAVzo07LFMEaicUmhLXV8JHy7oghs7vaQYJnww9.png','2025-06-11 19:13:14','2025-06-17 09:13:00',NULL,2),(37,'Alemannia Aachen',NULL,'2025-06-11 19:17:46','2025-06-11 19:17:46',NULL,3),(38,'Erzgebirge Aue',NULL,'2025-06-11 19:17:47','2025-06-11 19:17:47',NULL,3),(39,'Energie Cottbus',NULL,'2025-06-11 19:17:47','2025-06-11 19:17:47',NULL,3),(40,'MSV Duisburg',NULL,'2025-06-11 19:17:47','2025-06-11 19:17:47',NULL,3),(41,'Rot-Weiss Essen',NULL,'2025-06-11 19:17:47','2025-06-11 19:17:47',NULL,3),(42,'TSV Havelse',NULL,'2025-06-11 19:17:47','2025-06-11 19:17:47',NULL,3),(43,'TSG Hoffenheim II',NULL,'2025-06-11 19:17:47','2025-06-11 19:17:47',NULL,3),(44,'FC Ingolstadt',NULL,'2025-06-11 19:17:47','2025-06-11 19:17:47',NULL,3),(45,'Viktoria Köln',NULL,'2025-06-11 19:17:47','2025-06-11 19:17:47',NULL,3),(46,'Waldhof Mannheim',NULL,'2025-06-11 19:17:47','2025-06-11 19:17:47',NULL,3),(47,'1860 Munich',NULL,'2025-06-11 19:17:47','2025-06-11 19:17:47',NULL,3),(48,'VfL Osnabrück',NULL,'2025-06-11 19:17:47','2025-06-11 19:17:47',NULL,3),(49,'Jahn Regensburg',NULL,'2025-06-11 19:17:47','2025-06-11 19:17:47',NULL,3),(50,'Hansa Rostock',NULL,'2025-06-11 19:17:47','2025-06-11 19:17:47',NULL,3),(51,'1. FC Saarbrücken',NULL,'2025-06-11 19:17:47','2025-06-11 19:17:47',NULL,3),(52,'1. FC Schweinfurt',NULL,'2025-06-11 19:17:48','2025-06-11 19:17:48',NULL,3),(53,'VfB Stuttgart II',NULL,'2025-06-11 19:17:48','2025-06-11 19:17:48',NULL,3),(54,'SSV Ulm',NULL,'2025-06-11 19:17:48','2025-06-11 19:17:48',NULL,3),(55,'SC Verl',NULL,'2025-06-11 19:17:48','2025-06-11 19:17:48',NULL,3),(56,'Wehen Wiesbaden',NULL,'2025-06-11 19:17:48','2025-06-11 19:17:48',NULL,3);
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `email_verification_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verification_token_expires_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_verification_token_unique` (`email_verification_token`)
) ENGINE=InnoDB AUTO_INCREMENT=4389546486 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (4389546485,'Super Admin','SUPER ADMIN CEDRIC','admin@example.com','uploads/users/profile-image/MTVdBGMwIsqAkKuXXj7STd0E3LLKLCvYNyZYmBSR.png','uploads/users/banner-image/Mld0Vv9xX0ALNSxCOfRxsaOnszvgmZt50IIUPXAE.jpg','2025-06-11 18:45:36','$2y$12$LpVhygU6xPZl5aKrgb6HkOnUDH1/zeTHqdprsij0/kxyqGOuyf09K','ZtaharSMP0WNQF2iilusr7qtZfGAu1uBUGtUTtUel30ptmTpjhVc7k716p8W','2025-06-11 18:34:01','2025-06-17 13:49:37','super_admin',NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visits`
--

DROP TABLE IF EXISTS `visits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `visits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `game_id` bigint unsigned NOT NULL,
  `visited_at` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
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
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-17 20:36:00
