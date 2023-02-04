<?php

include_once './../../config/tokens.php';
include_once './../shared/tg_api.php';


function send_message(string $text, string $chat_id)
{
  $url = 'https://api.telegram.org/bot'.TOKEN_LAGGY_SPY.'/';
  send_message_url($url, $text, $chat_id);
}

?>
