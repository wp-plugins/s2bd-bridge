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
	<h3><?php _e('Associate the fields to the forums', 's2bd-bridge'); ?></h3>
	<hr />
	<div>
	<?php
	$i = 0 ;
	$sql_select = "SELECT * FROM `".$wpdb."sbd_relations`" ;
	$result_select = mysql_query($sql_select) or die('Erreur SQL !<br>'.$sql_select.'<br>'.mysql_error());
	if(mysql_num_rows($result_select)){
		echo '<h4>'.__('Required forums already stored: ', 's2bd-bridge').'</h4>';
		while($data = mysql_fetch_assoc($result_select)) { 	
			$i++ ;
			echo "<p>". $i . " - " . "Champs : " . $data['field'] . " >> Forum : " . $data['forum'] . "</p>" ;
			$selected_forums .= $data['forum'] . " " ;
		}
		//echo $selected_forums . '<hr />' ;
		echo '<p style="color : green;">'.__('If you want, you can always change your choice of related forums that are listed above.', 's2bd-bridge').'</p>' ;
		echo '<hr />' ;
	}
	else {
		echo '<p style="color : red;">'.__('There are not yet recorded forum.', 's2bd-bridge').'</p>' ;
	}
	$i = 0 ; $j = 0 ;
	$rely = isset($_POST['rely']) ? $_POST['rely'] : '' ;
	if (!isset($_POST['rely'])) {
	?>
	<form name="fields" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>" method="post">
	<?php
		$sql_fields = "SELECT * FROM `".$wpdb."sbd_relations`" ;
		$result_fields = mysql_query($sql_fields) or die('Erreur SQL !<br>'.$sql_fields.'<br>'.mysql_error());
		if(mysql_num_rows($result_fields)){
			while($data_fields = mysql_fetch_assoc($result_fields)) { 	
				$i++ ;
				$field_id = $data_fields['id'] ; 
				$field_name = $data_fields['field'] ;
				echo '<input type="hidden" name="field'.$i.'" value="'.$field_id.'" />' ;
				echo '<label for="'.$field_name.'">'.$field_name.' : </label>' ;	// ' - '.$field_id.
				echo '<select name="forum'.$i.'" >' ;
				echo '<option value="default">Choix forum</option>' ;
				$sql_forums = "SELECT `ID`, `post_title` FROM `".$wpdb."posts` WHERE `post_type` = 'forum' AND `post_status` = 'publish'" ;
				$req_forums = mysql_query($sql_forums) or die('Erreur SQL !<br>'.$sql_forums.'<br>'.mysql_error()); 
				if(mysql_num_rows($req_forums)){
					while($data_forums = mysql_fetch_assoc($req_forums)) {	
						$j++ ;
						$forum_id = $data_forums['ID'] ;
						$forum_title = $data_forums['post_title'] ;
						echo '<option value="'.$forum_id.'" >'.$forum_title. ' ('.$forum_id.')' . '</option>' ;
					}
				}
				else {
					echo '<p style="color : red;">'.__('There are currently no forum.', 's2bd-bridge').'</p>' ;
				}
				echo '</select>' ;
				echo '<br />' ;
			}
			echo '<br />' ;	
			echo '<input type="submit" name="rely" value="'.__('Register', 's2bd-bridge').'" />' ;
		}
		else {
			echo '<p style="color : red;">'.__('There are currently no recorded field.', 's2bd-bridge').'</p>' ;
		}
	?>
	<!--<br />
	<input type="submit" name="rely" value="Enregistrer" />-->
	</form>
	<?php
	}
	//if (isset($_POST['input'])) {
	else {
		
		
			$nb_champs = count ($_POST);
			$nb_champs = $nb_champs-1 ;
			$nb_champs = $nb_champs/2 ;
			//echo $nb_champs ;
			for ($i=1; $i<=$nb_champs; $i++){
				$datafield = 'field'.$i ; $dataforum = 'forum'.$i ;
				//echo 'field : ' . $_POST[$datafield] . ' - forum : ' . $_POST[$dataforum].'<br />' ;
				
				//Test si choix effectué
				$testdefault = mb_substr_count($_POST[$dataforum], 'default');
				if ( $testdefault != 0 ) {
					echo '<p style="color : red;">'.__('You must choose a forum for each stored field.', 's2bd-bridge').'</p>' ;
				}
				if ( $testdefault == 0 ) {
					$sql_update = "UPDATE `".$wpdb."sbd_relations` SET `forum` = '".$_POST[$dataforum]."' WHERE `".$wpdb."sbd_relations`.`id` = '".$_POST[$datafield]."'" ;
					$result_update = mysql_query($sql_update) or die('Erreur SQL !<br>'.$sql_update.'<br>'.mysql_error());
					echo '<p style="color : green;">'.__('Update done!', 's2bd-bridge').'</p>' ;
				}
				
			}
			echo '<a style="text-decoration: none; font-weight: medium; color: #333;" href="admin.php?page=s2bd-bridge/forums-settings.php"> >> '.__('Actualize screen', 's2bd-bridge').'</a>' ;
		
	}

	?>
	</div>
	<hr />
	<h4><?php _e('End of the installation', 's2bd-bridge'); ?></h4>
	<p>
	<?php _e('If everything was configured correctly you can now see the following screens for monitoring and subscriptions of your users:', 's2bd-bridge'); ?><br /> 
	<a style="text-decoration: none;" href="admin.php?page=s2bd-bridge/fields-per-member.php"><?php _e('Fields per member', 's2bd-bridge'); ?></a><br />
	<a style="text-decoration: none;" href="admin.php?page=s2bd-bridge/forums-per-member.php"><?php _e('Forums per member', 's2bd-bridge'); ?></a><br />
	<a style="text-decoration: none;" href="admin.php?page=s2bd-bridge/forums-subscriptions-stats.php"><?php _e('Statistics', 's2bd-bridge'); ?></a><br />
	<a style="text-decoration: none;" href="admin.php?page=s2bd-bridge/inscribe-members.php"><?php _e('Inscribe members', 's2bd-bridge'); ?></a>
	</p>
</div>