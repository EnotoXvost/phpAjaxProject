<?php
session_start();
$userId = $_SESSION["userId"] ?? false;
if ($userId) {
    header("Location: /profile.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require("../database.php");

    $email = $_POST["email"];
    $password = $_POST["password"];

    $user_has = mysqli_query($database, "select * from users where email = '$email'");

    if (mysqli_num_rows($user_has) > 0) {
        $user_has_message = "Данная почта уже зарегистрирована";
    } else {
        $insert = mysqli_query($database, "insert into users (admin_able, username, email, password) values (0, '', '$email', '$password')");

        if ($insert) {
            $user_id = mysqli_insert_id($database);
            $user = mysqli_query($database, "select id, admin_able from users where id = $user_id")->fetch_assoc();
            $_SESSION["userId"] = $user["id"];
            $_SESSION["admin_able"] = $user["admin_able"];
            header("Location: /index.php");
            exit();
        }
    }
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
    <link rel="stylesheet" href="./auth.css">
    <title>Автризация</title>
</head>
<body>
<div class="wrapper">
    <?php include("../navbar.php") ?>

    <main class="main">
        <div class="auth">
            <div class="auth-title">
                <h1>Регистрация</h1>
                <?php
                if ($user_has_message) {
                    echo "<span>Данная почта уже занята</span>";
                }
                ?>
            </div>
            <form method="post" class="form">
                <label>
                    Почта:
                    <br/>
                    <input class="input-form" type="email" name="email" autocomplete="off"
                           placeholder="Введите почту..." required>
                </label>
                <label>
                    Пароль:
                    <br/>
                    <input class="input-form" type="password" name="password" autocomplete="off"
                           placeholder="Введите пароль..." required>
                </label>
                <div class="wrapper-submit-btn">
                    <input type="submit" value="Создать аккаунт" class="auth-btn">
                    <a href="./auth.php">Есть аккаунт?</a>
                </div>
            </form>
        </div>
    </main>
</div>
</body>
</html>