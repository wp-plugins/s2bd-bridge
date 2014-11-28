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
	<h3><?php _e('List the forums subscriptions per member', 's2bd-bridge'); ?></h3>
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
		
		echo '<h4>'.__('Members ', 's2bd-bridge').' '.$value.'</h4>' ;
		/***/
		$i = 0 ;
		//$sql_users = "SELECT `user_id` FROM `".$wpdb."usermeta` WHERE `meta_value` LIKE '%s2member_level1%'" ; 
		$sql_users = "SELECT `user_id`, `user_login` FROM `".$wpdb."usermeta` INNER JOIN `".$wpdb."users` ON `".$wpdb."usermeta`.user_id = `".$wpdb."users`.ID WHERE `meta_key` = '".$wpdb."capabilities' AND `meta_value` LIKE '%".$value."%' ORDER BY `user_id`" ;
		$req_users = mysql_query($sql_users) or die('Erreur SQL !<br>'.$sql_users.'<br>'.mysql_error());
		if(mysql_num_rows($req_users)){
			while($data_users = mysql_fetch_assoc($req_users)) { 	
				$i++ ;
				$user_id = $data_users['user_id'] ;
				$user_login = $data_users['user_login'] ;
				//echo $user_id . '<br />' ;
				$sql_meta = "SELECT `meta_value` FROM `".$wpdb."usermeta` WHERE `user_id` = '" . $user_id . "' AND `meta_key` = 'bbp_digest_forums'" ;
				$req_meta = mysql_query($sql_meta) or die('Erreur SQL !<br>'.$sql_meta.'<br>'.mysql_error()); 
				$data_meta = mysql_fetch_assoc($req_meta);
				$data_dig = $data_meta['meta_value'] ;
				//echo $data_dig . '<br />' ;
				if ( $data_dig != '' ) {
					$test = '' ; $forums = '' ;
					$j = 0 ;
					$sql_forums = "SELECT * FROM `".$wpdb."sbd_relations` INNER JOIN `".$wpdb."posts` WHERE `".$wpdb."sbd_relations`.forum = `".$wpdb."posts`.ID" ;
					$req_forums = mysql_query($sql_forums) or die('Erreur SQL !<br>'.$sql_forums.'<br>'.mysql_error());
					if(mysql_num_rows($req_forums)){
						while($data_forums = mysql_fetch_assoc($req_forums)) { 	
							$j++ ;
							//echo $data_forums['forum'] . '<br />' ;
							$test = mb_substr_count($data_dig, ':"'.$data_forums['forum'].'";');
							if ( $test != 0 ) {$forums .= $data_forums['post_title'] . ' / ' ;}
						}
						
					}
					else {		
						echo '<p style="color : red;">'.__('You must first select your required forums.', 's2bd-bridge').'</p>' ;
					}
					echo '<p>'.$i.__(' - User ID : ', 's2bd-bridge').' '.$user_id.' '.__(' - Name : ', 's2bd-bridge').' '.$user_login.' '.__(' - Forums : ', 's2bd-bridge').' ' ;
					print_r ($forums);
					//echo $forums ;
					echo "</p>" ;
				}
				else {	
					echo '<p>'.$i.__(' - User ID : ', 's2bd-bridge').' '.$user_id.' '.__(' - Name : ', 's2bd-bridge').' '.$user_login.' '.__(' - Forums : no registration forum.', 's2bd-bridge').'</p>' ;	
				}
			}	
		}
		else {
			echo '<p style="color : red;">'.__('There are currently no members with this role.', 's2bd-bridge').'</p>' ;
		}
		//$forums = substr($forums,0,-1);
		/***/

	}
	?>
	</div>
	<hr />
	<p>
	<a style="text-decoration: none;" href="admin.php?page=s2bd-bridge/forums-subscriptions-stats.php"><?php _e('Statistics', 's2bd-bridge'); ?></a>
	</p>
</div>