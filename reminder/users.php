<?php

require_once './../public/shared/mysql.php';


class User
{
  public $user_id = false;
  public $d0 = false;
  public $d1 = false;
}


function get_users_from_db($connection) : array
{
  $query = "SELECT user_id, d0, d1 FROM users_tbl WHERE d0 OR d1";
  $result = mysqli_query($connection, $query);
  $num_users = mysqli_num_rows($result);

  $users = array();

  for ($i = 0; $i < $num_users; $i++)
  {
    $user = new User();
    $user->user_id = mysqli_result($result, $i, 'user_id');
    $user->d0 = mysqli_result($result, $i, 'd0');
    $user->d1 = mysqli_result($result, $i, 'd1');
    array_push($users, $user);
  }

  mysqli_free_result($result);

  return $users;
}


function get_users() : array
{
  $connection = connect();
  $users = get_users_from_db($connection);
  disconnect($connection);

  return $users;
}


?>
