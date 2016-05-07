<?php
class iFrame
{
  public $url;

  private $page_name;
  private $params_string;
  public $secure;

  private $secure_prefix = "https://system.spektrix.com/newwolsey/website/secure/";
  private $insecure_prefix = "https://system.spektrix.com/newwolsey/website/";

  function __construct($page_name, $params = false, $secure = false){
    $this->page_name = strtolower($page_name);
    $this->params_string = $params ? http_build_query($params) . '&' : '';
    $this->secure = $secure;
  }

  public function iframe_url(){
    return $this->prefix($this->secure) . $this->page_name . '.aspx?' . $this->params_string . 'stylesheet=condiment.css&resize=true';
  }

  public function render_iframe(){
    return '<iframe name="SpektrixIFrame" id="SpektrixIFrame" src="' . $this->iframe_url() . '" frameborder="0" height="400" width="100%" onload="scroll(0,0);"></iframe>';
  }

  public function is_insecure(){
    return !$this->secure;
  }

  private function prefix($secure = false){
    return $secure ? $this->secure_prefix : $this->insecure_prefix;
  }
}