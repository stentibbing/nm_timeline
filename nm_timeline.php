<?php

/**
 * @link              https://www.kbuum.com
 * @since             1.0.0
 * @package           Nm_timeline
 *
 * @wordpress-plugin
 * Plugin Name:       Nordic Milk Timeline
 * Plugin URI:        https://www.taifuun.ee
 * Description:       This plugin adds draggable timeline
 * Version:           1.0.4
 * Author:            Taifuun OÜ
 * Author URI:        https://www.taifuun.ee
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nm_timeline
 * Domain Path:       /languages
 * GitHub Plugin URI: stentibbing/nm_timeline
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 */
define('NM_TIMELINE_VERSION', '1.0.4');

/**
 * The code that runs during plugin activation.
 */
function activate_nm_timeline()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-nm_timeline-activator.php';
    Nm_timeline_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_nm_timeline()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-nm_timeline-deactivator.php';
    Nm_timeline_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_nm_timeline');
register_deactivation_hook(__FILE__, 'deactivate_nm_timeline');

/**
 * The core plugin class that is used to define internationalization,
 */
require plugin_dir_path(__FILE__) . 'includes/class-nm_timeline.php';

/**
 * Begins execution of the plugin.
 */
function run_nm_timeline()
{
    $plugin = new Nm_timeline();
    $plugin->run();
}

run_nm_timeline();
