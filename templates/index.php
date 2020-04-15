<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>
        * {
            box-sizing: border-box;
        }

        html {
            font-size: 16px;
            font-family: Arial,"Helvetica Neue",Helvetica,sans-serif;
        }

        body {
            margin: 0 auto;
            max-width: 900px;
            padding: 0 1rem;
            text-align: center;
        }

        .notifications {
            padding: 1rem 0;
        }

        .notification {
            position: relative;
            padding: .75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: .25rem;
        }

        .notification.success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .notification.error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .input {
            display: block;
            width: 100%;
            height: calc(1.5em + .75rem + 2px);
            padding: .375rem .75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }

        .btn {
            display: inline-block;
            font-weight: 400;
            color: #212529;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: .25rem;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }

        .btn.primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>

    <title>Бот ЭОС</title>
</head>
<body>

<?php if(isset($_SESSION['notifications'])) : ?>
    <section class="notifications">

        <?php foreach ($_SESSION['notifications'] as $notification) : ?>
            <div class="notification <?php echo $notification->type; ?>">
                <span><?php echo $notification->text; ?></span>
            </div>
        <?php endforeach; ?>

    </section>
<?php endif; ?>

<form action="/add" method="POST">
    <label>
        Логин: <br>
        <input name="login" type="text" class="input">
    </label>

    <label>
        Пароль: <br>
        <input name="password" type="password" class="input">
    </label>

    <br>

    <button type="submit" class="btn">Отправить</button>
</form>

</body>
</html>