<?php


function create_keyboard(array $pairs)
{
  $keyboard = [
    'inline_keyboard' => [
        [
            ['text' => 'forward me to groups', 'callback_data' => 'someString']
        ]
    ]
  ];
  $encodedKeyboard = json_encode($keyboard);
  return $encodedKeyboard;

  $inline_keyboard = array();

  foreach ($pairs as $key => $value)
  {
    $item = array();
    $item['text'] = $key;
    $item['callback_data'] = $value;
    array_push($inline_keyboard, $item);
  }

  $keyboard = array();
  $keyboard = [ 'inline_keyboard' => $inline_keyboard ];

  $encoded_keyboard = json_encode($keyboard);
  return $encoded_keyboard;
}


?>
