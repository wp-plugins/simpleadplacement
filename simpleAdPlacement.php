<?php
/*
Plugin Name: simpleAdPlacement 
Plugin URI: http://www.hydronitrogen.com/projects/simpleadplacement/
Description: A tool which allows the simple placement of ads after posts, or on the bottom of pages.
Version: 0.93
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
	add_option("simpleAd_preFooterAdCode");
	add_option("simpleAd_shortAdCode");

	add_option("simpleAd_footerDivId", "footer");
}

function simpleAdUninstall()
{
	delete_option("simpleAd_postAdCode");
	delete_option("simpleAd_bottomAdCode");
	delete_option("simpleAd_preFooterAdCode");
	delete_option("simpleAd_shortAdCode");

	delete_option("simpleAd_footerDivId");
}

if(is_admin())
{
	add_action("admin_menu", "simpleAd_adminMenu");

	function simpleAd_adminMenu()
	{
		$page = add_options_page("SimpleAdPlacement Options", "SimpleAdPlacement", "manage_options", "simpleAdOptions", "simpleAdOptionsHtml");
		add_action("admin_print_styles-$page", 'simpleAdLoadAdminStyles');
		add_action("admin_print_scripts-$page", 'simpleAdLoadAdminScripts');
	}

	function simpleAdLoadAdminStyles()
	{
		wp_enqueue_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/base/jquery-ui.css');
	}

	function simpleAdLoadAdminScripts()
	{
		wp_enqueue_script('jquery','http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
		wp_enqueue_script('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js');
	}

	function simpleAdOptionsHtml()
	{ ?>
		<h2>SimpleAdPlacement Options</h2>
		<form method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$("#formTabs").tabs();
			});
		</script>
		<div id="formTabs">
			<ul>
				<li><a href="#tabSingle">Single Posts</a></li>
				<li><a href="#tabBottom">Page Bottom</a></li>
				<li><a href="#tabAboveFooter">Above Footer</a></li>
				<li><a href="#tabShortCode">Short Code</a></li>
				<li><a href="#tabConfig">Configuration</a></li>
			</ul>
			
			<div id="tabSingle">
				<strong>Enter your ad code for the single posts here:</strong><br />
				<textarea name="simpleAd_postAdCode" cols="50" rows="20"><?php echo get_option("simpleAd_postAdCode"); ?></textarea>
			</div>
			
			<div id="tabBottom">
				<strong>Enter you ad code for the bottom of the page here:</strong><br />
				<textarea name="simpleAd_bottomAdCode" cols="50" rows="20"><?php echo get_option("simpleAd_bottomAdCode"); ?></textarea>
			</div>
			
			<div id="tabAboveFooter">
				<strong>Enter your ad code for the area right above the footer here:</strong><br />
				Note, this might not work if your theme uses a div id other than "footer" for the footer. It probably does though.<br />
				<textarea name="simpleAd_preFooterAdCode" cols="50" rows="20"><?php echo get_option("simpleAd_preFooterAdCode"); ?></textarea><br />
			</div>
			
			<div id="tabShortCode">
				<strong>Enter your ad code for the shortcode: </strong><br />
				To use it, just type [simpleAdPlacement] in a post or widget.<br />
				<textarea name="simpleAd_shortAdCode" cols="50" rows="20"><?php echo get_option("simpleAd_shortAdCode"); ?></textarea><br />
			</div>

			<div id="tabConfig">
				<p>If you're having problems with the "Above Footer" ad not showing up above the footer, your footer
				div is probably not id'd as "footer" view the source on your home page and find the id of your footer
				and enter it below.</p>
				<strong>Footer Div ID:</strong><input type="text" name="simpleAd_footerDivId" value="<?php echo get_option("simpleAd_footerDivId"); ?>" /><br />
			</div>
		</div>
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="simpleAd_postAdCode,simpleAd_bottomAdCode,simpleAd_preFooterAdCode,simpleAd_shortAdCode,simpleAd_footerDivId" /><br />
		If you feel this plugin was helpful, please consider giving it a good rating on wordpress.org and visiting <a href="http://www.hydronitrogen.com" target="_blank">my site.</a> Thanks!<br />
		<input type="submit" value="Save Changes" />
	<?php }
}

add_action('init', 'simpleAdInit');
function simpleAdInit()
{
	wp_enqueue_script('jquery','http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
}

add_filter('the_content', 'simpleAdPostAds');
add_action('wp_footer', 'simpleAdBottomAds');
add_action('get_footer', 'simpleAdPreFooterAds');
function simpleAdPostAds($content)
{
	if(is_single())
		return $content . "<br />\n" . get_option("simpleAd_postAdCode");
	else
		return $content;
}

function simpleAdBottomAds()
{
	echo get_option("simpleAd_bottomAdCode");
}

function simpleAdPreFooterAds()
{
	echo "<div id=\"simpleAdPreFooter\">" . get_option("simpleAd_preFooterAdCode") . "</div>";
	echo <<<EOF

	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#simpleAdPreFooter').insertBefore('#<?php echo get_option('simpleAd_footerDivId'); ?>');
		});
	</script>

EOF;
}

// For shortcodes to work we need to make sure that do_shortcode is called.
add_filter('the_content', 'do_shortcode', 11);
//if(!is_admin())
add_filter('widget_text', 'do_shortcode');

add_shortcode("simpleAdPlacement", "simpleAdPlacementShortCode");

function simpleAdPlacementShortcode()
{
	return get_option("simpleAd_shortAdCode");
}

?>
