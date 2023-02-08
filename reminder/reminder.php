<?php

require_once __DIR__.'/bdays.php';
require_once __DIR__.'/bot_conf.php';
require_once __DIR__.'/users.php';

require_once __DIR__.'/../public/shared/commands.php';


$texts = get_bdays_formatted();
$users = get_users();

foreach($users as &$user)
{
  $out = "";

  if ($user->d0)
    $out .= $texts[0];
  if ($user->d1)
    $out .= chr(10).$texts[1];
  echo $out;
  if (strlen($out) > 0)
    send_message($out, $user->user_id);
}


?>
