# CSS Backend - Tour Management System

## 📁 Cấu Trúc Thư Mục CSS Backend

Tất cả CSS cho Admin và HDV (Hướng dẫn viên) đã được gom vào folder `assets/css/backend/`.

### CSS Files Dashboard & Main Pages

#### Admin Pages
- **admin_communication.css** - CSS cho trang trao đổi giữa Admin và HDV
- **admin_dashboard.css** - CSS cho dashboard Admin (chứa metrics, charts, activity feed)
- **admin_incident_reports.css** - CSS cho trang báo cáo sự cố
- **admin_tour_financial_report.css** - CSS cho trang báo cáo tài chính

#### HDV (Guide) Pages
- **guides_assigned_tours.css** - CSS cho trang tour được phân công (thẻ tour, khách hàng)
- **guides_checkin.css** - CSS cho trang check-in & điểm danh
- **guides_communication.css** - CSS cho trang trao đổi tin nhắn
- **guides_dashboard.css** - CSS cho dashboard HDV
- **guides_final_report.css** - CSS cho trang báo cáo cuối tour
- **guides_incident_report.css** - CSS cho trang báo cáo sự cố
- **guides_itinerary.css** - CSS cho trang lịch trình tour
- **guides_tour_customers.css** - CSS cho trang danh sách khách hàng tour
- **guides_tour_detail.css** - CSS cho trang chi tiết tour

---

## 🔄 Cách Hoạt Động

### Loading CSS
- Tất cả CSS được load trong **views/layouts/main.php**
- Sử dụng helper function `asset()` để generate đường dẫn đúng
- CSS được load cho cả trang admin và HDV

### Style Tags
- Tất cả `<style>` tags đã được loại bỏ khỏi các view files
- CSS được tập trung trong các file riêng biệt để dễ bảo trì
- Không có inline styles trong view files nữa

---

## 📌 Features Và Styles

### 1. Card & Component Styles
- Border-left colors cho các card (primary, success, info, warning, danger)
- Hover effects với translateY animations
- Box-shadow transitions
- Responsive adjustments

### 2. Typography & Spacing
- Font-size, font-weight customization
- Margin và padding utilities
- Text color utilities
- Text transform (uppercase, etc)

### 3. Animations
- slideIn animations cho messages
- fadeIn animations
- Smooth transitions cho buttons và cards
- translateY hover effects

### 4. Responsive Design
- Breakpoints tại 768px và 1200px
- Responsive grid layouts
- Mobile-friendly styles
- Touch-friendly button sizes

### 5. Form Elements
- Input groups style
- Form control focus states
- Button hover effects
- Select/dropdown styling

---

## 🎨 Color Scheme

| Color | Hex | Usage |
|-------|-----|-------|
| Primary Blue | #007bff | Primary buttons, borders |
| Success Green | #28a745 | Success states, check marks |
| Warning Yellow | #ffc107 | Warning states, pending |
| Danger Red | #dc3545 | Error states, delete |
| Info Cyan | #17a2b8 | Info messages, secondary |
| Gray | #6c757d | Neutral elements |

---

## 🔧 Maintenance

### Thêm CSS Mới
1. Tạo file `{page_name}.css` trong folder này
2. Thêm link vào **views/layouts/main.php**
3. Viết CSS theo naming conventions hiện tại

### Update CSS
1. Chỉnh sửa file CSS tương ứng
2. Tránh dùng `!important` ngoại trừ khi cần thiết
3. Giữ CSS organized theo sections (Comments)

### Browser Compatibility
- All modern browsers (Chrome, Firefox, Safari, Edge)
- IE11+ support (CSS Grid, Flexbox)
- Mobile browsers (iOS Safari, Chrome Mobile)

---

## 📝 Notes

- Tất cả CSS files được load trong mọi page (admin & HDV)
- Nếu không cần CSS nào, có thể loại bỏ link khỏi layout
- CSS specificity: Normal classes được dùng, không có element selectors
- BEM naming convention không được follow (theo style cũ), có thể refactor sau

---

**Last Updated:** April 6, 2025
**Version:** 1.0
