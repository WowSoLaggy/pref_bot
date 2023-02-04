<?php

include_once('bday.php');


function connect()
{
  include('./../../config/secrets.php');
  
  if (!isset($dbHost) || !isset($dbName) || !isset($dbUser) || !isset($dbPass))
    throw new Exception('Cannot connect to DB: Please check connection settings in "secrets.php" file.');
  
  $link = mysqli_connect($dbHost, $dbUser, $dbPass);
  if (!$link)
    throw new Exception(mysqli_connect_error());

  mysqli_select_db($link, $dbName) or die('Error: '.mysqli_error($link));
  mysqli_query($link, "SET NAMES utf8;");
  
  return $link;
}

function disconnect($connection)
{
  mysqli_close($connection);
}


// Self-written (honestly copied from web) function
// that replaces 'mysql_result'
function mysqli_result($res, $row = 0, $col = 0)
{ 
    $numrows = mysqli_num_rows($res); 
    if ($numrows && $row <= ($numrows - 1) && $row >= 0)
    {
        mysqli_data_seek($res, $row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col]))
            return $resrow[$col];
    }
    return false;
}


function get_bdays_from_db($connection)
{
  $bdays = array();
  
  $result = mysqli_query($connection, "SELECT id, name, date FROM bdays_tbl");
  $num_bdays = mysqli_num_rows($result);
  for ($i = 0; $i < $num_bdays; $i++)
  {
    $bday = new BDay();

    $bday->id = mysqli_result($result, $i, 'id');
    $bday->name = mysqli_result($result, $i, 'name');
    $bday->date = mysqli_result($result, $i, 'date');
    $bday->bday = date('2020-m-d', strtotime($bday->date));

    array_push($bdays, $bday);
  }

  mysqli_free_result($result);

  return $bdays;
}


function bdays_sorter($user1, $user2)
{
  return strtotime($user1->bday) - strtotime($user2->bday);
}


function get_bdays()
{
  $connection = connect();
  $bdays = get_bdays_from_db($connection);
  disconnect($connection);

  usort($bdays, "bdays_sorter");

  // Move bdays with passed bday to the end of list
  for ($i = 0; $i < count($bdays); $i++)
  {
    if (strtotime($bdays[0]->bday) < strtotime(date('2020-m-1')))
      array_push($bdays, array_shift($bdays));
  }

  return $bdays;
}

?>
