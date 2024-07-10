<?php
require("./database.php");
session_start();

$userId = $_SESSION["userId"] ?? false;

if (!$userId) {
    header("Location: not_found.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];

    $updated = mysqli_query($database, "update users set email = '$email', username = '$username' where id = $userId");
    if ($updated) {
        header("Location: /profile.php");
        exit();
    }
}

$user = mysqli_query($database, "select * from users where id = $userId")->fetch_assoc();

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
    <link rel="stylesheet" href="./styles/profile.css">
    <title>Профиль пользователя</title>
</head>
<body>
<div class="wrapper">
    <?php include("./navbar.php") ?>

    <main class="main">
        <div class="card">
            <div class="card-title">
                <h1>Карточка пользователя</h1>
                <div class="card-avatar">
                    <?php echo mb_substr($user["username"], 0, 1); ?>
                </div>
            </div>
            <div class="card-content">
                <form method="post" class="form">
                    <div class="wrapper">
                        <div class="wrapper-label">
                            <label>
                                Имя пользователя
                                <br/>
                                <input type="text" name="username" value="<?= $user["username"] ?>" disabled>
                            </label>
                        </div>
                        <div class="wrapper-label">
                            <label>
                                Почта
                                <br/>
                                <input type="email" name="email" value="<?= $user["email"] ?>" disabled>
                            </label>
                        </div>
                    </div>
                </form>
                <div style="margin-top: 20px;">
                    <a href="/list-orders.php">Список покупок</a>
                </div>
                <div class="change-btn">
                    <button>Изменить профиль</button>
                </div>
                <div>
                    <a href="/auth/logout.php">Выйти</a>
                </div>
            </div>
        </div>
    </main>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('.form');
        const editButton = document.querySelector('.change-btn button');

        editButton.addEventListener('click', function () {
            const inputFields = form.querySelectorAll('input');
            inputFields.forEach(function (input) {
                input.removeAttribute('disabled');
            });

            const wrapperActions = document.createElement('div');
            const submitButton = document.createElement('button');
            const cancelButton = document.createElement('button');
            wrapperActions.className = "wrapper-actions"
            submitButton.type = 'submit';
            submitButton.textContent = 'Сохранить изменения';
            submitButton.className = "save-btn"
            cancelButton.type = 'button';
            cancelButton.textContent = 'Отмена';
            cancelButton.className = "cancel-btn";
            wrapperActions.appendChild(submitButton);
            wrapperActions.appendChild(cancelButton);
            form.appendChild(wrapperActions);

            editButton.style.display = 'none';

            cancelButton.addEventListener('click', function () {
                inputFields.forEach(function (input) {
                    input.setAttribute('disabled', 'disabled');
                });

                form.removeChild(wrapperActions);
                editButton.style.display = 'block';
            });
        });
    });
</script>
</body>
</html>