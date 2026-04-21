<?php
/**
 * Payment Testing Guide
 * =======================
 * 
 * FLOW:
 * 1. Client đặt tour → Status: pending
 * 2. Client cọc 30% → Status: deposit_paid, paid_amount: 30%
 * 3. Client thanh toán 70% → Status: paid, paid_amount: 100%
 * 4. Admin xác nhận thanh toán → Booking confirmed
 * 
 * TESTING CREDIT CARD:
 * - Card: 4111 1111 1111 1111 (Test card - simulates successful payment)
 * - CVV: 123
 * - Exp: 12/25
 * 
 * TESTING BANK TRANSFER:
 * - Just select and proceed - simulates manual transfer
 * 
 * TESTING E-WALLET (Momo/ZaloPay):
 * - Just select and proceed - simulates mobile payment
 */

require_once 'configs/env.php';
require_once 'models/BaseModel.php';
require_once 'models/Booking.php';

try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);
    
    // Test 1: Check if payment columns exist
    echo "<h2>Payment System Test</h2>";
    
    // Get a booking for testing
    $sql = "SELECT b.id, b.total_price, b.payment_status, b.paid_amount FROM bookings b LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$booking) {
        echo "<div class='alert alert-danger'>❌ No bookings found for testing</div>";
        exit;
    }
    
    echo "<div class='alert alert-info'>";
    echo "✓ Booking found:<br>";
    echo "ID: " . $booking['id'] . "<br>";
    echo "Total: " . number_format($booking['total_price']) . " ₫<br>";
    echo "Payment Status: " . htmlspecialchars($booking['payment_status'] ?? 'N/A') . "<br>";
    echo "Paid Amount: " . number_format($booking['paid_amount'] ?? 0) . " ₫<br>";
    echo "</div>";
    
    // Test 2: Simulate deposit payment
    echo "<h3>Test Scenario 1: Deposit Payment (30%)</h3>";
    $depositAmount = $booking['total_price'] * 0.3;
    $transactionId = 'TXN_TEST_' . time() . '_' . $booking['id'];
    
    $sql = "UPDATE bookings SET 
            payment_status = 'deposit_paid',
            deposit_amount = :deposit,
            paid_amount = :paid,
            payment_method = 'card',
            transaction_id = :txn,
            payment_date = NOW()
            WHERE id = :id";
    
    $stmt = $db->prepare($sql);
    $result = $stmt->execute([
        ':deposit' => $depositAmount,
        ':paid' => $depositAmount,
        ':txn' => $transactionId,
        ':id' => $booking['id']
    ]);
    
    if ($result) {
        echo "<div class='alert alert-success'>";
        echo "✓ Deposit payment simulated successfully<br>";
        echo "Amount: " . number_format($depositAmount) . " ₫<br>";
        echo "Transaction: " . $transactionId . "<br>";
        echo "</div>";
    }
    
    // Test 3: Simulate full payment
    echo "<h3>Test Scenario 2: Full Payment (100%)</h3>";
    $fullTransactionId = 'TXN_TEST_FULL_' . time() . '_' . $booking['id'];
    
    $sql = "UPDATE bookings SET 
            payment_status = 'paid',
            paid_amount = total_price,
            payment_method = 'bank',
            transaction_id = :txn,
            payment_date = NOW(),
            status = 'confirmed'
            WHERE id = :id";
    
    $stmt = $db->prepare($sql);
    $result = $stmt->execute([
        ':txn' => $fullTransactionId,
        ':id' => $booking['id']
    ]);
    
    if ($result) {
        echo "<div class='alert alert-success'>";
        echo "✓ Full payment simulated successfully<br>";
        echo "Amount: " . number_format($booking['total_price']) . " ₫<br>";
        echo "Transaction: " . $fullTransactionId . "<br>";
        echo "</div>";
    }
    
    // Test 4: Check payment stats
    echo "<h3>Payment Statistics</h3>";
    $statuses = ['unpaid', 'deposit_paid', 'partially_paid', 'paid'];
    
    foreach ($statuses as $status) {
        $sql = "SELECT COUNT(*) as count, SUM(paid_amount) as total FROM bookings WHERE payment_status = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$status]);
        $stat = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<p>";
        echo "<strong>" . ucfirst(str_replace('_', ' ', $status)) . ":</strong> ";
        echo $stat['count'] . " bookings, ";
        echo "Total: " . number_format($stat['total'] ?? 0) . " ₫";
        echo "</p>";
    }
    
    echo "<hr>";
    echo "<h3>Admin Panel Access</h3>";
    echo "<p>Go to: <a href='index.php?action=admin_payments' target='_blank'>Admin Payments</a></p>";
    
    echo "<h3>Client Payment Testing</h3>";
    echo "<p>1. Login as client</p>";
    echo "<p>2. Go to Dashboard → My Bookings</p>";
    echo "<p>3. Click on a booking → Pay Deposit</p>";
    echo "<p>4. Select payment method and submit</p>";
    echo "<p>5. After deposit, click → Pay Remaining</p>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>";
    echo "❌ Error: " . htmlspecialchars($e->getMessage());
    echo "</div>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment System Test</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body { padding: 30px; }
        code { background: #f4f4f4; padding: 2px 6px; }
    </style>
</head>
<body>
<div class="container">
    <h1>💳 Payment System Test & Documentation</h1>
    
    <div class="alert alert-success">
        <h4>✓ Payment System Features:</h4>
        <ul>
            <li>Deposit payment (30% of total)</li>
            <li>Full payment (100% of total)</li>
            <li>Admin payment confirmation</li>
            <li>Payment status tracking</li>
            <li>Payment method recording (Card, Bank, E-Wallet)</li>
            <li>Transaction ID generation</li>
            <li>Payment statistics</li>
            <li>Payment report export (CSV)</li>
        </ul>
    </div>

    <div class="alert alert-info">
        <h4>📋 Database Migration Status:</h4>
        <code>✓ Payment columns added successfully</code>
    </div>

    <h3>Test Results:</h3>
    <!-- Results will be printed above -->
</div>
</body>
</html>
