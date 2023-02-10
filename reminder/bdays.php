<?php

require_once __DIR__.'/../public/shared/mysql.php';


class BDay
{
  public $id = 0;
  public $name = "";
  public $date = '2000-1-1';
  public $bday = '1-1';
}


function get_bdays_from_db($connection) : array
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


function get_bdays_d0(array $bdays) : array
{
  $bdays_d0 = array();

  foreach ($bdays as &$bday)
  {
    $dm_now = date('d.m');
    $dm_bday = date('d.m', strtotime($bday->bday));
    if ($dm_now == $dm_bday)
      array_push($bdays_d0, $bday);
  }

  return $bdays_d0;
}


function get_bdays_d1(array $bdays) : array
{
  $bdays_d1 = array();

  foreach ($bdays as &$bday)
  {
    $dm_now = date('d.m', strtotime('+1 day'));
    $dm_bday = date('d.m', strtotime($bday->bday));
    if ($dm_now == $dm_bday)
      array_push($bdays_d1, $bday);
  }

  return $bdays_d1;
}


function get_bdays_formatted() : array
{
  $connection = connect();
  $bdays = get_bdays_from_db($connection);
  disconnect($connection);

  $bdays_d0 = get_bdays_d0($bdays);
  $bdays_d1 = get_bdays_d1($bdays);

  $out = array();

  if (!empty($bdays_d0))
  {
    $date_formatted = date('d M', strtotime($bdays[0]->date));

    $out[0] = $date_formatted.': ';
    foreach ($bdays_d0 as &$bday)
      $out[0] .= $bday->name.' ';
  }

  if (!empty($bdays_d1))
  {
    $date_formatted = date('d M', strtotime($bdays[0]->date));

    $out[1] = $date_formatted.': ';
    foreach ($bdays_d1 as &$bday)
      $out[1] .= $bday->name.' ';
  }

  return $out;
}


?>
