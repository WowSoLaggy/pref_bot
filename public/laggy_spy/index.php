<?php

include_once('bdays.php');
include_once('bot_conf.php');
include_once('users.php');

include_once('./../shared/commands.php');
include_once('./../shared/keyboard.php');
include_once('./../shared/logger.php');


function process(string $user_id, string $chat_id, string $text = null)
{
  $user = get_user($user_id);
  $is_auth = false;

  if (!is_null($user) && !is_null($text))
  {
    if ($text === '/allgroups')
    {
      if ($user->is_admin)
      {
        $is_auth = true;
        $kb = create_keyboard(array('test_text' => 'test_callback'));
        send_message('Groupy-groups', $chat_id, $kb);
      }
    }
    else
    {
      $is_auth = true;
      send_message(get_bdays_formatted(), $chat_id);
    }
  }

  if (!$is_auth)
      send_message('Sorry, you are not authorized', $chat_id);

  return $is_auth;
}


function process_callback(array $callback)
{
  $user_id = $callback['from']['id'];
  $chat_id = $callback['chat_instance'];
  $text = isset($callback['data']) ? $callback['data'] : null;

  $is_auth = process($user_id, $chat_id, $text);
}


function process_message(array $message)
{
  $user_id = $message['from']['id'];
  $chat_id = $message['chat']['id'];
  $text = isset($message['text']) ? $message['text'] : null;

  $is_auth = process($user_id, $chat_id, $text);

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

if (isset($update['callback_query']))
  process_callback($update['callback_query']);
else if (isset($update['message']))
  process_message($update['message']);


?>
