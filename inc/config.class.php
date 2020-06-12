<?php

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginSatisfactionsmileyConfig extends CommonDBTM
{

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
      $checked = '';

      echo "<tr class='tab_bg_1'>";
      echo "<td>" . __('Order display in config & notification', 'satisfactionsmiley') . "</td>";
      echo "<td>";
      $elements = [
         'badfirst'  => __('From bad to good', 'satisfactionsmiley'),
         'goodfirst' => __('From good to bad', 'satisfactionsmiley')
      ];
      Dropdown::showFromArray('displayorder', $elements, ['value' => $this->fields['displayorder']]);
      echo "</td>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "</tr>";

      $smileys = [
         '1' => __('Smiley very bad', 'satisfactionsmiley'),
         '2' => __('Smiley bad', 'satisfactionsmiley'),
         '3' => __('Smiley ok', 'satisfactionsmiley'),
         '4' => __('Smiley happy', 'satisfactionsmiley'),
         '5' => __('Smiley very happy', 'satisfactionsmiley'),
      ];
      if ($this->fields['displayorder'] == 'goodfirst') {
         krsort($smileys);
      }
      foreach ($smileys as $smlNumber=>$smlName) {
         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         if($this->fields['is_active_'.$smlNumber]) {
            $checked = 'checked';
         } else {
            $checked = '';
         }
         echo "<input type='checkbox' name='check_list[]' value='".$smlNumber."' $checked>";
         echo "</td>";
         echo "<td>" . $smlName . "</td>";
         echo "<td><img src='" . $this->fields['smiley_'.$smlNumber] . "'></td>";
         echo "<td>";
         Html::file(['name' => 'smiley_'.$smlNumber, 'display' => true, 'onlyimages' => true]);
         echo "</td>";
         echo "</tr>";

      }

      $options['candel'] = false;
      $this->showFormButtons($options);

      return true;
   }
}