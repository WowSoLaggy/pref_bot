<?php

require_once __DIR__.'/user.php';

require_once __DIR__.'/../shared/mysql.php';


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

?>
