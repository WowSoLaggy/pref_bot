<?php


function starts_with(string $hay, string $needle) : bool
{
  $needle_length = strlen($needle);
  return (substr($hay, 0, $needle_length) === $needle);
}


?>
