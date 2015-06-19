<?php
// require() and parse tags in a skin component
function require_skin($x){
  ob_start();
  require('skins/' . $GLOBALS['skin'] . '/' . $x);
  $y = ob_get_contents();
  ob_end_clean();
  
  if (isset($GLOBALS['skin'])){ $y = str_replace('{{skinURL}}', 'skins/' . $GLOBALS['skin'], $y); } else {
    str_replace('{{skinURL}}', '', $y); }
  if (isset($GLOBALS['imageURL'])){ $y = str_replace('{{imageURL}}', $GLOBALS['imageURL'], $y); }
  if (isset($GLOBALS['page'])){ $y = str_replace('{{page}}', $GLOBALS['page'], $y); }
  if (isset($GLOBALS['prevPage'])){ $y = str_replace('{{prevPageURL}}', 'read?page=' . $GLOBALS['prevPage'], $y); }
  if (isset($GLOBALS['nextPage'])){ $y = str_replace('{{nextPageURL}}', 'read?page=' . $GLOBALS['nextPage'], $y); }
  
  echo $y;
}
?>
