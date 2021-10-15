<?php

define ("PLUGIN_SATISFACTIONSMILEY_VERSION", "9.5+1.0");

include_once(GLPI_ROOT."/inc/includes.php");


/**
 * Init the hooks of satisfactionsmiley
 *
 * @global array $PLUGIN_HOOKS
 * @global array $CFG_GLPI
 */
function plugin_init_satisfactionsmiley() {
   global $PLUGIN_HOOKS, $CFG_GLPI;

   $PLUGIN_HOOKS['csrf_compliant']['satisfactionsmiley'] = true;
   $PLUGIN_HOOKS['config_page']['satisfactionsmiley'] = 'front/config.form.php';


   $Plugin = new Plugin();
   $moduleId = 0;

   $debug_mode = false;
   if (isset($_SESSION['glpi_use_mode'])) {
      $debug_mode = ($_SESSION['glpi_use_mode'] == Session::DEBUG_MODE);
   }

   if ($Plugin->isActivated('satisfactionsmiley')) { // check if plugin is active
      Plugin::registerClass('PluginSatisfactionsmileyConfig');
      Plugin::registerClass('PluginSatisfactionsmileyTicketsatisfaction',
                     array('notificationtemplates_types' => true));
      Plugin::registerClass('PluginSatisfactionsmileyNotificationTargetTicketsatisfaction');

      $PLUGIN_HOOKS['item_add']['satisfactionsmiley'] = [
         'TicketSatisfaction' => 'plugin_satisfactionsmiley_satisfactionInternal',
       ];
   }
}


/**
 * Manage the version information of the plugin
 *
 * @return array
 */
function plugin_version_satisfactionsmiley() {
   return ['name'           => 'SatisfactionSmiley',
           'shortname'      => 'satisfactionsmiley',
           'version'        => PLUGIN_SATISFACTIONSMILEY_VERSION,
           'license'        => 'AGPLv3+',
           'author'         => '<a href="mailto:david@durieux.family">David DURIEUX</a>',
           'homepage'       => 'https://github.com/',
           'requirements'   => [
              'glpi' => [
                 'min' => '9.5',
                  'max' => '9.6',
                  'dev' => '9.5+1.0' == 0
               ],
            ]
         ];
}


/**
 * Manage / check the prerequisites of the plugin
 *
 * @global object $DB
 * @return boolean
 */
function plugin_satisfactionsmiley_check_prerequisites() {
   global $DB;

   $version = rtrim(GLPI_VERSION, '-dev');
   if (version_compare($version, '9.5', 'lt')) {
      //echo "This plugin requires GLPI 9.5";
      //return false;
   }

   if (!isset($_SESSION['glpi_plugins'])) {
      $_SESSION['glpi_plugins'] = [];
   }

   if (version_compare(GLPI_VERSION, '9.5-dev', '!=')
      && version_compare(GLPI_VERSION, '9.5', 'lt')
      || version_compare(GLPI_VERSION, '9.6', 'ge')) {
      if (method_exists('Plugin', 'messageIncompatible')) {
         echo Plugin::messageIncompatible('core', '9.5', '9.6');
      } else {
         echo __('Your GLPI version not compatible, require >= 9.5 and < 9.6', 'satisfactionsmiley');
      }
      return false;
   }
   return true;
}


/**
 * Check if the config is ok
 *
 * @return boolean
 */
function plugin_satisfactionsmiley_check_config() {
   return true;
}


/**
 * Check the rights
 *
 * @param string $type
 * @param string $right
 * @return boolean
 */
function plugin_satisfactionsmiley_haveTypeRight($type, $right) {
   return true;
}
