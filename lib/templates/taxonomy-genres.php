<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/**
 * The template for displaying genre pages.
 *
 * @package WPSPX
 */

get_header(); ?>

	<div id="primary" class="content-area span8">
		<main id="main" class="site-main" role="main">

			<?php
			require WPPSX_PLUGIN_DIR . 'lib/helpers/show-loader.php';

			//$tax = str_replace('-',' ',get_query_var('genre'));

			$tax = strtolower(single_term_title("", false));
			echo $tax;

			$shows = filter_shows_with_genre($shows,$tax);

			$all_performances = Performance::find_all_in_future(true);
			$all_performances = $fake_performances + $all_performances;

			$api = new Spektrix();
			$availabilities = $api->get_availabilities();

			uasort($all_performances, function($a, $b) {
				return $a[0]->start_time->format('U') - $b[0]->start_time->format('U');
			});

			$genres = get_terms( array(
				'taxonomy' => 'genres',
				'hide_empty' => false,
			) );

			$performance_months = array();
			foreach($all_performances as $show_id => $ps):
				if(!is_in_past($ps)){
			    	$months = get_performance_months($ps);
					foreach($months as $month):
						if(array_key_exists($show_id,$shows)){
							$month = strtotime($month);
							$performance_months[$month][] = array($shows[$show_id],get_performance_range($ps,""));
						}
			    	endforeach;
				}
			endforeach;
			ksort($performance_months);

			?>

			<section id="all_upcoming_shows">

				<div class="row">
					<div class="show-filter span12">
						<h4>Genre</h4>
						
						<hr>

						<ul class="nav nav-pills show-filter" style="margin-bottom:5px;">
							<?php foreach($genres as $genre): ?>
								<li><a href="<?php echo home_url( 'genre/'.$genre->slug); ?>" ><?php echo $genre->name; ?></a></li>
							<?php endforeach; ?>
						</ul>

					</div>
				</div>

				<div class="all_shows">
				<?php foreach($performance_months as $month => $shows): $month = date("F Y",$month); ?>

					<h2 id="<?php echo strtolower(str_replace(' ','-',$month)) ?>" class="month"><?php echo $month ?></h2>
					<div class="row">
					<?php 
						$i = 0;foreach($shows as $show) {
						$performances = $show[1];
						$show = $show[0];
						$show_id = $wp_shows[$show->id] ? $wp_shows[$show->id] : str_replace('fs_','',$show->id);
						$is_sold_out = false;

						$this_show_performances = $show->get_performances();
						$now = new DateTime(); $number_tikets = array();
						foreach($this_show_performances as $this_show_performance):
							if($this_show_performance->start_time > $now):
								$number_tikets[] = $availabilities[$this_show_performance->id]->available;
							endif;
						endforeach;
						if(array_sum($number_tikets) === 0) {
							$is_sold_out = true;
						} 
						?>
						<div data-tickets-left="<?php echo array_sum($number_tikets); ?>" 
							class="span2 show <?php echo $show->website_category; ?> <?php if($is_sold_out): ?>sold-out<?php endif; ?>">
							<?php if($is_sold_out): ?><div class="sold-out-container"></div><?php endif; ?>
							<a href="<?php echo get_permalink($show_id); ?>">
								<?php 
								$poster = get_the_post_thumbnail($show_id, 'poster');
								if($poster):
									echo $poster;
								else: ?>
								<img src="<?php echo WPPSX_PLUGIN_URL; ?>lib/assets/no-image.jpg">
								<?php endif; ?>
							</a>
							<div class="info">
								<h5><a href="<?php echo get_permalink($show_id); ?>"><?php echo $show->name ?></a></h5>
								<p><?php echo $performances; ?></p>
		
								<ul class="genres">
									<?php
									$show_terms = get_the_terms($show_id, 'genres');
									foreach ($show_terms as $show_term): ?>
										<li><?php echo $show_term->name; ?></li>
									<?php 
									endforeach; 
									?>
								</ul>

							</div>
						</div>
						<?php $i++; if(($i % 6 === 0) && $i != count($shows)) echo '</div><div class="row">';
					}
				endforeach; ?>
				</div>

			</section>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
