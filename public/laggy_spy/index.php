<?php

include_once('bot_conf.php');

include_once('./../shared/commands.php');
include_once('./../shared/logger.php');


function get_is_admin(string $user_id)
{
  $green_users = array(
    305099932, // ae
  );
  return in_array($user_id, $green_users);
}

function get_is_user(string $user_id)
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
    246963951, // Ira Nemkina
  );
  return in_array($user_id, $green_users);
}


function get_bdays_formatted()
{
  include_once('reader.php');
  $bdays = get_bdays();

  $out = "";
  $cur_month = "";

  foreach ($bdays as &$bday)
  {
    $bday_month = date('M', strtotime($bday->date));
    if ($cur_month != $bday_month)
    {
      if (!empty($cur_month))
        $out .= chr(10);
      
      $out .= $bday_month.chr(10);
      $out .= "-----------------------------------".chr(10);
    }

    $date_formatted = date('d M', strtotime($bday->date));
    $date_birth = new DateTime($bday->date);
    $date_now = new DateTime(date('d.m.Y', strtotime("-1 days")));
    $date_diff = $date_now->diff($date_birth);
    $years_full = $date_diff->y;
    if (strtotime($bday->bday) >= strtotime(date('2020-m-d')) ||
      date('m') != date('m', strtotime($bday->bday)))
    {
      $years_full++;
    }

    $out .= $date_formatted.' - '.$bday->name.' ('.$years_full.' yo.)'.chr(10);

    $cur_month = $bday_month;
  }

  return $out;
}


function process_message($message)
{
  $user_id = $message['from']['id'];
  $chat_id = $message['chat']['id'];

  if (isset($message['text']))
  {
    $text = $message['text'];
    $is_auth = false;

    if ($text === '/allgroups')
    {
      if (get_is_admin($user_id))
      {
        $is_auth = true;
        send_message('Groupy-groups', $chat_id);
      }
    }
    else
    {
      if (get_is_user($user_id))
      {
        $is_auth = true;
        send_message(get_bdays_formatted(), $chat_id);
      }
    }

    if (!$is_auth)
      send_message('Sorry, you are not authorized', $chat_id);
  }

  log_message($message, $is_auth);
}


//
// MAIN
//


$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update)
{
  // receive wrong update, must not happen
  exit;
}

if (isset($update["message"]))
  process_message($update["message"]);
