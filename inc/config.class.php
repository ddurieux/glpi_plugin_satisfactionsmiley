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
      echo "<td>";
      if($this->fields['is_active_1']) {
         $checked = 'checked';
      } else {
         $checked = '';
      }
      echo "<input type='checkbox' name='check_list[]' value='1' $checked>";
      echo "</td>";
      echo "<td>" . __('Smiley very bad', 'satisfactionsmiley') . "</td>";
      echo "<td><img src='" . $this->fields['smiley_1'] . "'></td>";
      echo "<td>";
      Html::file(['name' => 'smiley_1', 'display' => true, 'onlyimages' => true]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      if($this->fields['is_active_2']) {
         $checked = 'checked';
      } else {
         $checked = '';
      }
      echo "<input type='checkbox' name='check_list[]' value='2' $checked>";
      echo "</td>";
      echo "<td>" . __('Smiley bad', 'satisfactionsmiley') . "</td>";
      echo "<td><img src='" . $this->fields['smiley_2'] . "'></td>";
      echo "<td>";
      Html::file(['name' => 'smiley_2', 'display' => true, 'onlyimages' => true]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      if($this->fields['is_active_3']) {
         $checked = 'checked';
      } else {
         $checked = '';
      }
      echo "<input type='checkbox' name='check_list[]' value='3' $checked>";
      echo "</td>";
      echo "<td>" . __('Smiley ok', 'satisfactionsmiley') . "</td>";
      echo "<td><img src='" . $this->fields['smiley_3'] . "'></td>";
      echo "<td>";
      Html::file(['name' => 'smiley_3', 'display' => true, 'onlyimages' => true]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      if($this->fields['is_active_4']) {
         $checked = 'checked';
      } else {
         $checked = '';
      }
      echo "<input type='checkbox' name='check_list[]' value='4' $checked>";
      echo "</td>";
      echo "<td>" . __('Smiley happy', 'satisfactionsmiley') . "</td>";
      echo "<td><img src='" . $this->fields['smiley_4'] . "'></td>";
      echo "<td>";
      Html::file(['name' => 'smiley_4', 'display' => true, 'onlyimages' => true]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      if($this->fields['is_active_5']) {
         $checked = 'checked';
      } else {
         $checked = '';
      }  
      echo "<input type='checkbox' name='check_list[]' value='5' $checked>";
      echo "</td>";
      echo "<td>" . __('Smiley very happy', 'satisfactionsmiley') . "</td>";
      echo "<td><img src='" . $this->fields['smiley_5'] . "'></td>";
      echo "<td>";
      Html::file(['name' => 'smiley_5', 'display' => true, 'onlyimages' => true]);
      echo "</td>";
      echo "</tr>";

      $options['candel'] = false;
      $this->showFormButtons($options);

      return true;
   }
}