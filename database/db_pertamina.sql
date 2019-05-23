/*
 Navicat Premium Data Transfer

 Source Server         : mysql
 Source Server Type    : MySQL
 Source Server Version : 100116
 Source Host           : localhost:3306
 Source Schema         : db_pertamina

 Target Server Type    : MySQL
 Target Server Version : 100116
 File Encoding         : 65001

 Date: 02/04/2019 10:27:06
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES (1, 'Operator check', 'item');
INSERT INTO `categories` VALUES (2, 'Predictive Maintenance', 'item');
INSERT INTO `categories` VALUES (3, 'Preventive Maintenance', 'item');
INSERT INTO `categories` VALUES (4, 'Engineering Planning Maintenance', 'submission');
INSERT INTO `categories` VALUES (5, 'Sales General Admin', 'submission');
INSERT INTO `categories` VALUES (6, 'Receiving Storage Distribution', 'submission');
INSERT INTO `categories` VALUES (7, 'Healthy Safety Security Environment', 'submission');
INSERT INTO `categories` VALUES (8, 'SKPP', 'certificate');
INSERT INTO `categories` VALUES (9, 'SKPA', 'certificate');
INSERT INTO `categories` VALUES (10, 'Grounding', 'certificate');
INSERT INTO `categories` VALUES (11, 'Keluhan Pelanggan', 'ptpp');
INSERT INTO `categories` VALUES (12, 'Audit Internal', 'ptpp');
INSERT INTO `categories` VALUES (13, 'Tinjauan Manajemen', 'ptpp');
INSERT INTO `categories` VALUES (14, 'Saran/Keluhan Masyarakat', 'ptpp');
INSERT INTO `categories` VALUES (15, 'Usulan/Saran', 'ptpp');
INSERT INTO `categories` VALUES (16, 'Penggantian/Perbaikan', 'ptpp');

-- ----------------------------
-- Table structure for certificates
-- ----------------------------
DROP TABLE IF EXISTS `certificates`;
CREATE TABLE `certificates`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `year` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `date_start` timestamp(0) NULL DEFAULT NULL,
  `date_end` timestamp(0) NULL DEFAULT NULL,
  `publisher` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `file` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `category_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_by` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `certificates_category_id_index`(`category_id`) USING BTREE,
  INDEX `certificates_created_by_index`(`created_by`) USING BTREE,
  CONSTRAINT `certificates_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `certificates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of certificates
-- ----------------------------
INSERT INTO `certificates` VALUES (1, 'CHK-IV-2019-002', 'Ijazah S3', '2019', '2019-05-01 08:59:00', '2019-09-21 08:59:00', 'Airlangga', 'uploads/certificate/20190402085954-Screenshot_2.png', 9, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for item_maintenance_activities
-- ----------------------------
DROP TABLE IF EXISTS `item_maintenance_activities`;
CREATE TABLE `item_maintenance_activities`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id` int(10) UNSIGNED NOT NULL,
  `periode_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `item_maintenance_activities_item_id_index`(`item_id`) USING BTREE,
  INDEX `item_maintenance_activities_periode_id_index`(`periode_id`) USING BTREE,
  INDEX `item_maintenance_activities_category_id_index`(`category_id`) USING BTREE,
  CONSTRAINT `item_maintenance_activities_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `item_maintenance_activities_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `item_maintenance_activities_periode_id_foreign` FOREIGN KEY (`periode_id`) REFERENCES `periodes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of item_maintenance_activities
-- ----------------------------
INSERT INTO `item_maintenance_activities` VALUES (1, 1, 3, 1, 'Cek terminasi kabel power dan data');
INSERT INTO `item_maintenance_activities` VALUES (2, 1, 3, 1, 'Cek kondisi sealant pada oanel box');
INSERT INTO `item_maintenance_activities` VALUES (3, 1, 3, 2, 'Infrared thermography pada module');
INSERT INTO `item_maintenance_activities` VALUES (4, 1, 1, 2, 'Ukur tegangan dan arus pada module');
INSERT INTO `item_maintenance_activities` VALUES (5, 1, 2, 3, 'Bersihkan strainer secara berkala');
INSERT INTO `item_maintenance_activities` VALUES (6, 1, 2, 3, 'Bersihkan batch controller, DCV dan coriolis sensor');

-- ----------------------------
-- Table structure for items
-- ----------------------------
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `brand` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `production_year` int(11) NULL DEFAULT NULL,
  `location_of_use` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of items
-- ----------------------------
INSERT INTO `items` VALUES (1, 'Massflow Meter 1', 'Tahoma', 'Alat Berat', 2019, 'Kilang 1', NULL, NULL);
INSERT INTO `items` VALUES (2, 'Pompa Produk 6', 'Toyota', 'Alat Berat', 2019, 'Kilang 1', NULL, NULL);

-- ----------------------------
-- Table structure for maintenance_activities
-- ----------------------------
DROP TABLE IF EXISTS `maintenance_activities`;
CREATE TABLE `maintenance_activities`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `user_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `item_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `item_maintenance_activity_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `date` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `notes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `approval_to` int(10) UNSIGNED NULL DEFAULT NULL,
  `approval_at` timestamp(0) NULL DEFAULT NULL,
  `status_approval` int(11) NOT NULL DEFAULT 0,
  `notes_approval` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `maintenance_activities_user_id_index`(`user_id`) USING BTREE,
  INDEX `maintenance_activities_item_id_index`(`item_id`) USING BTREE,
  INDEX `maintenance_activities_item_maintenance_activity_id_index`(`item_maintenance_activity_id`) USING BTREE,
  INDEX `maintenance_activities_approval_to_index`(`approval_to`) USING BTREE,
  CONSTRAINT `maintenance_activities_approval_to_foreign` FOREIGN KEY (`approval_to`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `maintenance_activities_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `maintenance_activities_item_maintenance_activity_id_foreign` FOREIGN KEY (`item_maintenance_activity_id`) REFERENCES `item_maintenance_activities` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `maintenance_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of maintenance_activities
-- ----------------------------
INSERT INTO `maintenance_activities` VALUES (1, 'CHK-IV-2019-001', 1, 1, 2, '2019-04-18 20:29:00', 'asd', 1, 1, '2019-04-01 20:30:27', 2, NULL, '2019-04-01 20:29:39', '2019-04-01 20:30:27');

-- ----------------------------
-- Table structure for maintenance_activity_histories
-- ----------------------------
DROP TABLE IF EXISTS `maintenance_activity_histories`;
CREATE TABLE `maintenance_activity_histories`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `maintenance_activity_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `item_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `user_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `date` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `notes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `is_executor_vendor` tinyint(1) NOT NULL,
  `vendor_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `executor_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `approval_to` int(10) UNSIGNED NULL DEFAULT NULL,
  `status_approval` int(11) NOT NULL DEFAULT 0,
  `notes_approval` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `approval_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `maintenance_activity_histories_maintenance_activity_id_index`(`maintenance_activity_id`) USING BTREE,
  INDEX `maintenance_activity_histories_item_id_index`(`item_id`) USING BTREE,
  INDEX `maintenance_activity_histories_user_id_index`(`user_id`) USING BTREE,
  INDEX `maintenance_activity_histories_vendor_id_index`(`vendor_id`) USING BTREE,
  INDEX `maintenance_activity_histories_approval_to_index`(`approval_to`) USING BTREE,
  CONSTRAINT `maintenance_activity_histories_approval_to_foreign` FOREIGN KEY (`approval_to`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `maintenance_activity_histories_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `maintenance_activity_histories_maintenance_activity_id_foreign` FOREIGN KEY (`maintenance_activity_id`) REFERENCES `maintenance_activities` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `maintenance_activity_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `maintenance_activity_histories_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of maintenance_activity_histories
-- ----------------------------
INSERT INTO `maintenance_activity_histories` VALUES (1, 'HTR-IV-2019-001', 1, 1, 1, '2019-04-10 20:30:00', 'asd', 1, 1, NULL, 1, 2, NULL, '2019-04-01 20:31:14', '2019-04-01 20:30:57', '2019-04-01 20:31:14');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (3, '2015_01_15_105324_create_roles_table', 1);
INSERT INTO `migrations` VALUES (4, '2015_01_15_114412_create_role_user_table', 1);
INSERT INTO `migrations` VALUES (5, '2015_01_26_115212_create_permissions_table', 1);
INSERT INTO `migrations` VALUES (6, '2015_01_26_115523_create_permission_role_table', 1);
INSERT INTO `migrations` VALUES (7, '2015_02_09_132439_create_permission_user_table', 1);
INSERT INTO `migrations` VALUES (8, '2015_10_09_163033_add_column_group_type_permissions_table', 1);
INSERT INTO `migrations` VALUES (9, '2019_03_07_172510_create_vendors_table', 1);
INSERT INTO `migrations` VALUES (10, '2019_03_07_172531_create_periodes_table', 1);
INSERT INTO `migrations` VALUES (11, '2019_03_07_172554_create_categories_table', 1);
INSERT INTO `migrations` VALUES (12, '2019_03_07_172606_create_items_table', 1);
INSERT INTO `migrations` VALUES (13, '2019_03_07_172620_create_item_maintenance_activities_table', 1);
INSERT INTO `migrations` VALUES (14, '2019_03_07_172637_create_maintenance_activities_table', 1);
INSERT INTO `migrations` VALUES (15, '2019_03_07_172644_create_maintenance_activity_histories_table', 1);
INSERT INTO `migrations` VALUES (16, '2019_03_22_103657_create_p_t_p_ps_table', 1);
INSERT INTO `migrations` VALUES (17, '2019_03_22_103806_create_p_t_p_p_files_table', 1);
INSERT INTO `migrations` VALUES (18, '2019_03_22_103817_create_p_t_p_p_follow_ups_table', 1);
INSERT INTO `migrations` VALUES (19, '2019_03_22_103822_create_p_t_p_p_follow_up_details_table', 1);
INSERT INTO `migrations` VALUES (20, '2019_03_22_103833_create_p_t_p_p_follow_up_files_table', 1);
INSERT INTO `migrations` VALUES (21, '2019_03_22_103843_create_p_t_p_p_verificators_table', 1);
INSERT INTO `migrations` VALUES (22, '2019_03_22_131838_create_certificates_table', 1);
INSERT INTO `migrations` VALUES (23, '2019_03_22_131901_create_submissions_table', 1);
INSERT INTO `migrations` VALUES (24, '2019_03_22_131907_create_submission_files_table', 1);
INSERT INTO `migrations` VALUES (25, '2019_03_22_131917_create_settings_table', 1);

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email`) USING BTREE,
  INDEX `password_resets_token_index`(`token`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for periodes
-- ----------------------------
DROP TABLE IF EXISTS `periodes`;
CREATE TABLE `periodes`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of periodes
-- ----------------------------
INSERT INTO `periodes` VALUES (1, '1');
INSERT INTO `periodes` VALUES (2, '3');
INSERT INTO `periodes` VALUES (3, '6');

-- ----------------------------
-- Table structure for permission_role
-- ----------------------------
DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE `permission_role`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `permission_role_permission_id_index`(`permission_id`) USING BTREE,
  INDEX `permission_role_role_id_index`(`role_id`) USING BTREE,
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 65 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of permission_role
-- ----------------------------
INSERT INTO `permission_role` VALUES (1, 1, 1, '2019-04-01 19:43:36', '2019-04-01 19:43:36');
INSERT INTO `permission_role` VALUES (2, 2, 1, '2019-04-01 19:43:36', '2019-04-01 19:43:36');
INSERT INTO `permission_role` VALUES (3, 3, 1, '2019-04-01 19:43:36', '2019-04-01 19:43:36');
INSERT INTO `permission_role` VALUES (4, 4, 1, '2019-04-01 19:43:36', '2019-04-01 19:43:36');
INSERT INTO `permission_role` VALUES (5, 5, 1, '2019-04-01 19:43:36', '2019-04-01 19:43:36');
INSERT INTO `permission_role` VALUES (6, 6, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_role` VALUES (7, 7, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_role` VALUES (8, 8, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_role` VALUES (9, 9, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_role` VALUES (10, 10, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_role` VALUES (11, 11, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_role` VALUES (12, 12, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_role` VALUES (13, 13, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_role` VALUES (14, 14, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_role` VALUES (15, 15, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_role` VALUES (16, 16, 1, '2019-04-01 19:43:38', '2019-04-01 19:43:38');
INSERT INTO `permission_role` VALUES (17, 17, 1, '2019-04-01 19:43:38', '2019-04-01 19:43:38');
INSERT INTO `permission_role` VALUES (18, 18, 1, '2019-04-01 19:43:38', '2019-04-01 19:43:38');
INSERT INTO `permission_role` VALUES (19, 19, 1, '2019-04-01 19:43:38', '2019-04-01 19:43:38');
INSERT INTO `permission_role` VALUES (20, 20, 1, '2019-04-01 19:43:38', '2019-04-01 19:43:38');
INSERT INTO `permission_role` VALUES (21, 21, 1, '2019-04-01 19:43:38', '2019-04-01 19:43:38');
INSERT INTO `permission_role` VALUES (22, 22, 1, '2019-04-01 19:43:38', '2019-04-01 19:43:38');
INSERT INTO `permission_role` VALUES (23, 23, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_role` VALUES (24, 24, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_role` VALUES (25, 25, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_role` VALUES (26, 26, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_role` VALUES (27, 27, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_role` VALUES (28, 28, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_role` VALUES (29, 29, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_role` VALUES (30, 30, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_role` VALUES (31, 31, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_role` VALUES (32, 32, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_role` VALUES (33, 33, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_role` VALUES (34, 34, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_role` VALUES (35, 35, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_role` VALUES (36, 36, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_role` VALUES (37, 37, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_role` VALUES (38, 38, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_role` VALUES (39, 39, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_role` VALUES (40, 40, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_role` VALUES (41, 41, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_role` VALUES (42, 42, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_role` VALUES (43, 43, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_role` VALUES (44, 44, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_role` VALUES (45, 45, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_role` VALUES (46, 46, 1, '2019-04-01 19:43:41', '2019-04-01 19:43:41');
INSERT INTO `permission_role` VALUES (47, 47, 1, '2019-04-01 19:43:41', '2019-04-01 19:43:41');
INSERT INTO `permission_role` VALUES (48, 48, 1, '2019-04-01 19:43:41', '2019-04-01 19:43:41');
INSERT INTO `permission_role` VALUES (49, 49, 1, '2019-04-01 19:43:41', '2019-04-01 19:43:41');
INSERT INTO `permission_role` VALUES (50, 50, 1, '2019-04-01 19:43:41', '2019-04-01 19:43:41');
INSERT INTO `permission_role` VALUES (51, 51, 1, '2019-04-01 19:43:41', '2019-04-01 19:43:41');
INSERT INTO `permission_role` VALUES (52, 52, 1, '2019-04-01 19:43:42', '2019-04-01 19:43:42');
INSERT INTO `permission_role` VALUES (53, 53, 1, '2019-04-01 19:43:42', '2019-04-01 19:43:42');
INSERT INTO `permission_role` VALUES (54, 54, 1, '2019-04-01 19:43:42', '2019-04-01 19:43:42');
INSERT INTO `permission_role` VALUES (55, 55, 1, '2019-04-01 19:43:42', '2019-04-01 19:43:42');
INSERT INTO `permission_role` VALUES (56, 56, 1, '2019-04-01 19:43:43', '2019-04-01 19:43:43');
INSERT INTO `permission_role` VALUES (57, 57, 1, '2019-04-01 19:43:43', '2019-04-01 19:43:43');
INSERT INTO `permission_role` VALUES (58, 58, 1, '2019-04-01 19:43:43', '2019-04-01 19:43:43');
INSERT INTO `permission_role` VALUES (59, 59, 1, '2019-04-01 19:43:43', '2019-04-01 19:43:43');
INSERT INTO `permission_role` VALUES (60, 60, 1, '2019-04-01 19:43:43', '2019-04-01 19:43:43');
INSERT INTO `permission_role` VALUES (61, 61, 1, '2019-04-01 19:43:43', '2019-04-01 19:43:43');
INSERT INTO `permission_role` VALUES (62, 62, 1, '2019-04-01 19:43:43', '2019-04-01 19:43:43');
INSERT INTO `permission_role` VALUES (63, 63, 1, '2019-04-01 19:43:43', '2019-04-01 19:43:43');
INSERT INTO `permission_role` VALUES (64, 64, 1, '2019-04-01 19:43:44', '2019-04-01 19:43:44');

-- ----------------------------
-- Table structure for permission_user
-- ----------------------------
DROP TABLE IF EXISTS `permission_user`;
CREATE TABLE `permission_user`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `permission_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `permission_user_permission_id_index`(`permission_id`) USING BTREE,
  INDEX `permission_user_user_id_index`(`user_id`) USING BTREE,
  CONSTRAINT `permission_user_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `permission_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 66 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of permission_user
-- ----------------------------
INSERT INTO `permission_user` VALUES (1, 2, 1, '2019-04-01 19:43:36', '2019-04-01 19:43:36');
INSERT INTO `permission_user` VALUES (2, 3, 1, '2019-04-01 19:43:36', '2019-04-01 19:43:36');
INSERT INTO `permission_user` VALUES (3, 4, 1, '2019-04-01 19:43:36', '2019-04-01 19:43:36');
INSERT INTO `permission_user` VALUES (4, 5, 1, '2019-04-01 19:43:36', '2019-04-01 19:43:36');
INSERT INTO `permission_user` VALUES (5, 6, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_user` VALUES (6, 7, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_user` VALUES (7, 8, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_user` VALUES (8, 9, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_user` VALUES (9, 10, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_user` VALUES (10, 11, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_user` VALUES (11, 12, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_user` VALUES (12, 13, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_user` VALUES (13, 14, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_user` VALUES (14, 15, 1, '2019-04-01 19:43:37', '2019-04-01 19:43:37');
INSERT INTO `permission_user` VALUES (15, 16, 1, '2019-04-01 19:43:38', '2019-04-01 19:43:38');
INSERT INTO `permission_user` VALUES (16, 17, 1, '2019-04-01 19:43:38', '2019-04-01 19:43:38');
INSERT INTO `permission_user` VALUES (17, 18, 1, '2019-04-01 19:43:38', '2019-04-01 19:43:38');
INSERT INTO `permission_user` VALUES (18, 19, 1, '2019-04-01 19:43:38', '2019-04-01 19:43:38');
INSERT INTO `permission_user` VALUES (19, 20, 1, '2019-04-01 19:43:38', '2019-04-01 19:43:38');
INSERT INTO `permission_user` VALUES (20, 21, 1, '2019-04-01 19:43:38', '2019-04-01 19:43:38');
INSERT INTO `permission_user` VALUES (21, 22, 1, '2019-04-01 19:43:38', '2019-04-01 19:43:38');
INSERT INTO `permission_user` VALUES (22, 23, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_user` VALUES (23, 24, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_user` VALUES (24, 25, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_user` VALUES (25, 26, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_user` VALUES (26, 27, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_user` VALUES (27, 28, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_user` VALUES (28, 29, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_user` VALUES (29, 30, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_user` VALUES (30, 31, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_user` VALUES (31, 32, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_user` VALUES (32, 33, 1, '2019-04-01 19:43:39', '2019-04-01 19:43:39');
INSERT INTO `permission_user` VALUES (33, 34, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_user` VALUES (34, 35, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_user` VALUES (35, 36, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_user` VALUES (36, 37, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_user` VALUES (37, 38, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_user` VALUES (38, 39, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_user` VALUES (39, 40, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_user` VALUES (40, 41, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_user` VALUES (41, 42, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_user` VALUES (42, 43, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_user` VALUES (43, 44, 1, '2019-04-01 19:43:40', '2019-04-01 19:43:40');
INSERT INTO `permission_user` VALUES (44, 45, 1, '2019-04-01 19:43:41', '2019-04-01 19:43:41');
INSERT INTO `permission_user` VALUES (45, 46, 1, '2019-04-01 19:43:41', '2019-04-01 19:43:41');
INSERT INTO `permission_user` VALUES (46, 47, 1, '2019-04-01 19:43:41', '2019-04-01 19:43:41');
INSERT INTO `permission_user` VALUES (47, 48, 1, '2019-04-01 19:43:41', '2019-04-01 19:43:41');
INSERT INTO `permission_user` VALUES (48, 49, 1, '2019-04-01 19:43:41', '2019-04-01 19:43:41');
INSERT INTO `permission_user` VALUES (49, 50, 1, '2019-04-01 19:43:41', '2019-04-01 19:43:41');
INSERT INTO `permission_user` VALUES (50, 51, 1, '2019-04-01 19:43:42', '2019-04-01 19:43:42');
INSERT INTO `permission_user` VALUES (51, 52, 1, '2019-04-01 19:43:42', '2019-04-01 19:43:42');
INSERT INTO `permission_user` VALUES (52, 53, 1, '2019-04-01 19:43:42', '2019-04-01 19:43:42');
INSERT INTO `permission_user` VALUES (53, 54, 1, '2019-04-01 19:43:42', '2019-04-01 19:43:42');
INSERT INTO `permission_user` VALUES (54, 55, 1, '2019-04-01 19:43:43', '2019-04-01 19:43:43');
INSERT INTO `permission_user` VALUES (55, 56, 1, '2019-04-01 19:43:43', '2019-04-01 19:43:43');
INSERT INTO `permission_user` VALUES (56, 57, 1, '2019-04-01 19:43:43', '2019-04-01 19:43:43');
INSERT INTO `permission_user` VALUES (57, 58, 1, '2019-04-01 19:43:43', '2019-04-01 19:43:43');
INSERT INTO `permission_user` VALUES (58, 59, 1, '2019-04-01 19:43:43', '2019-04-01 19:43:43');
INSERT INTO `permission_user` VALUES (59, 60, 1, '2019-04-01 19:43:43', '2019-04-01 19:43:43');
INSERT INTO `permission_user` VALUES (60, 61, 1, '2019-04-01 19:43:43', '2019-04-01 19:43:43');
INSERT INTO `permission_user` VALUES (61, 62, 1, '2019-04-01 19:43:43', '2019-04-01 19:43:43');
INSERT INTO `permission_user` VALUES (62, 63, 1, '2019-04-01 19:43:43', '2019-04-01 19:43:43');
INSERT INTO `permission_user` VALUES (63, 64, 1, '2019-04-01 19:43:44', '2019-04-01 19:43:44');
INSERT INTO `permission_user` VALUES (64, 1, 1, '2019-04-01 20:29:20', '2019-04-01 20:29:20');
INSERT INTO `permission_user` VALUES (65, 65, 1, '2019-04-02 09:24:37', '2019-04-02 09:24:37');

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `model` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `group` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `permissions_slug_unique`(`slug`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 66 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES (1, 'Create CHECKLIST', 'create.checklist', NULL, NULL, '2019-04-01 19:43:27', '2019-04-01 19:43:27', 'CHECKLIST', 'CHECKLIST', 'Create');
INSERT INTO `permissions` VALUES (2, 'Read CHECKLIST', 'read.checklist', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'CHECKLIST', 'CHECKLIST', 'Read');
INSERT INTO `permissions` VALUES (3, 'Update CHECKLIST', 'update.checklist', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'CHECKLIST', 'CHECKLIST', 'Edit');
INSERT INTO `permissions` VALUES (4, 'Delete CHECKLIST', 'delete.checklist', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'CHECKLIST', 'CHECKLIST', 'Delete');
INSERT INTO `permissions` VALUES (5, 'Approval CHECKLIST', 'approval.checklist', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'CHECKLIST', 'CHECKLIST', 'Approval');
INSERT INTO `permissions` VALUES (6, 'Create HISTORY', 'create.history', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'HISTORY', 'HISTORY', 'Create');
INSERT INTO `permissions` VALUES (7, 'Read HISTORY', 'read.history', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'HISTORY', 'HISTORY', 'Read');
INSERT INTO `permissions` VALUES (8, 'Update HISTORY', 'update.history', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'HISTORY', 'HISTORY', 'Edit');
INSERT INTO `permissions` VALUES (9, 'Delete HISTORY', 'delete.history', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'HISTORY', 'HISTORY', 'Delete');
INSERT INTO `permissions` VALUES (10, 'Approval HISTORY', 'approval.history', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'HISTORY', 'HISTORY', 'Approval');
INSERT INTO `permissions` VALUES (11, 'Create PTPP FORM', 'create.ptpp.form', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'PTPP', 'PTPP FORM', 'Create');
INSERT INTO `permissions` VALUES (12, 'Read PTPP FORM', 'read.ptpp.form', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'PTPP', 'PTPP FORM', 'Read');
INSERT INTO `permissions` VALUES (13, 'Update PTPP FORM', 'update.ptpp.form', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'PTPP', 'PTPP FORM', 'Edit');
INSERT INTO `permissions` VALUES (14, 'Delete PTPP FORM', 'delete.ptpp.form', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'PTPP', 'PTPP FORM', 'Delete');
INSERT INTO `permissions` VALUES (15, 'Approval PTPP FORM', 'approval.ptpp.form', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'PTPP', 'PTPP FORM', 'Approval');
INSERT INTO `permissions` VALUES (16, 'File PTPP FORM', 'file.ptpp.form', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'PTPP', 'PTPP FORM', 'File');
INSERT INTO `permissions` VALUES (17, 'Create PTPP FOLLOW UP', 'create.ptpp.follow.up', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'PTPP', 'PTPP FOLLOW UP', 'Create');
INSERT INTO `permissions` VALUES (18, 'Read PTPP FOLLOW UP', 'read.ptpp.follow.up', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'PTPP', 'PTPP FOLLOW UP', 'Read');
INSERT INTO `permissions` VALUES (19, 'Update PTPP FOLLOW UP', 'update.ptpp.follow.up', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'PTPP', 'PTPP FOLLOW UP', 'Edit');
INSERT INTO `permissions` VALUES (20, 'Delete PTPP FOLLOW UP', 'delete.ptpp.follow.up', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'PTPP', 'PTPP FOLLOW UP', 'Delete');
INSERT INTO `permissions` VALUES (21, 'Approval PTPP FOLLOW UP', 'approval.ptpp.follow.up', NULL, NULL, '2019-04-01 19:43:28', '2019-04-01 19:43:28', 'PTPP', 'PTPP FOLLOW UP', 'Approval');
INSERT INTO `permissions` VALUES (22, 'File PTPP FOLLOW UP', 'file.ptpp.follow.up', NULL, NULL, '2019-04-01 19:43:29', '2019-04-01 19:43:29', 'PTPP', 'PTPP FOLLOW UP', 'File');
INSERT INTO `permissions` VALUES (23, 'Create PTPP VERIFICATION', 'create.ptpp.verification', NULL, NULL, '2019-04-01 19:43:29', '2019-04-01 19:43:29', 'PTPP', 'PTPP VERIFICATION', 'Create');
INSERT INTO `permissions` VALUES (24, 'Read PTPP VERIFICATION', 'read.ptpp.verification', NULL, NULL, '2019-04-01 19:43:29', '2019-04-01 19:43:29', 'PTPP', 'PTPP VERIFICATION', 'Read');
INSERT INTO `permissions` VALUES (25, 'Update PTPP VERIFICATION', 'update.ptpp.verification', NULL, NULL, '2019-04-01 19:43:29', '2019-04-01 19:43:29', 'PTPP', 'PTPP VERIFICATION', 'Edit');
INSERT INTO `permissions` VALUES (26, 'Delete PTPP VERIFICATION', 'delete.ptpp.verification', NULL, NULL, '2019-04-01 19:43:29', '2019-04-01 19:43:29', 'PTPP', 'PTPP VERIFICATION', 'Delete');
INSERT INTO `permissions` VALUES (27, 'Approval PTPP VERIFICATION', 'approval.ptpp.verification', NULL, NULL, '2019-04-01 19:43:29', '2019-04-01 19:43:29', 'PTPP', 'PTPP VERIFICATION', 'Approval');
INSERT INTO `permissions` VALUES (28, 'Create CERTIFICATE', 'create.certificate', NULL, NULL, '2019-04-01 19:43:29', '2019-04-01 19:43:29', 'CERTIFICATE', 'CERTIFICATE', 'Create');
INSERT INTO `permissions` VALUES (29, 'Read CERTIFICATE', 'read.certificate', NULL, NULL, '2019-04-01 19:43:29', '2019-04-01 19:43:29', 'CERTIFICATE', 'CERTIFICATE', 'Read');
INSERT INTO `permissions` VALUES (30, 'Update CERTIFICATE', 'update.certificate', NULL, NULL, '2019-04-01 19:43:29', '2019-04-01 19:43:29', 'CERTIFICATE', 'CERTIFICATE', 'Edit');
INSERT INTO `permissions` VALUES (31, 'Delete CERTIFICATE', 'delete.certificate', NULL, NULL, '2019-04-01 19:43:29', '2019-04-01 19:43:29', 'CERTIFICATE', 'CERTIFICATE', 'Delete');
INSERT INTO `permissions` VALUES (32, 'Menu SUBMISSION', 'menu.submission', NULL, NULL, '2019-04-01 19:43:29', '2019-04-01 19:43:29', 'SUBMISSION', '# Menu SUBMISSION', 'Access Menu');
INSERT INTO `permissions` VALUES (33, 'Create SUBMISSION', 'create.submission', NULL, NULL, '2019-04-01 19:43:29', '2019-04-01 19:43:29', 'SUBMISSION', 'SUBMISSION', 'Create');
INSERT INTO `permissions` VALUES (34, 'Read SUBMISSION', 'read.submission', NULL, NULL, '2019-04-01 19:43:29', '2019-04-01 19:43:29', 'SUBMISSION', 'SUBMISSION', 'Read');
INSERT INTO `permissions` VALUES (35, 'Update SUBMISSION', 'update.submission', NULL, NULL, '2019-04-01 19:43:29', '2019-04-01 19:43:29', 'SUBMISSION', 'SUBMISSION', 'Edit');
INSERT INTO `permissions` VALUES (36, 'Delete SUBMISSION', 'delete.submission', NULL, NULL, '2019-04-01 19:43:29', '2019-04-01 19:43:29', 'SUBMISSION', 'SUBMISSION', 'Delete');
INSERT INTO `permissions` VALUES (37, 'Approval SUBMISSION', 'approval.submission', NULL, NULL, '2019-04-01 19:43:30', '2019-04-01 19:43:30', 'SUBMISSION', 'SUBMISSION', 'Approval');
INSERT INTO `permissions` VALUES (38, 'File SUBMISSION', 'file.submission', NULL, NULL, '2019-04-01 19:43:30', '2019-04-01 19:43:30', 'SUBMISSION', 'SUBMISSION', 'File');
INSERT INTO `permissions` VALUES (39, 'Create USER', 'create.user', NULL, NULL, '2019-04-01 19:43:30', '2019-04-01 19:43:30', 'USER', 'USER', 'Create');
INSERT INTO `permissions` VALUES (40, 'Read USER', 'read.user', NULL, NULL, '2019-04-01 19:43:30', '2019-04-01 19:43:30', 'USER', 'USER', 'Read');
INSERT INTO `permissions` VALUES (41, 'Update USER', 'update.user', NULL, NULL, '2019-04-01 19:43:30', '2019-04-01 19:43:30', 'USER', 'USER', 'Edit');
INSERT INTO `permissions` VALUES (42, 'Delete USER', 'delete.user', NULL, NULL, '2019-04-01 19:43:30', '2019-04-01 19:43:30', 'USER', 'USER', 'Delete');
INSERT INTO `permissions` VALUES (43, 'Permission USER', 'permission.user', NULL, NULL, '2019-04-01 19:43:30', '2019-04-01 19:43:30', 'USER', 'USER', 'Permission');
INSERT INTO `permissions` VALUES (44, 'Menu SETTING', 'menu.setting', NULL, NULL, '2019-04-01 19:43:30', '2019-04-01 19:43:30', 'SETTING', '# Menu SETTING', 'Access Menu');
INSERT INTO `permissions` VALUES (45, 'Create SETTING', 'create.setting', NULL, NULL, '2019-04-01 19:43:30', '2019-04-01 19:43:30', 'SETTING', 'SETTING', 'Create');
INSERT INTO `permissions` VALUES (46, 'Read SETTING', 'read.setting', NULL, NULL, '2019-04-01 19:43:30', '2019-04-01 19:43:30', 'SETTING', 'SETTING', 'Read');
INSERT INTO `permissions` VALUES (47, 'Update SETTING', 'update.setting', NULL, NULL, '2019-04-01 19:43:30', '2019-04-01 19:43:30', 'SETTING', 'SETTING', 'Edit');
INSERT INTO `permissions` VALUES (48, 'Delete SETTING', 'delete.setting', NULL, NULL, '2019-04-01 19:43:30', '2019-04-01 19:43:30', 'SETTING', 'SETTING', 'Delete');
INSERT INTO `permissions` VALUES (49, 'Create MASTER PERIODE', 'create.master.periode', NULL, NULL, '2019-04-01 19:43:30', '2019-04-01 19:43:30', 'MASTER', 'MASTER PERIODE', 'Create');
INSERT INTO `permissions` VALUES (50, 'Read MASTER PERIODE', 'read.master.periode', NULL, NULL, '2019-04-01 19:43:31', '2019-04-01 19:43:31', 'MASTER', 'MASTER PERIODE', 'Read');
INSERT INTO `permissions` VALUES (51, 'Update MASTER PERIODE', 'update.master.periode', NULL, NULL, '2019-04-01 19:43:31', '2019-04-01 19:43:31', 'MASTER', 'MASTER PERIODE', 'Edit');
INSERT INTO `permissions` VALUES (52, 'Delete MASTER PERIODE', 'delete.master.periode', NULL, NULL, '2019-04-01 19:43:31', '2019-04-01 19:43:31', 'MASTER', 'MASTER PERIODE', 'Delete');
INSERT INTO `permissions` VALUES (53, 'Create MASTER VENDOR', 'create.master.vendor', NULL, NULL, '2019-04-01 19:43:31', '2019-04-01 19:43:31', 'MASTER', 'MASTER VENDOR', 'Create');
INSERT INTO `permissions` VALUES (54, 'Read MASTER VENDOR', 'read.master.vendor', NULL, NULL, '2019-04-01 19:43:31', '2019-04-01 19:43:31', 'MASTER', 'MASTER VENDOR', 'Read');
INSERT INTO `permissions` VALUES (55, 'Update MASTER VENDOR', 'update.master.vendor', NULL, NULL, '2019-04-01 19:43:31', '2019-04-01 19:43:31', 'MASTER', 'MASTER VENDOR', 'Edit');
INSERT INTO `permissions` VALUES (56, 'Delete MASTER VENDOR', 'delete.master.vendor', NULL, NULL, '2019-04-01 19:43:31', '2019-04-01 19:43:31', 'MASTER', 'MASTER VENDOR', 'Delete');
INSERT INTO `permissions` VALUES (57, 'Create MASTER ITEM', 'create.master.item', NULL, NULL, '2019-04-01 19:43:31', '2019-04-01 19:43:31', 'MASTER', 'MASTER ITEM', 'Create');
INSERT INTO `permissions` VALUES (58, 'Read MASTER ITEM', 'read.master.item', NULL, NULL, '2019-04-01 19:43:32', '2019-04-01 19:43:32', 'MASTER', 'MASTER ITEM', 'Read');
INSERT INTO `permissions` VALUES (59, 'Update MASTER ITEM', 'update.master.item', NULL, NULL, '2019-04-01 19:43:32', '2019-04-01 19:43:32', 'MASTER', 'MASTER ITEM', 'Edit');
INSERT INTO `permissions` VALUES (60, 'Delete MASTER ITEM', 'delete.master.item', NULL, NULL, '2019-04-01 19:43:32', '2019-04-01 19:43:32', 'MASTER', 'MASTER ITEM', 'Delete');
INSERT INTO `permissions` VALUES (61, 'Create MASTER CATEGORY', 'create.master.category', NULL, NULL, '2019-04-01 19:43:32', '2019-04-01 19:43:32', 'MASTER', 'MASTER CATEGORY', 'Create');
INSERT INTO `permissions` VALUES (62, 'Read MASTER CATEGORY', 'read.master.category', NULL, NULL, '2019-04-01 19:43:32', '2019-04-01 19:43:32', 'MASTER', 'MASTER CATEGORY', 'Read');
INSERT INTO `permissions` VALUES (63, 'Update MASTER CATEGORY', 'update.master.category', NULL, NULL, '2019-04-01 19:43:33', '2019-04-01 19:43:33', 'MASTER', 'MASTER CATEGORY', 'Edit');
INSERT INTO `permissions` VALUES (64, 'Delete MASTER CATEGORY', 'delete.master.category', NULL, NULL, '2019-04-01 19:43:33', '2019-04-01 19:43:33', 'MASTER', 'MASTER CATEGORY', 'Delete');
INSERT INTO `permissions` VALUES (65, 'Copy MASTER ITEM', 'copy.master.item', NULL, NULL, '2019-04-02 09:20:03', '2019-04-02 09:20:03', 'MASTER', 'MASTER ITEM', 'Copy');

-- ----------------------------
-- Table structure for ptpp
-- ----------------------------
DROP TABLE IF EXISTS `ptpp`;
CREATE TABLE `ptpp`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `date_created` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `to_function` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `from` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `category_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_by` int(10) UNSIGNED NULL DEFAULT NULL,
  `approval_to_spv_rsd` int(10) UNSIGNED NULL DEFAULT NULL,
  `status_approval_to_spv_rsd` int(11) NOT NULL DEFAULT 0,
  `approval_at_spv_rsd` timestamp(0) NULL DEFAULT NULL,
  `notes_approval_to_spv_rsd` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `approval_to_oh` int(10) UNSIGNED NULL DEFAULT NULL,
  `status_approval_to_oh` int(11) NOT NULL DEFAULT 0,
  `approval_at_oh` timestamp(0) NULL DEFAULT NULL,
  `notes_approval_to_oh` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `ptpp_category_id_index`(`category_id`) USING BTREE,
  INDEX `ptpp_created_by_index`(`created_by`) USING BTREE,
  INDEX `ptpp_approval_to_spv_rsd_index`(`approval_to_spv_rsd`) USING BTREE,
  INDEX `ptpp_approval_to_oh_index`(`approval_to_oh`) USING BTREE,
  CONSTRAINT `ptpp_approval_to_oh_foreign` FOREIGN KEY (`approval_to_oh`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `ptpp_approval_to_spv_rsd_foreign` FOREIGN KEY (`approval_to_spv_rsd`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `ptpp_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `ptpp_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of ptpp
-- ----------------------------
INSERT INTO `ptpp` VALUES (1, 'PTPP-IV-2019-001', '2019-04-01 19:45:16', 'Engineering Planing & Maintenance', 'P2', 'dermaga', 'Perlu perbaikan', 16, 1, 2, 2, '2019-04-02 08:43:45', NULL, 3, 2, '2019-04-01 19:49:20', NULL, '2019-04-01 19:44:00', '2019-04-02 08:43:45');

-- ----------------------------
-- Table structure for ptpp_files
-- ----------------------------
DROP TABLE IF EXISTS `ptpp_files`;
CREATE TABLE `ptpp_files`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ptpp_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `file` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `ptpp_files_ptpp_id_index`(`ptpp_id`) USING BTREE,
  CONSTRAINT `ptpp_files_ptpp_id_foreign` FOREIGN KEY (`ptpp_id`) REFERENCES `ptpp` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of ptpp_files
-- ----------------------------
INSERT INTO `ptpp_files` VALUES (1, 1, 'ABC', 'uploads/ptpp-file/20190401194516-Screenshot_3.png');
INSERT INTO `ptpp_files` VALUES (2, 1, 'DSK', 'uploads/ptpp-file/20190401194516-Screenshot_4.png');

-- ----------------------------
-- Table structure for ptpp_follow_up_details
-- ----------------------------
DROP TABLE IF EXISTS `ptpp_follow_up_details`;
CREATE TABLE `ptpp_follow_up_details`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ptpp_follow_up_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `problem` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `prevention` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `pic` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `date_finish` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `ptpp_follow_up_details_ptpp_follow_up_id_index`(`ptpp_follow_up_id`) USING BTREE,
  CONSTRAINT `ptpp_follow_up_details_ptpp_follow_up_id_foreign` FOREIGN KEY (`ptpp_follow_up_id`) REFERENCES `ptpp_follow_ups` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of ptpp_follow_up_details
-- ----------------------------
INSERT INTO `ptpp_follow_up_details` VALUES (11, 1, 'Ba', 'ABC', 'PJS', '2019-04-02 00:00:00');
INSERT INTO `ptpp_follow_up_details` VALUES (12, 1, 'CD', 'asd', 'asd', '2019-04-10 00:00:00');

-- ----------------------------
-- Table structure for ptpp_follow_up_files
-- ----------------------------
DROP TABLE IF EXISTS `ptpp_follow_up_files`;
CREATE TABLE `ptpp_follow_up_files`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ptpp_follow_up_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `file` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `ptpp_follow_up_files_ptpp_follow_up_id_index`(`ptpp_follow_up_id`) USING BTREE,
  CONSTRAINT `ptpp_follow_up_files_ptpp_follow_up_id_foreign` FOREIGN KEY (`ptpp_follow_up_id`) REFERENCES `ptpp_follow_ups` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of ptpp_follow_up_files
-- ----------------------------
INSERT INTO `ptpp_follow_up_files` VALUES (2, 1, 'asdas', NULL);
INSERT INTO `ptpp_follow_up_files` VALUES (3, 1, 'asdasd', NULL);

-- ----------------------------
-- Table structure for ptpp_follow_ups
-- ----------------------------
DROP TABLE IF EXISTS `ptpp_follow_ups`;
CREATE TABLE `ptpp_follow_ups`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ptpp_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `date` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) UNSIGNED NULL DEFAULT NULL,
  `approval_to_spv_epm` int(10) UNSIGNED NULL DEFAULT NULL,
  `status_approval_to_spv_epm` int(11) NOT NULL DEFAULT 0,
  `approval_at_spv_epm` timestamp(0) NULL DEFAULT NULL,
  `notes_approval_to_spv_epm` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `ptpp_follow_ups_ptpp_id_index`(`ptpp_id`) USING BTREE,
  INDEX `ptpp_follow_ups_created_by_index`(`created_by`) USING BTREE,
  INDEX `ptpp_follow_ups_approval_to_spv_epm_index`(`approval_to_spv_epm`) USING BTREE,
  CONSTRAINT `ptpp_follow_ups_approval_to_spv_epm_foreign` FOREIGN KEY (`approval_to_spv_epm`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `ptpp_follow_ups_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `ptpp_follow_ups_ptpp_id_foreign` FOREIGN KEY (`ptpp_id`) REFERENCES `ptpp` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of ptpp_follow_ups
-- ----------------------------
INSERT INTO `ptpp_follow_ups` VALUES (1, 1, 'Perbaikan segera', '2019-04-01 00:00:00', 1, 4, 2, '2019-04-01 20:59:57', NULL, '2019-04-01 19:50:33', '2019-04-01 20:59:57');

-- ----------------------------
-- Table structure for ptpp_verificators
-- ----------------------------
DROP TABLE IF EXISTS `ptpp_verificators`;
CREATE TABLE `ptpp_verificators`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ptpp_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `approval_to_oh` int(10) UNSIGNED NULL DEFAULT NULL,
  `status_approval_to_oh` int(11) NOT NULL DEFAULT 0,
  `approval_at_oh` timestamp(0) NULL DEFAULT NULL,
  `notes_approval_to_oh` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `no_ptpp_new` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `ptpp_verificators_ptpp_id_index`(`ptpp_id`) USING BTREE,
  INDEX `ptpp_verificators_approval_to_oh_index`(`approval_to_oh`) USING BTREE,
  CONSTRAINT `ptpp_verificators_approval_to_oh_foreign` FOREIGN KEY (`approval_to_oh`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `ptpp_verificators_ptpp_id_foreign` FOREIGN KEY (`ptpp_id`) REFERENCES `ptpp` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of ptpp_verificators
-- ----------------------------
INSERT INTO `ptpp_verificators` VALUES (1, 1, 3, 2, '2019-04-02 08:45:47', NULL, 0, '', '2019-04-02 08:10:45', '2019-04-02 08:45:47');

-- ----------------------------
-- Table structure for role_user
-- ----------------------------
DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `role_user_role_id_index`(`role_id`) USING BTREE,
  INDEX `role_user_user_id_index`(`user_id`) USING BTREE,
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of role_user
-- ----------------------------
INSERT INTO `role_user` VALUES (1, 1, 1, '2019-04-01 19:43:34', '2019-04-01 19:43:34');
INSERT INTO `role_user` VALUES (2, 1, 2, '2019-04-01 19:43:34', '2019-04-01 19:43:34');
INSERT INTO `role_user` VALUES (3, 1, 3, '2019-04-01 19:43:35', '2019-04-01 19:43:35');
INSERT INTO `role_user` VALUES (4, 1, 4, '2019-04-01 19:43:36', '2019-04-01 19:43:36');

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `roles_slug_unique`(`slug`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'Administrator', 'administrator', NULL, 1, '2019-04-01 19:43:33', '2019-04-01 19:43:33');

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `input_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of settings
-- ----------------------------
INSERT INTO `settings` VALUES (1, 'Approval ke Sr. Spv. RSD', 'spv_rsd', 'Pengaturan approval ke pegawai yang mempunyai jabatan Sr. Spv. RSD', '2', 'user');
INSERT INTO `settings` VALUES (2, 'Approval ke OH ', 'spv_oh', 'Pengaturan approval ke pegawai yang mempunyai jabatan OH', '3', 'user');
INSERT INTO `settings` VALUES (3, 'Approval ke Spv. EPM ', 'spv_epm', 'Pengaturan approval ke pegawai yang mempunyai jabatan Spv. EPM', '4', 'user');

-- ----------------------------
-- Table structure for submission_files
-- ----------------------------
DROP TABLE IF EXISTS `submission_files`;
CREATE TABLE `submission_files`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `submission_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `file` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `submission_files_submission_id_index`(`submission_id`) USING BTREE,
  CONSTRAINT `submission_files_submission_id_foreign` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of submission_files
-- ----------------------------
INSERT INTO `submission_files` VALUES (1, 1, 'Foto pompa', 'uploads/submission-file/20190402102607-4adbec0a-d7b8-44e1-ab39-74ddb3cbb799_169.jpeg', NULL, NULL);

-- ----------------------------
-- Table structure for submissions
-- ----------------------------
DROP TABLE IF EXISTS `submissions`;
CREATE TABLE `submissions`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `notes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `category_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_by` int(10) UNSIGNED NULL DEFAULT NULL,
  `approval_to_oh` int(10) UNSIGNED NULL DEFAULT NULL,
  `status_approval_to_oh` int(11) NOT NULL DEFAULT 0,
  `approval_at_oh` timestamp(0) NULL DEFAULT NULL,
  `notes_approval_to_oh` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `approval_to_spv_epm` int(10) UNSIGNED NULL DEFAULT NULL,
  `status_approval_to_spv_epm` int(11) NOT NULL DEFAULT 0,
  `approval_at_spv_epm` timestamp(0) NULL DEFAULT NULL,
  `notes_approval_to_spv_epm` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `submissions_category_id_index`(`category_id`) USING BTREE,
  INDEX `submissions_created_by_index`(`created_by`) USING BTREE,
  INDEX `submissions_approval_to_oh_index`(`approval_to_oh`) USING BTREE,
  INDEX `submissions_approval_to_spv_epm_index`(`approval_to_spv_epm`) USING BTREE,
  CONSTRAINT `submissions_approval_to_oh_foreign` FOREIGN KEY (`approval_to_oh`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `submissions_approval_to_spv_epm_foreign` FOREIGN KEY (`approval_to_spv_epm`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `submissions_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `submissions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of submissions
-- ----------------------------
INSERT INTO `submissions` VALUES (1, 'SUB-IV-2019-001', 'Pompa Rajawali', 'Tekanan pompa sudah tidak kuat lagi', 4, 1, 3, 1, NULL, NULL, 4, 0, NULL, NULL, '2019-04-02 10:26:06', '2019-04-02 10:26:14');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'admin', 'admin', 'admin@pertamina.co.id', '$2y$10$BRXqjiIr3bEGXk7waqDOoeEYTG5tVrUvesEw2/6.Z.m8z9n0FsA2G', NULL, '2019-04-01 19:43:34', '2019-04-01 19:43:34');
INSERT INTO `users` VALUES (2, 'Sr. Spv. RSD', 'rsd', 'rsd@pertamina.co.id', '$2y$10$260nnZGvYnnCZmCgnUZIIO9G8YdLkwZQ9OcRSM9gQaHoKkcIdClQa', 'k4WzjOFr0lFXvjoLd0lw32SKI76T9Vy2k0ns0aBakmEKKgBHcZcKQpRI7DaD', '2019-04-01 19:43:34', '2019-04-01 19:43:34');
INSERT INTO `users` VALUES (3, 'OH', 'oh', 'oh@pertamina.co.id', '$2y$10$mxIzN0eFCpyAyJOBVpKdX.l0zNwChhV4DwqU2lG//ER1V3PDdPCmO', '8YiBZG2nHWQw7Qew6stx6mK7b3xsvhOmOeL1JJXdQe4qeMz1JUp4603Z3JFO', '2019-04-01 19:43:35', '2019-04-01 19:43:35');
INSERT INTO `users` VALUES (4, 'Spv. EPM', 'epm', 'epm@pertamina.co.id', '$2y$10$RrM3S4BKWkG79dzChnuKV.ZP8vNtuOLnWi82I90w6l71u1cK6DqSW', NULL, '2019-04-01 19:43:35', '2019-04-01 19:43:35');

-- ----------------------------
-- Table structure for vendors
-- ----------------------------
DROP TABLE IF EXISTS `vendors`;
CREATE TABLE `vendors`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `pic` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of vendors
-- ----------------------------
INSERT INTO `vendors` VALUES (1, 'PT. JAYA TANK SENTOSA', '085736676648', 'Pringgo Juni Saputro');
INSERT INTO `vendors` VALUES (2, 'PT. PIPA LISTRIK INDONESIA', '085738886648', 'Wijaya Putra');

SET FOREIGN_KEY_CHECKS = 1;
