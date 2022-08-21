<?php

include_once('api.php');


function isAuth($user_id)
{
  $green_users = array(305099932);
  return in_array($user_id, $green_users);
}


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
    $date_birth = new DateTime($user->date);
    $date_now = new DateTime(date('d.m.Y', strtotime("-1 days")));
    $date_diff = $date_now->diff($date_birth);
    $years_full = $date_diff->y + 1;

    $out .= $date_formatted.' - '.$user->name.' ('.$years_full.')'.chr(10);

    $cur_month = $user_month;
  }

  return $out;
}


function processMessage($message)
{
  $user_id = $message['from']['id'];
  $chat_id = $message['chat']['id'];

  if (isset($message['text']))
  {
    if (!isAuth($user_id))
    {
      sendMessage('Sorry, you are not authorized', $chat_id);
      return;
    }

    $text = mb_convert_case($message['text'], MB_CASE_LOWER, "UTF-8");

    if ($text === "др")
      sendMessage(getBDays(), $chat_id);
  }
}


//
// MAIN
//
echo(isAuth(2) ? 'true' : 'false');
echo('<br>');
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
