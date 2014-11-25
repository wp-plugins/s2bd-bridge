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
?>
<div class="wrap">
	<h3><?php _e('List the members interests', 's2bd-bridge'); ?></h3>
	<em><?php _e('(subscription form, detection of response fields "yes")', 's2bd-bridge'); ?></em>
	<hr />
	<div>
	<?php
	$sql_roles = "SELECT * FROM `".$wpdb."sbd_options`" ;
	$req_roles = mysql_query($sql_roles) or die('Erreur SQL !<br>'.$sql_roles.'<br>'.mysql_error());
	if(mysql_num_rows($req_roles)){
		$data_roles = mysql_fetch_assoc($req_roles) ;
		$user_roles = $data_roles['user_role'] ;
		//echo $user_roles.'<br />' ;
	}
	else {
		echo '<p style="color : red;">'.__('There are no registered user roles for now.', 's2bd-bridge').'</p>' ;
	}
	/***/
	$listroles = array(); 
	$listroles = preg_split("/[\s,;]+/", $user_roles) ;
	foreach ($listroles as $value) {
		
		echo '<h4>'.__('Members ', 's2bd-bridge').$value.'</h4>' ;
		/***/
		$i = 0 ;
		//$sql_users = "SELECT `user_id` FROM `".$wpdb."usermeta` WHERE `meta_key` = '".$wpdb."capabilities' AND `meta_value` LIKE '%".$value."%'" ;
		$sql_users = "SELECT `user_id`, `user_login` FROM `".$wpdb."usermeta` INNER JOIN `".$wpdb."users` ON `".$wpdb."usermeta`.user_id = `".$wpdb."users`.ID WHERE `meta_key` = '".$wpdb."capabilities' AND `meta_value` LIKE '%".$value."%' ORDER BY `user_id`" ;
		$req_users = mysql_query($sql_users) or die('Erreur SQL !<br>'.$sql_users.'<br>'.mysql_error());
		if(mysql_num_rows($req_users)){
			while($data_users = mysql_fetch_assoc($req_users)) { 	
				$i++ ;
				$user_id = $data_users['user_id'] ;
				$user_login = $data_users['user_login'] ;
				$sql_fields = "SELECT `meta_value` FROM `".$wpdb."usermeta` WHERE `user_id` = '" . $user_id . "' AND `meta_key` = '".$wpdb."s2member_custom_fields'" ;
				$req_fields = mysql_query($sql_fields) or die('Erreur SQL !<br>'.$sql_fields.'<br>'.mysql_error()); 
				$data_fields = mysql_fetch_assoc($req_fields);
				$data_int = $data_fields['meta_value'] ;
				//echo $data_int . '<br />' ;	//test
				$test = '' ; $fields = '' ;
				$j = 0 ;
				$sql_select = "SELECT * FROM `".$wpdb."sbd_relations`" ;
				$result_select = mysql_query($sql_select) or die('Erreur SQL !<br>'.$sql_select.'<br>'.mysql_error());
				if(mysql_num_rows($result_select)){
					while($data = mysql_fetch_assoc($result_select)) { 	
						$j++ ;
						$test = mb_substr_count($data_int, '"'.$data['field'].'";s:3:"oui";');
						if ( $test != 0 ) {
							//echo 'Réponse oui à ' . $data['field'] . '... ce champ sera pris en compte et l\'utilisateur sera inscrit au forum correspondant...<br />' ;	//test
							$fields .= $data['field'] . ' / ' ;
						}
						else {
							//echo 'Réponse non à ' . $data['field'] . '... ce champ ne sera pas pris en compte et l\'utilisateur ne sera pas inscrit au forum correspondant...<br />' ;	//test
						}
					}
					//$fields = substr($fields,0,-1);
				}
				echo '<p>'.$i.__(' - User ID : ', 's2bd-bridge').' '.$user_id.' '.__(' - Name : ', 's2bd-bridge').' '.$user_login.' '.__(' - Fields : ', 's2bd-bridge').' ' ;
				print_r ($fields);
				echo '</p>' ;
			}
		}
		else {
			echo '<p style="color : red;">'.__('There are currently no members with this role.', 's2bd-bridge').'</p>' ;
		}
		/***/

	}
	?>
	</div>
	<hr />
	<p>
	<a style="text-decoration: none;" href="admin.php?page=s2bd-bridge/forums-per-member.php"><?php _e('Forums per member', 's2bd-bridge'); ?></a>
	</p>
</div>