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
	add_option("simpleAd_bottomAdCode");
}

function simpleAdUninstall()
{
	delete_option("simpleAd_postAdCode");
	delete_option("simpleAd_bottomAdCode");
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
		echo "<strong>Enter your ad code for the single posts here:</strong><br />";
		echo "<textarea name=\"simpleAd_postAdCode\" cols=\"50\" rows=\"20\">" . get_option("simpleAd_postAdCode") . "</textarea><br />";
		echo "<strong>Enter you ad code for the bottom of the page here:</strong><br />";
		echo "<textarea name=\"simpleAd_bottomAdCode\" cols=\"50\" rows=\"20\">" . get_option("simpleAd_bottomAdCode") . "</textarea><br />";
		echo '<input type="hidden" name="action" value="update" />';
		echo '<input type="hidden" name="page_options" value="simpleAd_postAdCode,simpleAd_bottomAdCode" /><br />';
		echo '<input type="submit" value="Save Changes" /><br />';

		echo 'If you feel this plugin was helpful, please consider giving it a good rating on wordpress.org and clicking the works button. Thanks!';
	}
}

add_filter('the_content', 'simpleAdPostAds');
add_action('wp_footer', 'simpleAdBottomAds');

function simpleAdPostAds($content)
{
	if(is_single())
		return $content . "<br />\n" . get_option("simpleAd_postAdCode");
	else
		return $content;
}

function simpleAdBottomAds()
{
	echo "<div align=\"center\">" . get_option("simpleAd_bottomAdCode") .  "</div>";
}

?>
