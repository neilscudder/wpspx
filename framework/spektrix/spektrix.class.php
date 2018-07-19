<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/**
	* Spektrix is a base class for hitting the API and retrieving data
	*/

  class Spektrix
  {

    private static $api_key = SPEKTRIX_API;
    private static $api_url = SPEKTRIX_URL;
    protected $wp_theme = THEME_SLUG;

    public static function get_path_to_cert()
    {
      return SPEKTRIX_CERT;
    }

    public static function get_path_to_key()
    {
      return SPEKTRIX_KEY;
    }

    function build_url($resource,$params = array())
    {
      $params_string = "";
      if(empty($params)):
        $url = self::$api_url.$resource."?&api_key=".self::$api_key;
      else:

        foreach($params as $k => $v):
          $params_string .= $k . '=' . $v . '&';
        endforeach;

        $url = self::$api_url.$resource."?".$params_string."api_key=".self::$api_key;
      endif;

      $url.= "&all=true";

      return $url;
    }

    function get_xml($xml_url)
    {
      $curl = curl_init();
      $options = array(
        CURLOPT_URL => $xml_url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSLCERT => self::get_path_to_cert(),
        CURLOPT_SSLKEY => self::get_path_to_key()
      );
      curl_setopt_array($curl, $options);
      $string = curl_exec($curl);
      return $string;
    }

    function post_xml($xml_url,$params)
    {
      $xml_builder = '<?xml version="1.0" encoding="utf-8"?>';
      $curl = curl_init();
      $options = array(
        CURLOPT_HTTPHEADER => array('Content-Type: text/xml'),
        CURLOPT_URL => $xml_url,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $xml_builder,
        CURLOPT_SSLCERT => self::get_path_to_cert(),
        CURLOPT_SSLKEY => self::get_path_to_key()
      );
      curl_setopt_array($curl, $options);
      $string = curl_exec($curl);
      curl_close($curl);
      return $string;
    }

    function get_object($resource, $params = array(), $skip_cache = false)
    {
      $file = new CachedFile($resource, $params);
      try {
        if(!$skip_cache && $file->is_cached_and_fresh()){
          $xml_string = $file->retrieve();
        } else {
          $xml_url = $this->build_url($resource,$params);
          $xml_string = $this->get_xml($xml_url);
          $file->store($xml_string);
        }
        if($xml_string){
          $xml_as_object = simplexml_load_string($xml_string);
          return $xml_as_object;
        } else {
          throw new Exception('no XML received from Spektrix');
        }
      }
      catch (Exception $e){ ?>
      <div class="notice notice-error">
        <p>Oops, <?php echo $e->getMessage(); ?>. Double check your settings are correct.</a></p>
      </div>
      <?php
    }
  }

  function redirectAsError(){
    header("Location: " . home_url());
    die();
  }

  function post_object($resource,$params=array())
  {
    $xml_url = self::$api_url.$resource;
    $xml_string = $this->post_xml($xml_url,$params);
    $xml_as_object = simplexml_load_string($xml_string);
    return $xml_as_object;
  }

  function get_event($id, $skip_cache = false)
  {
    return $this->get_object('events',array('event_id'=>$id), $skip_cache)->Event;
  }

  function get_events($skip_cache = false)
  {
    return $this->get_object('events',array(), $skip_cache);
  }

  function get_shows_in_range($unix_from_date, $unix_until_date)
  {
    $from_date = date('Y-m-d\TH:i:s\Z', $unix_from_date);
    $until_date = date('Y-m-d\TH:i:s\Z',$unix_until_date);
    return $this->get_object('events',array('instance_start_from'=>$from_date,'instance_start_to'=>$until_date));
  }

  function get_performances_in_range($unix_from_date, $unix_until_date)
  {
    $from_date = date('Y-m-d\TH:i:s\Z', $unix_from_date);
    $until_date = date('Y-m-d\TH:i:s\Z',$unix_until_date);
    return $this->get_object('instances',array('instance_start_from'=>$from_date,'instance_start_to'=>$until_date));
  }

  function get_shows_until($unix_until_date)
  {
    $today = date('Y-m-d\TH:i:s\Z');
    $until_date = date('Y-m-d\TH:i:s\Z',$unix_until_date);
    return $this->get_object('events',array('instance_start_from'=>$today,'instance_start_to'=>$until_date));
  }

  function get_performances_until($unix_until_date)
  {
    $today = date('Y-m-d\TH:i:s\Z');
    $until_date = date('Y-m-d\TH:i:s\Z',$unix_until_date);
    return $this->get_object('instances',array('instance_start_from'=>$today,'instance_start_to'=>$until_date));
  }

  protected function collect_shows($shows)
  {
    $collection = array();
    foreach($shows->Event as $show){
      $id = (integer) $show->attributes()->id;
      $collection[$id] = new Show($show);
    }
    return $collection;
  }

  function get_performance($id)
  {
    return $this->get_object('instances',array('instance_id'=>$id))->Instance;
  }

  function get_performances()
  {
    return $this->get_object('instances');
  }

  protected function collect_performances($performances)
  {
    $collection = array();
    foreach($performances->Instance as $performance){
      $collection[] = new Performance($performance);
    }
    return $collection;
  }

  protected function collect_performances_by_show($performances)
  {
    $collection = array();
    foreach($performances->Instance as $performance){
      $show_id = (integer) $performance->Event['id'];
      $collection[$show_id][] = new Performance($performance);
    }
    return $collection;
  }

  function get_plans($event_id)
  {
    return $this->get_object('plans',array('event_id'=>$event_id));
  }

  function get_price_list_for_show($event_id)
  {
    return $this->get_object('price-lists',array('event_id'=>$event_id));
  }

  function get_ticket_types()
  {
    return $this->get_object('ticket-types');
  }

  function get_bands()
  {
    return $this->get_object('bands');
  }

  function get_availabilities()
  {
    $availabilities = $this->get_object('instance-status');
    $collection = array();
    foreach($availabilities->InstancePlanStatus as $availability){
      $id = (integer) $availability->Instance->attributes()->id;
      if(!array_key_exists($id,$collection)){
        $collection[$id] = new Availability($availability);
      }
    }
    return $collection;
  }

  function get_availability($performance_id)
  {
    return $this->get_object('instance-status',array('instance_id'=>$performance_id));
  }

  function login($email,$password)
  {
    $this->post_object('customers/authenticate',array('Email'=>$email,'Password'=>$password));
  }
}
