<?php

require_once 'tg_api.php';


function get_url()
{
  $url = 'https://api.telegram.org/bot'.BOT_TOKEN.'/';
  return $url;
}


function send_message(string $text, string $chat_id, string $json_keyboard = null)
{
  $parameters = array(
    'chat_id' => $chat_id,
    'text' => $text
  );
  
  if (!is_null($json_keyboard))
    $parameters['reply_markup'] = $json_keyboard;

  api_request(get_url(), "sendMessage", $parameters);
}


?>
