<?php

$login    = $_GET['login'];
$password = $_GET['password'];

if (!isset($login, $password)) {
    die('No login or password');
}

echo $login . ' - ' . $password . '<br><br>';

// замена Math.random() из JS
//function jsRand() {
//    return (float)mt_rand() / (float)mt_getrandmax();
//}
//
//// генерирует случайный ClientID
//function guidGenerator() {
//    $S4 = static function(): string {
//        return substr((string)dechex((1+jsRand())*0x10000), 1);
//    };
//    return ($S4() . $S4() . '-' . $S4() . '-' . $S4() . '-' . $S4() . '-' . $S4() . $S4() . $S4());
//}
//
//$passwordHash = base64_encode(sha1($password, true));
//echo 'password hash: ' . $passwordHash . '<br>';
//
//$clientID     = guidGenerator();
//
//$version  = '2.0.2';
//$key      = base64_encode(sha1($version . $passwordHash . $clientID, true));
//
//$usePinCode    = true;
//$pinCode       = '';
//$pinCodeOption = null;
//
//$userData = array(
//    'login' => $login,
//    'clientID' => $clientID,
//    'key' => $key,
//    'version' => $version,
//    'usePinCode' => $usePinCode,
//    'pinCode' => $pinCode,
//    'pinCodeOption' => $pinCodeOption
//);

echo '<pre>';
var_dump($userData);
echo '</pre>';

echo '<br><br> ----------------------------- <br><br>';

$url = 'https://portaldo.mgul.ac.ru/sdo/admin/hs/users/session/new/standard';
$postData = json_encode($userData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

//die($postData);

//$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//// Указываем, что у нас POST запрос
//curl_setopt($ch, CURLOPT_POST, 1);
//// Добавляем переменные
//curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
//
//$output = curl_exec($ch);
//curl_close($ch);

echo '<pre>';
var_dump($output ?? '');
echo '</pre>';

$file = fopen('sdo-output.txt', 'ab+');
fwrite($file, $output ?? '' . PHP_EOL . ' ' . PHP_EOL);
fclose($file);