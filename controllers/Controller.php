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
      if($this->view){
        extract($this->sanitize($this->data));
        extract($this->data, EXTR_PREFIX_ALL, "");
        // sanitized is $x, not sanitized is $_x

        require("views/$this->view.phtml");
      }
    }

    public function redirect($url){
      header("Location: /$url");
      header("Connection: close");
      exit;
    }

    public function addMessage($message, $positive){
      $styledMessage = ($positive) ? '<div class="alert alert-success">' : '<div class="alert alert-danger">';
      $styledMessage .= $message . '</div>';

      if(isset($_SESSION['messages']))
        $_SESSION['messages'][] = $styledMessage;
      else
        $_SESSION['messages'] = array($styledMessage);
    }
    public function getMessages(){
      if(isset($_SESSION['messages'])){
        $messages = $_SESSION['messages'];
        unset($_SESSION['messages']);
        return $messages;
      }
      else return array();
    }

    private function sanitize($x = null) {
        if (!isset($x)) return null;
        elseif (is_string($x)) return htmlspecialchars($x, ENT_QUOTES);
        elseif (is_array($x)) {
            foreach($x as $k => $v){
                $x[$k] = $this->sanitize($v);
            }
            return $x;
        }
        else return $x;
    }

    public function verifyUser($level = 0){
      $userManager = new UserManager();
      $user = $userManager->getUser();

      if(!$user || ($user['user_permissions'] < $level)){
        $this->addMessage('Nedostatečná oprávnění', false);
        $this->redirect('prihlaseni');
      }
    }
}
