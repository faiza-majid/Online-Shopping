<?php
include 'db.php';
$id = intval($_GET['id'] ?? 0);
$conn->query("DELETE FROM customers WHERE customer_id=$id");
header("Location: customers.php");
exit;
