<?php
$record_id = $_GET["id"] ?? false;

if (!$record_id) {
    header("Location: /not_found.php");
    exit();
}

session_start();
if (!isset($_SESSION['productIds'])) {
    $_SESSION['productIds'] = array();
}

$productIds = $_SESSION['productIds'];

if (!in_array($record_id, $productIds)) {
    $productIds[] = $record_id;
    $_SESSION['productIds'] = $productIds;
}

header("Location: /cart/cart.php");
exit();
