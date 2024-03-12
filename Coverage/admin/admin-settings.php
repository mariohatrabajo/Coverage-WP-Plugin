<h1>Coverage Settings</h1>

<h2>How to use shortcodes for displaying custom-post fields</h2>
<h3>Copy this code in custom-post-type template where you want to display custom main fields</h3>
<blockquote>
    <pre>&lt;?php do_shortcode('[mha_show_main_fields id="'.$post->ID.'"]');?&gt;</pre>
</blockquote>
<h3>Copy this code in custom-post-type single template where you want to display all custom fields</h3>
<blockquote>
    <pre>&lt;?php do_shortcode('[mha_show_all_fields id="'.$post->ID.'"]');?&gt;</pre>
</blockquote>

<h2>Settings List</h2>
<form method="post" action="options.php">
    <?php
        settings_fields('mha_coverage_settings');
        do_settings_sections('mha_coverage_settings');
        
        // Obtain settings data
        $options = get_option('mha_coverage_settings');
    ?>
    <label for="mha_color">Color</label>
    <input type="color" id="mha_color" name="mha_coverage_settings[mha_color]" value="<?php echo $options['mha_color']; ?>">
    
    <p>
        <label for="mha_allow_rating">Allow Ratings&nbsp;&nbsp;</label>
        <input type="radio" name="mha_coverage_settings[mha_allow_rating]" id="mha_allow_rating" value="yes" <?php if($options['mha_allow_rating']=='yes') echo 'checked'; ?>> Yes &nbsp;&nbsp;
        <input type="radio" name="mha_coverage_settings[mha_allow_rating]" id="mha_allow_rating" value="no" <?php if($options['mha_allow_rating']=='no') echo 'checked'; ?>> No
    </p>
    
    <p>
        <label for="mha_max_budget">Max Budget</label>
        <input type="number" name="mha_coverage_settings[mha_max_budget]" id="mha_max_budget" value="<?php echo $options['mha_max_budget']; ?>" max="10000000" min="0" step="1000">
    </p>
    
    <p>
        <input type="submit" class="button button-primary" value="Save Settings">
    </p>
</form>
