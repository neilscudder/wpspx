<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

$show = new Show($spektrix_id);

$performances = $show->get_performances();
$is_in_future = !is_in_past($performances);
$pricelists = $show->get_price_lists();
$api = new Spektrix();
$availabilities = $api->get_availabilities();
$extremes = availability_extremes($performances,$availabilities);
$count = count($performances);
$any_accessible = any_accessible($performances);
$is_blockbuster = $count > 4;

get_header();
?>

<?php if(has_post_thumbnail()): ?>
<div class="wowsers show-information">
	<?php the_post_thumbnail('full'); ?>
</div>
<?php endif; ?>

<div class="container show-information">
	<div class="row">
		<div class="span12">
			<h1><?php the_title(); ?></h1>
			<h3 class="date-range">
				<?php echo $performances ? get_performance_range($performances) : "No performance info available"; ?>
				<?php if($is_in_future): ?>
				<br>
				<small>
					<a href="#prices" class="scoller btn btn-info">Jump to performances and prices</a>
				</small>
				<?php endif ?>
			</h3>
		</div>
	</div>

  	<hr>

	<div class="row">
		<div class="span3 showcard">

			<?php 
			if(has_post_thumbnail()):
				the_post_thumbnail('poster');
			else: ?>
			<img src="<?php echo WPPSX_PLUGIN_URL; ?>lib/assets/no-image.jpg">
			<?php endif; ?>

			<?php if ( function_exists( 'sharing_display' ) ): ?>
			<hr>
			<p><strong>It's better with friends</strong><br>
			Share this performance.</p>
			<?php sharing_display( '', true ); ?>
			<?php endif; ?>
		</div>

		<div class="span6">
			<p><?php echo nl2br($show->short_description) ?></p>
		</div>

		<div class="span2 offset1">
		<?php if($is_in_future): ?>
			<hr>
			<p style="padding-top:10px;"><small><strong>Not sure if it's for you?</strong><br>Chat to one of our friendly ticket sales assistants on XXXXXXxxx</small></p>
		<?php endif; ?>

		</div>
	</div>
</div>

<?php if($is_in_future) include 'partials/details.php'; ?>

<?php get_footer(); ?>