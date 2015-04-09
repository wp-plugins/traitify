=== Wordpress Traitify ===
Contributors: smackcoders
Donate link: http://www.smackcoders.com/donate.html
Tags: personalityassessment, selfdiscovery, decisionmaking, personality, assessment, decision, users, people, registration, user, member, profile, wordpress, traitify, test, quiz, products, events, media, dating, solution, customers, engage, audience, Lead, students, professionals, opportunities, admin, Matchmaking, Marketing, Sales, CuratedContent
Requires at least: 4
Tested up to: 4.1
Stable tag: 1.0	
Version: 1.0
Author: smackcoders
Author URI: http://profiles.wordpress.org/smackcoders/

License: GPLv2 or later

Wordpress Traitify plugin to integrate Traitify's Personality API to your wordpress

== Description ==
Wordpress Traitify plugin to integrate Traitify's Personality API completely to your wordpress site. You can turn your wordpress site users to discover their personalities now. 

- At the time of sign-up, on boarding, or profile management you can capture personality information to.
- Get the perfect measurement of your audience based different assessments

User's need to simply select "Me" or "Not Me" to get s series of images and Traitify actionable data. With this psychology-backed data in hand can help for following
    Matchmaking
    Marketing / Sales
    Curated Content
    Recommendations
    Human Resources Management
    Personalized Experiences
    Career Building
    Behavior Predictions

Let us know your feedback, feature suggestion etc., here - 

== Installation ==

I. For simple general way to install

* Download the plugin (.zip file) on the right side above menu
* Click the Red Download Button ( Download Version X.X.X)
* Login to your Wordpress Admin (e.g. yourdomain.com/wp-admin/)
* Go to Plugins >> Add New
* Select the tab "Upload"
* Browse and Upload the downloaded zip file
* Activate the plugin after install
* You can see a new menu Wordpress Traitify in your Admin now

II. For familiar FTP users

* Download the plugin (.zip file) on the right side above menu
* Click the Red Download Button ( Download Version X.X.X)
* Extract the plugin zip
* Upload plugin zip to /wp-content/plugins/ 
* Go to Plugins >> Installed Plugins >> Inactive 	
* Click Activate to activate the plugin
* You can see a new menu Wordpress Traitify in your Admin now

III. Straight from Wordpress Admin

* Login to your Wordpress Admin (e.g. yourdomain.com/wp-admin/)
* Go to Plugins >> Add New
* Search for Wordpress Traitify
* Click Install Now to install
* Activate the plugin after install
* You can see a new menu Wordpress Traitify in your Admin now	


== Screenshots ==
1. List of all available Short-Codes.
2. List of all Users and their Assessment details.
3. List of Tests which users attended attended.
4. Detailed view of single test result.
5. Traitify app settings (SecretKey, PublicKey).


== Frequently Asked Questions ==

1) What are the minimum requirements before install the plugin?
	* Your PHP version should be above 5.3.
	* WordPress version should be above 4.0.
	
2) How to use the Traitify add-on ?
	After plugin activation. You can see the Traitify menu in left side menu bar. Goto settings menu configure your traitify app SecretKey & PublicKey. Then, Choose the Traitify menu there are multi Short Codes & Codes  avilable for different purpose of assessment test. You can copy the short code and place in your post content section (Or) You can copy the code and place it on your templates wherever you want. Now you can check the assessment test in front-end to the respective short-code.

Examples: 
	* Place the following short-code to view the super hero assessment. 
		[wp-traitify-shortcode type="super-hero"]
	* Place the code in your template section. 
		if(class_exists('wp_traitify')) { wp_traitify::traitify_show_questions('super-hero'); }

== Changelog ==
= 1.0.0 =	
* Initial release version. Tested and found works well without any issues.

== Upgrade Notice ==
= 1.0.0 =	
* Initial stable release version
