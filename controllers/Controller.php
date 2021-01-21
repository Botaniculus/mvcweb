<?php
abstract class Controller
{
    protected $data = array();
    protected $view = "";
    protected $header = array(
      'title' => '',
      'keywords' => '',
      'description' => ''
    );

    abstract function execute($parameters);

    public function printView(){
      if($this->pohled){
        extract($this->data); //extract data
        require("views/$this->pohled.phtml");
      }
    }

    public function redirect($url){
      header("Location: /$url");
      header("Connection: close");
      exit;
    }
}
