<?php
/*
Plugin Name: simpleAdPlacement 
Plugin URI: http://www.hydronitrogen.com/projects/simpleadplacement/
Description: A tool which allows the simple placement of ads after posts, or on the bottom of pages.
Version: 0.81
Author: Hamel Ajay Kothari
Author URI: http://www.hydronitrogen.com/
License: GPL2
*/

register_activation_hook(__FILE__, 'simpleAdInstall');
register_deactivation_hook(__FILE__, 'simpleAdUninstall');

function simpleAdInstall()
{
	add_option("simpleAd_postAdCode");
	add_option("simpleAd_bottomAdCode");
	add_option("simpleAd_shortAdCode");
}

function simpleAdUninstall()
{
	delete_option("simpleAd_postAdCode");
	delete_option("simpleAd_bottomAdCode");
	delete_option("simpleAd_shortAdCode");
}

if(is_admin())
{
	add_action("admin_menu", "simpleAd_adminMenu");

	function simpleAd_adminMenu()
	{
		add_options_page("SimpleAdPlacement Options", "SimpleAdPlacement", "manage_options", "simpleAdOptions", "simpleAdOptionsHtml");
	}

	function simpleAdOptionsHtml()
	{ ?>
		<h2>SimpleAdPlacement Options</h2>
		<form method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>
		<table>
			<tr>
				<td>
					<strong>Enter your ad code for the single posts here:</strong><br />
					<textarea name="simpleAd_postAdCode" cols="50" rows="20"><?php echo get_option("simpleAd_postAdCode"); ?></textarea><br />
				</td>
				<td>
					<strong>Enter you ad code for the bottom of the page here:</strong><br />
					<textarea name="simpleAd_bottomAdCode" cols="50" rows="20"><?php echo get_option("simpleAd_bottomAdCode"); ?></textarea><br />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<strong>Enter your ad code for the shortcode: (To use it, just type [simpleAdPlacement] in a post or widget)</strong><br />
					<textarea name="simpleAd_shortAdCode" cols="50" rows="20"><?php echo get_option("simpleAd_shortAdCode"); ?></textarea><br />
				</td>
			</tr>
		</table>
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="simpleAd_postAdCode,simpleAd_bottomAdCode,simpleAd_shortAdCode" /><br />
		<input type="submit" value="Save Changes" /><br />

		If you feel this plugin was helpful, please consider giving it a good rating on wordpress.org and clicking the works button. Thanks!';

	<?php }
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

add_shortcode("simpleAdPlacement", "simpleAdPlacementShortCode");

function simpleAdPlacementShortcode()
{
	return get_option("simpleAd_shortAdCode");
}

?>
