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
	<h3><?php _e('Inscribe members to forums digests', 's2bd-bridge'); ?></h3>
	<hr />
	<div>
	<?php
	// v1.2 change
	/*
	$fp = fopen (dirname(__FILE__)."/lastiduser.txt", "r+");
	$lastiduser = fgets ($fp, 11);
	fclose ($fp);
	*/
	// end v1.2 change
	/***/
	$sql_options = "SELECT * FROM `".$wpdb."sbd_options`" ;
	$req_options = mysql_query($sql_options) or die('Erreur SQL !<br>'.$sql_options.'<br>'.mysql_error());
	if(mysql_num_rows($req_options)){
		$data_options = mysql_fetch_assoc($req_options) ;
		$user_roles = $data_options['user_role'] ;
		//echo $user_roles.'<br />' ;	//test
		$default_forums = $data_options['default_forums'] ;
		$s_values = $data_options['s_values'] ;
		$variant_forum = $data_options['variant_forum'] ;
		$variant_operator = $data_options['variant_operator'] ;
		$lastiduser = $data_options['lastuserid'] ;	// add v1.2
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
		
		$sql_lastuser = "SELECT `user_id` FROM `".$wpdb."usermeta` WHERE `meta_key` = '".$wpdb."capabilities' AND `meta_value` LIKE '%".$value."%' AND `user_id` > '" . $lastiduser . "' LIMIT 0 , 30" ;
		
		$req_lastuser = mysql_query($sql_lastuser) or die('Erreur SQL !<br>'.$sql_lastuser.'<br>'.mysql_error()); 
		if(mysql_num_rows($req_lastuser)){
			$i = 0 ;
			while($data_lastuser = mysql_fetch_assoc($req_lastuser)) { 	
				$i++ ;
				$user_id = $data_lastuser['user_id'] ;
				$sql_meta = "SELECT `meta_value` FROM `".$wpdb."usermeta` WHERE `user_id` = '" . $user_id . "' AND `meta_key` = '".$wpdb."s2member_custom_fields'" ;
				$req_meta = mysql_query($sql_meta) or die('Erreur SQL !<br>'.$sql_meta.'<br>'.mysql_error()); 
				$data_meta = mysql_fetch_assoc($req_meta);
				$data_int = $data_meta['meta_value'] ;
				$test = '' ; 
				$fields = '' ;
				$forums = $default_forums.'-' ;	//ONLINE pre settings : forum obligatoire
				$nbforums = 1 ;	
				$sql_bridge = "SELECT * FROM `".$wpdb."sbd_relations`" ;
				$result_bridge = mysql_query($sql_bridge) or die('Erreur SQL !<br>'.$sql_bridge.'<br>'.mysql_error());
				if(mysql_num_rows($result_bridge)){
					$j = 0 ;
					while($data_bridge = mysql_fetch_assoc($result_bridge)) { 	
						$j++ ;
						//$test = mb_substr_count($data_int, '"'.$data_bridge['field'].'";s:3:"oui"');	//old
						$test = mb_substr_count($data_int, '"'.$data_bridge['field'].'";s:3:"'.__('yes', 's2bd-bridge').'"');	//new 190315
						if ( $test != 0 ) {
							$fields .= $data_bridge['field'] . ' - ' ; 
							$forums .= $data_bridge['forum'] . '-' ;
							$nbforums++; 
						}
					}
					$fields = substr($fields,0,-2);
					$forums = substr($forums,0,-1);
				}
				$digests = preg_split("/-/", $forums) ;
				//print_r($digests).'<br />' ;	//test
				$bbpdigestforums = 'a:' . $nbforums . ':{' ;
				$k = -1 ;
				foreach ($digests as &$value) {
					$k++;
					//echo $value.'<br />' ;	//test
					//if($value >= 13729) {$s = 5 ;} else {$s = 4 ;}							//ONLINE pre settings : s values
					//if($value == 5) {$s = 1 ;} else {$s = 2 ;}								//LOCAL
					list($s_value1, $s_value2) = explode(',', $s_values);
					$testegal = mb_substr_count($variant_operator, '==');
					if ( $testegal != 0 ) {
						if($value == $variant_forum) {$s = $s_value1 ;} else {$s = $s_value2 ;}
					}
					$testsupegal = mb_substr_count($variant_operator, '>=');
					if ( $testsupegal != 0 ) {
						if($value >= $variant_forum) {$s = $s_value2 ;} else {$s = $s_value1 ;}
					}
					if ( $variant_operator == '' && $variant_forum == '' ) {
						$s = $s_value1 ;
					}
					$bbpdigestforums .= 'i:' . $k . ';s:'. $s .':"' . $value . '";' ;
						
				}
				$bbpdigestforums .= '}' ;
				
				echo '<p>'.$i.__(' - User ID : ', 's2bd-bridge').' '.$user_id.' '.__(' - Number of forums : ', 's2bd-bridge').' '.$nbforums.' '.__(' - Forums : ', 's2bd-bridge').' '.$forums.' '.__(' - Fields interests : ', 's2bd-bridge').' '.$fields.'</p>' ;
				
				//echo $bbpdigestforums . "<br />" ;	//test what ???
				
				if ( $bbpdigestforums != '' ) {
					$sql_digest = "SELECT `umeta_id`, `meta_value` FROM `".$wpdb."usermeta` WHERE `user_id` = '" . $user_id . "' AND `meta_key` = 'bbp_digest_forums'" ;
					$req_digest = mysql_query($sql_digest) or die('Erreur SQL !<br>'.$sql_digest.'<br>'.mysql_error()); 
					$data_digest = mysql_fetch_assoc($req_digest);
					$data_id = $data_digest['umeta_id'] ;
					if( $data_id != '' ){
						echo '<p style="color : green;">'.__('This user already has a bbpress digest... ', 's2bd-bridge') ;
						echo __('No update to do', 's2bd-bridge').'.</p>' ;
						//echo '</p>'.echo __('Forums already registered: ', 's2bd-bridge'). ' ' .$data_digest['meta_value'].'</p>' ;	//test
					}
					else {
						echo '<p style="color : red;">'.__('This user has no bbp digest... to do...', 's2bd-bridge').'</p>' ;
						$sql_insert1 = "INSERT INTO `".$wpdb."usermeta` (`umeta_id` ,`user_id` ,`meta_key` ,`meta_value`)VALUES (NULL , '" . $user_id . "', 'bbp_digest_forums', '" . $bbpdigestforums . "')" ;			
						$result_insert1 = mysql_query($sql_insert1) or die('Erreur SQL !<br>'.$sql_insert1.'<br>'.mysql_error());
						$sql_insert2 = "INSERT INTO `".$wpdb."usermeta` (`umeta_id` ,`user_id` ,`meta_key` ,`meta_value`)VALUES (NULL , '" . $user_id . "', 'bbp_digest_time', '00')" ;			
						$result_insert2 = mysql_query($sql_insert2) or die('Erreur SQL !<br>'.$sql_insert2.'<br>'.mysql_error());
						echo '<p style="color : green;">'.__('Insertion done... update well done!', 's2bd-bridge').'</p>' ;
					}
				}
				echo '<hr />' ;	
			}
			$lastiduser = $user_id ;
			echo '<p style="color : orange;">'.__('Last processed user, user ID : ', 's2bd-bridge').' '.$lastiduser.'</p>' ;
			// v1.2 change
			/*$fp = fopen (dirname(__FILE__)."/lastiduser.txt", "r+");
			fseek ($fp, 0);
			fputs ($fp, $lastiduser);
			fclose ($fp);*/
			// Store last user in in DB
			$sql_store = "UPDATE `".$wpdb."sbd_options` SET `lastuserid` = '".$lastiduser."' WHERE `".$wpdb."sbd_options`.`id` =1 LIMIT 1 ;" ;
			$req_store = mysql_query($sql_store) or die('Erreur SQL !<br>'.$sql_store.'<br>'.mysql_error());
			// end v1.2 change
		}
		else {
			echo '<p style="color : red;">'.__('Last processed user, user ID : ', 's2bd-bridge').' '.$lastiduser.'</p>' ;
			echo '<p style="color : green;">'.__('There are currently no other member of this role to process.', 's2bd-bridge').'</p>' ;
		}
		
		/***/
		echo '<hr />' ;

	}
	?>
	</div>
</div>