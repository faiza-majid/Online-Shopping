<?php
include 'db.php';
include 'header.php';
$sql = "SELECT p.payment_id, p.payment_date, p.amount, p.payment_method, o.order_id, c.name as customer_name
        FROM payments p
        JOIN orders o ON o.order_id = p.order_id
        JOIN customers c ON c.customer_id = o.customer_id
        ORDER BY p.payment_id DESC";
$res = $conn->query($sql);
?>
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center">
    <h2>Payments</h2>
    <a class="btn btn-success" href="payment_add.php">+ Add Payment</a>
  </div>
  <table>
    <tr><th>ID</th><th>Order #</th><th>Customer</th><th>Method</th><th>Amount (PKR)</th><th>Date</th></tr>
    <?php while($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $row['payment_id'] ?></td>
        <td><a href="order_view.php?id=<?= $row['order_id'] ?>">#<?= $row['order_id'] ?></a></td>
        <td><?= htmlspecialchars($row['customer_name']) ?></td>
        <td><?= htmlspecialchars($row['payment_method']) ?></td>
        <td><?= number_format($row['amount'],2) ?></td>
        <td><?= htmlspecialchars($row['payment_date']) ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>
<?php include 'footer.php'; ?>
