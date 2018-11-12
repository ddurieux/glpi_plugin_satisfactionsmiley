<?php

class PluginSatisfactionsmileyNotificationTargetTicketsatisfaction extends NotificationTargetTicket {

   function __construct($entity = '', $event = '', $object = null, $options = []) {

      $ticket = new Ticket();
      $ticket->getFromDB($options['tickets_id']);
      parent::__construct($entity, $event, $ticket, $options);
   }

   /**
    * Get the ticket event and add our smiley event
    *
    * @return array list of events
    */
   public function getEvents() {
      $events = parent::getEvents();
      $events['Smiley'] = __('Satisfaction smiley', 'satisfactionsmiley');
      return $events;
   }

   function addDataForTemplate($event, $options = array()) {
      global $CFG_GLPI;

      $ticket = new Ticket();
      $ticket->getFromDB($options['tickets_id']);
      $newOptions = [
         'tickets_id'        => $options['tickets_id'],
         'mode'              => $options['mode'],
         'additionnaloption' => $options['additionnaloption'],
         'item'              => $ticket->fields,
      ];
      parent::addDataForTemplate($event, $newOptions);

      $psConfig = new PluginSatisfactionsmileyConfig();
      $psConfig->getFromDB(1);

      $links = "";
      for ($i=1; $i <= 5; $i++) {
         $links .= "<a href='".urldecode($CFG_GLPI["url_base"].
               "/plugins/satisfactionsmiley/front/ticketsatisfaction.answer.php?tickets_id=".$options['item']->fields['tickets_id'].
               "&token=".$options['item']->fields['token']."&note=".$i)."' "
               . "target='_blank'><img src='".$psConfig->fields['smiley_'.$i]."'></a>";
      }
      $this->data['##satisfactionsmiley.smileys##'] = $links;
   }

   /**
    * Ajoute le tag afin d'afficher les smileys
    */
   function getTags() {

      parent::getTags();

      $tags = [
         'satisfactionsmiley.smileys'  => __("Affichage notation avec les smileys", "satisfactionsmiley"),
      ];

      foreach ($tags as $tag => $label) {
         $this->addTagToList([
            'tag'   => $tag,
            'label' => $label,
            'value' => true,
            'events' => NotificationTarget::TAG_FOR_ALL_EVENTS
         ]);
      }

      asort($this->tag_descriptions);
   }


}
