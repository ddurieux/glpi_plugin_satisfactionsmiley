<?php

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginSatisfactionsmileyConfig extends CommonDBTM {

   static $rightname = 'config';

   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
      return __('Configuration', 'satisfactionsmiley');
   }

   function showForm($id, $options = []) {

      $this->getFromDB($id);
      $this->initForm($id, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Smiley very happy', 'satisfactionsmiley')."</td>";
      echo "<td><img src='".$this->fields['smiley_1']."'></td>";
      echo "<td colspan='2'>";
      Html::file(['name' => 'smiley_1', 'display' => true, 'onlyimages' => true]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Smiley happy', 'satisfactionsmiley')."</td>";
      echo "<td><img src='".$this->fields['smiley_2']."'></td>";
      echo "<td colspan='2'>";
      Html::file(['name' => 'smiley_2', 'display' => true, 'onlyimages' => true]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Smiley ok', 'satisfactionsmiley')."</td>";
      echo "<td><img src='".$this->fields['smiley_3']."'></td>";
      echo "<td colspan='2'>";
      Html::file(['name' => 'smiley_3', 'display' => true, 'onlyimages' => true]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Smiley bad', 'satisfactionsmiley')."</td>";
      echo "<td><img src='".$this->fields['smiley_4']."'></td>";
      echo "<td colspan='2'>";
      Html::file(['name' => 'smiley_4', 'display' => true, 'onlyimages' => true]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Smiley very bad', 'satisfactionsmiley')."</td>";
      echo "<td><img src='".$this->fields['smiley_5']."'></td>";
      echo "<td colspan='2'>";
      Html::file(['name' => 'smiley_5', 'display' => true, 'onlyimages' => true]);
      echo "</td>";
      echo "</tr>";

      $options['candel'] = false;
      $this->showFormButtons($options);

      return true;

   }
}
