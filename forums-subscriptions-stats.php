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
	<h3><?php _e('Statistics', 's2bd-bridge'); ?></h3>
	<hr />
	<div>
	<h4><?php _e('Forums statistics', 's2bd-bridge'); ?></h4>
	<?php
	//list forums
	$nbforums = 0 ;
	$sql = "SELECT COUNT(DISTINCT `ID`) FROM `".$wpdb."posts` WHERE `post_type` = 'forum' AND `post_status` = 'publish'" ;
	$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
	if(mysql_num_rows($req)){
		$data = mysql_fetch_row($req); 
		$nbforums = $data[0] ;
		echo '<p>'.__('Forums number : ', 's2bd-bridge').$nbforums.'</p>' ;
	}
	else {
		echo '<p style="color : red;">'.__('There are currently no forum in the database.', 's2bd-bridge').'</p>' ;
	}
	?>
	<hr />
	<h4><?php _e('Members statistics', 's2bd-bridge'); ?></h4>
	<p><em><?php _e('(selected by level, see your settings in the "General Settings")', 's2bd-bridge'); ?></em></p>
	<?php
	//list members
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
		//echo $value.'<br />' ;
		
		$sql_count = "SELECT COUNT(DISTINCT `user_id`) FROM `".$wpdb."usermeta` WHERE `meta_key` = '".$wpdb."capabilities' AND `meta_value` LIKE '%".$value."%'" ;
		$req_count = mysql_query($sql_count) or die('Erreur SQL !<br>'.$sql_count.'<br>'.mysql_error());
		if(mysql_num_rows($req_count)){
			$data_count = mysql_fetch_row($req_count); 
			$countUsers = $data_count[0] ;
			echo '<p>'.__('Members of level ', 's2bd-bridge').' '.$value.' : '.$countUsers.' '.__(' member(s)', 's2bd-bridge').'</p>' ;
		}
		else {
			echo '<p style="color : red;">'.__('There are currently no following role member : ', 's2bd-bridge').$user_roles.'</p>' ;
		}
	}
	?>
	<hr />
	<h4><?php _e('Subscriptions Statistics ("digests") per forum', 's2bd-bridge'); ?></h4>
	<?php
	//digests stats
	$i = 0 ; 
	$forums = '' ; 
	$sql_forums = "SELECT `ID`, `post_title` FROM `".$wpdb."posts` WHERE `post_type` = 'forum' AND `post_status` = 'publish'" ;
	$req_forums = mysql_query($sql_forums) or die('Erreur SQL !<br>'.$sql_forums.'<br>'.mysql_error());
	if(mysql_num_rows($req_forums)){
		while($data = mysql_fetch_assoc($req_forums)) {	
			$i++ ;
			$forum_id = $data['ID'] ;
			$forum_title = $data['post_title'] ;
			if ($i == $nbforums)
				$forums .= $forum_id . ' - ' . $forum_title  ;
			else
				$forums .= $forum_id . ' - ' . $forum_title . " ; " ;
		}
		$listforums = array(); 
		$listforums = explode(" ; ", $forums);
		
		$j = 0 ; $count = 0 ;
		foreach ($listforums as $value) {
			$nbforum = 0 ; //IMPORTANT
			$count++;
			//$sql_users = "SELECT DISTINCT `user_id` FROM `".$wpdb."usermeta` WHERE `meta_key` = '".$wpdb."capabilities' AND `meta_value` LIKE '%s2member_level1%'" ;
			$sql_users = "SELECT DISTINCT `user_id` FROM `".$wpdb."usermeta` WHERE `meta_key` = '".$wpdb."capabilities' AND `meta_value` LIKE '%s2member_level%'" ;
			$req_users = mysql_query($sql_users) or die('Erreur SQL !<br>'.$sql_users.'<br>'.mysql_error());
			
			//list($id,$title) = explode(' - ',$value);
			$arr = preg_split("/ - /", $value) ;
			//echo $arr[0] .'<br />' ;
			//echo $id . '-' . $title .'<br />' ;
			//$idtemp = $id ;
			
			if(mysql_num_rows($req_users)){
				while($data_users = mysql_fetch_assoc($req_users)) { 	
					$j++ ;
					$sql_digest = "SELECT DISTINCT `umeta_id` FROM `".$wpdb."usermeta` WHERE `meta_key` = 'bbp_digest_forums' AND `meta_value` LIKE '%" . ':"' . $arr[0] . '";' . "%' AND `user_id` = '" . $data_users['user_id'] . "'" ;
					$req_digest = mysql_query($sql_digest) or die('Erreur SQL !<br>'.$sql_digest.'<br>'.mysql_error());
					$data_digest = mysql_fetch_assoc($req_digest);
					if($data_digest['umeta_id'] != '') {
						$nbforum = $nbforum+1 ; 
					}
				}
				echo '<p>'.$count.__(' - Forum ID-Name : ', 's2bd-bridge').' '.$value.' : '.$nbforum.' '.__(' member(s)', 's2bd-bridge').'</p>' ;
			}
			else {
				echo '<p style="color : red;">'.__('There are currently no s2member_level1 member.', 's2bd-bridge').'</p>' ;
			}
		}
	}
	else {
		echo '<p style="color : red;">'.__('There are currently no forum in the database.', 's2bd-bridge').'</p>' ;
	}
	?>
	</div>
	<hr />
	<p>
	<a style="text-decoration: none;" href="admin.php?page=s2bd-bridge/inscribe-members.php"><?php _e('Inscribe members', 's2bd-bridge'); ?></a>
	</p>
</div>