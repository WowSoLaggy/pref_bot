<?php

include_once('api.php');
include_once('log_message.php');


function get_is_auth(string $user_id)
{
  $green_users = array(
    '305099932', // ae
  );
  return in_array($user_id, $green_users);
}


function processMessage($message)
{
  $user_id = $message['from']['id'];
  $chat_id = $message['chat']['id'];

  $is_auth = is_auth($user_id);
  
  if (isset($message['text']))
  {
    if (!isAuth)
    {
      sendMessage('Sorry, you are not authorized', $chat_id);
    }
    else
    {
      sendMessage('Hi Anton!', $chat_id);
    }
  }

  log_message($message, $isAuth);
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
