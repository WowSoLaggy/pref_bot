<meta charset="UTF-8">
<?php

include_once('./../shared/logger.php');

function log_message(string $message, bool $is_auth)
{
  $dir_path = '/var/log/laggy_spy_bot';
  log_message_auth($message, $is_auth, $dir_path);
}

?>
