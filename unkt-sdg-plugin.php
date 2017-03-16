<?php
/**
 * Plugin Name: SDGs & Indicators
 * Plugin URI: http://opendatakosovo.org
 * Description: #TODO: Add description for the plugin
 * Version: 1.0.0
 * Author: Partin Imeri
 * Author URI: http://opendatakosovo.org
 * License: GPL2
 */
define('SDGS__PLUGIN_URL', plugin_dir_url(__FILE__));
define('SDGS__PLUGIN_DIR', plugin_dir_path(__FILE__));
require_once( SDGS__PLUGIN_DIR . 'class.unkt.php' );
$SDGPlugin = Unkt::init();
// Add the template option so when we create a page we can make it an SDG Template page.
require_once(SDGS__PLUGIN_DIR . 'page.php');
