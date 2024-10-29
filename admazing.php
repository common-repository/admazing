<?php
/*
Plugin Name: Admazing
Plugin URI: http://www.knewsplugin.com
Description: Admazing
Version: 0.9.1
Author: Carles Reverter
Author URI: http://www.carlesrever.com
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
*/

$admazing_ajax=false; $admazing_autosave=false;
if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) $admazing_autosave=true;
if (defined('DOING_AJAX') && DOING_AJAX) $admazing_ajax=true;

if ( (is_admin() && !$admazing_ajax && !$admazing_autosave) || in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php')) ) {

	if (!class_exists("Admazing")) require 'admazing_class.php';
	
	//Initialize the plugin
	if (!function_exists("Admazing_ap") && class_exists("Admazing")) {
		$Admazing = new Admazing();
		define('ADMAZING_VERSION', '0.9.1');

		add_action('admin_menu', array($Admazing, 'init'));
		add_action('wp_ajax_admazing', array($Admazing, 'ajax') );
		//add_action('after_setup_theme', array($Admazing, 'menu_support' ), 11 );

		add_image_size( "loginLogoSize", 320, 84, false );
		add_filter( 'image_size_names_choose', array($Admazing, 'admazing_custom_sizes') );	
			
		add_action('login_head', array($Admazing, 'login_css') );
		add_filter( 'login_headerurl', array($Admazing, 'login_logo_url') );

		$AdmazingOptions = $Admazing->getAdminOptions();

		if (!defined('WP_POST_REVISIONS') && $AdmazingOptions['revisions']==0) define('WP_POST_REVISIONS', false);
		if ($AdmazingOptions['autosave']==0) add_action( 'wp_print_scripts', array($Admazing, 'disable_autosave') );

		if ($AdmazingOptions['excerptpage']==1) add_action( 'init', array($Admazing, 'my_add_excerpts_to_pages') );

	}
}

?>
