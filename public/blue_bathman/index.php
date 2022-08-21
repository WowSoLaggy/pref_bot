<?php

include_once('api.php');


function getBDays()
{
  include_once('reader.php');
  $users = getUsers();

  $out = "";
  $cur_month = "";

  foreach ($users as &$user)
  {
    $user_month = date('M', strtotime($user->date));
    if ($cur_month != $user_month)
    {
      if (!empty($cur_month))
        $out .= chr(10);
      
      $out .= $user_month.chr(10);
      $out .= "-----------------------------------".chr(10);
    }

    $date_formatted = date('d M', strtotime($user->date));
    $out .= $date_formatted.' - '.$user->name.chr(10);

    $cur_month = $user_month;
  }

  return $out;
}


function processMessage($message)
{
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];

  if (isset($message['text']))
  {
    $text = mb_convert_case($message['text'], MB_CASE_LOWER, "UTF-8");

    if ($text === "др")
      sendMessage(getBDays(), $chat_id);
  }
}


//
// MAIN
//
echo(getBDays());


$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update)
{
  // receive wrong update, must not happen
  exit;
}

if (isset($update["message"]))
  processMessage($update["message"]);
