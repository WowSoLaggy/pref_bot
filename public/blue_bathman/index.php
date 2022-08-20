<?php

include_once('api.php');


function getOutput()
{
  include_once('reader.php');

  $out = "";

  foreach ($users as $user)
    $out .= $user->name.'\n';

  return "Hello";
  //return count($users);
  //return $out;
}


function processMessage($message)
{
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];

  if (isset($message['text']))
  {
    $text = mb_convert_case($message['text'], MB_CASE_LOWER, "UTF-8");

    if ($text === "др")
    {
      $output = getOutput();
      if (empty($output))
        $output = "NO OUTPUT";

      sendMessage($output, $chat_id);
    }
  }
}


//
// MAIN
//
echo(getOutput());


$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update)
{
  // receive wrong update, must not happen
  exit;
}

if (isset($update["message"]))
  processMessage($update["message"]);
