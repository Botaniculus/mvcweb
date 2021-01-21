<?php
mb_internal_encoding("UTF-8");

function autoloadClass($name){
  if(preg_match('/Controller$/', $name))
    require("controllers/$name.php");
  else
    require("models/$name.php");
}

spl_autoload_register("autoloadClass");
