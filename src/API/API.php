<?php


namespace Bot\API;


use Bot\User;

class API
{
    /**
     * Основной uri API
     */
    public const API_URI = 'https://portaldo.mgul.ac.ru/sdo/admin/hs/users';

    /**
     * Версия API
     */
    public const API_VERSION = '2.0.2';

    /**
     * Выполняет запрос к API
     *
     * @param string $uri - путь до метода
     * @param $data - данные для запроса
     * @param string $contentType - тип данных запроса
     *
     * @return RequestAnswer
     */
    public static function sendRequest(string $uri, $data, string $contentType = 'text/plain'): RequestAnswer
    {
        $ch = curl_init();

        // url для запроса
        curl_setopt($ch, CURLOPT_URL, static::API_URI . $uri);
        // нам необходим ответ в виде строки
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // нам необходимы заголовки ответа
        curl_setopt($ch, CURLOPT_HEADER, true);
        // указываем, что у нас POST запрос
        curl_setopt($ch, CURLOPT_POST, 1);
        // добавляем данные
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // указываем content-type
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: ' . $contentType));
        // ограничиваем время ответа
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $output = curl_exec($ch);
        curl_close($ch);

        if ($output !== false) {
            $chInfo   = curl_getinfo($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode) {
                $httpCode = (int)$httpCode;
            }
            $headers  = substr($output, 0, $chInfo['header_size']);
            $body     = substr($output, $chInfo['header_size']);
        }

        return new RequestAnswer($body ?? false, $httpCode ?? null, $headers ?? null);
    }

    /**
     * Отправляет запрос на авторизацию пользователя
     *
     * @param User $user
     *
     * @return RequestAnswer|null - ответ на запрос или null, если не переданы логин и/или пароль
     */
    public static function auth(User $user): ?RequestAnswer
    {
        $userData = array(
            'login'         => $user->getLogin(),
            'clientID'      => $user->getClientID(),
            'key'           => $user->getKey(),
            'version'       => static::API_VERSION,
            'usePinCode'    => true,
            'pinCode'       => '',
            'pinCodeOption' => null
        );
        $userData = json_encode($userData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return static::sendRequest('/session/new/standard', $userData, 'text/plain');
    }
}