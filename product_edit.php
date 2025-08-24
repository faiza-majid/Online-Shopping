<?php
include 'db.php';
$id = intval($_GET['id'] ?? 0);
$product = $conn->query("SELECT * FROM products WHERE product_id=$id")->fetch_assoc();
if(!$product){ die("Product not found"); }
if($_SERVER['REQUEST_METHOD']==='POST'){
  $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, stock=? WHERE product_id=?");
  $stmt->bind_param("ssdii", $_POST['name'], $_POST['description'], $_POST['price'], $_POST['stock'], $id);
  if($stmt->execute()){ header("Location: products.php"); exit; } else { $err = $stmt->error; }
}
include 'header.php';
?>
<div class="card">
  <h2>Edit Product</h2>
  <?php if(!empty($err)) echo "<p class='small' style='color:#dc3545'>Error: ".htmlspecialchars($err)."</p>"; ?>
  <form method="post">
    <label>Name</label>
    <input name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
    <label>Description</label>
    <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>
    <label>Price</label>
    <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
    <label>Stock</label>
    <input type="number" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required>
    <button class="btn btn-success" type="submit">Update</button>
    <a class="btn" href="products.php">Back</a>
  </form>
</div>
<?php include 'footer.php'; ?>
