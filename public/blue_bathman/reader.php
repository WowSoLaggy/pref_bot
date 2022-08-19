<?php

include_once('user.php');


function connect()
{
  include('./../../config/secrets.php');
  
  if (!isset($dbHost) || !isset($dbName) || !isset($dbUser) || !isset($dbPass))
    throw new Exception('Cannot connect to DB: Please check connection settings in "secrets.php" file.');
  
  $link = mysqli_connect($dbHost, $dbUser, $dbPass);
  mysqli_select_db($link, $dbName) or die('Error: '.mysqli_error());
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


function getUsers($connection)
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

    array_push($users, $user);
  }

  mysqli_free_result($result);

  return $users;
}


//
// MAIN
//


// Get data

$connection = connect();

$users = getUsers($connection);

disconnect($connection);

// Sort users

function users_sorter($lhv, $rhv)
{
  return strtotime($lhv->date) - strtotime($rhv->date);
}
usort($users, "users_sorter");

?>
