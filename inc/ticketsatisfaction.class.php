<?php

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginSatisfactionsmileyTicketsatisfaction extends CommonDBTM {

   public $dohistory = true;


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
      return __('Satisfaction smileys', 'satisfactionsmiley');
   }


   /**
    * Get search function for the class
    *
    * @return array
    */
   function rawSearchOptions() {

      $tab = [];

      $tab[] = [
         'id' => 'common',
         'name' => __('Satisfaction smileys', 'satisfactionsmiley')
      ];


      $tab[] = [
         'id'        => '2',
         'table'     => $this->getTable(),
         'field'     => 'satisfaction',
         'name'      => __('Satisfaction', 'satisfactionsmiley'),
         'datatype'  => 'number',
      ];

      $tab[] = [
         'id'        => '3',
         'table'     => $this->getTable(),
         'field'     => 'is_called',
         'name'      => __('User called', 'satisfactionsmiley'),
         'datatype'  => 'bool',
      ];

      $tab[] = [
         'id'        => '4',
         'table'     => $this->getTable(),
         'field'     => 'date_answered',
         'name'      => __('Answer date', 'satisfactionsmiley'),
         'datatype'  => 'datetime',
      ];

      $tab[] = [
         'id'        => '5',
         'table'     => 'glpi_tickets',
         'field'     => 'closedate',
         'name'      => __('Ticket close date', 'satisfactionsmiley'),
         'datatype'  => 'datetime',
      ];

      $tab[] = [
         'id'        => '6',
         'table'     => 'glpi_tickets',
         'field'     => 'id',
         'name'      => __('Ticket id', 'satisfactionsmiley'),
         'datatype'  => 'itemlink',
      ];

      $tab[] = [
         'id'        => '7',
         'table'     => 'glpi_tickets',
         'field'     => 'name',
         'name'      => __('Ticket title', 'satisfactionsmiley'),
      ];

      $tab[] = [
         'id'         => '8',
         'table'      => 'glpi_entities',
         'field'      => 'name',
         'name'       => __('Entity'),
         'datatype'   => 'dropdown',
         'joinparams' => [
            'beforejoin' => [
               'table' => 'glpi_tickets'
            ]
         ]
      ];

      return $tab;
   }


   /**
    * Add an answer to a ticket
    *
    */
   function setAnswer($tickets_id, $token, $note) {
      global $DB;

      $iterator = $DB->request([
         'FROM'   => $this->getTable(),
         'WHERE'  => [
            'tickets_id' => $tickets_id,
            'token'      => $token
         ]
      ]);

      if (count($iterator) == 0) {
         $this->errorMessage(__('The token is wrong', 'satisfactionsmiley'));
         return false;
      }

      $iterator = $DB->request([
         'FROM'   => $this->getTable(),
         'WHERE'  => [
            'tickets_id' => $tickets_id,
            'token'      => $token,
            'satisfaction' => 0
         ]
      ]);

      if (count($iterator) == 0) {
         $this->errorMessage(__('The satisfaction has been yet posted', 'satisfactionsmiley'));
         return false;
      }
      $result = $iterator->next();

      $input = [
         'id'            => $result['id'],
         'date_answered' => $_SESSION["glpi_currenttime"],
         'satisfaction'  => $note
      ];

      $this->update($input);

      $this->okMessage(__('The satisfaction has been right posted, thank you!', 'satisfactionsmiley'));
      return true;
   }

   function errorMessage($message) {
      echo '
  <div class="popuperror">
    <div class="valid">
      <i class="fa fa-times fa-5x"></i>
    </div>
    <h1>'.$message.'</h1>
  </div>';
   }


   function okMessage($message) {
      echo '
  <div class="popupok">
    <div class="valid">
      <i class="fa fa-check fa-5x"></i>
    </div>
    <h1>'.$message.'</h1>
  </div>';

   }


   /**
    * Create token and
    */
   function generateToken() {
      // generate a token
      return hash('sha256', uniqid());
   }


   static function getAllStatusArray($withmetaforsearch = false) {
      return Ticket::getAllStatusArray($withmetaforsearch);
   }

   /**
    * @see CommonDBTM::prepareInputForAdd()
   **/
   function prepareInputForAdd($input) {

      $input = parent::prepareInputForAdd($input);
      $input["token"] = $this->generateToken();

      return $input;
   }


   function post_addItem() {
      global $CFG_GLPI;

      if (!isset($this->input['_disablenotif']) && $CFG_GLPI["use_notifications"]) {
         NotificationEvent::raiseEvent("Smiley", $this, ["tickets_id" => $this->fields["tickets_id"]]);
      }
   }

   /**
    * Code get from inc/ticket.class.php
    *
    */
   static function cronSendinquest($task) {
      global $DB;

      $conf        = new Entity();
      // Line modified from source
      // $inquest     = new TicketSatisfaction();
      $inquest     = new PluginSatisfactionsmileyTicketsatisfaction();
      $tot         = 0;
      $maxentity   = [];
      $tabentities = [];

      $rate = Entity::getUsedConfig('inquest_config', 0, 'inquest_rate');
      if ($rate > 0) {
         $tabentities[0] = $rate;
      }

      foreach ($DB->request('glpi_entities') as $entity) {
         $rate   = Entity::getUsedConfig('inquest_config', $entity['id'], 'inquest_rate');
         $parent = Entity::getUsedConfig('inquest_config', $entity['id'], 'entities_id');

         if ($rate > 0) {
            $tabentities[$entity['id']] = $rate;
         }
      }

      foreach ($tabentities as $entity => $rate) {
         $parent        = Entity::getUsedConfig('inquest_config', $entity, 'entities_id');
         $delay         = Entity::getUsedConfig('inquest_config', $entity, 'inquest_delay');
         $duration      = Entity::getUsedConfig('inquest_config', $entity, 'inquest_duration');
         $type          = Entity::getUsedConfig('inquest_config', $entity);
         $max_closedate = Entity::getUsedConfig('inquest_config', $entity, 'max_closedate');

         // line modified from source
         /*
         $query = "SELECT `glpi_tickets`.`id`,
                          `glpi_tickets`.`closedate`,
                          `glpi_tickets`.`entities_id`
                   FROM `glpi_tickets`
                   LEFT JOIN `glpi_ticketsatisfactions`
                       ON `glpi_ticketsatisfactions`.`tickets_id` = `glpi_tickets`.`id`
                   LEFT JOIN `glpi_entities`
                       ON `glpi_tickets`.`entities_id` = `glpi_entities`.`id`
                   WHERE `glpi_tickets`.`entities_id` = '$entity'
                         AND `glpi_tickets`.`is_deleted` = 0
                         AND `glpi_tickets`.`status` = '".self::CLOSED."'
                         AND `glpi_tickets`.`closedate` > '$max_closedate'
                         AND ADDDATE(`glpi_tickets`.`closedate`, INTERVAL $delay DAY)<=NOW()
                         AND ADDDATE(`glpi_entities`.`max_closedate`, INTERVAL $duration DAY)<=NOW()
                         AND `glpi_ticketsatisfactions`.`id` IS NULL
                   ORDER BY `closedate` ASC";
         */
         $query = "SELECT `glpi_tickets`.`id`,
                          `glpi_tickets`.`closedate`,
                          `glpi_tickets`.`entities_id`
                   FROM `glpi_tickets`
                   LEFT JOIN `glpi_plugin_satisfactionsmiley_ticketsatisfactions`
                       ON `glpi_plugin_satisfactionsmiley_ticketsatisfactions`.`tickets_id` = `glpi_tickets`.`id`
                   LEFT JOIN `glpi_entities`
                       ON `glpi_tickets`.`entities_id` = `glpi_entities`.`id`
                   WHERE `glpi_tickets`.`entities_id` = '$entity'
                         AND `glpi_tickets`.`is_deleted` = 0
                         AND `glpi_tickets`.`status` = '".Ticket::CLOSED."'
                         AND `glpi_tickets`.`closedate` > '$max_closedate'
                         AND ADDDATE(`glpi_tickets`.`closedate`, INTERVAL $delay DAY)<=NOW()
                         AND ADDDATE(`glpi_entities`.`max_closedate`, INTERVAL $duration DAY)<=NOW()
                         AND `glpi_plugin_satisfactionsmiley_ticketsatisfactions`.`id` IS NULL
                   ORDER BY `closedate` ASC";

         $nb            = 0;
         $max_closedate = '';

         foreach ($DB->request($query) as $tick) {
            $max_closedate = $tick['closedate'];
            if (mt_rand(1, 100) <= $rate) {
               if ($inquest->add(['tickets_id'  => $tick['id'],
                                  'date_begin'  => $_SESSION["glpi_currenttime"],
                                  'entities_id' => $tick['entities_id'],
                                  'type'        => $type])) {
                  $nb++;
               }
            }
         }

         // conservation de toutes les max_closedate des entites filles
         if (!empty($max_closedate)
             && (!isset($maxentity[$parent])
                 || ($max_closedate > $maxentity[$parent]))) {
            $maxentity[$parent] = $max_closedate;
         }

         if ($nb) {
            $tot += $nb;
            $task->addVolume($nb);
            $task->log(sprintf(__('%1$s: %2$s'),
                               Dropdown::getDropdownName('glpi_entities', $entity), $nb));
         }
      }

      // Sauvegarde du max_closedate pour ne pas tester les m??me tickets 2 fois
      foreach ($maxentity as $parent => $maxdate) {
         $conf->getFromDB($parent);
         $conf->update(['id'            => $conf->fields['id'],
                             //'entities_id'   => $parent,
                             'max_closedate' => $maxdate]);
      }

      return ($tot > 0);
   }
}
