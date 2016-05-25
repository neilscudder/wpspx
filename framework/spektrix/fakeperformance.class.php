<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

class FakePerformance
{
  public $id;
  public $start_time;
  public $show_id;
  
  function __construct($fake_show)
  {
    $this->id = (string) 'performance_'.$fake_show->id;
    $this->start_time = $fake_show->first_show;
    $this->show_id = (string) $fake_show->id;
  }
}

function load_fake_performances($fake_shows){
  $fake_performances = array();
  foreach($fake_shows as $fake_show){
    $fake_performances[$fake_show->id][] = new FakePerformance($fake_show);
  }
  return $fake_performances;
}