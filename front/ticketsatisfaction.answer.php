<?php

include ("../../../inc/includes.php");

echo '<html>
  <head>
    <link href="../css/answer.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../../../lib/font-awesome-4.7.0/css/font-awesome.min.css?v=9.3.1" media="all">
  </head>
  <body>';
$psTicketsatisfaction = new PluginSatisfactionsmileyTicketsatisfaction();
if (isset($_GET["tickets_id"])
      && isset($_GET["token"])
      && isset($_GET["note"])) {
   $psTicketsatisfaction->setAnswer($_GET["tickets_id"], $_GET["token"], $_GET["note"]);
}

echo '</body>';