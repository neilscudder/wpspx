<?php $av_helper = availability_helper($availabilities[$performances[0]->id],$extremes); ?>
<div class="quick-buy">
  <div class="container show-information">
    <div class="row">
      <div class="span12">
        <hr>
        <a id="buytickets"></a>
        <h2 id="performance-information">Performance information</h2>
        <a id="closequickbuy">
          <span></span>
          <span></span>
        </a>

        <?php if($show->duration): ?>
          <p>Running time: <?php echo convert_to_hours_minutes($show->duration) ?></p>
        <?php endif; ?>

        <h3 class="hidden-phone">
          <?php if($count > 1): ?>
            When can I see one of the <?php echo convert_number_to_words($count); ?> performances of <?php the_title(); ?>?
          <?php else: ?>
            When can I see the only performance of <?php the_title(); ?>?
          <?php endif; ?>
        </h3>

        <?php if(($is_blockbuster && $av_helper->show_best) || $any_accessible): ?>
          <div class="row">
            <?php if($is_blockbuster && $av_helper->show_best): ?>
              <div class="span1">
                <div class="switch" id="availability-switch">
                  <input type="checkbox" data-toggle="switch" data-show=".best-availability">
                </div>
              </div>
              <div class="span5">
                <p style="padding-top:4px;padding-left:8px;">Only show performances with best availability?</p>
              </div>
            <?php endif; ?>
            <?php if($any_accessible): ?>
              <div class="span1">
                <div class="switch">
                  <input type="checkbox" data-toggle="switch" data-show=".accessible">
                </div>
              </div>
              <div class="span5">
                <p style="padding-top:4px;padding-left:8px;">Only show accessible performances?</p>
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <table class="table">
          <tr>
            <th width="25%"><strong>Date &amp; Details</strong></th>
            <th><strong>Time &amp; Place</strong></th>
            <th class="hidden-phone"><strong>Tickets &amp; Pricing</strong></th>
          </tr>
          <tr class="no-best-availability">
            <td colspan="3">
              <h3>Sorry!<br>All performances have sold spectacularly well.<br>Call us 01473 295900 and we'll do our best to squeeze you in.</h3>
            </td>
          </tr>
          <?php $now = new DateTime(); ?>
          <?php foreach($performances as $performance): ?>
            <?php if($performance->start_time > $now) include 'table-rows.php'; ?>
          <?php endforeach; ?>
        </table>
      </div>
    </div>
  </div>
</div>