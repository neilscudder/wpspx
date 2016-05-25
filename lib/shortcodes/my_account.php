<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
add_shortcode( 'my_account', 'load_my_account' );
function load_my_account() {

	$spektrix_iframe_url = new iFrame('MyAccount',NULL,true);
	?>
	<div class="row">
		<div class="span12">
			<?php echo $spektrix_iframe_url->render_iframe(); ?>
		</div>    
	</div>
	
	<?php 
}