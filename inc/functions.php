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
  if (isset($GLOBALS['imageURL2'])){ $y = str_replace('{{imageURL2}}', $GLOBALS['imageURL2'], $y); }
  if (isset($GLOBALS['chapter'])){ $y = str_replace('{{chapter}}', $GLOBALS['chapter'], $y); }
  if (isset($GLOBALS['page'])){ $y = str_replace('{{page}}', $GLOBALS['page'], $y); }
  if (isset($GLOBALS['chapterTitle'])){ $y = str_replace('{{chapterTitle}}', $GLOBALS['chapterTitle'], $y); }
  if (isset($GLOBALS['nextPageURL'])){ $y = str_replace('{{nextPageURL}}', $GLOBALS['nextPageURL'], $y); }
  if (isset($GLOBALS['prevPageURL'])){ $y = str_replace('{{prevPageURL}}', $GLOBALS['prevPageURL'], $y); }
  if (isset($GLOBALS['nextPage'])){ $y = str_replace('{{nextPage}}', $GLOBALS['nextPage'], $y); }
  if (isset($GLOBALS['prevPage'])){ $y = str_replace('{{prevPage}}', $GLOBALS['prevPage'], $y); }
  if (isset($GLOBALS['nextPageCh'])){ $y = str_replace('{{nextPageCh}}', $GLOBALS['nextPageCh'], $y); }
  if (isset($GLOBALS['prevPageCh'])){ $y = str_replace('{{prevPageCh}}', $GLOBALS['prevPageCh'], $y); }

  $y = preg_replace_callback('/({{nextPage:if}})(.*?)({{end}})/is', 'nextPage_if', $y);
  $y = preg_replace_callback('/({{nextPage:ifNot}})(.*?)({{end}})/is', 'nextPage_ifNot', $y);
  $y = preg_replace_callback('/({{prevPage:if}})(.*?)({{end}})/is', 'prevPage_if', $y);
  $y = preg_replace_callback('/({{prevPage:ifNot}})(.*?)({{end}})/is', 'prevPage_ifNot', $y);
  $y = preg_replace_callback('/({{mobile:if}})(.*?)({{end}})/is', 'mobile_if', $y);
  $y = preg_replace_callback('/({{mobile:ifNot}})(.*?)({{end}})/is', 'mobile_ifNot', $y);

  if ($a == 0){
    $y = preg_replace_callback('/({{inc:)(.*?)(}})/i', 'customIncTag', $y);
  }

  return $y;
}

// count amount of chapters
function chapterTotal() {
  $x = new FilesystemIterator(__DIR__ . '/../data/' . $GLOBALS['lang'] . '/ch/', FilesystemIterator::SKIP_DOTS);
  return sprintf('%d', iterator_count($x));
}

// convert image to data uri
function getDataURI($x) {
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mime = finfo_file($finfo, $x);
  return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($x));
}

// custom if tag functions
function nextPage_if($z){
  if ($GLOBALS['nextPageCh'] != 0){
    return $z[2];
  }
}
function nextPage_ifNot($z){
  if ($GLOBALS['nextPageCh'] == 0){
    return $z[2];
  }
}
function prevPage_if($z){
  if ($GLOBALS['prevPageCh'] != 0){
    return $z[2];
  }
}
function prevPage_ifNot($z){
  if ($GLOBALS['prevPageCh'] == 0){
    return $z[2];
  }
}
function mobile_if($z){
  if ($GLOBALS['isMobile'] == true){
    return $z[2];
  }
}
function mobile_ifNot($z){
  if ($GLOBALS['isMobile'] != true){
    return $z[2];
  }
}

// custom inc tag
function customIncTag($z){
  return require_skin_return($z[2] . '.php', 1);
}
?>
