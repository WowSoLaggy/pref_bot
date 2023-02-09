<?php

require_once __DIR__.'/../shared/commands.php';


function cmd_fake($user_id, $chat_id)
{
  throw new Exception('Not implemented.');
}

function get_help()
{
  $text = 'Привет! Я бот - Голубой Банщик.'.chr(10);
  $text .= 'Я умею показывать дни рождения, если ты меня попросишь:'.chr(10);
  $text .= '/bd (или любое другое слово) - ближайшие 2 месяца'.chr(10);
  $text .= '/all (или /все) - на весь год';

  return $text;
}

function cmd_start($user_id, $chat_id)
{
  send_message(get_help(), $chat_id);
}

function cmd_all_groups($user_id, $chat_id)
{
  $kb = create_keyboard(array('test_text' => 'test_callback'));
  send_message('Groupy-groups', $chat_id, $kb);
}

function cmd_all($user_id, $chat_id)
{
  send_message(get_bdays_formatted(12), $chat_id);
}

function cmd_d0($user_id, $chat_id)
{
  $new_d0 = !is_d01($user_id, 'd0');
  switch_d01($user_id, 'd0', $new_d0);
  
  $text = $new_d0 ?
    'Ура! Теперь каждый день примерно в полночь (по Мск) я буду предупреждать тебя о наступивших днях рождения! Это ведь отличная идея присылать сообщения в полночь!' :
    'Уговорил, больше не буду предупреждать о наступивших днях рождения...';
  send_message($text, $chat_id);
}

function cmd_d1($user_id, $chat_id)
{
  $new_d1 = !is_d01($user_id, 'd1');
  switch_d01($user_id, 'd1', $new_d1);
  
  $text = $new_d1 ?
    'Спасибо! Теперь я буду предупреждать тебя о предстоящих днях рождения примерно за сутки!' :
    'Лааааадно, больше не буду предупреждать за сутки...';
  send_message($text, $chat_id);
}

function cmd_rem($user_id, $chat_id)
{
  exec('php -f '.__DIR__.'/../../reminder/reminder.php');
}

function cmd_default($user_id, $chat_id)
{
  send_message(get_bdays_formatted(2), $chat_id);
}


class BotCommand
{
  public $command = '';
  public $func = 'cmd_fake';
  public $admin = false;
}

function get_commands() : array
{
  $commands = array();

  array_push($commands, new BotCommand('/start', 'cmd_start', false));
  array_push($commands, new BotCommand('/allgroups', 'cmd_all_groups', true));
  array_push($commands, new BotCommand('/all', 'cmd_all', false));
  array_push($commands, new BotCommand('/d0', 'cmd_d0', true));
  array_push($commands, new BotCommand('/d1', 'cmd_d1', true));
  array_push($commands, new BotCommand('/rem', 'cmd_rem', true));
  array_push($commands, new BotCommand('', 'cmd_default', false));

  return $commands;
}


?>
