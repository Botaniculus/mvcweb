<?php

class RegistraceController extends Controller
{
  public function execute($parameters){
    $this->header['title'] = 'Registrace';
    if(isset($_POST['register_submit'])){
      try{
        $userManager = new UserManager();
        $userManager->register($_POST['username'], $_POST['password'], $_POST['password_again'], $_POST['year']);
        $userManager->login($_POST['username'], $_POST['password']);
        $this->addMessage('Registrace proběhla úspěšně.', true);
        $this->redirect('administrace');
      } catch(UserException $e){
        $this->addMessage($e->getMessage(), false);
      }
    }

    $this->view = 'registration';

  }

}
