<?php
// require() and parse tags in a skin component
function require_skin($x){
  global $skin,$imageURL,$prevPage,$nextPage;
  
  ob_start();
  require('skins/' . $skin . '/' . $x);
  $y = ob_get_contents();
  ob_end_clean();
  
  if (isset($skin)){ $y = str_replace('{{skinURL}}', 'skins/' . $skin, $y); } else {
    str_replace('{{skinURL}}', '', $y); }
  if (isset($imageURL)){ $y = str_replace('{{imageURL}}', $imageURL, $y); }
  if (isset($prevPage)){ $y = str_replace('{{prevPageURL}}', 'read?page=' . $prevPage, $y); }
  if (isset($nextPage)){ $y = str_replace('{{nextPageURL}}', 'read?page=' . $nextPage, $y); }
  
  echo $y;
}
?>
