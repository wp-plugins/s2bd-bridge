<?php
//Security access to file directly
defined('ABSPATH') or die("No script kiddies please!");

### Check Whether User Can Manage Database
if(!current_user_can('manage_database')) {
	die('Access Denied');
}

### Variables Variables Variables
$base_name = plugin_basename('s2bd-bridge/s2bd-bridge-manager.php');
$base_page = 'admin.php?page='.$base_name;
?>
<div class="wrap">
	<h3>S2Member BbPress Digest Bridge - <?php _e('Home', 's2bd-bridge'); ?></h3>
	<hr />
	<p>
	<strong>S2BD Bridge</strong> <?php _e('allows the linking of user custom fields (S2Member plugin) with forums (bbPress plugin) and activity notifications by email (Digests bbPress plugin) for users who are subscribed.', 's2bd-bridge'); ?>
	</p>
	<p>
	S2BD Bridge <?php _e('is an added extension and therefore requires the prior installation of the following extensions which makes it the gateway:', 's2bd-bridge'); ?><br />
	- <a href="https://wordpress.org/plugins/s2member/">S2Member</a><br />
	- <a href="https://wordpress.org/plugins/bbpress/">bbPress</a><br />
	- <a href="https://wordpress.org/plugins/bbpress-digest/">bbPress Digests</a>
	</p>
	
	<h4><?php _e('Scenario', 's2bd-bridge'); ?></h4>
	<p>
	- <?php _e('A user registers by answering your custom questions', 's2bd-bridge'); ?> <strong><?php _e('(only questions with answers "yes/no")', 's2bd-bridge'); ?></strong> (S2Member custom fields) : 
		<br />&nbsp;&nbsp;&nbsp;&nbsp; . <?php _e('sample question', 's2bd-bridge'); ?> : <em><?php _e('Are you interested in English literature', 's2bd-bridge'); ?> (<?php _e('Possible answers: Yes or No', 's2bd-bridge'); ?>)</em><br />
	- <?php _e('Answers to questions are coupled to specific forums', 's2bd-bridge'); ?> (bbPress) : 
		<br />&nbsp;&nbsp;&nbsp;&nbsp; . <?php _e('sample forum correlated', 's2bd-bridge'); ?> : <em><?php _e('English literature', 's2bd-bridge'); ?></em><br />
	- <?php _e('Email notifications to user about news on related forums', 's2bd-bridge'); ?> (bbPress Digests)
	</p>
	
	<h4><?php _e('Features', 's2bd-bridge'); ?></h4>
	<p>
	<ul>
		<li>- <?php _e('Customizable settings', 's2bd-bridge'); ?> :</li>
		 <ul>
			 <li>&nbsp;&nbsp;&nbsp;&nbsp; . <?php _e('you can choose which custom fields and forums should be considered', 's2bd-bridge'); ?> (<?php _e('"Fields settings" and "Forums settings"', 's2bd-bridge'); ?>)</li>
			 <li>&nbsp;&nbsp;&nbsp;&nbsp; . <?php _e('you can choose which member roles should be considered', 's2bd-bridge'); ?>  (<?php _e('"General settings"', 's2bd-bridge'); ?>)</li>
			 <li>&nbsp;&nbsp;&nbsp;&nbsp; . <?php _e('you can choose a forum mandatory notification to all members', 's2bd-bridge'); ?>  (<?php _e('"General settings"', 's2bd-bridge'); ?>)</li>
		</ul>
		<li>- <?php _e('Customizable S2Member membership counting', 's2bd-bridge'); ?> ("<?php _e('Statistics', 's2bd-bridge'); ?>")</li>
		<li>- <?php _e('Listing S2Member members answers to questions', 's2bd-bridge'); ?> ("<?php _e('Fields per member', 's2bd-bridge'); ?>")</li>
		<li>- <?php _e('Listing Bbpress forums members registrations', 's2bd-bridge'); ?> ("<?php _e('Forums per member', 's2bd-bridge'); ?>")</li>
		<li>- <?php _e('Counting subscribers digests by forum', 's2bd-bridge'); ?> ("<?php _e('Statistics', 's2bd-bridge'); ?>")</li>
		<li>- <?php _e('Manual registration of S2Member members at email notifications of activity on bbpress forums', 's2bd-bridge'); ?> ("<?php _e('Inscribe members', 's2bd-bridge'); ?>")</li>
	</ul>
	</p>
	
	<hr />
	<h4><?php _e('Begin the installation (step 1)', 's2bd-bridge'); ?></h4>
	<p>
	<a style="text-decoration: none;" href="admin.php?page=s2bd-bridge/general-settings.php"><?php _e('General settings', 's2bd-bridge'); ?></a>
	</p>
	
	<hr />
	<p><?php _e('If you have found this extension very useful, thank you for making a donation to the developer! :)', 's2bd-bridge'); ?></p>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="L3VGXBMWAD2VG">
	<input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
	<img alt="" border="0" src="https://www.paypalobjects.com/fr_XC/i/scr/pixel.gif" width="1" height="1">
	</form>

</div>
