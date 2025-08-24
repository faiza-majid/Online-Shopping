<?php
include 'db.php';

// Fetch customers and products for selection
$customers = $conn->query("SELECT customer_id, name FROM customers ORDER BY name ASC");
$products  = $conn->query("SELECT product_id, name, price, stock FROM products ORDER BY name ASC");

if($_SERVER['REQUEST_METHOD']==='POST'){
  $customer_id = intval($_POST['customer_id'] ?? 0);

  if($customer_id > 0){
    // Create order header
    $stmt = $conn->prepare("INSERT INTO orders(customer_id) VALUES (?)");
    $stmt->bind_param("i", $customer_id);
    if($stmt->execute()){
      $order_id = $stmt->insert_id;

      // Insert up to 5 line items
      for($i=0;$i<5;$i++){
        $pid = intval($_POST['product_id'][$i] ?? 0);
        $qty = intval($_POST['quantity'][$i] ?? 0);
        if($pid > 0 && $qty > 0){
          $stmt2 = $conn->prepare("INSERT INTO order_details(order_id, product_id, quantity) VALUES (?,?,?)");
          $stmt2->bind_param("iii", $order_id, $pid, $qty);
          $stmt2->execute();
        }
      }
      header("Location: order_view.php?id=".$order_id);
      exit;
    } else {
      $err = $stmt->error;
    }
  } else {
    $err = "Please select a customer.";
  }
}

include 'header.php';
?>
<div class="card">
  <h2>Place Order</h2>
  <?php if(!empty($err)) echo "<p class='small' style='color:#dc3545'>Error: ".htmlspecialchars($err)."</p>"; ?>
  <form method="post">
    <label>Customer</label>
    <select name="customer_id" required>
      <option value="">-- select --</option>
      <?php while($c = $customers->fetch_assoc()): ?>
        <option value="<?= $c['customer_id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
      <?php endwhile; ?>
    </select>

    <h3>Items</h3>
    <p class="small">Add up to 5 items. Leave extra rows empty.</p>

    <table>
      <tr><th>Product</th><th>Qty</th></tr>
      <?php for($i=0;$i<5;$i++): ?>
      <tr>
        <td>
          <select name="product_id[]">
            <option value="">-- select product --</option>
            <?php
              $products->data_seek(0);
              while($p = $products->fetch_assoc()): ?>
                <option value="<?= $p['product_id'] ?>">
                  <?= htmlspecialchars($p['name']) ?> (PKR <?= $p['price'] ?>, Stock <?= $p['stock'] ?>)
                </option>
            <?php endwhile; ?>
          </select>
        </td>
        <td><input type="number" name="quantity[]" min="1" value="1"></td>
      </tr>
      <?php endfor; ?>
    </table>

    <button class="btn btn-success" type="submit">Create Order</button>
    <a class="btn" href="orders.php">Cancel</a>
  </form>
</div>
<?php include 'footer.php'; ?>
