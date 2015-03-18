=== S2BD Bridge ===
Contributors: webbud65
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=L3VGXBMWAD2VG
Tags: s2member custom profile fields, bbpress forum digests, bbpress digest email notifications, linking fields with forums, bbpress, bbpress digest, s2member.
Requires at least: 4.0 (not tested below)
Tested up to: 4.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Addon plugin which allows the linking of custom profile fields (S2Member) with forums (bbPress) digests (bbPress Digests).

== Description ==

S2BD Bridge allows the linking of user's custom profile fields (S2Member plugin) with forums (bbPress plugin) and activity notifications by email (bbPress Digests plugin) for users who are subscribed.

S2BD Bridge is an added extension and therefore requires the prior installation of the following extensions which makes it the gateway:
* S2Member : https://wordpress.org/plugins/s2member/
* bbPress : https://wordpress.org/plugins/bbpress/
* bbPress Digests : https://wordpress.org/plugins/bbpress-digest/

Scenario :

* A user registers by answering your custom questions (only questions with answers "yes/no") (S2Member custom fields) : 
     * sample question: Are you interested in English literature ? (Possible answers: Yes or No)
* Answers to questions are coupled to specific forums (BbPress) : sample forum correlated : English literature
* Email notification alerts inform the user accordingly inform the user about the upcoming related forums (BbPress Digests)

Features :

On free version :

* Customizable settings :
    * you can choose which custom fields and forums should be considered ("Fields settings" and "Forums settings")
    * you can choose which member Roles should be considered ("General settings")
    * you can choose a forum mandatory notification to all members ("General settings")
* Customizable S2Member membership counting  ("Statistics")
* Listing S2Member members answers to questions ("Fields per member")
* Listing Bbpress forums members registrations ("Forums per member")
* Counting subscribers digests by forum ("Statistics")
* Manual registration of S2Member members at email notifications of activity on Bbpress forums ("Inscribe members")

On pro version (http://buddy-wds.com/developpements/extensions/s2bd-bridge-extension/):

* Manual registrations (only one member, all members, 5 by 5... or reset to your custom value)
* Automatic registration checker with schedule options
* Listing members demoted in level S0 by S2Member (useful for a raise by email, for example)

IMPORTANT :

* When you update, if you do not want to lose your last member ID treated, thank you to follow the upgrade notice in "Other notes", points 1 and 5.

== Installation ==

1. Open wp-content/plugins folder
2. Put s2bd-bridge folder
3. Go to wp-admin, in your dashboard, and activate S2BD Bridge Plugin
4. Rename htaccess.txt to .htaccess file in folder: wp-content/plugins/s2bd-bridge
5. Go to wp-admin, in your dashboard, then S2BD Bridge menu to configure the S2BD Bridge options.

== Upgrading ==

1. Before upgrade, go to wp-admin, in your dashboard, then S2BD Bridge/Inscribe members menu and note ID of your last member treated
2. Deactivate S2BD Bridge plugin in your dashboard
3. Open wp-content/plugins folder
4. Put/Overwrite s2bd-bridge folder
5. Open wp-content/plugins/s2bd-bridge/lastiduser.txt file and write/replace ID by your own last ID member treated
6. Now, go to wp-admin, in your dashboard, and reactivate S2BD Bridge plugin
7. Go to wp-admin, in your dashboard, then S2BD Bridge menu to re-configure the database options, if necessary.


== Frequently Asked Questions ==

not yet.

== Screenshots ==

1. Dashboard -> S2BD Bridge : reception and presentation of plugin. 
2. Dashboard -> General settings : general settings of plugin.
3. Dashboard -> Fields settings : configuration of which custom fields should be considered (only questions with answers "yes/no").
4. Dashboard -> Forums settings : configuration of which forums should be considered, in relation to fields previously selected.
5. Dashboard -> Fields per member : listing of custom fields (answers "yes/no") per member.
6. Dashboard -> Forums per member : listing of digest forums per member.
7. Dashboard -> Statistics : counting forums, members and digest subscriptions per forum.
8. Dashboard -> Inscribe members : manual registration of members to digests, since the previously registered member to the digests.

== Changelog ==

= 1.0.2 =
15/01/2015
* Database storage of last member ID treated 

= 1.0.1 =
27/11/14
* Small layout corrections
* Corrections of the translation file
* Corrections of the readme.txt file

= 1.0 =
First release 25/11/14

