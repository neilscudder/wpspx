<?php
add_shortcode( 'basket', 'load_basket' );
function load_basket() {

	$spektrix_iframe_url = new iFrame('Basket2');
	?>
	<div class="row">
		<div class="span12">
			<?php echo $spektrix_iframe_url->render_iframe(); ?>
		</div>    
	</div>
	
	<?php 
}