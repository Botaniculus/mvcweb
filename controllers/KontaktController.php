<?php
class KontaktController extends Controller
{
  public function execute($parameters){
    $this->header = array(
      'title' => 'Kontaktní formulář',
      'keywords' => 'kontakt, formulář, adresa, formulář',
      'description' => 'Kontaktní formulář SOVF.'
    );
    if(isset($_POST['send'])){
      try{
        $emailSender = new EmailSender();
        $emailSender->sendWithAntispam($_POST['year'], "bofin@skaut.cz", "Email z webu", $_POST['message'], $_POST['email']);
        $this->addMessage('Email byl úspěšně odeslán.', true);
        $this->redirect('kontakt');
      }
      catch(UserException $e){
        $this->addMessage($e->getMessage(), false);
      }
    }

    $this->view = 'contact';
  }
}
