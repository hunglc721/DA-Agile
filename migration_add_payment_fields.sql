-- Migration: Thêm các cột thanh toán/cọc vào bảng bookings
-- Chạy lệnh này để cập nhật cơ sở dữ liệu

ALTER TABLE `bookings` ADD COLUMN `payment_status` ENUM('unpaid', 'deposit_paid', 'partially_paid', 'paid') DEFAULT 'unpaid' AFTER `status`;

ALTER TABLE `bookings` ADD COLUMN `deposit_amount` DECIMAL(10,2) DEFAULT 0 AFTER `payment_status`;

ALTER TABLE `bookings` ADD COLUMN `paid_amount` DECIMAL(10,2) DEFAULT 0 AFTER `deposit_amount`;

ALTER TABLE `bookings` ADD COLUMN `payment_date` DATETIME DEFAULT NULL AFTER `paid_amount`;

ALTER TABLE `bookings` ADD COLUMN `payment_method` VARCHAR(50) DEFAULT NULL AFTER `payment_date`;

ALTER TABLE `bookings` ADD COLUMN `transaction_id` VARCHAR(100) DEFAULT NULL AFTER `payment_method`;

ALTER TABLE `bookings` ADD COLUMN `receipt_url` VARCHAR(255) DEFAULT NULL AFTER `transaction_id`;

-- Thêm index để tìm kiếm nhanh
ALTER TABLE `bookings` ADD INDEX `idx_payment_status` (`payment_status`);
ALTER TABLE `bookings` ADD INDEX `idx_transaction_id` (`transaction_id`);

-- Cập nhật các booking hiện tại thành 'paid' (giả định đã thanh toán)
UPDATE `bookings` SET `payment_status` = 'paid', `paid_amount` = `total_price` WHERE `status` IN ('confirmed', 'completed');
