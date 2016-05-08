<?php

// Menu Item
add_action( 'admin_menu', 'wpspx_settings_menu' );
function wpspx_settings_menu() {
    add_options_page(
        'WP Spektrix',
        'WP Spektrix',
        'manage_options',
        'wpspx-settings',
        'wpspx_settings'
    );
}

// Settings Page
function wpspx_settings() {
    
    if (isset($_POST['cachebuster']))
        wpspk_bust_cache();
    ?>
    
    <div class="wrap wpspx-settings">
        <img src="<?php echo plugins_url( '../assets/logo.svg', __FILE__ ) ?>" class="wpspx-logo" width="173" alt="WP Spectrix">

        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <input type="submit" name="cachebuster" id="cachebuster" class="button button-secondary" value="Clear Spektrix Cache">
        </form>

        <hr>
        <p>The <strong>WP Spektrix</strong> plugin allows you to display your bookable spektrix performaces dirently on your website. </p>
        <p>To get started, create a show and assign your spextrix performance.</p>
    </div>
    <?php
}