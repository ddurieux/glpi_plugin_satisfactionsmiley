<?php

ini_set("memory_limit", "-1");
ini_set("max_execution_time", "0");

include ('../../../inc/includes.php');

$CFG_GLPI["debug"]=0;
$_SESSION['glpiname'] = 'glpi';
$_SESSION['glpiID'] = 4;

// This will get custom smileys in database and copy them in pics folder.
// With that, it will be possible to have the link to the image into the notification

if (!file_exists('../pics/smiley_1.png.orig')) {
   for ($i=1; $i<=5; $i++) {
      $source = '../pics/smiley_'.$i.'.png';
      $destination = '../pics/smiley_'.$i.'.png.orig';

      if (!copy($source, $destination)) {
         echo "Unable to copy file (perhaps right problem)\n";
         exit;
      }
   }
}

$psConfig = new PluginSatisfactionsmileyConfig();
$psConfig->getFromDB(1);

for ($i=1; $i<=5; $i++) {
   $file = fopen('../pics/smiley_'.$i.'.png', 'w+');

   fwrite($file, base64_decode(str_replace("data:image/x-icon;base64,", "" ,$psConfig->fields['smiley_'.$i])));
   fclose($file);
}
