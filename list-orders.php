<?php
session_start();
$userId = $_SESSION["userId"] ?? false;
$recordsIds = $_SESSION["productIds"];

if (!$userId) {
    header("Location: /auth/auth.php");
    exit();
}

require("./database.php");
$orders = mysqli_query($database, "select * from orders where user_id = $userId");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./styles/reset.css">
    <link rel="stylesheet" href="./styles/nav.css">
    <link rel="stylesheet" href="./cart/cart.css">
    <title>Список покупок</title>
</head>
<body>
<div class="wrapper">
    <?php include("./navbar.php") ?>

    <main class="main">
        <div class="container" style="gap: 40px;">

            <div class="content">

                <?php
                while ($order = $orders->fetch_assoc()) {
                    $order_id = $order["id"];
                    $items = mysqli_query($database,
                        "select vR.*, g.name as group_name from records_in_orders
                                left join music_shop.vinylRecords vR on vR.id = records_in_orders.records_id
                                left join music_shop.`groups` g on g.id = vR.group_id
                                where order_id = $order_id");

                    echo "<div class='order'>";
                    echo "<div class='order-title'>".$order["date"]."</div>";

                    echo "<div class='wrapper-table'>";
                    echo "Список покупок";
                    echo "<table class='table'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Пластинка</th>";
                    echo "<th></th>";
                    echo "<th>Цена</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    while ($item = $items->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><div style='width: 100px'><img style='width: 100%; object-fit: cover;' src='".$item["img_album"]."' alt=''/></div></td>";
                        echo "<td>" . $item["title"] . " <br/>" . $item["group_name"] . "</td>";
                        echo "<td>".$item["price"]."</td>";
                        echo "</tr>";
                    }

                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";

                    echo "</div>";
                }
                ?>

            </div>
        </div>
    </main>
</div>
</body>
</html>
