<?php

include ("../../../inc/includes.php");

if (isset($_GET['img'])
      && !strstr($_GET['img'], "..")
      && !strstr($_GET['img'], "/")
      && !strstr($_GET['img'], "\\")) {

   $config = new PluginSatisfactionsmileyConfig();
   $config->getFromDB(1);
   if (isset($config->fields[$_GET['img']])) {
      $code_base64 = $config->fields[$_GET['img']];
      $code_base64 = str_replace('data:image/png;base64,','',$code_base64);
      $code_binary = base64_decode($code_base64);
      $image= imagecreatefromstring($code_binary);
      imagealphablending($image, false);
      imagesavealpha($image, true);
      header('Content-Type: image/png');
      imagepng($image);
      imagedestroy($image);
   }
}
