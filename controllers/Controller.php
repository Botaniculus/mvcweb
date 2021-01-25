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
        extract($this->treat($this->data));
        extract($this->data, EXTR_PREFIX_ALL, "");
        // treated is $x, not treated is $_x

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

    private function treat($x = null) {
        if (!isset($x)) return null;
        elseif (is_string($x)) return htmlspecialchars($x, ENT_QUOTES);
        elseif (is_array($x)) {
            foreach($x as $k => $v){
                $x[$k] = $this->treat($v);
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
