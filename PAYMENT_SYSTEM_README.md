# Payment Management System - Documentation

## Overview
Admin panel cho quản lý thanh toán booking từ phía khách hàng. Admin có thể xem trạng thái thanh toán, xác nhận đã nhận tiền, và xuất báo cáo.

## Features

### 1. ✅ Admin Panel - Quản Lý Thanh Toán
- **URL:** `/?action=admin_payments`
- **Location:** Admin Sidebar → Quản Lý Thanh Toán

#### Tính Năng:
1. **Danh Sách Booking**
   - Hiển thị tất cả booking với payment status
   - Filter theo trạng thái thanh toán (Unpaid, Deposit, Partial, Paid)
   - Tìm kiếm theo: Mã booking, tên tour, tên khách

2. **Thống Kê Thanh Toán** (Dashboard Cards)
   - Chưa Thanh Toán (Unpaid)
   - Đã Cọc 30% (Deposit Paid)
   - Thanh Toán Một Phần (Partially Paid)
   - Thanh Toán Đầy Đủ (Paid)

3. **Chi Tiết Booking**
   - Click "Xem" → View payment details
   - Xem thông tin khách hàng
   - Xem chi tiết thanh toán (tổng tiền, đã thanh toán, còn phải thanh toán)
   - **Xác nhận thanh toán** - Cập nhật trạng thái thanh toán

4. **Xuất Báo Cáo**
   - Export CSV danh sách thanh toán
   - Filter theo trạng thái trước khi export

### 2. 🔄 Client Payment Flow (Không Thay Đổi)
Khách hàng vẫn có thể:
1. Đặt tour
2. Thanh toán cọc 30%
3. Thanh toán đầy đủ 70%
4. Xem xác nhận

## Admin Payment Management Routes

| Action | URL | Method | Handler |
|--------|-----|--------|---------|
| List payments | `?action=admin_payments` | GET | PaymentController->index() |
| View details | `?action=admin_payments_show&id=X` | GET | PaymentController->show() |
| Confirm payment | `?action=admin_payments_confirm` | POST | PaymentController->confirmPayment() |
| Get stats | `?action=admin_payments_stats` | GET | PaymentController->getPaymentStats() |
| Export report | `?action=admin_payments_export` | GET | PaymentController->exportReport() |

## Payment Status Definition

| Status | Meaning | Client Action | Admin Action |
|--------|---------|---------------|--------------|
| `unpaid` | Chưa thanh toán gì | Thanh toán cọc | N/A |
| `deposit_paid` | Đã thanh toán cọc 30% | Thanh toán 70% còn lại | Chờ thanh toán đầy đủ |
| `partially_paid` | Thanh toán một phần (không đủ) | Thanh toán phần còn lại | Đợi thanh toán đầy đủ |
| `paid` | Thanh toán đầy đủ 100% | Xác nhận hoàn tất | Xác nhận đã nhận tiền |

## Database Schema (Added via Migration)

```sql
ALTER TABLE bookings ADD COLUMN:

- payment_status ENUM('unpaid', 'deposit_paid', 'partially_paid', 'paid') 
  DEFAULT 'unpaid'
  
- deposit_amount DECIMAL(10,2) DEFAULT 0
  -- Số tiền cọc (30% × total_price)
  
- paid_amount DECIMAL(10,2) DEFAULT 0
  -- Tổng số tiền đã thanh toán
  
- payment_date DATETIME NULL
  -- Thời gian thanh toán lần cuối
  
- payment_method VARCHAR(50) NULL
  -- Phương thức: card, bank, wallet
  
- transaction_id VARCHAR(100) NULL
  -- Mã giao dịch: TXN_timestamp_bookingId
  
- receipt_url VARCHAR(255) NULL
  -- URL hóa đơn (để mở rộng)

-- Indexes
ALTER TABLE bookings ADD INDEX idx_payment_status (payment_status);
ALTER TABLE bookings ADD INDEX idx_transaction_id (transaction_id);
```

## How to Use

### For Admin:
1. Login with admin account
2. Go to: **Quản Lý Thanh Toán** in sidebar
3. View all bookings with payment status
4. Filter by payment status or search
5. Click **Xem** to view payment details
6. Update payment status if needed
7. Export report as CSV

### For Client:
1. Login with client account
2. Go to: **Thông Tin Cá Nhân** → **Booking Của Tôi**
3. Select a booking
4. Click **Thanh Toán Cọc** (if not paid)
5. Select payment method and pay
6. After deposit, click **Thanh Toán Đầy Đủ**
7. View confirmation

## Payment Methods Supported

### Current (Simulated):
- ✅ **Thẻ Tín Dụng (Credit Card)** - Visa, Mastercard, JCB
- ✅ **Chuyển Khoản Ngân Hàng (Bank Transfer)**
- ✅ **Ví Điện Tử (E-Wallet)** - Momo, ZaloPay

### To Integrate Real Payment Gateway:
Modify in `ClientController.php`:
- `processDepositPayment()` - Line ~610
- `processFullPayment()` - Line ~670

Replace simulated success with actual API call to:
- Stripe
- PayPal
- Momo API
- ZaloPay API
- Local bank API

## Testing

### Run Payment Test:
```
http://localhost/path-to-agile/test_payment_system.php
```

This will:
1. Check database migration
2. Simulate payment transactions
3. Show payment statistics
4. Verify payment columns

### Manual Test Flow:
1. Create a booking as client
2. Go to deposit payment
3. Select payment method
4. Submit form
5. Should see success message
6. Check admin panel → payment status updated
7. Repeat for full payment

## Files Modified/Created

### New Controllers:
- `controllers/PaymentController.php` - Payment admin management

### New Views:
- `views/admin/payments/index.php` - List payments
- `views/admin/payments/show.php` - View payment details

### Modified Files:
- `routes/index.php` - Added payment routes
- `models/Booking.php` - Added getByPaymentStatus(), getConnection()
- `views/admin/admin_sidebar.php` - Added payment link

### Database:
- `migration_add_payment_fields.sql` - **EXECUTED ✅**

## API Responses

### confirmPayment Success:
```json
{
  "status": "success",
  "message": "Cập nhật trạng thái thanh toán thành công",
  "newStatus": "paid"
}
```

### getPaymentStats Success:
```json
{
  "status": "success",
  "data": {
    "unpaid": {"count": 5, "totalAmount": 1000000},
    "deposit_paid": {"count": 3, "totalAmount": 900000},
    "partially_paid": {"count": 2, "totalAmount": 500000},
    "paid": {"count": 15, "totalAmount": 10000000}
  }
}
```

## Security Notes

1. ✅ Admin check via `requireAdmin()` in PaymentController
2. ✅ Client check via `requireClient()` in ClientController
3. ✅ User verification - payment can only update user's own bookings
4. ✅ CSRF protection - use form token if needed
5. ⚠️ TODO: Validate payment amount matches booking total

## Troubleshooting

### Issue: "Unknown column 'payment_status'"
**Solution:** Migration not run. Run: `php run_migration.php`

### Issue: Admin payment page shows blank
**Solution:** Check if BookingModel has methods:
- `getByPaymentStatus($status)`
- `getConnection()`

### Issue: Payment buttons don't work
**Solution:** Check browser console for JS errors. Ensure AJAX URL is correct.

## Future Enhancements

1. ✅ Add real payment gateway integration
2. ✅ Add payment receipt generation (PDF)
3. ✅ Add email confirmation on payment
4. ✅ Add retry logic for failed payments
5. ✅ Add webhook support for payment status updates
6. ✅ Add payment timeline/history view
7. ✅ Add automated refund processing
8. ✅ Add payment fraud detection

## Contact
For issues or questions, check the admin panel help section.
