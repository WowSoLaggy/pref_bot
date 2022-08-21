<meta charset="UTF-8">
<?php

function logText($text)
{
  $dir_path = '/var/log/blue_bathman_bot';
  file_put_contents($dir_path.'/log_'.date("Y_m_d").'.log', $text.PHP_EOL, FILE_APPEND);
}

function logMessage($message, $isAuth)
{
  $user_id = $message['from']['id'];
  $user_name = $message['from']['username'];
  $is_bot = $message['from']['is_bot'];
  $has_text = isset($message['text']);

  $log = date('Y.m.d H:i:s').' ';

  $log .= $user_name.' ('.$user_id.') ';
  $log .= $is_bot ? '(BOT) ' : '';
  $log .= '- ';
  $log .= $isAuth ? 'GRANT' : 'DENY';
  $log .= ' - ';
  $log .= $has_text ? $message['text'] : 'NO TEXT';

  logText($log);
}

?>
