<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
add_shortcode( 'spektrix_memberships_donations', 'spektrix_memberships_donations_shortcode' );
function spektrix_memberships_donations_shortcode() {
  $spektrix_iframe_url = new iFrame('donations', array('Attribute_Memberships' => 'Yes'), false);
  ?>
  <div class="row">
    <div class="span12">
      <?php echo $spektrix_iframe_url->render_iframe(); ?>
    </div>
  </div>

  <?php
}
