<?php
// require() and parse tags in a skin component
function require_skin($x){
  echo require_skin_return($x);
}
function require_skin_return($x, $a=0){
  ob_start();
  require('skins/' . $GLOBALS['skin'] . '/' . $x);
  $y = ob_get_contents();
  ob_end_clean();
  
  if (isset($GLOBALS['skin'])){ $y = str_replace('{{skinURL}}', 'skins/' . $GLOBALS['skin'], $y); }
  if (isset($GLOBALS['imageURL'])){ $y = str_replace('{{imageURL}}', $GLOBALS['imageURL'], $y); }
  if (isset($GLOBALS['chapter'])){ $y = str_replace('{{chapter}}', $GLOBALS['chapter'], $y); }
  if (isset($GLOBALS['page'])){ $y = str_replace('{{page}}', $GLOBALS['page'], $y); }
  if (isset($GLOBALS['chapterTitle'])){ $y = str_replace('{{chapterTitle}}', $GLOBALS['chapterTitle'], $y); }
  if (isset($GLOBALS['nextPageURL'])){ $y = str_replace('{{nextPageURL}}', $GLOBALS['nextPageURL'], $y); }
  if (isset($GLOBALS['prevPageURL'])){ $y = str_replace('{{prevPageURL}}', $GLOBALS['prevPageURL'], $y); }
  if (isset($GLOBALS['nextPage'])){ $y = str_replace('{{nextPage}}', $GLOBALS['nextPage'], $y); }
  if (isset($GLOBALS['prevPage'])){ $y = str_replace('{{prevPage}}', $GLOBALS['prevPage'], $y); }
  if (isset($GLOBALS['nextPageCh'])){ $y = str_replace('{{nextPageCh}}', $GLOBALS['nextPageCh'], $y); }
  if (isset($GLOBALS['prevPageCh'])){ $y = str_replace('{{prevPageCh}}', $GLOBALS['prevPageCh'], $y); }
  
  $y = preg_replace('/({{nextPage:if}})(.*?)({{end}})/is', nextPage_if('$2'), $y);
  $y = preg_replace('/({{nextPage:ifNot}})(.*?)({{end}})/is', nextPage_ifNot('$2'), $y);
  $y = preg_replace('/({{prevPage:if}})(.*?)({{end}})/is', prevPage_if('$2'), $y);
  $y = preg_replace('/({{prevPage:ifNot}})(.*?)({{end}})/is', prevPage_ifNot('$2'), $y);
  
  if ($a == 0){
    $y = str_replace('{{inc:menu}}', require_skin_return('menu.php', 1), $y);
  }
  
  return $y;
}

// count amount of chapters
function chapterTotal() {
  $fi = new FilesystemIterator(__DIR__ . '/../data/' . $GLOBALS['lang'] . '/ch/', FilesystemIterator::SKIP_DOTS);
  return sprintf('%d', iterator_count($fi));
}

// custom if tag functions
function nextPage_if($z){
  if ($GLOBALS['nextPageCh'] != 0){
    return $z;
  }
}
function nextPage_ifNot($z){
  if ($GLOBALS['nextPageCh'] == 0){
    return $z;
  }
}
function prevPage_if($z){
  if ($GLOBALS['prevPageCh'] != 0){
    return $z;
  }
}
function prevPage_ifNot($z){
  if ($GLOBALS['prevPageCh'] == 0){
    return $z;
  }
}
?>
