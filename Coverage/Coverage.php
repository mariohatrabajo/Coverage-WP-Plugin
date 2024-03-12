<?php
/**
 * Coverage
 * 
 * @package           Coverage Package
 * @author            Marioio
 * @copyright         2024
 * @license           GPL-2.0-or-llater
 * 
 * Plugin Name:       Coverage
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the custom post types Coverage
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Marioio
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       coverage-slug
 * Domain Path:       /languages
 */
 
// Avoid execute plugin from direction input in browser
defined('ABSPATH') or die("You shouldn't be here");

class Coverage {
    function __construct() {
        // Declare the shortcodes
        add_shortcode('mha_show_main_fields', array($this, 'mha_show_main_fields'));
        add_shortcode('mha_show_all_fields', array($this, 'mha_show_all_fields'));
    }
    
    function execute_actions() {
        // Register Coverage custom-post-type
        add_action('init', array($this, 'mha_register_coverage'));
        
        // Create a meta-box to display the CPFields
        add_action('add_meta_boxes', array($this, 'mha_add_metabox'));
        
        // Save custom post fields in DDBB
        add_action('save_post', array($this, 'mha_save_custom_fields'));
        
        // Add CSS and JS scripts to admin area for plugin
        add_action('admin_enqueue_scripts', array($this, 'mha_admin_enqueue_scripts'));
        
        // Add CSS and JS scripts to front-end for plugin
        add_action('wp_enqueue_scripts', array($this, 'mha_front_enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'mha_front_injection_styles'));
        
        // Add a settings page to the admin area
        add_action('admin_menu', array($this, 'mha_coverage_settings_menu'));
        
        // Register the coverage settings
        add_action('admin_init', array($this, 'mha_coverage_settings_register'));
        
        // Activate error launcher in settings
        add_action('admin_notices', array($this, 'mha_coverage_settings_error_activation'));
    }
    
    function mha_register_coverage(){
        $supports = array(
            'title', // Display the title panel in admin-area
            'editor', // Display the editor window in admin-area
            'excerpt',
            'thumbnail',
            'author',
            'comments',
        );
        $labels = array(
            'name' => _x('Coverage', 'plural'),
            'singular_name' => _x('Coverage', 'singular'),
            'menu_name' => _x('Coverage', 'admin menu'),
            'menu_admin_bar' => _x('Coverage', 'admin bar'),
            'add_new' => _x('Add New Coverage', 'add_new'),
            'all_items' => __('Coverage'),
            'add_new_item' => __('Add new Coverage'),
            'view_item' => __('View Coverage'),
            'search' => __('Search Coverage'),
            'not_found' => __('No coverage found...'), 
        );
        
        $args = array(
            'supports' => $supports,  // Add suppoort to admin-area
            'labels' => $labels,     // Change labels in admin-area for custom-post-type
            'public' => true,        // Custom-post-type can be viewed in front-end
            'wp_query' => true,      // We can Loop with WP_Query
            'query_var' => true,     // Access to wuery vars with CPT
            'hierarchical' => false, // No post delegated from our CPT
            'show_in_rest' => true,  // We are using the Gutenberg editor
            'rewrite' => array('slug' => 'coverage'), // CPT slug
            'has_archive' => true,   // CPT showing in archive.php
            'show_in_menu' => true,  // Show CPT option in admin-bar
            'menu_position' => 5,
            'menu_icon' => 'dashicons-video-alt',       // Dashicons icon css class
        );
        register_post_type('coverage', $args);
        
        // Add category and tags panels in custom-post-type (shared with posts)
        // register_taxonomy_for_object_type(taxonomy, custom-post-type);
        register_taxonomy_for_object_type('category', 'coverage');
        register_taxonomy_for_object_type('post_tag', 'coverage');
        
        // Add category and tags panels in custom-post-type (NOT shared with posts)
        /* register_taxonomy(
            'coverage-category', // Slug
            'coverage', // CPT
            array(
                'label' => 'Coverage Category',
                'rewrite' => array('slug' => 'coverage-category'),
                'hierarchical' => true, // To use the same interface of post categories
                'show_in_rest' => true, // To display the category pannel in Gutemberg editor
                'query_var' => true, // Include new taxonomy in query var
                'show_admin_column' => true, // Display category taxonomy in admin menu
            ),
        );*/
        
    }
    
    function mha_add_metabox($screens) {
        $screens = array('coverage');
        
        foreach($screens as $screen) {
            //  add_meta_box(id del div, titulo, funcion callback, pantalla, contexto);
            add_meta_box('coverage-metabox', 'MH. Coverage', array($this, 'mha_metabox_callback'), $screen, 'advanced');
        }
    }
    
    /**
     * Callback function for displaying custom-fields using HTML tags
     * @param $post The post object
     */
    function mha_metabox_callback($post) {
        // Create a validation mechanism to prevent executions outside my website using a nonce field
        wp_nonce_field(basename(__FILE__), 'coverage-nonce');
        
        // Data Harvesting
        $date = get_post_meta($post->ID, 'mha_date', true);
        $duration = get_post_meta($post->ID, 'mha_duration', true);
        $location = get_post_meta($post->ID, 'mha_location', true);
        $budget = get_post_meta($post->ID, 'mha_budget', true);
        $published = get_post_meta($post->ID, 'mha_published', true);
        
        // STAFF
        $custom_array_values = get_post_meta($post->ID, 'mha_staff', true);
        
        // Display fields with HTML tags
        ?>
            
            <div class="flex-metabox">
                
                <div class="details">
                    <h2>Coverage Details</h2>
                    <div class="flex-item item-1">
                        <label for="mha_date">Start Date</label>
                        <input type="date" name="mha_date" id="mha_date" value="<?php echo $date; ?>">
                    </div>
                    <div class="flex-item item-2">
                        <label for="mha_duration">Duration (In months)</label>
                        <input type="number" name="mha_duration" id="mha_duration" value="<?php echo $duration; ?>">
                    </div>
                    <div class="flex-item item-3">
                        <label for="mha_location">Location</label>
                        <input type="text" name="mha_location" id="mha_location" value="<?php echo $location; ?>">
                    </div>
                    <div class="flex-item item-4">
                        <label for="mha_budget">Budget</label>
                        <input type="number" name="mha_budget" id="mha_budget" value="<?php echo $budget; ?>">
                    </div>
                    <div class="flex-item item-5">
                        <label for="mha_published">Publishing Date</label>
                        <input type="date" name="mha_published" id="mha_published" value="<?php echo $published; ?>">
                    </div>
                    <!--<div class="flex-item item-6">-->
                    <!--    <label for="mha_type">Type:</label>-->
                    <!--    <select class="type-select" name="mha_type" id="mha_type">-->
                    <!--        <option value="Choose a Type" <?php //if($type == 'Choose a Type') echo 'selected'; ?>>Choose a Type</option>-->
                    <!--    </select>-->
                    <!--</div>-->
                </div>
                
                <div class="staff">
                    <h2>Staff</h2>
                    <?php
                        require_once(plugin_dir_path(__FILE__).'admin/includes/staff.php');
                    ?>
                </div>
            </div>
            
        <?php
    }
    
    /**
     * Function for saving custom post fields
     * @param $post_id integer post id
     * */
    function mha_save_custom_fields($post_id) {
        // Check if we are in an autosave
        $is_autosave = wp_is_post_autosave($post_id);
        // Check if we are in revision
        $is_revision = wp_is_post_revision($post_id);
        // Check if the nonce field is valid
        $is_valid_nonce = wp_verify_nonce( $_POST['coverage-nonce'], basename(__FILE__));
        
        if($is_autosave || $is_revision || !$is_valid_nonce){
            return;
        }
        
        // Check if user have the capabilities to save posts
        if(!current_user_can('edit_post', $post_id)) {
           return ;
        }
        
        // Sanitize fields to avoid code injections
        $date = sanitize_text_field($_POST['mha_date']);
        $duration = sanitize_text_field($_POST['mha_duration']);
        $location = sanitize_text_field($_POST['mha_location']);
        $budget = sanitize_text_field($_POST['mha_budget']);
        $published = sanitize_text_field($_POST['mha_published']);
        
        // Store STAFF
        if( isset($_POST['mha_staff'])){ // If the staff array isn't empty
            $array_aux = array();
            
            foreach($_POST['mha_staff'] as $row){
                // If one of the fields is not empty
                if( !empty($row['key1']) || !empty($row['key2'])){
                    // We save the array in aux
                    $array_aux[] = array(
                        'key1' => sanitize_text_field($row['key1']),
                        'key2' => sanitize_text_field($row['key2']),    
                    );
                }
            }
            update_post_meta($post_id, 'mha_staff', $array_aux);
        }
        
        
        // Update custom post fields
        update_post_meta($post_id, 'mha_date', $date);
        update_post_meta($post_id, 'mha_duration', $duration);
        update_post_meta($post_id, 'mha_location', $location);
        update_post_meta($post_id, 'mha_budget', $budget);
        update_post_meta($post_id, 'mha_published', $published);
    }
    
    /**
     * Register CSS and JS scripts to admin-area
     * */
    function mha_admin_enqueue_scripts() {
        wp_register_style('mha_admin_css', plugins_url('/admin/css/admin.css', __FILE__));
        wp_enqueue_style('mha_admin_css');
        
        wp_register_script('mha_staff', plugins_url('/admin/js/staff.js', __FILE__));
        wp_enqueue_script('mha_staff');
    }
    
    /**
     * Register CSS and JS scripts to front-end
     * */
    function mha_front_enqueue_scripts() {
        wp_register_style('mha_front_css', plugins_url('/admin/css/front.css', __FILE__));
        wp_enqueue_style('mha_front_css');
    }
    
    /**
     * Inject code in Front.css in order to asign color to the border of the custom post fields
     * */
    function mha_front_injection_styles() {
        // Harvest the settings
        $options = get_option('mha_coverage_settings');
        $color = $options['mha_color'];
        
        // Store all the styles in a variable
        $styles = '
            .data-fields {
                border: 3px solid '.$color.';
            }
            
            .box1 span:nth-child(2), .box2 span:nth-child(2), .staff-header {
                color: '.$color.';
            }
        ';
        
        // Register and enqueue the styles to be injected
        wp_register_style('mha_injection', false);
        wp_enqueue_style('mha_injection');
        
        // Inject Styles
        wp_add_inline_style('mha_injection', $styles);
    }
    
    // ------------------------------------------------------------------------ SETTINGS
    
    /**
     * Add a plugin settings menu to the admin area
     * */
    function mha_coverage_settings_menu() {
        // add_menu_page(title, menu option, capability, slug, callback function, icon, position);
        add_menu_page('MH. Coverage Settings Page', 'Coverage Settings', 'manage_options', 'coverage-settings', array($this, 'mha_coverage_settings_callback'), 'dashicons-admin-settings', 6);
    }
    
    /**
     * HTML for the settings page
     * */
    function mha_coverage_settings_callback(){
        require_once(plugin_dir_path(__FILE__).'admin/admin-settings.php');
    }
    
    /**
     * Register the coverage settings in wp_options table
     * */
    function mha_coverage_settings_register(){
        // register_setting(array of settings name, group name, callback function for validating);
        register_setting('mha_coverage_settings', 'mha_coverage_settings', array($this, 'mha_coverage_settings_validation'));
    }
    
    /**
     * Validate settings fields
     * @param $settings array Coverage Settings
     * @return $settings array Validated Coverage Settings
     * */
    function mha_coverage_settings_validation($settings){
        // If there is not a color chosen, we asign a default color
        if(!isset($settings['mha_color'])){
            $settings['mha_color'] = "#ffc451";
        }
        if(!isset($settings['mha_allow_rating'])){
            $settings['mha_allow_rating'] = "yes";
        }
        // Max Budget
        if(!isset($settings['mha_max_budget']) || $settings['mha_max_budget'] < 0 || $settings['mha_max_budget'] > 10000000){
            $settings['mha_max_budget'] = 0;
        }
        return $settings;
    }
    
    /**
     * Activate error launcher in settings
     * */
    function mha_coverage_settings_error_activation() {
        settings_errors();
    }
    
    // ------------------------------------------------------------------------ SHORTCODES
    function mha_show_main_fields($attr) {
        $args = shortcode_atts(array('id' => 0), $attr);
        $post_id = $args['id'];
        
        ?>
            <div class="data-fields box1">
                <div class="field field1"><span>Start Date:</span> <span><?php echo get_post_meta($post_id, 'mha_date', true); ?></span></div>
                <div class="field field2"><span>Duration:</span> <span><?php echo get_post_meta($post_id, 'mha_duration', true); ?> months</span></div>
                <div class="field field3"><span>Location:</span> <span><?php echo get_post_meta($post_id, 'mha_location', true); ?></span></div>
            </div>
        <?php
    }
    
    function mha_show_all_fields($attr) {
        $args = shortcode_atts(array('id' => 0), $attr);
        $post_id = $args['id'];

        ?>
            <div class="data-fields box1">
                <div class="field field1"><span>Start Date:</span> <span><?php echo get_post_meta($post_id, 'mha_date', true); ?></span></div>
                <div class="field field2"><span>Duration:</span> <span><?php echo get_post_meta($post_id, 'mha_duration', true); ?> months</span></div>
                <div class="field field3"><span>Location:</span> <span><?php echo get_post_meta($post_id, 'mha_location', true); ?></span></div>
            </div>
            <div class="data-fields box2">
                <div class="field field3"><span>Budget:</span> <span><?php echo get_post_meta($post_id, 'mha_budget', true); ?>€</span></div>
                <div class="field field3"><span>Published on:</span> <span><?php echo get_post_meta($post_id, 'mha_published', true); ?></span></div>
            </div>
            <div class="data-fields box3">
                <h4>Staff</h4>
                <div class="staff-header">
                    <span class="staff-lbl">Name: </span>
                    <span class="staff-lbl">Role: </span>
                </div>
                <?php
                    $custom_array_values = get_post_meta($post_id, 'mha_staff', true);
                    if(!empty($custom_array_values)){
                        foreach($custom_array_values as $row){
                            echo '<div class="staff-row">';
                            echo '<span class="staff-lbl key1">'.$row['key1'].'</span>';
                            echo '<span class="staff-lbl key2">'.$row['key2'].'</span>';
                            echo '</div>';
                        }
                    }
                ?>
            </div>
        <?php
    }
    
} // End Coverage Class

if( class_exists('Coverage') ){
    $coverage = new Coverage();
    $coverage->execute_actions();
    
    // TODO Register Activation and Deactivation hooks
    
}