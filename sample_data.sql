-- ========== ADD MISSING COLUMNS TO TOUR_ITINERARIES ==========
ALTER TABLE `tour_itineraries`
ADD COLUMN IF NOT EXISTS `accommodation` VARCHAR(255) DEFAULT NULL COMMENT 'Nơi lưu trú';

-- ========== DELETE OLD DATA AND INSERT NEW DETAILED ITINERARIES FOR TOUR 1 ==========
DELETE FROM `tour_itineraries` WHERE `tour_id` IN (1, 2);

INSERT INTO `tour_itineraries`
(`tour_id`, `day_number`, `title`, `activity`, `meals`, `accommodation`)
VALUES
-- TOUR 1: HẠ LONG
(1, 1, 'Khởi hành từ Hà Nội - Đến Hạ Long',
  'Sáng: Đón khách tại điểm hẹn, khởi hành từ Hà Nội. Trên đường, tham quan Bảo tàng Quảng Ninh. Chiều: Nhận phòng tại khách sạn, dạo phố Hạ Long, tắm biển Bãi Cháy.',
  'Cơm trưa, Cơm tối',
  'Khách sạn 3 sao Hạ Long, view biển'),

(1, 2, 'Du thuyền Vịnh Hạ Long - Tham quan hang động',
  'Sáng: Ăn sáng tại khách sạn. Lên du thuyền 5 sao, khởi hành tham quan Vịnh Hạ Long. Tham quan đảo Titop (trang trại ngọc trai), hang Sửng Sốt (kỳ vĩ nhất), hang Dâu (hang cổ). Trưa: Ăn hải sản tươi trên thuyền. Chiều: Watch sunset trên biển, câu cá đêm. Tối: Ngủ đêm trên du thuyền.',
  'Cơm sáng, Cơm trưa, Cơm tối',
  'Du thuyền 5 sao, cabin sang trọng'),

(1, 3, 'Ngắm bình minh, trở về Hà Nội',
  'Sáng sớm: Ngắm bình minh trên vịnh Hạ Long, tập Yoga trên boong tàu. Ăn sáng trên thuyền. Tham quan thêm các hòn đảo khác, quần thể vân hải. Trưa: Trở về thành phố Hạ Long, ăn trưa. Chiều: Lên xe về Hà Nội, dừng tại Bắc Giang uống trà cúc vàng, mua đặc sản địa phương. Tối: Về đến Hà Nội khoảng 19h.',
  'Cơm sáng, Cơm trưa',
  'Quay về Hà Nội'),

-- TOUR 2: SAPA
(2, 1, 'Hà Nội - Sapa - Tham quan phố cổ',
  'Khởi hành từ Hà Nội lúc 8 giờ sáng. Du lịch trên tàu hỏa có cabin riêng, thoải mái ngủ. Đến Sapa lúc chiều tối. Nhận phòng khách sạn, dạo phố Sapa cổ, tham quan chợ Sapa, gặp gỡ dân tộc Mông Đen bán hàng thủ công.',
  'Cơm trưa, Cơm tối',
  'Khách sạn Sapa 3 sao, view rừng'),

(2, 2, 'Chinh phục Fansipan - Ngọn núi cao nhất Đông Dương',
  'Sáng sớm khởi hành, leo đèo Ô Quy Hồ nhan nhĩ. Đến Fansipan, cáp treo lên đỉnh (3143m) trong 15 phút. Tham quan vườn hoa mùa xuân trên đỉnh, tòa tháp bằng đá cổ. Ăn trưa tại nhà hàng trên đỉnh, ngắm toàn cảnh. Chiều: Quay về Sapa, tham quan bản làng dân tộc Mông, uống cơm lẩu địa phương. Tối: Dạo phố Sapa, ăn tối tự do.',
  'Cơm sáng, Cơm trưa, Cơm tối',
  'Khách sạn Sapa');

-- ========== ADD MORE SAMPLE TOUR DEPARTURES ==========
INSERT INTO `tour_departures`
(`name`, `StartDate`, `ReturnDate`, `Destination`, `IsActive`, `tour_id`, `departure_date`, `capacity`, `status`, `end_date`, `notes`)
VALUES
('Tour Hạ Long 3N2D - 15/4/2026', '2026-04-15', '2026-04-17', 'Hạ Long', 1, 1, '2026-04-15', 20, 'scheduled', '2026-04-17', 'Ngày khởi hành sắp tới'),
('Tour Hạ Long 3N2D - 25/4/2026', '2026-04-25', '2026-04-27', 'Hạ Long', 1, 1, '2026-04-25', 20, 'scheduled', '2026-04-27', 'Có sẵn chỗ'),
('Tour Hạ Long 3N2D - 5/5/2026', '2026-05-05', '2026-05-07', 'Hạ Long', 1, 1, '2026-05-05', 20, 'scheduled', '2026-05-07', 'Giai đoạn khác'),
('Tour Sapa 2N1D - 20/4/2026', '2026-04-20', '2026-04-21', 'Sapa', 1, 2, '2026-04-20', 15, 'scheduled', '2026-04-21', 'Tour cực nổi');

-- ========== UPDATE TOURS WITH FULL INFORMATION ==========
UPDATE `tours` SET
  `description` = 'Khám phá vịnh Hạ Long - di sản thế giới UNESCO. Du thuyền 5 sao, tham quan hang động kỳ thú, tắm biển, ngắm bình minh trên biển. Kỳ nghỉ hoàn hảo cho gia đình và bạn bè.',
  `policy` = 'Bao gồm: Vé vận chuyển, lưu trú 3 sao, ăn uống, du thuyền, hướng dẫn viên tiếng Việt, bảo hiểm du lịch.
Không bao gồm: Chi phí cá nhân, tip hướng dẫn viên, nước uống trên thuyền.
Yêu cầu: Mang CMND/Hộ chiếu còn giá trị.
Hủy tour: Trước 7 ngày hoàn 100%, 7-3 ngày hoàn tiền 50%, dưới 3 ngày không hoàn tiền.',
  `start_location` = 'Hà Nội / Bắc Giang',
  `destinations` = 'Hạ Long => Bãi Cháy => Đảo Titop => Hang Sửng Sốt => Hang Dâu => Vịnh Hạ Long kỳ vĩ',
  `max_capacity` = 30,
  `available_slots` = 25,
  `duration` = 3,
  `status` = 'active'
WHERE `id` = 1;

UPDATE `tours` SET
  `description` = 'Chinh phục Fansipan (3143m) - nóc nhà Đông Dương. Khám phá vùng núi Tây Bắc quyến rũ, gặp gỡ dân tộc Mông Đen, dạo phố cổ Sapa lãng mạn. Tour tuyệt vời cho những ai yêu thích thiên nhiên.',
  `policy` = 'Bao gồm: Tàu hỏa cabin, khách sạn 3 sao, ăn uống, cáp treo Fansipan, hướng dẫn viên, bảo hiểm.
Yêu cầu: Khỏe mạnh, không sợ độ cao, mang theo áo khoác ấm, giày leo núi.
Ghi chú: Thời tiết Sapa hay có sương mù, mang theo áo mưa.',
  `start_location` = 'Hà Nội',
  `destinations` = 'Sapa => Fansipan (3143m) => Bản làng dân tộc Mông => Đèo Ô Quy Hồ nhan nhĩ',
  `status` = 'active'
WHERE `id` = 2;

-- ========== FIX TOUR IMAGES - USE EXISTING ASSETS ==========
UPDATE `tour_images` SET `image_url` = '/assets/css/frontend/images/destination-1.jpg' WHERE `tour_id` = 1 LIMIT 1;
UPDATE `tour_images` SET `image_url` = '/assets/css/frontend/images/destination-2.jpg' WHERE `tour_id` = 1 AND `image_url` != '/assets/css/frontend/images/destination-1.jpg' LIMIT 1;
UPDATE `tour_images` SET `image_url` = '/assets/css/frontend/images/destination-2.jpg' WHERE `tour_id` = 2 LIMIT 1;
UPDATE `tour_images` SET `image_url` = '/assets/css/frontend/images/destination-3.jpg' WHERE `tour_id` = 3 LIMIT 1;

-- ========== DISPLAY SUCCESS ==========
SELECT
  '✅ Cập nhật dữ liệu thành công!' as 'Trạng thái',
  (SELECT COUNT(*) FROM tour_itineraries WHERE tour_id IN (1,2)) as 'Tổng ngày lịch trình',
  (SELECT COUNT(*) FROM tour_departures WHERE tour_id IN (1,2)) as 'Tổng ngày khởi hành',
  (SELECT COUNT(*) FROM tours WHERE id IN (1,2)) as 'Tổng tour'
UNION ALL
SELECT '---', '---', '---', '---'
UNION ALL
SELECT 'Truy cập:', '/index.php?action=client_tour_single&id=1', '(Tour Hạ Long)', ''
UNION ALL
SELECT 'Hoặc:', '/index.php?action=client_tour_single&id=2', '(Tour Sapa)', '';
