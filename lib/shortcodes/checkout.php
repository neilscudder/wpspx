<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
add_shortcode( 'spektrix_checkout', 'spektrix_load_checkout' );
function spektrix_load_checkout() {

	$spektrix_iframe_url = new iFrame('Checkout',NULL,true);
	?>
	<div class="row">
		<div class="span12">
			<?php echo $spektrix_iframe_url->render_iframe(); ?>
		</div>
	</div>

	<?php
}
