<meta charset="UTF-8">
<?php

include_once('./../shared/logger.php');

function log_message(array $message, bool $is_auth)
{
  // Don't forget to create a dir for logs and set owner:
  // chmod www-data:www-data /var/log/my_folder

  $dir_path = '/var/log/blue_bathman_bot';
  log_message_auth($message, $is_auth, $dir_path);
}

?>
