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
      'smiley_1' => "data:image/x-icon;base64," . $imgdata,
      'displayorder' => $_POST['displayorder']
   ];

   $psConfig->update($input);
   Html::back();
}

if (isset($_POST['_smiley_2'])) {
   $fullpath = GLPI_TMP_DIR . "/" . $_POST['_smiley_2'][0];
   $img = file_get_contents($fullpath);
   $imgdata = base64_encode($img);

   $input = [
      'id' => 1,
      'smiley_2' => "data:image/x-icon;base64," . $imgdata,
      'displayorder' => $_POST['displayorder']
   ];

   $psConfig->update($input);
   Html::back();
}

if (isset($_POST['_smiley_3'])) {
   $fullpath = GLPI_TMP_DIR . "/" . $_POST['_smiley_3'][0];
   $img = file_get_contents($fullpath);
   $imgdata = base64_encode($img);

   $input = [
      'id' => 1,
      'smiley_3' => "data:image/x-icon;base64," . $imgdata,
      'displayorder' => $_POST['displayorder']
   ];

   $psConfig->update($input);
   Html::back();
}

if (isset($_POST['_smiley_4'])) {
   $fullpath = GLPI_TMP_DIR . "/" . $_POST['_smiley_4'][0];
   $img = file_get_contents($fullpath);
   $imgdata = base64_encode($img);

   $input = [
      'id' => 1,
      'smiley_4' => "data:image/x-icon;base64," . $imgdata,
      'displayorder' => $_POST['displayorder']
   ];

   $psConfig->update($input);
   Html::back();
}

if (isset($_POST['_smiley_5'])) {
   $fullpath = GLPI_TMP_DIR . "/" . $_POST['_smiley_5'][0];
   $img = file_get_contents($fullpath);
   $imgdata = base64_encode($img);

   $input = [
      'id' => 1,
      'smiley_5' => "data:image/x-icon;base64," . $imgdata,
      'displayorder' => $_POST['displayorder']
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
      'displayorder' => $_POST['displayorder']
   ];
   foreach ($_POST['check_list'] as $selected) {
      $input2["is_active_".$selected] = 1;
   }
   $psConfig->update($input2);
   Html::back();
} 

if (isset($_POST['displayorder'])) {
   $psConfig->update([
      'id' => 1,
      'displayorder' => $_POST['displayorder']
   ]);
   Html::back();
}


$psConfig->display([
   "id" => 1
]);

Html::footer();
