<?php
class PrihlaseniController extends Controller
{
  public function execute($parameters){
    $userManager = new UserManager();

    if($userManager->getUser())
      $this->redirect('administrace');

    $this->header['title'] = "Přihlášení";

    if(isset($_POST['login_submit'])){
      try{
        $userManager->login($_POST['username'], $_POST['password']);
        $this->addMessage('Přihlášení probělo úspěšně', true);
        $this->redirect('administrace');
      }
      catch(UserException $e){
        $this->addMessage($e->getMessage(), false);
      }
    }

    $this->view = 'login';

  }
}
