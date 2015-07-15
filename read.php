<?php
require_once('inc/functions.php');

$skin = 'caramel';
$lang = 'en';
$allowComments = true;

if (isset($_GET['chapter'])){
  $chapter = $_GET['chapter'];
} else {
  $chapter = 1;
}
if (isset($_GET['page'])){
  $page = $_GET['page'];
} else {
  $page = 1;
}

$mangaTitle = 'Untitled';

if (file_exists('data/' . $lang . '/ch/' . $chapter)){
  $chapterArray = unserialize(file_get_contents('data/' . $lang . '/ch/' . $chapter));
  $chapterTitle = $chapterArray['title'];
  $chapterPages = $chapterArray['pages'];
  $chapterKey = $chapterArray['imagekey'];
} else {
  echo 'Chapter does not exist';
  exit;
  // Needs proper page
}

if ($chapter > 1 && $page == 1 && file_exists('data/' . $lang . '/ch/' . ($chapter - 1))){
  $PrevChapterArray = unserialize(file_get_contents('data/' . $lang . '/ch/' . ($chapter - 1)));
  $PrevChapterPages = $chapterArray['pages'];
}

if ($page == 1){
  $nextPage = $page + 1;
  $nextPageCh = $chapter;
  if ($chapter == 1){
    $prevPage = 0;
    $prevPageCh = 0;
  } else {
    if ($allowComments == true){
      $prevPage = 'comments';
    } else {
      $prevPage = $PrevChapterPages;
    }
    $prevPageCh = $chapter - 1;
  }
} elseif ($page == $chapterPages && $allowComments == true){
  $nextPage = 'comments';
  $prevPage = $page - 1;
  $nextPageCh = $chapter;
  $prevPageCh = $chapter;
} elseif ($page == $chapterPages && $allowComments == false && $chapter != chapterTotal()){
  $nextPage = 1;
  $prevPage = $chapterPages - 1;
  $nextPageCh = $chapter + 1;
  $prevPageCh = $chapter;
} elseif ($page == $chapterPages && $allowComments == false && $chapter == chapterTotal()){
  $nextPage = 0;
  $prevPage = $chapterPages - 1;
  $nextPageCh = 0;
  $prevPageCh = $chapter;
} elseif ($page == 'comments' && $allowComments == true && $chapter != chapterTotal()){
  $nextPage = 1;
  $prevPage = $chapterPages;
  $nextPageCh = $chapter + 1;
  $prevPageCh = $chapter;
} elseif ($page == 'comments' && $allowComments == true && $chapter == chapterTotal()){
  $nextPage = 0;
  $prevPage = $chapterPages;
  $nextPageCh = 0;
  $prevPageCh = $chapter;
} else {
  $nextPage = $page + 1;
  $prevPage = $page - 1;
  $nextPageCh = $chapter;
  $prevPageCh = $chapter;
}

$nextPageURL = 'read?chapter=' . $nextPageCh . '&page=' . $nextPage;
$prevPageURL = 'read?chapter=' . $prevPageCh . '&page=' . $prevPage;

$imageURL = 'content/' . $chapterKey . '/' . $page . '.png';
//$imageURL = 'http://dummyimage.com/1533x2160/111/eee&text=Chapter+' . $chapter . '+Page+' . $page . '+(high:1533x2160)';
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php echo $chapterTitle . ' - ' . $mangaTitle ?></title>
    <?php require_skin('head.php'); ?>
    <link rel="prefetch" href="<?php echo $nextPageURL; ?>">
  </head>
<body>
  <?php
  require_skin('header.php');
  if ($page == 'comments' && $allowComments == true){
    require_skin('comments.php');
  } else {
    require_skin('read.php');
  }
  require_skin('footer.php');
  ?>
</body>
</html>
