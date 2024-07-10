<?php
session_start();
$userId = $_SESSION["userId"] ?? false;


if (!isset($_SESSION["productIds"])) {
    $_SESSION["productIds"] = array();
}

$recordsIds = $_SESSION["productIds"];

if (!$userId) {
    header("Location: /auth/auth.php");
    exit();
}

require("../database.php");

if (count($recordsIds)) {
    $recordsIdsStr = implode(",", $recordsIds);
    $records = mysqli_query($database,
        "select vinylRecords.*, g.name as group_name 
            from vinylRecords join music_shop.`groups` g on g.id = vinylRecords.group_id 
            where vinylRecords.id in ($recordsIdsStr)");
} else {
    $empty = true;
}

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
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/nav.css">
    <link rel="stylesheet" href="./cart.css">
    <title>Корзина</title>
</head>
<body>
<div class="wrapper">
    <?php include("../navbar.php") ?>

    <main class="main">
        <div class="container">

            <div class="content">
                <h1 class="page-title">Корзина</h1>
                <?php
                if ($empty) {
                    echo "<h2>Корзина пуста</h2>";
                }
                ?>
                <div class="wrapper-table">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Пластинка</th>
                            <th></th>
                            <th>Цена</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($records != null && mysqli_num_rows($records) > 0) {
                        while ($item = $records->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td><div style='width: 100px'><img  style='object-fit: cover; width: 100%' src='" . $item["img_album"] . "' alt=''/></div></td>";
                            echo "<td>" . $item["title"] . "<br/>" . $item["group_name"] . "</td>";
                            echo "<td>" . $item["price"] . "</td>";
                            echo "<td><a href='/cart/remove-cart.php?id=" . $item["id"] . "'>Убрать</a></td>";
                            echo "</tr>";
                        }
                    }
                        ?>


                        </tbody>
                    </table>

                    <div style="margin-top: 20px;">
                        <form action="/order/order-form.php" method="post">
                            <input class="btn-order" type="submit" value="Оформить заказ">
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>
</body>
</html>
