<?php

/**
 * Add search options for GLPI objects
 *
 * @param string $itemtype
 * @return array
 */
function plugin_satisfactionsmiley_getAddSearchOptions($itemtype) {

   $sopt = [];
   if ($itemtype == "Ticket") {
      $sopt[19001]['table']     = 'glpi_plugin_satisfactionsmiley_ticketsatisfactions';
      $sopt[19001]['field']     = 'satisfaction';
//      $sopt[19001]['linkfield'] = 'id';
      $sopt[19001]['name']      = __('Satisfaction Smileys', 'satisfactionsmiley');
      $sopt[19001]['datatype']  = 'text';
      $sopt[19001]['joinparams']  = ['jointype' => 'child'];
      //      $sopt[19001]['itemlink_type'] = 'PluginSatisfactionsmileyTicketsatisfaction';
      $sopt[19001]['massiveaction'] = false;
   }

   return $sopt;
}


function plugin_satisfactionsmiley_giveItem($type, $id, $data, $num) {

   $searchopt = &Search::getOptions($type);
   $table = $searchopt[$id]["table"];
   $field = $searchopt[$id]["field"];

   if ($table . '.' . $field == "glpi_plugin_satisfactionsmiley_ticketsatisfactions.satisfaction") {
      if ($data['raw']['ITEM_' . $num] == 0) {
         return " ";
      } else {
         if (isset($_GET['display_type'])) {
            // Case of PDF / CSV export
            return $data['raw']['ITEM_' . $num];
         } else {
            $psConfig = new PluginSatisfactionsmileyConfig();
            $psConfig->getFromDB(1);
            return "<img src='" . $psConfig->fields['smiley_' . $data['raw']['ITEM_' . $num]] . "' height='25' width='25'/>";
         }
      }
   }
}


/**
 * Manage the installation process
 *
 * @return boolean
 */
function plugin_satisfactionsmiley_install() {
   global $DB;

   ini_set("max_execution_time", "0");

   if (!$DB->tableExists('glpi_plugin_satisfactionsmiley_ticketsatisfactions')) {
      $query = "CREATE TABLE `glpi_plugin_satisfactionsmiley_ticketsatisfactions` (
         `id` int(11) NOT NULL AUTO_INCREMENT,
         `tickets_id` int(11) NOT NULL DEFAULT '0',
         `date_answered` datetime DEFAULT NULL,
         `satisfaction` int(11) DEFAULT '0',
         `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
         `comment` text COLLATE utf8_unicode_ci,
         `is_called` tinyint(1) NOT NULL DEFAULT '0',
         PRIMARY KEY (`id`),
         UNIQUE KEY `tickets_id` (`tickets_id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
      $DB->query($query);
   }

   if (!$DB->tableExists('glpi_plugin_satisfactionsmiley_configs')) {
      $query = "CREATE TABLE `glpi_plugin_satisfactionsmiley_configs` (
         `id` int(11) NOT NULL AUTO_INCREMENT,
         `smiley_1` longtext COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'very bad',
         `smiley_2` longtext COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'bad',
         `smiley_3` longtext COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'good',
         `smiley_4` longtext COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'happy',
         `smiley_5` longtext COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'very happy',
         `is_active_1` tinyint(1) NOT NULL DEFAULT '1',
         `is_active_2` tinyint(1) NOT NULL DEFAULT '1',
         `is_active_3` tinyint(1) NOT NULL DEFAULT '1',
         `is_active_4` tinyint(1) NOT NULL DEFAULT '1',
         `is_active_5` tinyint(1) NOT NULL DEFAULT '1',

         PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
      $DB->query($query);

      $DB->query("INSERT INTO `glpi_plugin_satisfactionsmiley_configs` (`id`) VALUES(1)");

      // Fill smiley with default
      for ($i = 1; $i <= 5; $i++) {
         $data = file_get_contents(GLPI_ROOT . "/plugins/satisfactionsmiley/pics/smiley_" . $i . ".png");
         $base64 = 'data:image/png;base64,' . base64_encode($data);
         $DB->query("UPDATE `glpi_plugin_satisfactionsmiley_configs` SET `smiley_" . $i . "`='" . $base64 . "' WHERE `id`=1");
      }
   }
   if (!$DB->fieldExists('glpi_plugin_satisfactionsmiley_configs', 'is_active_1')) {
      $query = "ALTER TABLE `glpi_plugin_satisfactionsmiley_configs`
         ADD `is_active_1` tinyint(1) NOT NULL DEFAULT '1' AFTER `smiley_5` ";
      $DB->queryOrDie($query);
      $query = "ALTER TABLE `glpi_plugin_satisfactionsmiley_configs`
         ADD `is_active_2` tinyint(1) NOT NULL DEFAULT '1' AFTER `is_active_1` ";
      $DB->queryOrDie($query);
      $query = "ALTER TABLE `glpi_plugin_satisfactionsmiley_configs`
         ADD `is_active_3` tinyint(1) NOT NULL DEFAULT '1' AFTER `is_active_2` ";
      $DB->queryOrDie($query);
      $query = "ALTER TABLE `glpi_plugin_satisfactionsmiley_configs`
         ADD `is_active_4` tinyint(1) NOT NULL DEFAULT '1' AFTER `is_active_3` ";
      $DB->queryOrDie($query);
      $query = "ALTER TABLE `glpi_plugin_satisfactionsmiley_configs`
         ADD `is_active_5` tinyint(1) NOT NULL DEFAULT '1' AFTER `is_active_4` ";
      $DB->queryOrDie($query);
   }
   if (!$DB->fieldExists('glpi_plugin_satisfactionsmiley_configs', 'displayorder')) {
      $query = "ALTER TABLE `glpi_plugin_satisfactionsmiley_configs`
         ADD `displayorder` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'badfirst' AFTER `is_active_5` ";
      $DB->queryOrDie($query);
   }
   CronTask::Register(
      'PluginSatisfactionsmileyTicketsatisfaction',
      'sendinquest',
      '86400',
      ['mode' => 2, 'allowmode' => 3, 'logs_lifetime' => 30]
   );

   return true;
}


/**
 * Manage the uninstallation of the plugin
 *
 * @return boolean
 */
function plugin_satisfactionsmiley_uninstall() {
   global $DB;

   $query = "DROP TABLE `glpi_plugin_satisfactionsmiley_ticketsatisfactions`";
   $DB->query($query);

   $query = "DROP TABLE `glpi_plugin_satisfactionsmiley_configs`";
   $DB->query($query);

   return true;
}


function plugin_satisfactionsmiley_satisfactionInternal(TicketSatisfaction $ticketSatisfaction) {
   $ticketSatisfaction->update([
      'id'            => $ticketSatisfaction->fields['id'],
      'tickets_id'    => $ticketSatisfaction->fields['tickets_id'],
      'satisfaction'  => 3,
      'date_answered' => $_SESSION["glpi_currenttime"]
   ]);
}
