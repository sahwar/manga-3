<?php
$file = '/en/ch/2';

$data = array('title' => 'Chapter 2 Title',
              'pages' => '3',
              'imagekey' => 'qweasd');

file_put_contents('data' . $file,serialize($data));
$return = unserialize(file_get_contents('data' . $file));

echo 'ORIGINAL DATA';
var_dump($data);

echo 'STORED DATA LOCATION<br>';
echo '<pre>data' . $file . '</pre>';

echo 'STORED DATA<br>';
echo '<pre>' . file_get_contents('data' . $file) . '</pre>';

echo 'RETRIEVED DATA';
var_dump($return);
?>
