<?php
include 'db.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $stmt = $conn->prepare("INSERT INTO customers(name,email,phone,address) VALUES (?,?,?,?)");
  $stmt->bind_param("ssss", $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address']);
  if($stmt->execute()){ header("Location: customers.php"); exit; } else { $err = $stmt->error; }
}
include 'header.php';
?>
<div class="card">
  <h2>Add Customer</h2>
  <?php if(!empty($err)) echo "<p class='small' style='color:#dc3545'>Error: ".htmlspecialchars($err)."</p>"; ?>
  <form method="post">
    <label>Name</label>
    <input name="name" required>
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Phone</label>
    <input name="phone">
    <label>Address</label>
    <textarea name="address"></textarea>
    <button class="btn btn-success" type="submit">Save</button>
    <a class="btn" href="customers.php">Cancel</a>
  </form>
</div>
<?php include 'footer.php'; ?>
