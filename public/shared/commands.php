<?php

include_once('tg_api.php');


function get_url()
{
  $url = 'https://api.telegram.org/bot'.BOT_TOKEN.'/';
  return $url;
}

function send_message(string $text, string $chat_id)
{
  api_request(get_url(), "sendMessage", array('chat_id' => $chat_id, "text" => $text));
}


?>
