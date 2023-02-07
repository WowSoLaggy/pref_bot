<?php

require_once 'bot_conf.php';
require_once 'users.php';

require_once './../public/shared/commands.php';


$users = get_users();

foreach($users as &$user)
{
  send_message('Hello', $user->user_id);
}


?>
