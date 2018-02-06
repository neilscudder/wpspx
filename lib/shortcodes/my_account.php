<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
add_shortcode( 'spektrix_my_account', 'spektrix_load_my_account' );
function spektrix_load_my_account() {

	$spektrix_iframe_url = new iFrame('MyAccount',NULL,true);
	?>
	<div class="row">
		<div class="span12">
			<?php echo $spektrix_iframe_url->render_iframe(); ?>
		</div>
	</div>

	<?php
}
