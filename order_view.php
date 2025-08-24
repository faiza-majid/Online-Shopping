<?php
include 'db.php';
include 'header.php';
$id = intval($_GET['id'] ?? 0);

$sql = "SELECT o.order_id, o.order_date, c.name AS customer_name, c.email, c.phone, c.address
        FROM orders o
        JOIN customers c ON c.customer_id = o.customer_id
        WHERE o.order_id=$id";
$header = $conn->query($sql)->fetch_assoc();
if(!$header){ die("Order not found"); }

$items = $conn->query("SELECT od.quantity, p.name, p.price, (od.quantity*p.price) as line_total
                       FROM order_details od
                       JOIN products p ON p.product_id = od.product_id
                       WHERE od.order_id=$id");

$total = 0; $rows = [];
while($r = $items->fetch_assoc()){ $rows[] = $r; $total += $r['line_total']; }

$paidRes = $conn->query("SELECT COALESCE(SUM(amount),0) AS paid FROM payments WHERE order_id=$id")->fetch_assoc();
$paid = $paidRes ? (float)$paidRes['paid'] : 0.0;
$due = $total - $paid;
?>
<div class="card">
  <h2>Order #<?= $header['order_id'] ?></h2>
  <p><b>Customer:</b> <?= htmlspecialchars($header['customer_name']) ?> (<?= htmlspecialchars($header['email']) ?>, <?= htmlspecialchars($header['phone']) ?>)</p>
  <p><b>Address:</b> <?= htmlspecialchars($header['address']) ?></p>
  <p><b>Date:</b> <?= htmlspecialchars($header['order_date']) ?></p>
</div>

<div class="card">
  <h3>Items</h3>
  <table>
    <tr><th>Product</th><th>Price</th><th>Qty</th><th>Line Total</th></tr>
    <?php foreach($rows as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['name']) ?></td>
        <td><?= number_format($r['price'],2) ?></td>
        <td><?= (int)$r['quantity'] ?></td>
        <td><?= number_format($r['line_total'],2) ?></td>
      </tr>
    <?php endforeach; ?>
    <tr>
      <th colspan="3" style="text-align:right">Total</th>
      <th><?= number_format($total,2) ?></th>
    </tr>
    <tr>
      <th colspan="3" style="text-align:right">Paid</th>
      <th><?= number_format($paid,2) ?></th>
    </tr>
    <tr>
      <th colspan="3" style="text-align:right">Due</th>
      <th><?= number_format($due,2) ?></th>
    </tr>
  </table>
  <div style="margin-top:12px">
    <a class="btn btn-success" href="payment_add.php?order_id=<?= $header['order_id'] ?>">+ Add Payment</a>
    <a class="btn" href="orders.php">Back to Orders</a>
  </div>
</div>
<?php include 'footer.php'; ?>
