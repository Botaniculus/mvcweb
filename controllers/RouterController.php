<?php
class RouterController extends Controller
{
  protected $controller;

  public function execute($parameters){
    $parsedURL = $this->parseURL($parameters[0]);

    if(empty($parsedURL[0]))
      $this->redirect('clanek/uvod');

    $controllerClass = $this->convertToControllerName(array_shift($parsedURL)) . 'Controller'; //array_shift - output 0. index and remove it from the array

    if(file_exists("controllers/$controllerClass.php"))
      $this->controller = new $controllerClass;
    else
      $this->redirect('error');

    $this->controller->execute($parsedURL);

    $userManager = new UserManager();

    $this->data['title'] = $this->controller->header['title'];
    $this->data['description'] = $this->controller->header['description'];
    $this->data['keywords'] = $this->controller->header['keywords'];
    $this->data['messages'] = $this->getMessages();
    $this->data['administration'] = ($userManager->getUser()) ? $userManager->getUser()['user_name'] : 'Přihlášení';
    $this->view = 'layout';
  }

  private function parseURL($url){
    $parsedURL = parse_url($url);
    $parsedURL['path'] = ltrim($parsedURL['path'], "/");
    $parsedURL['path'] = trim($parsedURL['path']);

    $splittedPath = explode("/", $parsedURL['path']);
    return $splittedPath;
  }

  private function convertToControllerName($text){
    $sentence = str_replace('-', ' ', $text);
    $sentence = ucwords($sentence);
    $sentence = str_replace(' ', '', $sentence);

    return $sentence;
  }


}
