<?php 
$videos = get_field("videos");
$images = get_field('show_gallery');
$cover_image = get_field('cover_image');
$show = new FakeShow($post);
$performance = new FakePerformance($show);
get_header(); ?>

<?php if($cover_image): ?>
  <div class="wowsers show-information" style="background-image: url(<?php echo $cover_image['sizes']['large'] ?>);">
      <div class="titles">
        <h1><?php echo str_replace("#PULSE14 &#8211; ",'',get_the_title()); ?></h1>
        <h3 class="date-range">
          <?php the_field('performance_free_text'); ?>
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
          <?php the_field('performance_free_text'); ?>
        </h3>
      </div>
    </div>
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
    </div>
    <div class="span6">
      <p><?php the_content(); ?></p>
      <?php include 'partials/cast-and-crew.php'; ?>      
      <?php include 'partials/performance-links.php' ?>
    </div>
    <div class="span2 offset1">

      <?php if ( function_exists( 'sharing_display' ) ): ?>
        <h2>It's better with your friends<br>
        Share rhis performance.</h2>
        <?php sharing_display( '', true ); ?>
      <?php endif; ?>

      <div id="sponsor-ad">
      </div>

      <?php // This needs converting to a 'pick a sponsor box' ?>
      <script>
      var url = window.location.pathname;
      var sponsor = document.getElementById("sponsor-ad");

      if (url == "/shows/sinbad/") {
        var sponsorName = 'Ipswich Building Society';
        var sponsorImageSrc = 'https://s3-eu-west-1.amazonaws.com/nwt-web-assets/ibs-web-banner.jpg';
        var sponsorImageWidth = '170';
        var sponsorImageHeight = '300';
        var sponsorUrl = 'http://www.ibs.co.uk/';

        sponsor.innerHTML = '<p><strong>Sponsored By:</strong></p><a href="' + sponsorUrl + '" ><img src="' + sponsorImageSrc + '" width="' + sponsorImageWidth + '" height="' + sponsorImageHeight + '" alt="Sponsored by ' + sponsorName + '" /></a><br/><br/>';

      } else if (url == "/shows/a-midsummer-nights-dream/") {

        var sponsorName = 'Ashton KCJ';
        var sponsorImageSrc = 'https://s3-eu-west-1.amazonaws.com/nwt-web-assets/ashton-kcj-web-banner.jpg';
        var sponsorImageWidth = 'auto';
        var sponsorImageHeight = 'auto';
        var sponsorUrl = 'https://www.ashtonkcj.co.uk/';

        sponsor.innerHTML = '<p><strong>Sponsored By:</strong></p><a href="' + sponsorUrl + '" ><img src="' + sponsorImageSrc + '" width="' + sponsorImageWidth + '" height="' + sponsorImageHeight + '" alt="Sponsored by ' + sponsorName + '" /></a><br/><br/>';
      }
      </script>
      <?php if($is_in_future): ?>
        <p style="padding-top:10px;"><small><strong>Not sure if it's for you?</strong><br>Chat to one of our friendly ticket sales assistants on 01473&nbsp;295900</small></p>
      <?php endif ?>
    </div>
  </div>
  
  <div class="row">
    <div class="span12 gallery">
      <?php include 'partials/performance-gallery.php'; ?>
    </div>
  </div>
  
</div>

<?php get_footer(); ?>