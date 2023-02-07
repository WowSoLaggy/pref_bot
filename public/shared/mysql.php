<?php


function connect()
{
  require __DIR__.'/../../config/secrets.php';
  
  if (!isset($dbHost) || !isset($dbName) || !isset($dbUser) || !isset($dbPass))
    throw new Exception('No DB connection settings: Please check "secrets.php" file.');
  
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


?>
