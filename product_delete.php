<?php
include 'db.php';
$id = intval($_GET['id'] ?? 0);
$conn->query("DELETE FROM products WHERE product_id=$id");
header("Location: products.php");
exit;
