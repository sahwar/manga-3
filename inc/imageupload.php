<?php
if (isset($_GET['ik'])){
  $chKey = $_GET['ik'];
} else {
  exit;
}
$uploaddir = '../content/' . $chKey . '/';
$uploadfile = $uploaddir . basename($_FILES['file']['name']);

if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
  echo 'Success';
} else {
  echo 'Error';
  // Return HTTP status error?
}
?>
