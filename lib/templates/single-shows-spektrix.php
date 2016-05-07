<?php
// set a cookie to disable cache following wordpress_* logic
setcookie("wordpress__spektrix_show", "mis", time()+31536000);

$show = new Show($spectrix_id);

$performances = $show->get_performances();
$is_in_future = !is_in_past($performances);
$pricelists = $show->get_price_lists();
$cover_image = get_field('cover_image');
$videos = get_field("videos");
$api = new Spectrix();
$availabilities = $api->get_availabilities();
$extremes = availability_extremes($performances,$availabilities);
$count = count($performances);
$any_accessible = any_accessible($performances);
$is_blockbuster = $count > 4;
$images = get_field('show_gallery');
$cover_image = get_field('cover_image');

//We use include so we can pass the $show variable to the Twitter Card partial in the head
include 'header.php';
?>

<?php if($cover_image): ?>
  <div class="wowsers show-information" style="background-image: url(<?php echo $cover_image['sizes']['large'] ?>);">
      <div class="titles">
        <h1><?php echo str_replace("#PULSE14 &#8211; ",'',get_the_title()); ?></h1>
        <h3 class="date-range">
          <?php echo $performances ? get_performance_range($performances) : "No performance info available"; ?>
            <?php if($is_in_future): ?>
            <br>
            <small>
              <a href="#prices" class="scoller btn btn-info">Jump to performances and prices</a>
            </small>
            <small class="quickbooklink">
              <a id="quickbook" class="btn btn-primary">Buy Tickets</a>
            </small>
            <?php endif ?>
        </h3>
      </div>
    </div>
<?php endif; ?>

<div class="container show-information">
  <?php if(!$cover_image): ?>
    <div class="row">
      <div class="span12">
        <h1><?php echo str_replace("#PULSE14 &#8211; ",'',get_the_title()); ?></h1>
        <h3 class="date-range">
          <?php echo $performances ? get_performance_range($performances) : "No performance info available"; ?>
          <?php if($is_in_future): ?>
            <br>
            <small>
              <a href="#prices" class="scoller btn btn-info">Jump to performances and prices</a>
            </small>
            <small class="quickbooklink">
              <a id="quickbook" class="btn btn-primary">Buy Tickets</a>
            </small>
          <?php endif ?>
        </h3>
      </div>
    </div>
    <?php if($is_in_future) include 'partials/performance-quickbuy.php'; ?>
  
  <?php endif; ?>
  <hr>
  <?php include 'partials/show-videos.php'; ?>
  <div class="row">
    <div class="span3 showcard">
      <?php if(has_post_thumbnail()): ?>
        <?php the_post_thumbnail('43cover'); ?>
      <?php else: ?>
        <img src="<?php echo $dir ?>/img/no-image.gif">
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
      <?php include 'partials/cast-and-crew.php'; ?>
      <?php include 'partials/performance-links.php' ?>
    </div>
    <div class="span2 offset1">
      <?php if(get_field('enable_sponsor_box')): ?>
      <div id="sponsor-ad">
        <p><strong>Sponsored By:</strong></p>
        <a href="<?php the_field('sponsor_url'); ?>">
          <?php $sponsorImage = get_field('sponsor_image'); ?>
          <img src="<?php echo $sponsorImage['sizes']['sponsor']; ?>" width="" height="" alt="Sponsored by <?php the_field('sponsor_name'); ?>" /></a>
      </div>
      <?php endif; ?>

      <?php if($is_in_future): ?>
        <hr>
        <p style="padding-top:10px;"><small><strong>Not sure if it's for you?</strong><br>Chat to one of our friendly ticket sales assistants on 01473&nbsp;295900</small></p>
      <?php endif; ?>

    </div>
  </div>

  <div class="row">
    <div class="span12 gallery">
      <?php include 'partials/performance-gallery.php'; ?>
    </div>
  </div>

  <div class="row">
    <div class="span12 reviews-container">
      <?php include 'partials/performance-reviews.php'; ?>
    </div>
  </div>

</div>


<div class="container">
  <hr>
  <div class="row">
    <div class="span12">
      <h4>Love Panto? You'll love this too ... catch it before it ends on 27th September.</h4>
      <a href="<?= home_url('/shows/midsummer-songs/#video1'); ?>"><img src="<?= theme_asset('img/midsummer-songs-banner.jpg'); ?>"></a>
    </div>
  </div>
</div>


<?php if($is_in_future) include 'partials/performance-details.php'; ?>

<?php get_footer(); ?>