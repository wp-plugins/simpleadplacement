<?php
/*
Plugin Name: simpleAdPlacement 
Plugin URI: http://www.hydronitrogen.com
Description: A tool which allows the simple placement of ads after posts, or on the bottom of pages.
Version: 0.5
Author: Hamel Ajay Kothari
Author URI: http://www.hydronitrogen.com
License: GPL2
*/

register_activation_hook(__FILE__, 'simpleAdInstall');
register_deactivation_hook(__FILE__, 'simpleAdUninstall');

function simpleAdInstall()
{
	add_option("simpleAd_postAdCode");
}

function simpleAdUninstall()
{
	delete_option("simpleAd_postAdCode");
}

if(is_admin())
{
	add_action("admin_menu", "simpleAd_adminMenu");

	function simpleAd_adminMenu()
	{
		add_options_page("SimpleAdPlacement Options", "SimpleAdPlacement", "manage_options", "simpleAdOptions", "simpleAdOptionsHtml");
	}

	function simpleAdOptionsHtml()
	{
		echo "<h2>SimpleAdPlacement Options</h2>";
		echo "<form method=\"post\" action=\"options.php\">";
		wp_nonce_field('update-options');
		echo "<strong>Enter your ad code here:</strong><br />";
		echo "<textarea name=\"simpleAd_postAdCode\" cols=\"50\" rows=\"20\">" . get_option("simpleAd_postAdCode") . "</textarea>";
		echo '<input type="hidden" name="action" value="update" />';
		echo '<input type="hidden" name="page_options" value="simpleAd_postAdCode" /><br />';
		echo '<input type="submit" value="Save Changes" />';
	}
}

add_filter('the_content', 'simpleAdPostAds');

function simpleAdPostAds($content)
{
	if(is_single())
		return $content . "<br />\n" . get_option("simpleAd_postAdCode");
	else
		return $content;
}

?>
