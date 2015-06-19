<?php
$skin = 'caramel';
$imageURL = 'http://dummyimage.com/1533x2160/111/eee';
$page = 2;
$prevPage = $page - 1;
$nextPage = $page + 1;
require_once('inc/functions.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Read - Manga</title>
    <?php require_skin('head.php'); ?>
    <!--<link rel="prefetch" href="read?page=">-->
  </head>
<body>
  <?php require_skin('header.php'); ?>
  <?php require_skin('read.php'); ?>
  <?php require_skin('footer.php'); ?>
</body>
</html>
