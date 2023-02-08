<?php

require_once __DIR__.'/bdays.php';
require_once __DIR__.'/bot_conf.php';
require_once __DIR__.'/d01.php';
require_once __DIR__.'/users.php';

require_once __DIR__.'/../shared/commands.php';
require_once __DIR__.'/../shared/keyboard.php';
require_once __DIR__.'/../shared/logger.php';


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
    else if ($text === '/d0')
    {
      if ($user->is_admin)
      {
        $new_d0 = !is_d01($user_id, 'd0');
        switch_d01($user_id, 'd0', $new_d0);
        
        $text = $new_d0 ?
          'Ура! Теперь каждый день примерно в полночь (по Мск) я буду предупреждать тебя о наступивших днях рождения! Это ведь отличная идея присылать сообщения в полночь!' :
          'Уговорил, больше не буду предупреждать о наступивших днях рождения...';
        send_message($text, $chat_id);
      }
      else
        $is_auth = false;
    }
    else if ($text === '/d1')
    {
      if ($user->is_admin)
      {
        $new_d1 = !is_d01($user_id, 'd1');
        switch_d01($user_id, 'd1', $new_d1);
        
        $text = $new_d1 ?
          'Спасибо! Теперь я буду предупреждать тебя о предстоящих днях рождения примерно за сутки!' :
          'Лааааадно, больше не буду предупреждать за сутки...';
        send_message($text, $chat_id);
      }
      else
        $is_auth = false;
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
