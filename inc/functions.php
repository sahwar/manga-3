<?php
// require() and parse tags in a skin component
function require_skin($x){
  ob_start();
  require('skins/' . $GLOBALS['skin'] . '/' . $x);
  $y = ob_get_contents();
  ob_end_clean();
  
  if (isset($GLOBALS['skin'])){ $y = str_replace('{{skinURL}}', 'skins/' . $GLOBALS['skin'], $y); }
  if (isset($GLOBALS['imageURL'])){ $y = str_replace('{{imageURL}}', $GLOBALS['imageURL'], $y); }
  if (isset($GLOBALS['chapter'])){ $y = str_replace('{{chapter}}', $GLOBALS['chapter'], $y); }
  if (isset($GLOBALS['page'])){ $y = str_replace('{{page}}', $GLOBALS['page'], $y); }
  if (isset($GLOBALS['prevPageURL'])){ $y = str_replace('{{prevPageURL}}', $GLOBALS['prevPageURL'], $y); }
  if (isset($GLOBALS['nextPageURL'])){ $y = str_replace('{{nextPageURL}}', $GLOBALS['nextPageURL'], $y); }
  
  echo $y;
}
?>
