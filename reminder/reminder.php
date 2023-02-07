<?php

require_once __DIR__.'/bot_conf.php';
require_once __DIR__.'/users.php';

require_once __DIR__.'/../public/shared/commands.php';


$users = get_users();

foreach($users as &$user)
{
  send_message('Hello', $user->user_id);
}


?>
