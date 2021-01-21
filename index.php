<?php
mb_internal_encoding("UTF-8");

function autoloadClass($name){
  if(preg_match('/Controller$/', $name))
    require("controllers/$name.php");
  else
    require("models/$name.php");
}

spl_autoload_register("autoloadClass");

Db::connect("127.0.0.1", "root", "", "mvc_db");

$router = new RouterController();
$router->execute(array($_SERVER['REQUEST_URI']));

$router->printView();
