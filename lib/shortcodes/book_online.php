<?php
add_shortcode( 'book_online', 'load_book_online' );
function load_book_online() {

	$performance = get_query_var('performance');

	if(strpos($performance,'event') === 0):
		$pieces = explode('-',$performance);
		$spektrix_iframe_url = new iFrame('EventDetails',array('EventId' => $pieces[1]));
	else:
		$spektrix_iframe_url = new iFrame('ChooseSeats',array('EventInstanceId' => $performance));
	endif;
	?>
	<div class="row">
		<div class="span12">
			<?php echo $spektrix_iframe_url->render_iframe(); ?>
		</div>    
	</div>
	
	<?php 
}