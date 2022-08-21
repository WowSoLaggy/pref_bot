<?php

include_once('user.php');


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


function getUsersConn($connection)
{
  $users = array();
  
  $result = mysqli_query($connection, "SELECT id, name, date FROM users_tbl");
  $num_users = mysqli_num_rows($result);
  for ($i = 0; $i < $num_users; $i++)
  {
    $user = new User();

    $user->id = mysqli_result($result, $i, 'id');
    $user->name = mysqli_result($result, $i, 'name');
    $user->date = mysqli_result($result, $i, 'date');
    $user->bday = date('2020-m-d', strtotime($user->date));

    array_push($users, $user);
  }

  mysqli_free_result($result);

  return $users;
}


function users_sorter($user1, $user2)
{
  return strtotime($user1->bday) - strtotime($user2->bday);
}


function getUsers()
{
  // Get raw users data

  $connection = connect();

  $users = getUsersConn($connection);

  disconnect($connection);

  // Sort users
  usort($users, "users_sorter");

  // Move users with passed bday to the end of list
  for ($i = 0; $i < count($users); $i++)
  {
    if ($users[$i]->bday < date('2020-m-d'))
      array_push($users, array_shift($users));
  }

  return $users;
}

?>
