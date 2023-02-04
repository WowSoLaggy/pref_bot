<?php

include_once('send_message.php');
include_once('log_message.php');


function get_is_auth(string $user_id)
{
  $green_users = array(
    '305099932', // ae
  );
  return in_array($user_id, $green_users);
}


function process_message(array $message)
{
  $user_id = $message['from']['id'];
  $chat_id = $message['chat']['id'];

  $is_auth = get_is_auth($user_id);
  
  if (isset($message['text']))
  {
    if (!$is_auth)
    {
      send_message('Sorry, you are not authorized', $chat_id);
    }
    else
    {
      send_message('Hi Anton!', $chat_id);
    }
  }

  log_message($message, $is_auth);
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
  process_message($update["message"]);
