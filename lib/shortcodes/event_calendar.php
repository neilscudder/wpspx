<?php
add_shortcode( 'spektrix_event_calendar', 'spektrix_event_calendar_shortcode' );
function spektrix_event_calendar_shortcode() {
  $spektrix_iframe_url = new iFrame('EventCalendar', array('MonthOnly' => 'true', 'Horizontal' => 'True', 'Weeks' => 5, 'StartDay' => 'Sunday'), false);
  ?>
  <div class="row">
    <div class="span12">
      <?php echo $spektrix_iframe_url->render_iframe(); ?>
    </div>
  </div>

  <?php
}
