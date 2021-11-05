<?php

/**
 * The public-facing functionality of the plugin.
 */
class Nm_timeline_Public
{

    /**
     * The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/nm_timeline-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/nm_timeline-public.js', array( 'jquery' ), $this->version, false);
    }

    /**
     * Register shortcode to display timeline
     */
    public function add_shortcodes()
    {
        add_shortcode('nm_timeline', array($this, 'render_timeline'));
    }
}
