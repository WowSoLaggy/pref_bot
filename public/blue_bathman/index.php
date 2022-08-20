<?php

include_once('api.php');


function getOutput()
{
  include('./../../config/secrets.php');
  include_once('reader.php');
  
  if (!isset($dbHost) || !isset($dbName) || !isset($dbUser) || !isset($dbPass))
    return 'Cannot connect to DB: Please check connection settings in "secrets.php" file.';
  
  $link = mysqli_connect($dbHost, $dbUser, $dbPass);
  if (!$link)
    return mysqli_connect_error();

  mysqli_select_db($link, $dbName) or die('Error: '.mysqli_error($link));
  mysqli_query($link, "SET NAMES utf8;");

  $arr = getUsers($link);
  $num = count($arr);

  return $num;
  
  //

  include_once('reader.php');

  $out = "";

  foreach ($users as $user)
    $out .= $user->name.'\n';

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
