<?php

include ("../../../inc/includes.php");

Session::checkRight('config', READ);

Html::header(__('Features', 'satisfactionsmiley'), $_SERVER["PHP_SELF"],
             "admin", "pluginsatisfactionsmileyconfig", "config");

$psConfig = new PluginSatisfactionsmileyConfig();

if (isset($_POST['_smiley_1'])) {
   $fullpath = GLPI_TMP_DIR."/".$_POST['_smiley_1'][0];
   $img = file_get_contents($fullpath);
   $imgdata = base64_encode($img);

   $input = [
      'id' => 1,
      'smiley_1' => "data:image/x-icon;base64,".$imgdata
   ];
   $psConfig->update($input);
   Html::back();
}

$psConfig->display([
   "id" => 1
]);

Html::footer();
