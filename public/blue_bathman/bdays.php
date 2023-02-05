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


function get_bdays_formatted(int $months_to_show)
{
  $bdays = get_bdays();

  $out = "";
  $cur_month = "";
  $months_shown = 0;

  foreach ($bdays as &$bday)
  {
    $bday_month = date('M', strtotime($bday->date));
    if ($cur_month != $bday_month)
    {
      ++$months_shown;
      if ($months_shown > $months_to_show)
        break;

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
      ++$years_full;
    }

    $out .= $date_formatted.' - '.$bday->name.' ('.$years_full.' yo.)'.chr(10);

    $cur_month = $bday_month;
  }

  return $out;
}


?>
