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
      if($_POST['year'] == date('Y')){
        $emailSender = new EmailSender();
        $emailSender->send("bofin@skaut.cz", "Email z webu", $_POST['message'], $_POST['email']);
      }
    }

    $this->view = 'contact';
  }
}
