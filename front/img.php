<?php

include ("../../../inc/includes.php");

if (isset($_GET['img'])
      && !strstr($_GET['img'], "..")
      && !strstr($_GET['img'], "/")
      && !strstr($_GET['img'], "\\")) {

   $config = new PluginSatisfactionsmileyConfig();
   $config->getFromDB(1);
   if (isset($config->fields[$_GET['img']])) {
      echo '<img src="'.$config->fields[$_GET['img']].'"/>';
   }
}