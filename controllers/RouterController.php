<?php
class RouterController extends Controller
{
  protected $controller;

  public function execute($parameters){

  }

  private function parseURL($url){
    $parsedURL = parse_url($url);
    $parsedURL['path'] = ltrim($parsedURL['path'], "/");
    $parsedURL['path'] = trim($parsedURL['path']);

    $splittedPath = explode("/", $parsedURL['path']);
    return $splittedPath;
  }
}
