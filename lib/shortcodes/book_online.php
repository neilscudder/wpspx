<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
add_shortcode( 'spektrix_book_online', 'spektrix_load_book_online' );
function spektrix_load_book_online() {

	$performance = get_query_var('performance');

	if(strpos($performance,'event') === 0):
		$pieces = explode('-',$performance);
		$spektrix_iframe_url = new iFrame('EventDetails',array('EventId' => $pieces[1]), false);
	else:
		$spektrix_iframe_url = new iFrame('ChooseSeats',array('EventInstanceId' => $performance), false);
	endif;
	?>
	<div class="row">
		<div class="span12">
			<?php echo $spektrix_iframe_url->render_iframe(); ?>
		</div>
	</div>

	<?php
}
