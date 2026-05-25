-- Database Backup
-- Generated: 2026-05-25 13:09:56
-- Database: pos_system_php

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `branches`;
CREATE TABLE `branches` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_no` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `created_at` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branch_name_UNIQUE` (`branch_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `branches` VALUES('2', 'Binan, Laguna', 'City of Binan, Laguna, Sto. Tomas', '845612364', 'ACTIVE', 'Super admin', '2026-05-14 12:42:03');
INSERT INTO `branches` VALUES('3', 'San Pedro Laguna', 'Barangay Chrysantemum, San pedro, Laguna', '999999999', 'ACTIVE', 'Super admin', '2026-05-15 16:52:24');
INSERT INTO `branches` VALUES('4', 'Sta. Rosa, Laguna', 'Binan laguna', '123123123', 'ACTIVE', 'Super admin', '2026-05-16 13:52:59');

DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=visible,1=hidden',
  `branch_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `customers` VALUES('14', 'Jason Mendoza', 'jm@gmail.com', '09554295634', '0', '3', '2026-05-22 21:46:49');
INSERT INTO `customers` VALUES('15', 'John Onario', 'jo@gmail.com', '09912837231', '0', '2', '2026-05-22 21:50:10');
INSERT INTO `customers` VALUES('16', 'Arnel Lipat', 'arnellip@gmail.com', '09952654578', '0', '3', '2026-05-24 12:42:24');

DROP TABLE IF EXISTS `laundry_consumables`;
CREATE TABLE `laundry_consumables` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_name` varchar(100) NOT NULL,
  `quantity` double DEFAULT '0',
  `price` decimal(10,2) DEFAULT '0.00',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `branch_id` int DEFAULT NULL,
  `status` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `laundry_consumables` VALUES('1', 'Fabcon / Fabric Conditioner', '23', '200.00', '2026-05-03 06:42:35', '2', '1');
INSERT INTO `laundry_consumables` VALUES('2', 'Liquid Detergent', '13', '20.00', '2026-05-03 06:55:12', '2', '1');
INSERT INTO `laundry_consumables` VALUES('3', 'Powder Detergent', '11', '15.00', '2026-05-03 19:25:02', '2', '1');
INSERT INTO `laundry_consumables` VALUES('4', 'Bleach / Zonrox', '13', '5.00', '2026-05-03 19:25:38', '2', '1');
INSERT INTO `laundry_consumables` VALUES('13', 'Fabcon / Fabric Conditioner', '94', '18.00', '2026-05-22 13:14:29', '3', '1');
INSERT INTO `laundry_consumables` VALUES('14', 'Liquid Detergent', '94', '20.00', '2026-05-22 13:14:44', '3', '1');
INSERT INTO `laundry_consumables` VALUES('15', 'Powder Detergent', '94', '8.00', '2026-05-22 13:15:01', '3', '1');
INSERT INTO `laundry_consumables` VALUES('16', 'Bleach / Zonrox', '94', '5.00', '2026-05-22 13:15:09', '3', '1');
INSERT INTO `laundry_consumables` VALUES('17', 'Fabcon', '100', '8.00', '2026-05-25 13:02:56', '3', '1');
INSERT INTO `laundry_consumables` VALUES('18', 'Fabcon 2', '100', '8.00', '2026-05-25 13:03:04', '3', '0');

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `service_id` int DEFAULT NULL,
  `consumable_id` int DEFAULT NULL,
  `price` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `order_items` VALUES('1', '1', '17', NULL, '260', '1');
INSERT INTO `order_items` VALUES('2', '1', '17', '4', '5.00', '1');
INSERT INTO `order_items` VALUES('3', '1', '17', '1', '200.00', '1');
INSERT INTO `order_items` VALUES('4', '1', '17', '2', '20.00', '1');
INSERT INTO `order_items` VALUES('5', '1', '17', '3', '15.00', '1');
INSERT INTO `order_items` VALUES('6', '2', '15', NULL, '130', '1');
INSERT INTO `order_items` VALUES('7', '2', '15', '16', '5.00', '1');
INSERT INTO `order_items` VALUES('8', '2', '15', '13', '18.00', '1');
INSERT INTO `order_items` VALUES('9', '2', '15', '14', '20.00', '1');
INSERT INTO `order_items` VALUES('10', '2', '15', '15', '8.00', '1');
INSERT INTO `order_items` VALUES('11', '3', '16', NULL, '260', '1');
INSERT INTO `order_items` VALUES('12', '3', '16', '16', '5.00', '2');
INSERT INTO `order_items` VALUES('13', '3', '16', '13', '18.00', '2');
INSERT INTO `order_items` VALUES('14', '3', '16', '14', '20.00', '2');
INSERT INTO `order_items` VALUES('15', '3', '16', '15', '8.00', '2');

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `tracking_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `invoice_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `total_amount` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `order_date` date NOT NULL,
  `order_status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'cash, online',
  `payment_mode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `order_placed_by_id` int NOT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'New',
  `service_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Pick-up',
  `branch_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `orders` VALUES('1', '15', 'OR-00001', 'INV-862367', '500', '2026-05-22', 'booked', 'Cash Payment', '5', 'New', 'Pick-up', '2');
INSERT INTO `orders` VALUES('2', '14', 'OR-00002', 'INV-897153', '181', '2026-05-22', 'booked', 'Cash Payment', '3', 'New', 'Pick-up', '3');
INSERT INTO `orders` VALUES('3', '16', 'OR-00003', 'INV-988298', '362', '2026-05-24', 'booked', 'Cash Payment', '3', 'New', 'Pick-up', '3');

DROP TABLE IF EXISTS `service_items`;
CREATE TABLE `service_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `service_id` int NOT NULL,
  `consumable_id` int NOT NULL,
  `quantity_required` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `service_items` VALUES('1', '12', '12', '1');
INSERT INTO `service_items` VALUES('2', '1', '4', '1');
INSERT INTO `service_items` VALUES('3', '1', '3', '1');
INSERT INTO `service_items` VALUES('4', '1', '12', '1');
INSERT INTO `service_items` VALUES('5', '2', '4', '1');
INSERT INTO `service_items` VALUES('6', '3', '2', '1');
INSERT INTO `service_items` VALUES('10', '13', '1', '2');
INSERT INTO `service_items` VALUES('11', '13', '3', '1');
INSERT INTO `service_items` VALUES('12', '4', '4', '1');
INSERT INTO `service_items` VALUES('13', '4', '1', '1');
INSERT INTO `service_items` VALUES('14', '4', '2', '3');
INSERT INTO `service_items` VALUES('15', '15', '16', '1');
INSERT INTO `service_items` VALUES('16', '15', '13', '1');
INSERT INTO `service_items` VALUES('17', '15', '14', '1');
INSERT INTO `service_items` VALUES('18', '15', '15', '1');
INSERT INTO `service_items` VALUES('19', '16', '16', '2');
INSERT INTO `service_items` VALUES('20', '16', '13', '2');
INSERT INTO `service_items` VALUES('21', '16', '14', '2');
INSERT INTO `service_items` VALUES('22', '16', '15', '2');
INSERT INTO `service_items` VALUES('23', '17', '4', '1');
INSERT INTO `service_items` VALUES('24', '17', '1', '1');
INSERT INTO `service_items` VALUES('25', '17', '2', '1');
INSERT INTO `service_items` VALUES('26', '17', '3', '1');

DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` int NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=visible,1=hidden',
  `branch_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `services` VALUES('1', '2 Wash 3 Rinse', 'Level: 7\\\\r\\\\nPulses: 18', '90', 'assets/uploads/services/1778907039.jpg', '0', '1', '2024-05-03 08:00:00');
INSERT INTO `services` VALUES('2', '1 Wash 3 Rinse / 2 Wash 2 Rinse', 'Level: 6\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\nPulses: 15', '75', 'assets/uploads/services/1778907501.jpg', '0', '1', '2024-05-03 08:00:00');
INSERT INTO `services` VALUES('3', 'Soak Spin', 'Level: 4\\r\\nPulses: 9', '45', 'assets/uploads/services/1752813882.jpg', '0', '1', '2024-05-03 08:00:00');
INSERT INTO `services` VALUES('4', 'Wash, Dry and Fold (16kg below)', 'Level: 3\\\\r\\\\nPulses: 6', '230', 'assets/uploads/services/1752813891.jpg', '0', '1', '2024-05-03 08:00:00');
INSERT INTO `services` VALUES('13', 'Wash, Dry and Fold (8kg below)', '8KG Below', '60', '', '0', '1', '2026-05-22 20:19:16');
INSERT INTO `services` VALUES('15', 'Wash, Dry and Fold (8kg below)', '8kg below', '130', 'assets/uploads/services/1779456484.jpg', '0', '3', '2026-05-22 21:28:04');
INSERT INTO `services` VALUES('16', 'Wash, Dry and Fold (16kg below)', '16kg below', '260', 'assets/uploads/services/1779456530.jpg', '0', '3', '2026-05-22 21:28:50');
INSERT INTO `services` VALUES('17', 'Wash, Dry and Fold (16kg below)', '16kg below', '260', 'assets/uploads/services/1779457783.jpg', '0', '2', '2026-05-22 21:49:43');

DROP TABLE IF EXISTS `stock_movement`;
CREATE TABLE `stock_movement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `consumable_id` int NOT NULL,
  `movement_type` enum('IN','OUT','ADJUSTMENT','DELETED') DEFAULT NULL,
  `quantity` int NOT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `remarks` text,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `branch_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `stock_movement` VALUES('1', '13', 'IN', '100', 'NEW-1779455669', 'Initial inventory stock', '3', '2026-05-22 21:14:29', '3');
INSERT INTO `stock_movement` VALUES('2', '14', 'IN', '100', 'NEW-1779455684', 'Initial inventory stock', '3', '2026-05-22 21:14:44', '3');
INSERT INTO `stock_movement` VALUES('3', '15', 'IN', '100', 'NEW-1779455701', 'Initial inventory stock', '3', '2026-05-22 21:15:01', '3');
INSERT INTO `stock_movement` VALUES('4', '16', 'IN', '100', 'NEW-1779455709', 'Initial inventory stock', '3', '2026-05-22 21:15:09', '3');
INSERT INTO `stock_movement` VALUES('5', '16', 'OUT', '1', 'OR-00001', 'Used in service: Wash, Dry and Fold (8kg below)', '3', '2026-05-22 21:29:16', NULL);
INSERT INTO `stock_movement` VALUES('6', '13', 'OUT', '1', 'OR-00001', 'Used in service: Wash, Dry and Fold (8kg below)', '3', '2026-05-22 21:29:16', NULL);
INSERT INTO `stock_movement` VALUES('7', '14', 'OUT', '1', 'OR-00001', 'Used in service: Wash, Dry and Fold (8kg below)', '3', '2026-05-22 21:29:16', NULL);
INSERT INTO `stock_movement` VALUES('8', '15', 'OUT', '1', 'OR-00001', 'Used in service: Wash, Dry and Fold (8kg below)', '3', '2026-05-22 21:29:16', NULL);
INSERT INTO `stock_movement` VALUES('9', '16', 'OUT', '2', 'OR-00002', 'Used in service: Wash, Dry and Fold (16kg below)', '3', '2026-05-22 21:46:53', NULL);
INSERT INTO `stock_movement` VALUES('10', '13', 'OUT', '2', 'OR-00002', 'Used in service: Wash, Dry and Fold (16kg below)', '3', '2026-05-22 21:46:53', NULL);
INSERT INTO `stock_movement` VALUES('11', '14', 'OUT', '2', 'OR-00002', 'Used in service: Wash, Dry and Fold (16kg below)', '3', '2026-05-22 21:46:53', NULL);
INSERT INTO `stock_movement` VALUES('12', '15', 'OUT', '2', 'OR-00002', 'Used in service: Wash, Dry and Fold (16kg below)', '3', '2026-05-22 21:46:53', NULL);
INSERT INTO `stock_movement` VALUES('13', '4', 'OUT', '1', 'OR-00003', 'Used in service: Wash, Dry and Fold (16kg below)', '5', '2026-05-22 21:50:13', NULL);
INSERT INTO `stock_movement` VALUES('14', '1', 'OUT', '1', 'OR-00003', 'Used in service: Wash, Dry and Fold (16kg below)', '5', '2026-05-22 21:50:13', NULL);
INSERT INTO `stock_movement` VALUES('15', '2', 'OUT', '1', 'OR-00003', 'Used in service: Wash, Dry and Fold (16kg below)', '5', '2026-05-22 21:50:13', NULL);
INSERT INTO `stock_movement` VALUES('16', '3', 'OUT', '1', 'OR-00003', 'Used in service: Wash, Dry and Fold (16kg below)', '5', '2026-05-22 21:50:13', NULL);
INSERT INTO `stock_movement` VALUES('17', '4', 'OUT', '1', 'OR-00004', 'Used in service: Wash, Dry and Fold (16kg below)', '5', '2026-05-22 21:52:21', NULL);
INSERT INTO `stock_movement` VALUES('18', '1', 'OUT', '1', 'OR-00004', 'Used in service: Wash, Dry and Fold (16kg below)', '5', '2026-05-22 21:52:21', NULL);
INSERT INTO `stock_movement` VALUES('19', '2', 'OUT', '1', 'OR-00004', 'Used in service: Wash, Dry and Fold (16kg below)', '5', '2026-05-22 21:52:21', NULL);
INSERT INTO `stock_movement` VALUES('20', '3', 'OUT', '1', 'OR-00004', 'Used in service: Wash, Dry and Fold (16kg below)', '5', '2026-05-22 21:52:21', NULL);
INSERT INTO `stock_movement` VALUES('21', '1', 'ADJUSTMENT', '5', 'ADJ-1779458041', 'Manual stock adjustment addition', '5', '2026-05-22 21:54:01', '2');
INSERT INTO `stock_movement` VALUES('22', '4', 'OUT', '1', 'OR-00001', 'Used in service: Wash, Dry and Fold (16kg below)', '5', '2026-05-22 21:54:17', '2');
INSERT INTO `stock_movement` VALUES('23', '1', 'OUT', '1', 'OR-00001', 'Used in service: Wash, Dry and Fold (16kg below)', '5', '2026-05-22 21:54:17', '2');
INSERT INTO `stock_movement` VALUES('24', '2', 'OUT', '1', 'OR-00001', 'Used in service: Wash, Dry and Fold (16kg below)', '5', '2026-05-22 21:54:17', '2');
INSERT INTO `stock_movement` VALUES('25', '3', 'OUT', '1', 'OR-00001', 'Used in service: Wash, Dry and Fold (16kg below)', '5', '2026-05-22 21:54:17', '2');
INSERT INTO `stock_movement` VALUES('26', '16', 'OUT', '1', 'OR-00002', 'Used in service: Wash, Dry and Fold (8kg below)', '3', '2026-05-22 21:55:27', '3');
INSERT INTO `stock_movement` VALUES('27', '13', 'OUT', '1', 'OR-00002', 'Used in service: Wash, Dry and Fold (8kg below)', '3', '2026-05-22 21:55:27', '3');
INSERT INTO `stock_movement` VALUES('28', '14', 'OUT', '1', 'OR-00002', 'Used in service: Wash, Dry and Fold (8kg below)', '3', '2026-05-22 21:55:27', '3');
INSERT INTO `stock_movement` VALUES('29', '15', 'OUT', '1', 'OR-00002', 'Used in service: Wash, Dry and Fold (8kg below)', '3', '2026-05-22 21:55:27', '3');
INSERT INTO `stock_movement` VALUES('30', '16', 'OUT', '2', 'OR-00003', 'Used in service: Wash, Dry and Fold (16kg below)', '3', '2026-05-24 12:42:28', '3');
INSERT INTO `stock_movement` VALUES('31', '13', 'OUT', '2', 'OR-00003', 'Used in service: Wash, Dry and Fold (16kg below)', '3', '2026-05-24 12:42:28', '3');
INSERT INTO `stock_movement` VALUES('32', '14', 'OUT', '2', 'OR-00003', 'Used in service: Wash, Dry and Fold (16kg below)', '3', '2026-05-24 12:42:28', '3');
INSERT INTO `stock_movement` VALUES('33', '15', 'OUT', '2', 'OR-00003', 'Used in service: Wash, Dry and Fold (16kg below)', '3', '2026-05-24 12:42:28', '3');
INSERT INTO `stock_movement` VALUES('34', '17', 'IN', '100', 'NEW-1779714176', 'Initial inventory stock', '3', '2026-05-25 21:02:56', '3');
INSERT INTO `stock_movement` VALUES('35', '18', 'IN', '100', 'NEW-1779714184', 'Initial inventory stock', '3', '2026-05-25 21:03:04', '3');
INSERT INTO `stock_movement` VALUES('36', '18', 'DELETED', '100', 'DEL-1779714512', 'Item deleted with remaining stock of: 100', '3', '2026-05-25 21:08:32', '3');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `user_type` enum('admin','staff','super_admin') NOT NULL DEFAULT 'staff',
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `branch_id` int DEFAULT NULL,
  `is_ban` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` VALUES('1', 'Super admin', 'super_admin', 'superadmin@gmail.com', '$2y$12$8BSPnLUS.WnttsQ8qQAX4.2fq7j.tNKOGUn6WxNvjycqE8nx7KlGW', '1234567890', '2', '0', '2026-05-01 15:10:11');
INSERT INTO `users` VALUES('2', 'SPL User', 'staff', 'splstaff@gmail.com', '$2y$12$/1Wz0Yd7BdvPMnBEaY3gJ.v2vQtt69uMVq57ydl6gSygghfwLSYdi', '0987654321', '3', '0', '2026-05-01 15:10:11');
INSERT INTO `users` VALUES('3', 'SPL Admin', 'admin', 'spladmin@gmail.com', '$2y$12$oHsAZe/Fts5k7bRwBdET3emR3SSwbmoGCj0/7reaCbRU2NCyVs3JK', '123321321', '3', '0', '2026-05-14 20:34:34');
INSERT INTO `users` VALUES('4', 'Binan User', 'staff', 'binanuser@gmail.com', '$2y$12$i3XT.pd69JYMCK4G8Ivme.IBpH0YPtagNqDI0kl8Sx.LCd9x.FZju', '09123123123123', '2', '0', '2026-05-15 22:51:59');
INSERT INTO `users` VALUES('5', 'Binan Admin', 'admin', 'binanadmin@gmail.com', '$2y$12$UBbKPur.sPB5bbTiYR1Ux.f3rJ008wYV9MBJjpLGsZiJfDmXVQ7PS', '123', '2', '0', '2026-05-15 23:17:54');

SET FOREIGN_KEY_CHECKS=1;
