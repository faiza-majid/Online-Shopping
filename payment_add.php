<?php
include 'db.php';

// For dropdown
$orders = $conn->query("SELECT o.order_id, c.name as customer_name
                        FROM orders o JOIN customers c ON c.customer_id=o.customer_id
                        ORDER BY o.order_id DESC");

$prefill_id = intval($_GET['order_id'] ?? 0);

if($_SERVER['REQUEST_METHOD']==='POST'){
  $order_id = intval($_POST['order_id'] ?? 0);
  $amount   = floatval($_POST['amount'] ?? 0);
  $method   = $_POST['payment_method'] ?? 'Cash';

  // Compute total and paid to validate
  $tot = $conn->query("SELECT COALESCE(SUM(od.quantity*p.price),0) as total
                       FROM order_details od JOIN products p ON p.product_id=od.product_id
                       WHERE od.order_id=$order_id")->fetch_assoc()['total'] ?? 0;
  $paid = $conn->query("SELECT COALESCE(SUM(amount),0) as paid FROM payments WHERE order_id=$order_id")->fetch_assoc()['paid'] ?? 0;
  $due = $tot - $paid;

  if($order_id<=0){ $err="Please select order."; }
  elseif($amount<=0){ $err="Amount must be greater than zero."; }
  elseif($amount>$due){ $err="Amount exceeds due (PKR ".number_format($due,2).")."; }
  else {
    $stmt = $conn->prepare("INSERT INTO payments(order_id, amount, payment_method) VALUES (?,?,?)");
    $stmt->bind_param("ids", $order_id, $amount, $method);
    if($stmt->execute()){ header("Location: payments.php"); exit; } else { $err = $stmt->error; }
  }
}

include 'header.php';
?>
<div class="card">
  <h2>Add Payment</h2>
  <?php if(!empty($err)) echo "<p class='small' style='color:#dc3545'>Error: ".htmlspecialchars($err)."</p>"; ?>
  <form method="post">
    <label>Order</label>
    <select name="order_id" required>
      <option value="">-- select order --</option>
      <?php while($o = $orders->fetch_assoc()): ?>
        <option value="<?= $o['order_id'] ?>" <?= ($prefill_id==$o['order_id'])?'selected':'' ?>>#<?= $o['order_id'] ?> (<?= htmlspecialchars($o['customer_name']) ?>)</option>
      <?php endwhile; ?>
    </select>

    <label>Amount (PKR)</label>
    <input type="number" step="0.01" name="amount" required>

    <label>Payment Method</label>
    <select name="payment_method" required>
      <option>Cash</option>
      <option>Card</option>
      <option>Bank Transfer</option>
      <option>EasyPaisa</option>
      <option>JazzCash</option>
    </select>

    <button class="btn btn-success" type="submit">Save</button>
    <a class="btn" href="payments.php">Cancel</a>
  </form>
</div>
<?php include 'footer.php'; ?>
