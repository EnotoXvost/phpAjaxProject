<?php
session_start();
$userId = $_SESSION["userId"] ?? false;
$recordsIds = $_SESSION["productIds"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require("../database.php");
    $date = date("Y-m-d");
    $order = mysqli_query($database, "insert into orders (id, user_id, date) values (null, $userId, '$date')");

    if ($order) {
        $order_id = mysqli_insert_id($database);

        for ($i = 0; $i < count($recordsIds); $i++) {
            $recordId = $recordsIds[$i];
            $update_data = mysqli_query($database, "update vinylRecords set quantity = quantity - 1 where id = $recordId;");
            $orderRecord = mysqli_query($database, "insert into records_in_orders (order_id, records_id) 
                                                            values ($order_id, $recordId)");
        }

        $_SESSION["productIds"] = array();
        header("Location: /profile.php");
        exit();
    } else {
        echo mysqli_error($database);
    }
}