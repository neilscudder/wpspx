<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
add_shortcode( 'next_six_weeks', 'shows_six_weeks' );
function shows_six_weeks() {

	require WPPSX_PLUGIN_DIR . 'lib/helpers/show-loader-sixweeks.php';

	$api = new Spektrix();
	$performances = Performance::six_weeks();
	$availabilities = $api->get_availabilities();

	?>

	<section id="shows_weekly">
	<?php 
	if($shows):
		$yesterday = "";
		$previous_performance = "";
		?>

		<table class="table">
		  <?php $i = 1;
		  foreach($performances as $performance):
			if(isset($shows[$performance->show_id])):
				$performace_terms = $shows[$performance->show_id]->tags;
				?>
				<tr class="row<?php echo $i ?>" <?php foreach ($performace_terms as $key => $performace_term): ?>data-term="<?php echo $performace_term; ?>" <?php endforeach; ?>>
					
					<td valign="top">
						<?php if($previous_performance && $performance->start_time->format('j M') == $previous_performance->start_time->format('j M')):
						else: ?>
						<h3><?php echo $performance->start_time->format('D j M') ?></h3>
						<?php endif; ?>
					</td>
					<td>
						<h3><small><?php echo $performance->start_time->format('H:i'); ?></small></h3>
					</td>
					<td width="20%" valign="top">
						<?php 
						$poster = get_the_post_thumbnail($show_id, 'poster');
						if($poster):
							echo $poster;
						else: ?>
						<img src="<?php echo WPPSX_PLUGIN_URL; ?>lib/assets/no-image.jpg">
						<?php endif; ?>
					</td>
					<td width="30%">

						<h3><a href="#" data-toggle="collapse" data-target="#performance<?php echo $performance->id ?>" onclick="return false;"><?php echo $shows[$performance->show_id]->name ?></a></h3>
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
			  
					<?php $previous_performance = $performance; ?>
			  
				  </tr>
				  <?php endif; ?>

				<?php $i++; ?>
			  <?php endforeach; ?>
			</table>
		<?php endif; ?>
	</section>

	<?php 
}
