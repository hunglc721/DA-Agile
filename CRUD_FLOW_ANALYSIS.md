# 📊 CÁC CRUD HOẠT ĐỘNG NHƯ THẾ NÀO

## 🏗️ KIẾN TRÚC CHUNG

- **View** → **Controller** → **Model** → **Database**
- URL có format: `index.php?action=tour_types_create` (action quyết định gọi method nào)

---

## 🔄 LUỒNG HOẠT ĐỘNG

### 1️⃣ CREATE (Tạo mới)

1. **Hiển thị form**: User click "Thêm" → Controller gọi `create()` → hiển thị form trống
2. **Submit form**: User điền dữ liệu rồi submit POST
3. **Validate**: Controller lấy `$_POST`, gọi `Model->validate()` kiểm tra dữ liệu
   - Nếu lỗi: lưu vào `$_SESSION['errors']`, redirect về form
   - Nếu hợp lệ: tiếp tục
4. **Lưu DB**: Gọi `Model->create($data)` thực thi INSERT
5. **Thông báo**: Lưu thông báo thành công vào `$_SESSION`, redirect về danh sách
6. **Hiển thị**: View danh sách và hiện alert thành công

---

### 2️⃣ READ (Đọc/Xem)

1. **Lấy dữ liệu**: Controller gọi `Model->findAllIncludeInactive()` (SELECT từ DB)
2. **Truyền đến view**: `view('main', ['tourTypes' => $tourTypes])`
3. **Hiển thị**: View duyệt foreach danh sách và in thành bảng HTML

---

### 3️⃣ UPDATE (Cập nhật)

1. **Hiển thị form edit**: User click "Chỉnh sửa" → URL có `id` → Controller lấy `$_GET['id']`
2. **Load dữ liệu cũ**: Model truy vấn để lấy record hiện tại, truyền vào form
3. **Submit form**: User sửa dữ liệu rồi submit POST (có `id` ẩn)
4. **Validate**: Kiểm tra dữ liệu (như CREATE nhưng bỏ qua check code trùng với chính nó)
   - Nếu lỗi: redirect về form edit
   - Nếu hợp lệ: tiếp tục
5. **Cập nhật DB**: Gọi `Model->update($id, $data)` thực thi UPDATE ... WHERE id
6. **Thông báo**: Redirect về danh sách với thông báo thành công

---

### 4️⃣ DELETE (Xóa)

1. **Xác nhận**: User click "Xóa" → confirm dialog (JavaScript)
2. **Kiểm tra ràng buộc**: Controller gọi `Model->delete()`, Model kiểm tra:
   - Có tour nào đang dùng loại tour này không?
   - Nếu có: throw exception, không cho xóa
   - Nếu không: thực thi DELETE
3. **Thông báo**: Redirect về danh sách

---

### 5️⃣ TOGGLE STATUS (Bật/Tắt)

1. **Kích hoạt**: User click "Bật/Tắt" → Controller lấy `$_GET['id']`
2. **Cập nhật**: `Model->toggleActive()` → UPDATE `is_active = !is_active`
3. **Thông báo**: Redirect về danh sách

---

## 🗂️ QUY TRÌNH CHUNG

```
User Action (click nút, submit form)
    ↓
URL: index.php?action=xxx
    ↓
Router phân tích action
    ↓
Khởi tạo Controller và gọi method tương ứng
    ↓
Controller:
├─ Lấy dữ liệu ($_GET, $_POST)
├─ Validate (nếu có)
├─ Gọi Model để query DB
└─ Render view hoặc redirect
    ↓
Nếu render view: hiển thị kết quả
Nếu redirect: chuyển sang trang khác + hiện thông báo
```

---

## 🔐 BẢNG SECURITY

| Vấn đề | Giải pháp |
|--------|----------|
| SQL Injection | Prepared statements (PDO prepare/execute) |
| XSS Attack | htmlspecialchars() khi in dữ liệu |
| Trùng lặp dữ liệu | validate() kiểm tra SELECT trước khi INSERT |
| Foreign key violation | Model check COUNT(*) trước DELETE |
| Unauthorized access | Check `isAdmin()` trước khi cho access |

---

## 🎯 ROUTING STRUCTURE

| Action | Method | Mục đích |
|--------|--------|---------|
| tour_types_create | create() | Hiện form tạo |
| tour_types_store | store() | Lưu dữ liệu mới |
| tour_types | index() | Danh sách |
| tour_types_edit | edit() | Hiện form edit |
| tour_types_update | update() | Cập nhật dữ liệu |
| tour_types_delete | delete() | Xóa dữ liệu |
| tour_types_toggle | toggleActive() | Bật/tắt trạng thái |
