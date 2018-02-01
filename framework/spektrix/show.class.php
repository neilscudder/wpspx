<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/**
 * This is the Show class.
 *
 * A Show is an Event in Spektrix
 * From their API docs:
 * "An event in Spektrix can be thought of as a ‘show’. It encapsulates the descriptive data about an event, such as its Name and Description. It is a container for instances."
 */
class Show extends Spektrix
{ 
  public $id;
  public $name;
  public $short_description;
  public $long_description;
  public $image_url;
  public $image_thumb;
  public $duration;
  public $is_on_sale;
  
  public $related_events;
  public $tags;
  
  public $venue;
  public $season;
  private $account_code;
  private $sales_type;
  private $vat_code_for_accounts;
  
  function __construct($event)
  {
    //If the $event variable isn't the event object, create it from the ID
    if(!is_object($event)) {
      $event = $this->get_event($event);
    }
    
    //Create an array of related events
    $related_events = array();
    if($event->RelatedEvents){
      foreach($event->RelatedEvents as $related_event){
        if($related_event){
          $related_events[] = (integer) $related_event->Event->attributes()->id;
        }
      }
    }
    
    $this->id = (integer) $event->attributes()->id;
    $this->name = (string) $event->Name;
    $this->short_description = (string) $event->Description;
    $this->long_description = (string) $event->HTMLDescription;
    $this->image_url = (string) $event->ImageUrl;
    $this->image_thumb = (string) $event->ThumbnailUrl;
    $this->is_on_sale = (boolean) $event->IsOnSale;
    $this->duration = (integer) $event->Duration;
    
    $this->related_events = (array) $related_events;
    
    foreach($event->Attribute as $object){
      $name = (string) $object->Name;
      $name = strtolower($name);
      $name = str_replace(' ','_',$name);
      $this->$name = (string) $object->Value;
    }
    
    $tags = array();
    
    foreach($event->Attribute as $object){  
      $name = (string) $object->Name;
      $name = strtolower($name);
      if($object->Value == 1){
        $tags[] = $name;
      }
    }
    
    $this->tags = (array) $tags;
  }
  
  static function find_all()
  {
    $api = new Spektrix();
    $shows = $api->get_events();
    return $api->collect_shows($shows);
  }
  
  static function find_all_in_future()
  {
    $api = new Spektrix();
    $eternity = time() + (60 * 60 * 24 * 7 * 500);
    $shows = $api->get_shows_until($eternity);
    return $api->collect_shows($shows);
  }
  
  static function this_week()
  {
    $api = new Spektrix();
    $next_week = time() + (60 * 60 * 24 * 7);
    $shows = $api->get_shows_until($next_week);
    return $api->collect_shows($shows);
  }
  
  static function six_weeks()
  {
    $api = new Spektrix();
    $six_weeks = time() + (60 * 60 * 24 * 7 * 6);
    $shows = $api->get_shows_until($six_weeks);
    return $api->collect_shows($shows);
  }
  
  function get_show_performances($show_id)
  {
    $performances = $this->get_object('instances',array('event_id'=>$show_id));
    return $this->collect_performances($performances);
  }
  
  function get_performances()
  {
    return $this->get_show_performances($this->id);
  }
  
  function get_price_lists()
  {
    $pricelists = $this->get_price_list_for_show($this->id);
    $collection = array();
    foreach($pricelists->PriceList as $pl){
      $collection[] = new PriceList($pl);
    }
    return $collection;
  }
  
  function tags_as_class()
  {
    $classes = implode(',',$this->tags);
    $classes = str_replace(' ','-',$classes);
    $classes = str_replace(',',' ',$classes);
    return $classes;
  }
  
}

function convert_to_array_of_ids($collection){
  $ids = array();
  foreach($collection as $c){
    $ids[] = $c->id;
  }
  return $ids;
}

function get_wp_shows_from_spektrix_shows($shows) {
  $show_ids = convert_to_array_of_ids($shows);

  $db_shows = get_posts(array(
     'post_type' => 'shows',
     'posts_per_page' => -1,
     'meta_query' => array(
        array(
           'key'     => '_spektrix_id',
           'value'   => $show_ids,
           'compare' => 'IN'
        )
     )
  ));

  $wp_shows = array();
  foreach($db_shows as $db_show):
    $spektrix_id = get_post_meta($db_show->ID,'_spektrix_id',true);
    $wp_shows[$spektrix_id] = $db_show->ID;
  endforeach;
  
  return $wp_shows;
}

function filter_published($shows,$wp_shows){
  $published = array();
  foreach($shows as $k => $show):
    if(array_key_exists($show->id,$wp_shows)):
      $published[$k] = $show;
    endif;
  endforeach;
  return $published;
}

function filter_meals($shows){
  $not_meals = array();
  foreach($shows as $k => $show):
    if($show->season != 'Meals & Set Menus'):
      $not_meals[$k] = $show;
    endif;
  endforeach;
  return $not_meals;
}

function filter_shows_by_spektrix_tag($all_shows,$term_slugs){
  $term_slugs = is_array($term_slugs) ? $term_slugs : array($term_slugs);
  $shows = array();
  foreach($all_shows as $show):
    $match_array = array_intersect($term_slugs,$show->tags);
    if(!empty($match_array)):
      $shows[] = $show;
    endif;
  endforeach;
  return $shows;
}

function is_pulse($show){
  $is_pulse = strpos($show->tags_as_class(),'pulse-festival') !== false;
  return $is_pulse;
}

function filter_shows_with_genre($all_shows, $genre){
  $shows = array();
  $show_terms = array();
  foreach($all_shows as $show):
    // if show has a genre that matche
    $show_terms = get_the_terms($show, 'genres');
    print_r($show_terms);
    echo ($show_terms);
    if($genre):
      $shows[$show->id] = $show;
    endif;
  endforeach;
  return $shows;
}