<?php
include 'db.php';
include 'header.php';
$res = $conn->query("SELECT * FROM products ORDER BY product_id DESC");
?>
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center">
    <h2>Products</h2>
    <a class="btn btn-success" href="product_add.php">+ Add Product</a>
  </div>
  <table>
    <tr><th>ID</th><th>Name</th><th>Price</th><th>Stock</th><th>Actions</th></tr>
    <?php while($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['product_id']) ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td>PKR <?= htmlspecialchars($row['price']) ?></td>
        <td><?= htmlspecialchars($row['stock']) ?></td>
        <td class="actions">
          <a class="btn btn-primary" href="product_edit.php?id=<?= $row['product_id'] ?>">Edit</a>
          <a class="btn btn-danger" href="product_delete.php?id=<?= $row['product_id'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>
<?php include 'footer.php'; ?>
