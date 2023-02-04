<meta charset="UTF-8">
<?php

// Don't forget to create a dir for logs and set owner:
// chmod www-data:www-data /var/log/my_folder

function log_text(string $text, string $dir_path)
{
  file_put_contents($dir_path.'/log_'.date("Y_m_d").'.log', $text.PHP_EOL, FILE_APPEND);
}

function log_message_auth(array $message, bool $is_auth, string $dir_path)
{
  $user_id = $message['from']['id'];
  $user_name = $message['from']['username'];
  $is_bot = $message['from']['is_bot'];
  $has_text = isset($message['text']);

  $log = date('Y.m.d H:i:s').' ';

  $log .= $user_name.' ('.$user_id.') ';
  $log .= $is_bot ? '(BOT) ' : '';
  $log .= '- ';
  $log .= $is_auth ? 'GRANT' : 'DENY';
  $log .= ' - ';
  $log .= $has_text ? $message['text'] : 'NO TEXT';

  logText($log, $dir_path);
}

?>
