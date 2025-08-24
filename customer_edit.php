<?php
include 'db.php';
$id = intval($_GET['id'] ?? 0);
$customer = $conn->query("SELECT * FROM customers WHERE customer_id=$id")->fetch_assoc();
if(!$customer){ die("Customer not found"); }
if($_SERVER['REQUEST_METHOD']==='POST'){
  $stmt = $conn->prepare("UPDATE customers SET name=?, email=?, phone=?, address=? WHERE customer_id=?");
  $stmt->bind_param("ssssi", $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address'], $id);
  if($stmt->execute()){ header("Location: customers.php"); exit; } else { $err = $stmt->error; }
}
include 'header.php';
?>
<div class="card">
  <h2>Edit Customer</h2>
  <?php if(!empty($err)) echo "<p class='small' style='color:#dc3545'>Error: ".htmlspecialchars($err)."</p>"; ?>
  <form method="post">
    <label>Name</label>
    <input name="name" value="<?= htmlspecialchars($customer['name']) ?>" required>
    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>" required>
    <label>Phone</label>
    <input name="phone" value="<?= htmlspecialchars($customer['phone']) ?>">
    <label>Address</label>
    <textarea name="address"><?= htmlspecialchars($customer['address']) ?></textarea>
    <button class="btn btn-success" type="submit">Update</button>
    <a class="btn" href="customers.php">Back</a>
  </form>
</div>
<?php include 'footer.php'; ?>
