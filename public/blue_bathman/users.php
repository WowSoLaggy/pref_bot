<?php

require_once __DIR__.'/../shared/mysql.php';


class User
{
  public $is_admin = false;
}


function get_user_from_db($connection, string $user_id)
{
  $query = "SELECT admin FROM users_tbl WHERE user_id=".$user_id." LIMIT 1";
  $result = mysqli_query($connection, $query);
  $num_users = mysqli_num_rows($result);

  $user = null;

  if ($num_users > 0)
  {
    $user = new User();
    $user->is_admin = mysqli_result($result, 0, 'admin');
  }

  mysqli_free_result($result);

  return $user;
}


function get_user(string $user_id)
{
  $connection = connect();
  $user = get_user_from_db($connection, $user_id);
  disconnect($connection);

  return $user;
}


function get_user_ind($connection, string $user_id) : int
{
  $query = 'SELECT id FROM users_tbl WHERE user_id='.$user_id.' LIMIT 1';
  $result = mysqli_query($connection, $query);
  
  if (mysqli_num_rows($result) == 0)
    throw new Exception('No user found for user_id: \''.$user_id.'\'');

  $user_ind = mysqli_result($result, 0, 'id');

  mysqli_free_result($result);

  return $user_ind;
}

?>
