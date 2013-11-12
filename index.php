<?php
include_once("./lib/resize-class.php");

//variables config
$resize_to_width = "190";
$resize_to_height = "130";
$srcDir = "/users/mygoare/Pictures/beauty/";
$distDir = dirname(__FILE__)."/dist/";
if (!is_dir($distDir)) {mkdir($distDir);}

$files_arr = Array();

if(is_dir($srcDir)) {
  if($beauty = opendir($srcDir)) {
    while ( ($file = readdir($beauty)) !== false ) {
      if ($file != "." && $file != ".." && exif_imagetype($srcDir.$file)) {
        array_push($files_arr, $file);
      }
    }
    closedir($beauty);
  }
}

foreach ($files_arr as $value) {
  $resizeObj = new resize($srcDir.$value);
  $resizeObj->resizeImage($resize_to_width, $resize_to_height, 'crop');
  $resizeObj->saveImage($distDir.$value);
}
?>
