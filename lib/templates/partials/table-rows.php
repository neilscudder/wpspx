<?php
$is_sold_out = ($availabilities[$performance->id]->available === 0);
$av_helper = availability_helper($availabilities[$performance->id],$extremes);
$tr_class = 'performance ';
$tr_class.= $av_helper->best_availability ? "best-availability " : "";
$tr_class.= $performance->is_accessible() ? "accessible" : "";
?>
<tr class="<?php echo $tr_class; ?>">
  <td colspan="3">
    <br>
  </td>
</tr>
<?php if($is_sold_out): ?>
<tr class="<?php echo $tr_class; ?> sold-out-container">
  <td colspan="2" style="border-top:none;">
    <span class="day-name"><?php echo $performance->start_time->format('l') ?></span>
    <span class="day-number"><?php echo $performance->start_time->format('d') ?></span>
    <span class="month-year"><?php echo $performance->start_time->format('M Y') ?></span>
  </td>
  <td colspan="2" style="border-top:none;">
    <h3 style="margin-top: 0;">Sorry, this date is sold out</h3>
    <p><?php echo book_online_button($availabilities[$performance->id],$av_helper,$performance,$is_blockbuster) ?></p>
  </td>
</tr>  
<?php else: ?>
<tr class="<?php echo $tr_class; ?>">
  <td style="border-top:none;">
    <span class="day-name"><?php echo $performance->start_time->format('l') ?></span>
    <span class="day-number"><?php echo $performance->start_time->format('d') ?></span>
    <span class="month-year"><?php echo $performance->start_time->format('M Y') ?></span>
    <p style="margin-top:20px;width:90%;"><?php echo book_online_button($availabilities[$performance->id],$av_helper,$performance,$is_blockbuster) ?></p>
  </td>
  <td style="border-top:none;">
    <time datetime="<?php echo $performance->start_time->format('Y-m-d H:i:s') ?>"></time>
    <span class="day-time"><small>Starts</small><br><?php echo $performance->start_time->format('H.i') ?></span>
    <?php if($show->duration): ?>
      <span class="day-time ends"><small>Ends</small><br> <?php echo $performance->end_time($show->duration,'H.i'); ?><small class="ish">ish</small></span>
    <?php endif; ?>
    <p style="margin-top:20px;"><a href="https://maps.google.co.uk/maps?q=<?php echo $show->venue ?>" target="_blank"><?php echo str_replace(', ','<br>',$show->venue) ?></a></p>
  </td>
  <td class="show-prices hidden-phone" rowspan="2" style="border-top:none;">
    <div class="<?php if($is_sold_out) echo 'sold-out-container' ?>">
      <table class="table table-hover">
        <?php
        if($is_sold_out && $performance->is_on_sale) echo '<img src="'.$dir.'/img/sold-out.png" class="sold-out">';
        $pricelist = get_price_list_for_performance($pricelists,$performance);
        $price_table = array();
        foreach($pricelist->prices as $price):
          if($price->ticket_type_name && $price->band_name):
            $price_table[$price->ticket_type_name][$price->band_name] = $price->price;
          endif;
        endforeach;
        $i = 0;
        foreach($price_table as $ticket_type => $bands):
          //If the key 'Premium' exists, remove it and add to the front
          if(array_key_exists('Premium',$bands)):
            $temp = array('Premium' => $bands['Premium']);
            unset($bands['Premium']);
            $bands = $temp + $bands;
          endif;
          //If it's the first one, print the band names
          if($i == 0):
            echo '<tr class="bands"><td>&nbsp;</td>';
            foreach($bands as $band_name => $price):
              echo '<td><strong>'.$band_name.'</strong></td>';
            endforeach;
            echo '</tr>';
          endif;
          //Now just cycle through each ticket type
          echo '<tr>';
            echo '<td>'.$ticket_type.'</td>';
            foreach($bands as $band_name => $price):
              if($price == 0):
                echo '<td>Free</td>';
              else:
                echo '<td> &pound;'.number_format($price,2).'</td>';
              endif;
            endforeach;
          echo '</tr>';
          $i++;
        endforeach;
        ?>
      </table>
    </div>
  </td>
</tr>
<?php $perfattri = $performance->attributes; if ($perfattri): ?>
<tr class="<?php echo $tr_class; ?> attributes">
  <td colspan="2" style="border-top:none; padding: 0;">
    <?php format_performances_attributes($performance->attributes); ?>
  </td>
</tr>
<?php endif; ?>
<?php endif; // end if sold out ?>
<tr class="<?php echo $tr_class; ?>">
  <td colspan="3" style="border-top:none;">
    <br>
  </td>
</tr>