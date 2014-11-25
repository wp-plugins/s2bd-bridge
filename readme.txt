=== S2BD Bridge ===
Contributors: webbud65
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=L3VGXBMWAD2VG
Tags: s2member, bbpress, bbpress digest, customs fields, forum digests, emails notifications, linking fields with forums.
Requires at least: 4.0 (not tested below)
Tested up to: ...
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

S2BD Bridge is an addon plugin which allows the linking of user's custom fields (S2Member) with forums (bbPress) digests (bbPress Digests).

== Description ==

S2BD Bridge allows the linking of user's custom profile fields (S2Member plugin) with forums (bbPress plugin) and activity notifications by email (bbPress Digests plugin) for users who are subscribed.

S2BD Bridge is an added extension and therefore requires the prior installation of the following extensions which makes it the gateway:
- S2Member : https://wordpress.org/plugins/s2member/
- bbPress : https://wordpress.org/plugins/bbpress/
- bbPress Digests : https://wordpress.org/plugins/bbpress-digest/

Scenario :

* A user registers by answering your custom questions (only questions with answers "yes/no") (S2Member custom fields) : 
     * sample question: Are you interested in English literature ? (Possible answers: Yes or No)
* Answers to questions are coupled to specific forums (BbPress) : sample forum correlated : English literature
* Email notification alerts inform the user accordingly inform the user about the upcoming related forums (BbPress Digests)

Features :

* Customizable settings :
    * you can choose which custom fields and forums should be considered ("Fields settings" and "Forums settings")
    * you can choose which member Roles should be considered ("General settings")
    * you can choose a forum mandatory notification to all members ("General settings")
* Customizable S2Member membership counting  ("Statistics")
* Listing S2Member members answers to questions ("Fields per member")
* Listing Bbpress forums members registrations ("Forums per member")
* Counting subscribers digests by forum ("Statistics")
* Manual registration of S2Member members at email notifications of activity on Bbpress forums ("Inscribe members")

== Installation ==

1. Open `wp-content/plugins` Folder
2. Put: `Folder: s2bd-bridge`
3. Go to `wp-admin and activate `S2BD Bridge` Plugin
4. Rename `htaccess.txt` to `.htaccess` file in `Folder: wp-content/plugins/s2bd-bridge`
5. Go to `wp-admin -> S2BD Bridge` to configure the S2BD Bridge options.

== Upgrading ==

1. Deactivate `S2BD Bridge` Plugin
2. Open `wp-content/plugins` Folder
3. Put/Overwrite: `Folder: s2bd-bridge`
4. Go to `wp-admin and reactivate `S2BD Bridge` Plugin
5. Go to `wp-admin -> S2BD Bridge` to re-configure the database options.

== Frequently Asked Questions ==

= not yet =

not yet.

== Screenshots ==

1. Dashboard -> S2BD Bridge : reception and presentation of plugin (corresponds to /assets/screenshot-1.jpg). 
2. Dashboard -> General settings : general settings of plugin (corresponds to /assets/screenshot-2.jpg).
3. Dashboard -> Fields settings : configuration of which custom fields should be considered (only questions with answers "yes/no") (corresponds to /assets/screenshot-3.jpg).
4. Dashboard -> Forums settings : configuration of which forums should be considered, in relation to fields previously selected (corresponds to /assets/screenshot-4.jpg).
5. Dashboard -> Fields per member : listing of custom fields (answers "yes/no") per member (corresponds to /assets/screenshot-5.jpg).
6. Dashboard -> Forums per member : listing of digest forums per member (corresponds to /assets/screenshot-6.jpg).
7. Dashboard -> Statistics : counting forums, members and digest subscriptions per forum (corresponds to /assets/screenshot-7.jpg).
8. Dashboard -> Inscribe members : manual registration of members to digests, since the previously registered member to the digests (corresponds to /assets/screenshot-8.jpg).

== Changelog ==

not yet

== Upgrade Notice ==

= 1.0 =
not yet

