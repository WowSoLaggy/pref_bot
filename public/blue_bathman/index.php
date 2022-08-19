<?php

include_once('api.php');


function processMessage($message)
{
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];

  if (isset($message['text']))
  {
    $text = strtolower($message['text']);
    $text = strtolower($text);

    if ($text === "др")
    {
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'Привет!'));
    }
    else
    {
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $text));
    }
  }
}


//
// MAIN
//


$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update)
{
  // receive wrong update, must not happen
  exit;
}

if (isset($update["message"]))
  processMessage($update["message"]);
