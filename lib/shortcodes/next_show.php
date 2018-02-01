<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
add_shortcode( 'next_show', 'shows_next_show' );
function shows_next_show() {

	//require WPPSX_PLUGIN_DIR . 'lib/helpers/show-loader-sixweeks.php';

	$api = new Spektrix();
	$performances = Performance::six_weeks();
	$shows = Show::six_weeks();
	$show_ids = convert_to_array_of_ids($shows);
	$availabilities = $api->get_availabilities();

	?>

	<section id="shows_next">
	<?php 
	$show_ids = convert_to_array_of_ids($shows);

	$db_shows = get_posts(array(
		'post_type' => 'shows',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key'     => '_spektrix_id',
				'value'   => $show_ids,
				'compare' => 'IN'
			)
		)
	));

	$wp_shows = array();
	foreach($db_shows as $db_show):
		$spektrix_id = get_post_meta($db_show->ID,'_spektrix_id',true);
		$wp_shows[$spektrix_id] = $db_show->ID;
	endforeach;

	foreach($performances as $i => $performance):
		$now = date('D j M H:i', strtotime('now'));
		$show_time = $performance->start_time->format('D j M H:i');
		if ($show_time > $now):
		?>
		<table>
			<tr class="row">
				
				<td valign="top">
					<h3><?php echo $performance->start_time->format('D j M') ?></h3>
				</td>
				<td>
					<h3><small><?php echo $performance->start_time->format('H:i'); ?></small></h3>
				</td>
				<td width="20%" valign="top">

					<?php 
					if(has_post_thumbnail()):
						the_post_thumbnail('poster');
					else: ?>
					<img src="<?php echo WPPSX_PLUGIN_URL; ?>lib/assets/no-image.jpg">
					<?php endif; ?>

				</td>
				<td width="30%">

					<h3><a href="#" data-toggle="collapse" data-target="#performance<?php echo $performance->id ?>" onclick="return false;"><?php echo $shows[$performance->show_id]->name ?></a></h3>
					<?php //format_performances_attributes($performance->attributes); ?>
					<p><?php echo $shows[$performance->show_id]->venue ?></p>
					<div id="performance<?php echo $performance->id ?>" class="collapse">
						<p><strong>A few more details ...</strong></p>
						<p><?php echo nl2br($shows[$performance->show_id]->short_description) ?></p>
						<?php
						$path_to_show = get_permalink($wp_shows[$performance->show_id]);
						?>
						<p><a href="<?php echo $path_to_show; ?>">Full performance details</a></p>
				  </div>
				</td>
				<td width="20%">
					<?php
					$performances = $shows[$performance->show_id]->get_performances();
					$is_blockbuster = count($performances) > 4;
					$extremes = availability_extremes($performances,$availabilities);
					$av_helper = availability_helper($availabilities[$performance->id],$extremes);
					?>
				 	<p style="margin-top:12px;"><?php echo book_online_button($availabilities[$performance->id],$av_helper,$performance,$is_blockbuster,$path_to_show); ?></p>
				</td>

			  </tr>
			</table>

		<?php die();
		endif;
	endforeach;	
	?>
	</section>

	<?php 
}