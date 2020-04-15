<?php


namespace Bot;


use Bot\API\API;

class App
{
    /**
     * Небольшой роутер
     */
    public static function run(): void
    {
        session_start();

        $uri = $_SERVER['REQUEST_URI'];
        $uri = explode('?', $uri);

        // обрезаем переданные параметры после ?
        if (count($uri)) {
            $uri = $uri[0];
        }

        // разбиваем строку запроса
        $uri = explode('/', $uri);
        if (!$uri) {
            return;
        }

        if ($uri[1] === 'add') {
            static::addUserPage();
        } else {
            static::indexPage();
        }
    }

    /**
     * Выводит индексную страницу
     */
    public static function indexPage(): void
    {
        require_once '../templates/index.php';
    }

    /**
     * Обрабатывает добавление пользователя
     */
    public static function addUserPage(): void
    {
        // если не переданы все данные
        if (!isset($_POST['login'], $_POST['password']) || $_POST['login'] === null || $_POST['password'] === null) {
            Notification::add('Не указан логин и/или пароль!', Notification::ERROR);
            static::indexPage();
            exit;
        }

        // если пользователь уже существует
        if (DataBase::run(
            'SELECT `id` FROM `users` WHERE `login` = ? LIMIT 1',
            [$_POST['login']])->fetch()
        ) {
            Notification::add('Пользователь добавлен!', Notification::SUCCESS);
            static::indexPage();
            exit;
        }

        // пытаемся авторизовать пользователя
        $user = User::createFromLoginPassword($_POST['login'], $_POST['password']);
        $user->auth();

        // если авторизация не удалась
        if ($user->getSession() === null) {
            Notification::add('Авторизация не удалась (либо сервер недоступен как обычно)!', Notification::ERROR);
            static::indexPage();
            exit;
        }

        $passwordHash = User::hash($_POST['password']);

        User::saveToDB(
            $user->getLogin(),
            $passwordHash,
            $user->getClientID(),
            $user->getKey(),
            $user->getSession()
        );
    }
}