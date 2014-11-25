<?php
//Security acces to file directly
defined('ABSPATH') or die("No script kiddies please!");

### Check Whether User Can Manage Database
if(!current_user_can('manage_database')) {
	die('Access Denied');
}


### Variables Variables Variables
$base_name = plugin_basename('s2bd-bridge/s2bd-bridge-manager.php');
$base_page = 'admin.php?page='.$base_name;
$wpdb = $table_prefix ;
//echo $table_prefix .'<br />' ;
?>
<div class="wrap">
	<h3><?php _e('Define custom fields required (subscription form S2Member)', 's2bd-bridge'); ?></h3>
	<em><?php _e('(Reminder: fields with answers "yes/no", essentially)', 's2bd-bridge'); ?></em>
	<hr />
	<div>
	<?php
	$i = 0 ;
	$sql_select = "SELECT * FROM `".$wpdb."sbd_relations`" ;
	$result_select = mysql_query($sql_select) or die('Erreur SQL !<br>'.$sql_select.'<br>'.mysql_error());
	if(mysql_num_rows($result_select)){
		echo '<h4>'.__('Required fields already stored: ', 's2bd-bridge').'</h4>';
		while($data = mysql_fetch_assoc($result_select)) { 	
			$i++ ;
			echo $i . ' - ' .__('Field : ', 's2bd-bridge'). ' ' . $data['field'] . '<br />' ;
			$selected_fields_o .= $data['field'] . " " ;
		}
		//echo $selected_fields_o . '<hr />' ;
		echo '<p style="color : green;">'.__('If you want, you can always change your choice of custom fields that are listed above.', 's2bd-bridge').'</p>' ;
		echo '<hr />' ;
	}
	else {
		echo '<p style="color : red;">'.__('There are not yet recorded field.', 's2bd-bridge').'</p>' ;
	}
	$input = isset($_POST['input']) ? $_POST['input'] : '' ;
	if (!isset($_POST['input'])) {
		$sql = "SELECT `option_value` FROM `".$wpdb."options` WHERE `option_name` = 'ws_plugin__s2member_options'" ;
		$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
		if(mysql_num_rows($req)){
			$data = mysql_fetch_assoc($req) ;
			$options = $data['option_value'] ;
			//echo $options . "<hr />"  ;
			$bal1 = '"custom_reg_fields";' ;
			$posbal1 = strpos($options,$bal1) ;
			//echo $posbal1 ;
			$options_t1 = substr($options, 0, $posbal1); 
			//echo $options_t1 . "<hr />"  ;
			$options_t2 = str_replace($options_t1, '', $options) ;
			//echo $options_t2 . "<hr />"  ;
			$bal2 = '"custom_reg_names"' ;
			$posbal2 = strpos($options_t2,$bal2) ;
			//echo $posbal2 ;
			$options_t3 = substr($options_t2, 0, $posbal2); 
			//echo $options_t3 . "<hr />"  ;
			$bal3 = '[{' ;
			$posbal3 = strpos($options_t3,$bal3) ;
			$options_t4 = substr($options_t3, 0, $posbal3); 
			//echo $options_t4 . "<hr />"  ;
			$options_t5 = str_replace($options_t4, '', $options_t3) ;
			//echo $options_t5 . "<hr />"  ;
			$bal4 = ']' ;
			$posbal4 = strpos($options_t5,$bal4) ;
			//echo $posbal2 ;
			$options_t6 = substr($options_t5, 0, $posbal4); 
			//echo $options_t6 . "<hr />"  ;
			$options_t7 = str_replace('[', '', $options_t6) ;
			//echo $options_t7 . "<hr />"  ;
			$options_t8 = str_replace('"', '', $options_t7);
			//echo $options_t8 . "<hr />"  ;
			$options_t9 = preg_split("/},{/", $options_t8) ;
			//print_r($options_t9);
			?>
			<!--<form name="fields" action="index.php?tab=fields-settings" method="post">-->
			<form name="fields" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>" method="post">
			<?php
			foreach ($options_t9 as &$value) {
				$j++;
				$optionline = preg_replace('/[{-}]/', '', $value) ;
				//echo $optionline . "<hr />" ;
				$fields = preg_split("/,/", $optionline) ;
				$field_id = str_replace('id:', '', $fields[2]) ;
				//echo $field_id . "<br />" ;
				echo '<label for="' . $field_id . '">' . $field_id . '</label>&nbsp;&nbsp;&nbsp;' ;
				if (mb_substr_count($selected_fields_o, $field_id) != 0)
					echo '<input type="checkbox" id="field' . $j . '" name="field' . $j . '" value="' . $field_id . '" checked />' ;
				else
					echo '<input type="checkbox" id="field' . $j . '" name="field' . $j . '" value="' . $field_id . '" />' ;
				echo '<br />' ;
			}
			?>
			<br />
			<input type="submit" name="input" value="<?php _e('Register', 's2bd-bridge'); ?>" />
			</form>
			<?php
		}
		else {
			echo '<p style="color : green;">'.__('There are currently no options in the database.', 's2bd-bridge').'</p>' ;
		}
	}
	//if (isset($_POST['input'])) {
	else {
		
		$sql_truncate = "TRUNCATE TABLE `".$wpdb."sbd_relations`" ;
		$result_truncate = mysql_query($sql_truncate) or die('Erreur SQL !<br>'.$sql_truncate.'<br>'.mysql_error());
		foreach($_POST as $cle=>$val){
			if ($val != 'Enregistrer') {
				echo $val.'<br />' ;
				$sql_select = "SELECT * FROM `".$wpdb."sbd_relations` WHERE `field` = '".$val."'" ;
				$result_select = mysql_query($sql_select) or die('Erreur SQL !<br>'.$sql_select.'<br>'.mysql_error());
				$data = mysql_fetch_assoc($result_select);
				$data_id = $data['id'] ;
				if( $data_id != '' ){
					/*$sql_update = "UPDATE `".$wpdb."sbd_relations` SET `field` = '".$val."' WHERE `".$wpdb."sbd_relations`.`id` = '".$data_id."'" ;
					$result_update = mysql_query($sql_update) or die('Erreur SQL !<br>'.$sql_update.'<br>'.mysql_error());
					echo "Mise à jour effectuée<br />" ;*/
					echo '<p style="color : red;">'.__('This field is already registered', 's2bd-bridge').'</p>' ;
				}
				else {
					$sql_insert = "INSERT INTO `".$wpdb."sbd_relations` (`id`,`field`,`forum`) VALUES (NULL ,'".$val."', NULL)" ;
					$result_insert = mysql_query($sql_insert) or die('Erreur SQL !<br>'.$sql_insert.'<br>'.mysql_error());
					echo '<p style="color : green;">'.__('Inserting done!', 's2bd-bridge').'</p>' ;
				}
			}
		}
		echo '<a style="text-decoration: none;" href="admin.php?page=s2bd-bridge/fields-settings.php">'.__('Actualize the screen', 's2bd-bridge').'</a>' ;
	}
	
	?>
	</div>
	
	<hr />
	<h4><?php _e('Complete the installation (step 3)', 's2bd-bridge'); ?></h4>
	<p>
	<a style="text-decoration: none;" href="admin.php?page=s2bd-bridge/forums-settings.php"><?php _e('Forums settings', 's2bd-bridge'); ?></a>
	</p>
</div>
