<?php
include 'db.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $stmt = $conn->prepare("INSERT INTO products(name, description, price, stock) VALUES (?,?,?,?)");
  $stmt->bind_param("ssdi", $_POST['name'], $_POST['description'], $_POST['price'], $_POST['stock']);
  if($stmt->execute()){ header("Location: products.php"); exit; } else { $err = $stmt->error; }
}
include 'header.php';
?>
<div class="card">
  <h2>Add Product</h2>
  <?php if(!empty($err)) echo "<p class='small' style='color:#dc3545'>Error: ".htmlspecialchars($err)."</p>"; ?>
  <form method="post">
    <label>Name</label>
    <input name="name" required>
    <label>Description</label>
    <textarea name="description"></textarea>
    <label>Price</label>
    <input type="number" step="0.01" name="price" required>
    <label>Stock</label>
    <input type="number" name="stock" required>
    <button class="btn btn-success" type="submit">Save</button>
    <a class="btn" href="products.php">Cancel</a>
  </form>
</div>
<?php include 'footer.php'; ?>
