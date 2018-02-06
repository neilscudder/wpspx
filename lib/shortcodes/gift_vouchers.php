<?php
add_shortcode( 'spektrix_gift_vouchers', 'spektrix_gift_vouchers_shortcode' );
function spektrix_gift_vouchers_shortcode() {
  $spektrix_iframe_url = new iFrame('GiftVouchers', NULL, false);
  ?>
  <div class="row">
    <div class="span12">
      <?php echo $spektrix_iframe_url->render_iframe(); ?>
    </div>
  </div>

  <?php
}
