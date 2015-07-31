<?php
/*
 *  MANGA PROJECT (http://manga-project.ga/)
 *  Created by 花木カズキ (Kazuki Hanaki)
 *  Released under the GNU GPLv2 license
 */
require_once('inc/functions.php');

$skin = 'caramel';
$lang = 'en';
$allowComments = true;

$useragent = $_SERVER['HTTP_USER_AGENT'];
if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
  $isMobile = true;
  $allowDoubleSpread = false;
} else {
  $isMobile = false;
  $allowDoubleSpread = true;
}

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
  $chapterDate = $chapterArray['date'];
  $chapterTime = $chapterArray['time'];
  $doubleSpreadStart = $chapterArray['doubleSpread'];
} else {
  echo 'Chapter does not exist';
  exit;
  // Needs proper page
}

$imageURL = 'content/' . $chapterKey . '/' . $page . '.png';
$imageURL2 = 'content/' . $chapterKey . '/' . ($page + 1) . '.png';

if ($allowDoubleSpread == true){
  if ($doubleSpreadStart == 1){
    $doubleSpread = true;
  } else {
    if ($page > 1){
      $doubleSpread = true;
    } else {
      $doubleSpread = false;
    }
  }
  if ($doubleSpread == true && !file_exists($imageURL2)){
    $doubleSpread = false;
  }
} else {
  $doubleSpread = false;
}

if ($chapter > 1 && $page < 3 && file_exists('data/' . $lang . '/ch/' . ($chapter - 1))){
  $PrevChapterArray = unserialize(file_get_contents('data/' . $lang . '/ch/' . ($chapter - 1)));
  $PrevChapterPages = $PrevChapterArray['pages'];
  $PrevChapterSpread = $PrevChapterArray['doubleSpread'];
}

if ($doubleSpread == true && $page != 'comments') {
  $tempPageNext = ($page + 1);
} else {
  $tempPageNext = $page;
}
if ($tempPageNext == 1 && $chapter == 1){
  $nextPage = $tempPageNext + 1;
  $nextPageCh = $chapter;
} elseif ($tempPageNext == 1 && $chapter != 1 && $allowComments == true){
  $nextPage = $tempPageNext + 1;
  $nextPageCh = $chapter;
} elseif ($tempPageNext == 1 && $chapter != 1 && $allowComments == false){
  $nextPage = $tempPageNext + 1;
  $nextPageCh = $chapter;
} elseif ($tempPageNext == $chapterPages && $allowComments == true){
  $nextPage = 'comments';
  $nextPageCh = $chapter;
} elseif ($tempPageNext == $chapterPages && $allowComments == false && $chapter != chapterTotal()){
  $nextPage = 1;
  $nextPageCh = $chapter + 1;
} elseif ($tempPageNext == $chapterPages && $allowComments == false && $chapter == chapterTotal()){
  $nextPage = 0;
  $nextPageCh = 0;
} elseif ($tempPageNext == 'comments' && $allowComments == true && $chapter != chapterTotal()){
  $nextPage = 1;
  $nextPageCh = $chapter + 1;
} elseif ($tempPageNext == 'comments' && $allowComments == true && $chapter == chapterTotal()){
  $nextPage = 0;
  $nextPageCh = 0;
} else {
  $nextPage = $tempPageNext + 1;
  $nextPageCh = $chapter;
}

if ($allowDoubleSpread == true && $doubleSpreadStart == 1 && $page > 1 && $page % 2 != 0 && $page != 'comments'
 || $allowDoubleSpread == true && $doubleSpreadStart == 2 && $page > 2 && $page % 2 == 0 && $page != 'comments') {
  $tempPagePrev = $page - 1;
} elseif ($allowDoubleSpread == true && $doubleSpreadStart == 1 && $page == 'comments' && $chapterPages % 2 == 0
       || $allowDoubleSpread == true && $doubleSpreadStart == 2 && $page == 'comments' && $chapterPages % 2 != 0) {
  $tempPagePrev = $chapterPages;
} else {
  $tempPagePrev = $page;
}
if ($tempPagePrev == 1 && $chapter == 1){
  $prevPage = 0;
  $prevPageCh = 0;
} elseif ($tempPagePrev == 1 && $chapter != 1 && $allowComments == true){
  $prevPage = 'comments';
  $prevPageCh = $chapter - 1;
} elseif ($tempPagePrev == 1 && $chapter != 1 && $allowComments == false){
  $prevPage = $PrevChapterPages;
  $prevPageCh = $chapter - 1;
} elseif ($tempPagePrev == $chapterPages && $allowComments == true){
  $prevPage = $tempPagePrev - 1;
  $prevPageCh = $chapter;
} elseif ($tempPagePrev == $chapterPages && $allowComments == false && $chapter != chapterTotal()){
  $prevPage = $chapterPages - 1;
  $prevPageCh = $chapter;
} elseif ($tempPagePrev == $chapterPages && $allowComments == false && $chapter == chapterTotal()){
  $prevPage = $chapterPages - 1;
  $prevPageCh = $chapter;
} elseif ($tempPagePrev == 'comments' && $allowComments == true && $chapter != chapterTotal()){
  $prevPage = $chapterPages;
  $prevPageCh = $chapter;
} elseif ($tempPagePrev == 'comments' && $allowComments == true && $chapter == chapterTotal()){
  $prevPage = $chapterPages;
  $prevPageCh = $chapter;
} else {
  $prevPage = $tempPagePrev - 1;
  $prevPageCh = $chapter;
}

$nextPageURL = 'read?chapter=' . $nextPageCh . '&page=' . $nextPage;
$prevPageURL = 'read?chapter=' . $prevPageCh . '&page=' . $prevPage;
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
    if ($doubleSpread){
      require_skin('read_double.php');
    } else {
      require_skin('read.php');
    }
  }
  require_skin('footer.php');
  ?>
</body>
</html>
