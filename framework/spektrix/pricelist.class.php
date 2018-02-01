<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

class PriceList extends Spektrix
{
  public $id;
  public $prices;
  
  public function __construct($pl)
  {
    $this->id = (string) $pl->attributes()->id;
    
    $api = new Spektrix();
    $bands = $api->get_bands();
    $ticket_types = $api->get_ticket_types();
    
    foreach($pl->Price as $p){
      $this->prices[] = (object) new Price($p,$bands,$ticket_types);
    }
  }
}

class Price extends Spektrix
{
  private $band_id;
  private $band_default;
  private $ticket_type_id;
  public $is_band_default;
  public $price;
  public $ticket_type_name;
  public $band_name;
  
  public function __construct($price,$bands,$ticket_types)
  {
    $this->price = (float) $price->Price;
    $this->band_default = (string) $price->IsBandDefault;
    $this->is_band_default = $this->band_default == 'true' ? true : false;
    
    //Getting bands
    $this->band_id = (integer) $price->Band->attributes()->id;
    $this->band_name = Band::get_name_by_id($bands,$this->band_id);
    //Getting ticket types
    $this->ticket_type_id = (integer) $price->TicketType->attributes()->id;
    $this->ticket_type_name = TicketType::get_name_by_id($ticket_types,$this->ticket_type_id);
  }
}

class Band extends Spektrix
{
  private $id;
  public $name;
    
  public static function get_name_by_id($bands,$band_id)
  {
    foreach($bands as $band){
      if($band_id == (integer) $band->attributes()->id){
        return (string) $band->Name;
      }
    }
  }
}

class TicketType extends Spektrix
{
  private $id;
  public $name;
  
  public static function get_name_by_id($ticket_types, $ticket_type_id)
  {
    foreach($ticket_types as $tt){
      if($ticket_type_id == (integer) $tt->attributes()->id){
        return (string) $tt->Name;
      }
    }
  }
}