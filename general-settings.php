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
	<h3><?php _e('Define the general parameters', 's2bd-bridge'); ?></h3>
	<hr />
	<div>
	<?php
	$input = isset($_POST['input']) ? $_POST['input'] : '' ;
	if (!isset($_POST['input'])) {

		$sql_options = "SELECT * FROM `".$wpdb."sbd_options`" ;
		$req_options = mysql_query($sql_options) or die('Erreur SQL !<br>'.$sql_options.'<br>'.mysql_error());
		if(mysql_num_rows($req_options)){
		
			$data_options = mysql_fetch_assoc($req_options) ;
				
				$user_role = $data_options['user_role'] ;
				$default_forums = $data_options['default_forums'] ;
				$s_values = $data_options['s_values'] ;
				$variant_forum = $data_options['variant_forum'] ;
				$variant_operator = $data_options['variant_operator'] ;
				
		
		}
		else {
			echo '<p style="color : red;">'.__('There are currently no option recorded.', 's2bd-bridge').'</p>' ;
		}
		
	}
	else {
		
		$user_role = $_POST['user_role'] ;
		$default_forums = $_POST['default_forums'] ;
		$s_values = $_POST['s_values'] ;
		$variant_forum = $_POST['variant_forum'] ;
		$variant_operator = $_POST['variant_operator'] ;
		
		echo '<p style="color : green;">'.__('Configured Options: Member role : ', 's2bd-bridge').$user_role.__(' - Default forum : ', 's2bd-bridge').$default_forums.__(' - S value(s) : ', 's2bd-bridge').$s_values.'</p>' ;
		
		$sql_options = "SELECT * FROM `".$wpdb."sbd_options`" ;
		$req_options = mysql_query($sql_options) or die('Erreur SQL !<br>'.$sql_options.'<br>'.mysql_error()); 
		$data_options = mysql_fetch_assoc($req_options);
		$data_id = $data_options['id'] ;
		if( $data_id != '' ){
			echo '<p style="color : green;">'.__('Some options are already registered', 's2bd-bridge').'</p>' ;
			
			$sql_update = "UPDATE `".$wpdb."sbd_options` SET `user_role` = '".$user_role."',`default_forums` = '".$default_forums."',`s_values` = '".$s_values."',`variant_forum` = '".$variant_forum."',`variant_operator` = '".$variant_operator."' WHERE `".$wpdb."sbd_options`.`id` ='".$data_id."'" ;
			$req_update = mysql_query($sql_update) or die('Erreur SQL !<br>'.$sql_update.'<br>'.mysql_error());
			echo '<p style="color : orange;">'.__('Updated!', 's2bd-bridge').'</p>' ;
		}
		else {
			echo '<p style="color : red;">'.__('No option is registered.', 's2bd-bridge').'</p>' ;
			$sql_truncate = "TRUNCATE TABLE `".$wpdb."sbd_options`" ;
			$result_truncate = mysql_query($sql_truncate) or die('Erreur SQL !<br>'.$sql_truncate.'<br>'.mysql_error());
		
			$sql_insert = "INSERT INTO `".$wpdb."sbd_options` (`id`,`user_role`,`default_forums`,`s_values`,`variant_forum`,`variant_operator`) VALUES ('1','".$user_role."','".$default_forums."','".$s_values."','".$variant_forum."','".$variant_operator."')" ;
			$result_insert = mysql_query($sql_insert) or die('Erreur SQL !<br>'.$sql_insert.'<br>'.mysql_error());
			echo '<p style="color : orange;">'.__('Inserting done!', 's2bd-bridge').'</p>' ;
		}
		
		
		
	}
	?>
	<form name="params" method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>">
		<table width="100%">
		<tbody>
		<tr>
		<td style="vertical-align: top;"><label for="user-role"><?php _e('User role', 's2bd-bridge'); ?></label><br />
			<input type="text" name="user_role" value="<?php echo $user_role ; ?>" />
		</td>
		<td style="vertical-align: top;"><label for="default-forums"><?php _e('Default forum', 's2bd-bridge'); ?></label><br />
			<input type="text" name="default_forums" value="<?php echo $default_forums ; ?>" />
		</td>
		<td style="vertical-align: top;"><label for="s-values"><?php _e('S values', 's2bd-bridge'); ?></label><br />
			<input type="text" name="s_values" value="<?php echo $s_values ; ?>" />
		</td>
		<td style="vertical-align: top;"><label for="variant-forum"><?php _e('Variant forum', 's2bd-bridge'); ?></label><br />
			<input type="text" name="variant_forum" value="<?php echo $variant_forum ; ?>" /><br />
			<em><?php _e('For the value S', 's2bd-bridge'); ?> : <br /> <?php _e('numeric identifier for which this value changes', 's2bd-bridge'); ?>.<br /> <?php _e('Leave blank if the value S is always the same', 's2bd-bridge'); ?>.</em>
		</td>
		<td style="vertical-align: top;">
			<label for="variant-operator"><?php _e('Variance operator', 's2bd-bridge'); ?></label><br />
			<input type="text" name="variant_operator" value="<?php echo $variant_operator ; ?>" /><br />
			<em><?php _e('For the value S', 's2bd-bridge'); ?> : <br />
			- "==" <?php _e('if the second value of S only changes for this forum', 's2bd-bridge'); ?><br />
			- ">=" <?php _e('if this value changes from this forum and for those whose numerical identifier is greater', 's2bd-bridge'); ?><br />
			- <?php _e('Leave blank if the value S is always the same', 's2bd-bridge'); ?>.
			</em>
		</td>
		</tr>
		<tr>
		<td colspan="5" style="border: 0px solid #eee;">
			<input type="submit" name="input" value="<?php _e('Register', 's2bd-bridge'); ?>" />
		</td>
		</tr>
		</tbody>
		</table>
	</form>
	<hr />

	<h4><?php _e('User manual', 's2bd-bridge'); ?></h4>
	<p>
	- <strong><?php _e('User role', 's2bd-bridge'); ?> :</strong><br /> <?php _e('Enter here S2Member user role(s) targeted for your subscription', 's2bd-bridge'); ?>.<br />
	<?php _e('Labels to apply', 's2bd-bridge'); ?> : <em>s2member_level1</em> <?php _e('or', 's2bd-bridge'); ?> <em>s2member_level2</em> <?php _e('or', 's2bd-bridge'); ?> <em>s2member_level3</em> <?php _e('or', 's2bd-bridge'); ?> <em>s2member_level4</em> <?php _e('or even multiple roles, spaced with commas', 's2bd-bridge'); ?>.<br />
	- <strong><?php _e('Default forum', 's2bd-bridge'); ?> :</strong><br /> <?php _e('You want your users to automatically be subscribed to a forum unrelated to any custom field, for example for internal communication to all your members', 's2bd-bridge'); ?>. <?php _e('Indicate above its numerical identifier', 's2bd-bridge'); ?>.<br />
	- <strong><?php _e('S values', 's2bd-bridge'); ?> :</strong><br /> <?php _e('The most delicate, because they change according to each environment, so you must first locate value(s) of S, as shown below', 's2bd-bridge'); ?> :<br />
	<?php _e('Example', 's2bd-bridge'); ?> :<br /> 
	- i:0;s:<span style="color: red;">1</span>:"5" : <?php _e('S value is 1', 's2bd-bridge'); ?><br />
	- i:3;s:<span style="color: red;">2</span>:"24" : <?php _e('S value is 2', 's2bd-bridge'); ?><br />
	<?php _e('So here you would specify the value above', 's2bd-bridge'); ?> : 1,2<br />
	- <strong><?php _e('Variant forum and variance operator', 's2bd-bridge'); ?> :</strong><br />
	<?php _e('Example', 's2bd-bridge'); ?> :<br />
	<?php _e('Seeking your values S via the form below you get for example', 's2bd-bridge'); ?> : 
	<em>a:9:{i:0;s:1:"5";i:1;s:2:"10";i:2;s:2:"12";i:3;s:2:"14";i:4;s:2:"16";i:5;s:2:"24";i:6;s:2:"26";i:7;s:2:"30";i:8;s:2:"34";}</em><br />
	<?php _e('In this case your settings to configure will be', 's2bd-bridge'); ?> : <br />
	- <?php _e('Variant forum', 's2bd-bridge'); ?> : <span style="color: red;">10</span> <br />
	- <?php _e('Variance operator', 's2bd-bridge'); ?> : <span style="color: red;">>=</span>
	<br />
	<?php _e('Indeed, for all forums higher or equal to 10, the value S is equal to 2. Below for the forum 5, the value S is equal to 1.', 's2bd-bridge'); ?>
	</p>

	<p>
	<strong><?php _e('Search the S values for my environment', 's2bd-bridge'); ?> :</strong><br />
	<form name="id" method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>">
	<input type="number" name="user_id" min="1" max="">
	<input type="submit" value="<?php _e('Send', 's2bd-bridge'); ?>" />
	</form>
	<?php
	$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '' ;
	if ($user_id != '') {
		$sql_user = "SELECT `meta_value` FROM `".$wpdb."usermeta` WHERE `user_id` = '" . $user_id . "' AND `meta_key` = 'bbp_digest_forums'" ;
		$req_user = mysql_query($sql_user) or die('Erreur SQL !<br>'.$sql_user.'<br>'.mysql_error()); 
		if(mysql_num_rows($req_user)){
			$data_user = mysql_fetch_assoc($req_user);
			$meta_value = $data_user['meta_value'] ;
			echo '<p style="color: orange;">'.__('Template value for user number ', 's2bd-bridge').$user_id.' : <em>'.$meta_value.'</em></p>' ;
		}
		else {
			echo '<p style="color: red;">'.__('There is no user registered for this identifier', 's2bd-bridge').'</p>' ;
		}
	}
	else {		
		echo '<p style="color: green;">'.__('First enter the numeric identifier of your witness user (user to pre-register on all your forums), then click on the button above to display a "meta value" complete template.', 's2bd-bridge').'</p>' ;
	}
	?>
	</p>
	</div>

	<hr />

	<h4><?php _e('Continue the installation (step 2)', 's2bd-bridge'); ?></h4>
	<p>
	<a style="text-decoration: none;" href="admin.php?page=s2bd-bridge/fields-settings.php"><?php _e('Fields settings', 's2bd-bridge'); ?></a>
	</p>
</div>