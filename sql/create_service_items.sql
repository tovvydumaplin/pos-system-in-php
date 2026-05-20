CREATE TABLE `service_items` (
    `id`                INT AUTO_INCREMENT PRIMARY KEY,
    `service_id`        INT NOT NULL,
    `consumable_id`     INT NOT NULL,
    `quantity_required` DECIMAL(10,2) NOT NULL DEFAULT 1,
    FOREIGN KEY (`service_id`)    REFERENCES `services`(`id`)            ON DELETE CASCADE,
    FOREIGN KEY (`consumable_id`) REFERENCES `laundry_consumables`(`id`) ON DELETE CASCADE
);
