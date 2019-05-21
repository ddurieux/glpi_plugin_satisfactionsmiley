<?php

include("../../../inc/includes.php");

Session::checkRight('config', READ);

Html::header(
   __('Features', 'satisfactionsmiley'),
   $_SERVER["PHP_SELF"],
   "admin",
   "pluginsatisfactionsmileyconfig",
   "config"
);

$psConfig = new PluginSatisfactionsmileyConfig();

if (isset($_POST['_smiley_1'])) {
   $fullpath = GLPI_TMP_DIR . "/" . $_POST['_smiley_1'][0];
   $img = file_get_contents($fullpath);
   $imgdata = base64_encode($img);

   $input = [
      'id' => 1,
      'smiley_1' => "data:image/x-icon;base64," . $imgdata
   ];

   $psConfig->update($input);
   Html::back();
}

if (isset($_POST['check_list'])) {
   $input2 = [
      'id' => 1,
      'is_active_1' => 0,
      'is_active_2' => 0,
      'is_active_3' => 0,
      'is_active_4' => 0,
      'is_active_5' => 0,

   ];
   foreach ($_POST['check_list'] as $selected) {
      $input2["is_active_".$selected] = 1;
   }
   $psConfig->update($input2);

} 


$psConfig->display([
   "id" => 1
]);

Html::footer();
