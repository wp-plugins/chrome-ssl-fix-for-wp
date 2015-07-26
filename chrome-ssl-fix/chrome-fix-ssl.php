<?php
/*
Plugin Name: Chrome SSL Fix
Plugin URI: http://www.wdc.me/chrome-ssl-fix.zip
Description: Fixes the problem with Google Chrome v44 forcing SSL on WordPress websites. You should deactivate when Chrome updates to new version.
Version: 1.0
Author: Stefan Vasiljevic
Author URI: http://www.wdc.me
*/
if ( ! defined( 'ABSPATH' ) ) exit;

/* Display a notice that can be dismissed
Thank you guys that pointed this out!
*/

add_action('admin_notices', 'cfs_wdc_admin_notice');

function cfs_wdc_admin_notice() {
	global $current_user ;
        $user_id = $current_user->ID;
        /* Check that the user hasn't already clicked to ignore the message */
	if ( ! get_user_meta($user_id, 'cfs_wdc_ignore_notice') ) {
        echo '<div class="updated"><p style="float:left;">'; 
        printf(__('Wow, now your website works again. Please consider making a donation. Thank you! <br> <br> <a href="%1$s">Hide Notice</a>'), '?cfs_wdc_nag_ignore=0');
        echo "</p>";
		
		echo '<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post" style="float:right; margin: .5em 0; padding: 2px;">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="WQKZ8N7D4AZYY">
<table>
<tr><td><input type="hidden" name="on0" value="Select donation amount">Select the amount you wish to donate:</td></tr><tr><td><select name="os0">
	<option value="Buy me a beer">Buy me a beer $5.00 USD</option>
	<option value="Buy me a coffe">Buy me a coffe $10.00 USD</option>
	<option value="Keep me in shape">Keep me in shape $20.00 USD</option>
	<option value="Keep me focused">Keep me focused $50.00 USD</option>
</select> </td></tr>
</table>
<input type="hidden" name="currency_code" value="USD">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>';
		
		echo "<div style='clear:both'></div>";
		echo "</div>";
	}
}

add_action('admin_init', 'cfs_wdc_nag_ignore');

function cfs_wdc_nag_ignore() {
	global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['cfs_wdc_nag_ignore']) && '0' == $_GET['cfs_wdc_nag_ignore'] ) {
             add_user_meta($user_id, 'cfs_wdc_ignore_notice', 'true', true);
	}
}


if ( ! class_exists( 'CFS_WDC_Google_Chrome_SSL_Fix' ) ) :
	class CFS_WDC_Google_Chrome_SSL_Fix {
		function __construct() {
			 if (!is_ssl()) {
			//Prevents issues with mixed requests
			//https://codex.wordpress.org/Function_Reference/is_ssl
				$_SERVER['HTTPS'] = false;
			}
		}
	}
	new CFS_WDC_Google_Chrome_SSL_Fix;
endif;
?>