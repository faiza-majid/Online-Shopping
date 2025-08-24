<?php
include 'db.php';
include 'header.php';
$sql = "SELECT o.order_id, o.order_date, c.name AS customer_name,
               COALESCE(SUM(od.quantity * p.price),0) AS total
        FROM orders o
        JOIN customers c ON c.customer_id = o.customer_id
        LEFT JOIN order_details od ON od.order_id = o.order_id
        LEFT JOIN products p ON p.product_id = od.product_id
        GROUP BY o.order_id
        ORDER BY o.order_id DESC";
$res = $conn->query($sql);
?>
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center">
    <h2>Orders</h2>
    <a class="btn btn-success" href="order_add.php">+ Place Order</a>
  </div>
  <table>
    <tr><th>ID</th><th>Customer</th><th>Date</th><th>Total (PKR)</th><th>Actions</th></tr>
    <?php while($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $row['order_id'] ?></td>
        <td><?= htmlspecialchars($row['customer_name']) ?></td>
        <td><?= htmlspecialchars($row['order_date']) ?></td>
        <td><?= number_format($row['total'], 2) ?></td>
        <td class="actions">
          <a class="btn btn-primary" href="order_view.php?id=<?= $row['order_id'] ?>">View</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>
<?php include 'footer.php'; ?>
