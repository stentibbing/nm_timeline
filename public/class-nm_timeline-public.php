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
        $args = array(
            'post_type' => 'event',
            'orderby' => 'meta_value_num',
            'meta_key' => 'nm_timeline_event_date',
            'order' => 'ASC',
            'nopaging' => true,
        );

        foreach (get_posts($args) as $event) {
            // echo '<pre>';
            // echo $event->post_title;
            // var_dump(get_post_meta($event->ID));
            // echo '</pre>';
        }

        add_shortcode('nm_timeline', array($this, 'return_timeline'));
    }

    /**
     * Render the timeline on the public page
     */
    public function return_timeline()
    {
        $events = [];
        $num_events = 0;

        $args = array(
            'post_type' => 'event',
            'orderby' => 'meta_value',
            'meta_key' => 'nm_timeline_event_date',
            'order' => 'ASC',
            'nopaging' => true,
        );

        foreach (get_posts($args) as $event) {
            if ($event->post_status == 'publish') {
                $events[$num_events]['id'] = $event->ID;
                $events[$num_events]['title'] = $event->post_title;
                $events[$num_events]['content'] = $event->post_content;
                $events[$num_events]['excerpt'] = $event->post_excerpt;
                $events[$num_events]['date'] = date("Y", get_post_meta($event->ID)['nm_timeline_event_date'][0]);
                $events[$num_events]['image'] = get_the_post_thumbnail_url($event->ID);
            }
            $num_events++;
        }

        if (count($events) >= 3) {
            return require_once plugin_dir_path(__FILE__) . '/partials/nm_timeline-public-display.php';
        } else {
            return __('Insufficient events');
        }
    }
}
