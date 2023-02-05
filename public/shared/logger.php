<meta charset="UTF-8">
<?php

// Don't forget to create a dir for logs and set owner:
// chmod www-data:www-data /var/log/my_folder


function log_text(string $text)
{
  file_put_contents(DIR_PATH.'/log_'.date("Y_m_d").'.log', $text.PHP_EOL, FILE_APPEND);
}


function log_data(array $from, string $chat_id, string $text, bool $is_auth)
{
  $log = date('Y.m.d H:i:s').' ';

  $log .= $from['username'].' (first: '.$from['first_name'].', last: '.$from['last_name'].', id: '.$from['id'].', chat: '.$chat_id.') ';
  $log .= $from['is_bot'] ? '(BOT) ' : '';
  $log .= '- ';
  $log .= $is_auth ? 'GRANT' : 'DENY';
  $log .= ' - ';
  $log .= is_null($text) ? 'NO TEXT' : $text;

  log_text($log);
}


function log_message(array $message, bool $is_auth)
{
  $from = $message['from'];
  $chat_id = $message['chat']['id'];
  $text = isset($message['text']) ? $message['text'] : null;

  log_data($from, $chat_id, $text, $is_auth);
}


function log_callback(array $callback, bool $is_auth)
{
  $from = $callback['from'];
  $chat_id = $callback['message']['chat']['id'];
  $text = isset($callback['data']) ? $callback['data'] : null;

  log_data($from, $chat_id, $text, $is_auth);
}


?>
