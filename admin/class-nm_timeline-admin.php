<?php

/**
 * The admin-specific functionality of the plugin.
 */
class Nm_timeline_Admin
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
     * Register the stylesheets for the admin area.
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/nm_timeline-admin.css', array(), $this->version, 'all');
        wp_enqueue_style('bootstrap-datepicker', plugin_dir_url(__FILE__) . 'css/bootstrap-datepicker3.standalone.min.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/nm_timeline-admin.js', array( 'jquery' ), $this->version, false);
        wp_enqueue_script('bootstrap-datepicker', plugin_dir_url(__FILE__) . 'js/bootstrap-datepicker.min.js', array( 'jquery' ), $this->version, false);
    }

    /**
     * Register event custom post type
     */
    public function register_event_cpt()
    {
        $labels = array(
                'name'                  => _x('Nordic Milk Timeline', 'Post Type General Name', 'nm_timeline'),
                'singular_name'         => _x('Event', 'Post Type Singular Name', 'nm_timeline'),
                'menu_name'             => __('Timeline', 'nm_timeline'),
                'name_admin_bar'        => __('Events', 'nm_timeline'),
                'archives'              => __('Our Events', 'nm_timeline'),
                'attributes'            => __('Event Attributes', 'nm_timeline'),
                'parent_item_colon'     => __('Parent event:', 'nm_timeline'),
                'all_items'             => __('All events', 'nm_timeline'),
                'add_new_item'          => __('Add new event', 'nm_timeline'),
                'add_new'               => __('Add new', 'nm_timeline'),
                'new_item'              => __('New event', 'nm_timeline'),
                'edit_item'             => __('Edit event', 'nm_timeline'),
                'update_item'           => __('Update event', 'nm_timeline'),
                'view_item'             => __('View event', 'nm_timeline'),
                'view_items'            => __('View events', 'nm_timeline'),
                'search_items'          => __('Search events', 'nm_timeline'),
                'not_found'             => __('Not found', 'nm_timeline'),
                'not_found_in_trash'    => __('Not found in Trash', 'nm_timeline'),
                'insert_into_item'      => __('Insert into event sheet', 'nm_timeline'),
                'uploaded_to_this_item' => __('Uploaded to this event sheet', 'nm_timeline'),
                'items_list'            => __('Event list', 'nm_timeline'),
                'item_published'				=> __('Event published', 'nm_timeline'),
                'items_list_navigation' => __('Event list navigation', 'nm_timeline'),
                'filter_items_list'     => __('Filter event list', 'nm_timeline'),
            );
    
        $args = array(
                'label'                 => __('Event', 'nm_timeline'),
                'description'           => __('Events to be shown on the timeline page', 'nm_timeline'),
                'labels'                => $labels,
                'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail'),
                'hierarchical'          => false,
                'public'                => true,
                'show_ui'               => true,
                'show_in_menu'          => true,
                'menu_position'         => 5,
                'menu_icon'             => 'dashicons-calendar-alt',
                'show_in_admin_bar'     => true,
                'show_in_nav_menus'     => true,
                'can_export'            => true,
                'has_archive'           => false,
                'exclude_from_search'   => false,
                'publicly_queryable'    => true,
                'capability_type'       => 'post',
                'show_in_rest'	    => false
            );

        register_post_type('event', $args);
    }

    /**
     * Add date column to event cpt list view
     */
    public function list_add_event_date()
    {
        $screen = get_current_screen();

        if (!isset($screen->post_type) || $screen->post_type != 'event') {
            return;
        }

        /**
         * Insert event date column into 3rd position
         */

        add_filter('manage_event_posts_columns', function ($columns) {
            $new_columns = array_slice($columns, 0, 2) + ['event_date' => __('Event Date', 'nm_timeline')] + array_slice($columns, 2, count($columns) - 2);
            return $new_columns;
        });

        add_action('manage_event_posts_custom_column', function ($column_key, $post_id) {
            if ($column_key == 'event_date') {
                $event_date = get_post_meta($post_id, 'nm_timeline_event_date', true);
                echo (!empty($event_date)) ? date("d.m.Y", $event_date) : __('Not set', 'nm_timeline');
            }
        }, 10, 2);

        /**
         * Make columns sortable
         */
        add_filter('manage_edit-event_sortable_columns', function ($columns) {
            $columns['event_date'] = 'event_date';
            return $columns;
        });

        /**
         * Sort event list by descending event date as default
         */
        add_action('pre_get_posts', function ($query) {
            if (!is_admin()) {
                return;
            }
            if ($query->get('post_type') == 'event' && empty($query->get('orderby'))) {
                $query->set('order', 'DSC');
                $query->set('orderby', 'meta_value');
            }
        });
    }

    /**
     * Add datepicker metabox to event cpt
     */
    public function add_datepicker_metabox()
    {
        add_meta_box('nm_timeline_datepicker', __('Date', 'nm_timeline'), array($this, 'render_datepicker_metabox'), 'event', 'side', 'low', null);
    }

    /**
     * Render content for datepicker metabox
     */
    public function render_datepicker_metabox($post)
    {
        wp_nonce_field('nm_timeline_datepicker', 'nm_timeline_datepicker_wpnonce');

        /**
         * If post meta has timeline event date and it is not unix epoch time, assign event date to datepicker for populating datepicker and hidden input
         */
        if (get_post_meta($post->ID, 'nm_timeline_event_date', true) && get_post_meta($post->ID, 'nm_timeline_event_date', true) != date('m/d/Y', 0)) {
            $datepicker_date = date('m/d/Y', get_post_meta($post->ID, 'nm_timeline_event_date', true));
        } ?>

        <div 
            id="nm-timeline-datepicker" 
            data-provide="datepicker-inline" 
            <?php if (isset($datepicker_date)): ?>
                data-date="<?php echo $datepicker_date; ?>"
            <?php endif; ?>
        >
        
        <input 
            name="nm_timeline_event_date" 
            type="hidden"
            <?php if (isset($datepicker_date)): ?>
                value="<?php echo $datepicker_date; ?>"
            <?php endif; ?>
        ></div> 
        <?php
    }

    /**
     * Save datepicker date
     */
    public function save_datepicker_date($post_id)
    {
        if (!isset($_POST['nm_timeline_datepicker_wpnonce']) ||
                    !wp_verify_nonce($_POST['nm_timeline_datepicker_wpnonce'], 'nm_timeline_datepicker') ||
                    defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ||
                    !current_user_can('edit_post', $post_id) ||
                    !isset($_POST['nm_timeline_event_date'])) {
            return;
        }

        /**
         * For sanitation purposes we convert passed string to date. Incase of non compliant date string, unix epoch time is saved.
         */
        $datepicker_date = strtotime($_POST['nm_timeline_event_date']);
        update_post_meta($post_id, 'nm_timeline_event_date', $datepicker_date);
    }
}
