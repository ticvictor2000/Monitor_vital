<?php

$botToken = "1039832084:AAGTpRuO9lE92Xo6EbpnCywy5dkOdksG1hQ";
$website = "https://api.telegram.org/bot".$botToken;

$update = file_get_contents('php://input');
$update = json_decode($update, TRUE);

$chatId = $update["message"]["chat"]["id"];
$chatType = $update["message"]["chat"]["type"];
$userId = $update["message"]['from']['id'];
$firstname = $update["message"]['from']['username'];


if ($modo == 0) {
    $firstname = "@".$firstname;
}

$message = $update["message"]["text"];

$agg = json_encode($update, JSON_PRETTY_PRINT);




//Extraemos el Comando
$arr = explode(' ',trim($message));
$command = $arr[0];

$message = substr(strstr($message," "), 1);

//No requieren variables del usuario.
switch ($command) {
    case '/test':
        $response = "Esto es un test";
        sendMessage($chatId, $response);
        break;
     case '/that':
        $response = "Esto es un that";
        sendMessage($chatId, $response);
        break;
}





function sendMessage($chatId, $response, $keyboard = NULL){
    if (isset($keyboard)) {
        $teclado = '&reply_markup={"keyboard":['.$keyboard.'], "resize_keyboard":true, "one_time_keyboard":true}';
    }
    $url = $GLOBALS[website].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response).$teclado;
    file_get_contents($url);
}


?>
