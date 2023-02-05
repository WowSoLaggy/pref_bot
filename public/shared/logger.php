<meta charset="UTF-8">
<?php

// Don't forget to create a dir for logs and set owner:
// chmod www-data:www-data /var/log/my_folder

function log_text(string $text)
{
  file_put_contents(DIR_PATH.'/log_'.date("Y_m_d").'.log', $text.PHP_EOL, FILE_APPEND);
}

function log_message(array $message, bool $is_auth)
{
  $user_id = $message['from']['id'];
  $chat_id = $callback['chat']['id'];
  $user_name = $message['from']['username'];
  $is_bot = $message['from']['is_bot'];
  $has_text = isset($message['text']);

  $log = date('Y.m.d H:i:s').' ';

  $log .= $user_name.' (id: '.$user_id.', chat: '.$chat_id.') ';
  $log .= $is_bot ? '(BOT) ' : '';
  $log .= '- ';
  $log .= $is_auth ? 'GRANT' : 'DENY';
  $log .= ' - ';
  $log .= $has_text ? $message['text'] : 'NO TEXT';

  log_text($log);
}

function log_callback(array $callback)
{
  $user_id = $callback['from']['id'];
  $chat_id = $callback['chat']['id'];
  $user_name = $callback['from']['username'];
  $is_bot = $callback['from']['is_bot'];
  $has_text = isset($callback['text']);

  $log = date('Y.m.d H:i:s').' ';

  $log .= $user_name.' (id: '.$user_id.', chat: '.$chat_id.') ';
  $log .= $is_bot ? '(BOT) ' : '';
  $log .= '- ';
  $log .= $is_auth ? 'GRANT' : 'DENY';
  $log .= ' - ';
  $log .= $has_text ? $callback['text'] : 'NO TEXT';

  log_text($log);
}

?>
