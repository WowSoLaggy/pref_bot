<?php

include_once('api.php');


function isAuth($user_id)
{
  $green_users = array(
    305099932, // ae
    1092343373, // N
    1878144297, // degt
    5236221588, // Nik
    1857829702, // Slava
    225599231, // Nemkin
    322416610, // Vano
    1753858804, // Kozlov
    582065565, // Den
    1166111956, // Goryunov
    1605467087, // Atyaka
  );
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
    $years_full = $date_diff->y;
    if (strtotime($user->bday) >= strtotime(date('2020-m-d')) ||
      date('m') != date('m', strtotime($user->bday)))
    {
      $years_full++;
    }

    $out .= $date_formatted.' - '.$user->name.' ('.$years_full.' yo.)'.chr(10);

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
