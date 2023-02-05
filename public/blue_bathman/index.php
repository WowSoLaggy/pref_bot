<?php

include_once('bdays.php');
include_once('bot_conf.php');
include_once('users.php');

include_once('./../shared/commands.php');
include_once('./../shared/keyboard.php');
include_once('./../shared/logger.php');


function get_help()
{
  $text = 'Привет! Я бот - Голубой Банщик.'.chr(10);
  $text .= 'Я умею показывать дни рождения, если ты меня попросишь:'.chr(10);
  $text .= '/bd (или любое другое слово) - ближайшие 2 месяца'.chr(10);
  $text .= '/all (или /все) - на весь год';

  return $text;
}


function process(string $user_id, string $chat_id, string $text = null)
{
  $user = get_user($user_id);
  $is_auth = !is_null($user);

  if (!is_null($user) && !is_null($text))
  {
    if ($text === '/start' || $text === '/help')
    {
      send_message(get_help(), $chat_id);
    }
    else if ($text === '/allgroups')
    {
      if ($user->is_admin)
      {
        $kb = create_keyboard(array('test_text' => 'test_callback'));
        send_message('Groupy-groups', $chat_id, $kb);
      }
      else
        $is_auth = false;
    }
    else if ($text === '/all' || $text === '/все')
    {
      send_message(get_bdays_formatted(12), $chat_id);
    }
    else
    {
      send_message(get_bdays_formatted(2), $chat_id);
    }
  }

  if (!$is_auth)
      send_message('Sorry, you are not authorized', $chat_id);

  return $is_auth;
}


function process_callback(array $callback)
{
  $user_id = $callback['from']['id'];
  $chat_id = $callback['message']['chat']['id'];
  $text = isset($callback['data']) ? $callback['data'] : null;

  $is_auth = process($user_id, $chat_id, $text);

  log_callback($callback, $is_auth);
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
