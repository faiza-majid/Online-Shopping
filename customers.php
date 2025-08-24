<?php
include 'db.php';
include 'header.php';
$res = $conn->query("SELECT * FROM customers ORDER BY customer_id DESC");
?>
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center">
    <h2>Customers</h2>
    <a class="btn btn-success" href="customer_add.php">+ Add Customer</a>
  </div>
  <table>
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Address</th><th>Actions</th></tr>
    <?php while($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['customer_id']) ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= htmlspecialchars($row['address']) ?></td>
        <td class="actions">
          <a class="btn btn-primary" href="customer_edit.php?id=<?= $row['customer_id'] ?>">Edit</a>
          <a class="btn btn-danger" href="customer_delete.php?id=<?= $row['customer_id'] ?>" onclick="return confirm('Delete this customer?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>
<?php include 'footer.php'; ?>
