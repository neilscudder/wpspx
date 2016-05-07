<?php
class FakeShow
{
  public $id;
  public $name;
  public $short_description;
  public $long_description;
  public $duration;
  public $is_on_sale;
  public $tags;
  public $venue;

  function __construct($wp_show){

    if(is_object($wp_show)){
      $show = $wp_show;
    } else {
      $show = $get_post($wp_show);
    }
    $show_meta = get_post_meta($show->ID);

    $this->id = (string) 'fs_'.$show->ID;
    $this->name = (string) $show->post_title;
    $this->short_description = (string) get_first_paragraph($show->post_content);
    $this->long_description = (string) $show->post_content;
    $this->tags = (string) $show_meta['non_spektrix_event_attributes'][0];
    $this->first_show = new DateTime((string) date('c',$show_meta['performance_start_date'][0]));
    $this->venue = (string) $show_meta['non_spektrix_venue'][0];
    /*
    $this->duration = (string) $fake_show_array['duration'];
    $this->is_on_sale = (string) $fake_show_array['is_on_sale'];
    $this->venue = (string) $fake_show_array['venue'];
    */
  }

  function tags_as_class()
  {
    $classes = str_replace(' ','-',$this->tags);
    $classes = str_replace(',',' ',$classes);
    return strtolower($classes);
  }
}

function load_fake_shows(){
  $args = array(
    'posts_per_page' => -1,
    'post_type' => 'shows',
    'meta_key' => 'non_spektrix_show',
    'meta_value' => 1
  );
  $wp_fake_shows = get_posts($args);
  $fake_shows = convert_wp_fake_shows($wp_fake_shows);
  return $fake_shows;
}

function convert_wp_fake_shows($wp_fake_shows){
  $fake_shows = array();
  foreach($wp_fake_shows as $fs){
    $fake_shows['fs_'.$fs->ID] = new FakeShow($fs);
  }
  return $fake_shows;
}

function is_fake_show($show){
  if(get_class($show) == "FakeShow"){
    return true;
  } else {
    return false;
  }
}