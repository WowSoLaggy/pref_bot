<?php

require_once __DIR__.'/bdays.php';
require_once __DIR__.'/groups.php';

require_once __DIR__.'/../shared/commands.php';
require_once __DIR__.'/../shared/translate.php';


class CommandCtx
{
  function __construct($user_id, $chat_id, $tokens)
  {
    $this->user_id = $user_id;
    $this->chat_id = $chat_id;
    $this->tokens = $tokens;
  }

  public string $user_id = "";
  public string $chat_id = "" ;
  public array $tokens;
}


function cmd_fake(CommandCtx $ctx)
{
  throw new Exception('Not implemented.');
}

function get_help() : string
{
  $text = 'Привет! Я бот - Голубой Банщик.'.chr(10);
  $text .= 'Я умею показывать дни рождения, если ты меня попросишь:'.chr(10);
  $text .= '/bd (или любое другое слово) - ближайшие 2 месяца'.chr(10);
  $text .= '/all (или /все) - на весь год';

  return $text;
}

function cmd_start(CommandCtx $ctx)
{
  send_message(get_help(), $ctx->chat_id);
}

function cmd_all_groups(CommandCtx $ctx)
{
  $kb = create_keyboard(array('test_text' => 'test_callback'));
  send_message('Groupy-groups', $ctx->chat_id, $kb);
}

function cmd_all(CommandCtx $ctx)
{
  send_message(get_bdays_formatted(12), $ctx->chat_id);
}

function cmd_d0(CommandCtx $ctx)
{
  $new_d0 = !is_d01($ctx->user_id, 'd0');
  switch_d01($ctx->user_id, 'd0', $new_d0);
  
  $text = $new_d0 ?
    'Ура! Теперь каждый день примерно вечером я буду предупреждать тебя о наступающих днях рождения!' :
    'Уговорил, больше не буду предупреждать о наступающих днях рождениях...';
  send_message($text, $ctx->chat_id);
}

function cmd_d1(CommandCtx $ctx)
{
  $new_d1 = !is_d01($ctx->user_id, 'd1');
  switch_d01($ctx->user_id, 'd1', $new_d1);
  
  $text = $new_d1 ?
    'Спасибо! Теперь я буду предупреждать тебя о предстоящих днях рождения примерно за сутки!' :
    'Лааааадно, больше не буду предупреждать за сутки...';
  send_message($text, $ctx->chat_id);
}

function cmd_rem(CommandCtx $ctx)
{
  exec('php -f '.__DIR__.'/../../reminder/reminder.php');
}

function cmd_default(CommandCtx $ctx)
{
  send_message(get_bdays_formatted(2), $ctx->chat_id);
}

function get_user_add_help() : string
{
  $text = 'Чтобы добавить новый ДР:'.chr(10);
  $text .= '/add <имя> <yyyy-mm-dd>'.chr(10);
  $text .= 'Например:'.chr(10);
  $text .= '/add Антон 1988-11-13';

  return $text;
}

function cmd_add_bday(CommandCtx $ctx)
{
  // /add Anton 1988-11-13
  if (count($ctx->tokens) < 3)
  {
    send_message(get_user_add_help(), $ctx->chat_id);
    return;
  }

  // Remove command from tokens
  array_shift($ctx->tokens);
  
  // Get date as the last element
  $date = array_pop($ctx->tokens);
  // Combine name from all remain elements
  $name = implode(' ', $ctx->tokens);
  $group = get_or_create_group($ctx->user_id);

  $result = add_bday($name, $date, $group);
  if (!empty($result))
    $response = $result;
  else
  {
    $date_formatted = date('d M', strtotime($date));
    $date_ru = translate_month_en2ru($date_formatted);
    $response = 'День рождения '.$date_ru.' у "'.$name.'" успешно добавлен! Спасибо!';
  }

  send_message($response, $ctx->chat_id);
}


class BotCommand
{
  function __construct($command, $func, $admin)
  {
    $this->command = $command;
    $this->func = $func;
    $this->admin = $admin;
  }

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
  array_push($commands, new BotCommand('/add', 'cmd_add_bday', true));
  array_push($commands, new BotCommand('', 'cmd_default', false));

  return $commands;
}


?>
