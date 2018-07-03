<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

// Menu Item
add_action( 'admin_menu', 'wpspx_settings_menu' );
function wpspx_settings_menu() {
	add_options_page(
		'WPSPX',
		'WPSPX',
		'edit_posts',
		'wpspx-settings',
		'wpspx_settings'
	);

	// register settings function
	add_action( 'admin_init', 'register_wpspx_settings' );
}

// register settings
function register_wpspx_settings() {
	register_setting( 'wpspx-settings-group', 'wpspx_account' );
	register_setting( 'wpspx-settings-group', 'wpspx_api' );
	register_setting( 'wpspx-settings-group', 'wpspx_crt' );
	register_setting( 'wpspx-settings-group', 'wpspx_key' );
}

if ( function_exists( 'members_get_capabilities' ) )
	add_filter( 'members_get_capabilities', 'plugin_name_extra_caps' );

function plugin_name_extra_caps( $caps ) {
	$caps[] = 'edit_my_plugin_settings';
	return $caps;
}

// Settings Page
function wpspx_settings() {

  if (isset($_POST['fetchshows'])) {
    if(function_exists('run_wbs_update')) {
      run_wbs_update(true);
    } else {
      echo('<div class="notice notice-error is-dismissible"><p><em><strong>ERROR! The <code>Clean Spektrix Cache + Fetch Shows</code> function requires the <code>WBS Cron</code> plugin be activated!</strong></em></p></div>');
    }
  }
  if (isset($_POST['cachebuster'])) {
    wpspk_bust_cache(true);
  }
  ?>

  <div class="wrap wpspx-settings">
    <div class="col col-100">
     <img src="<?php echo plugins_url( '../assets/logo.svg', __FILE__ ) ?>" class="wpspx-logo" width="280" alt="WP Spektrix">
   </div>

   <div class="col col-50" style="margin-right: 2%;">
    <h3>Having Trouble? Clear The Cache!</h3>
    <hr>
    <p>Before submitting a support ticket, hit the clear cache button below to clear out the spektrix cached files.</p>

    <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
      <input type="submit" name="cachebuster" id="cachebuster" class="wpspx-button button button-secondary" value="Clear Spektrix Cache">
    </form>
  </div>

  <div class="col col-50">
    <h3>Manually Sync Shows</h3>
    <hr>
    <p><em><strong>REQUIRES <code>WBS Cron</code> plugin be activated!</strong> Please use this action sparingly.</em></p>
    <p>This action will clear the local spektrix cache and import / update shows from the Spektrix API. This the same action that is run automatically with a scheduled task.</p>

    <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
      <input type="submit" name="fetchshows" id="fetchshows" class="wpspx-button button button-secondary" value="Clear Spektrix Cache + Fetch Shows">
    </form>
  </div>

  <div class="col col-100">
   <h3>Getting Started</h3>
   <p>The <strong>WPSPX</strong> plugin allows you to display your bookable spektrix performaces &amp; Instances dirently on your website. Once you have filled in the below setting, <a href="post-new.php?post_type=shows">create a show</a> and assign your spextrix performance.</p>
   <hr>
   <form method="post" action="options.php">
    <?php settings_fields( 'wpspx-settings-group' ); ?>
    <?php do_settings_sections( 'wpspx-settings-group' ); ?>
    <table class="form-table">
     <tr valign="top">
      <th scope="row">Spektrix Account Name</th>
      <td><input type="text" name="wpspx_account" value="<?php echo esc_attr( get_option('wpspx_account') ); ?>" />
        <br><small>Enter your Spektrix account name, eg: theatrename</small></td>
      </tr>
      <tr valign="top">
        <th scope="row">API Key</th>
        <td><input type="text" name="wpspx_api" value="<?php echo esc_attr( get_option('wpspx_api') ); ?>" />
          <br><small>Enter your Spektrix api key, eg: 1234567a-1234-1abc-a123-1abc23def456</small></td>
        </tr>
        <tr valign="top">
          <th scope="row">Path to Specktrix CRT</th>
          <td><input width="200" type="text" name="wpspx_crt" value="<?php echo esc_attr( get_option('wpspx_crt') ); ?>" />
            <br><small>Enter the path to your Spektrix certicicate crt, eg: /var/www/htdocs/fortnox/certificate.crt
              <br>Yuur server path: <?php echo $_SERVER['DOCUMENT_ROOT']; ?></small></td>
            </tr>
            <tr valign="top">
              <th scope="row">Path to Specktrix Key</th>
              <td><input type="text" name="wpspx_key" value="<?php echo esc_attr( get_option('wpspx_key') ); ?>" />
                <small>Enter the path to your Spektrix certicicate key, eg: /var/www/htdocs/fortnox/certificate.key
                  <br>Yuur server path: <?php echo $_SERVER['DOCUMENT_ROOT']; ?></small></td>
                </tr>
              </table>
              <hr>
              <?php submit_button(); ?>

            </form>
          </div>

        </div>
        <?php
      }
