<?php

include_once('bday.php');

include_once('./../shared/mysql.php');


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
