<?php
session_start();
$userId = $_SESSION["userId"] ?? false;
if ($userId) {
    header("Location: /profile.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"]) {
    require("../database.php");

    $email = $_POST["email"];
    $password = $_POST["password"];

    $user = mysqli_query($database, "select * from users where email = '$email' and password = '$password'")->fetch_assoc();

    if ($user["id"]) {
        $_SESSION["userId"] = $user["id"];
        $_SESSION["admin_able"] = $user["admin_able"];

        header("Location: /index.php");
        exit();
    } else {
        $error = "Неправильные данные";
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
    <title>Авторизация</title>
</head>
<body>
<div class="wrapper">
    <?php include("../navbar.php") ?>

    <main class="main">
        <div class="auth">
            <div class="auth-title">
                <h1>Вход</h1>
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
                    <input type="submit" value="Войти" class="auth-btn">
                    <a href="./registration.php">Нет аккаунта?</a>
                </div>
            </form>
        </div>
    </main>
</div>
</body>
</html>