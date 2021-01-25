<?php
class AdministraceController extends Controller
{
  public function execute($parameters){
    $this->verifyUser();
    $this->header['title'] = 'Administrace';
    $userManager = new UserManager();

    if(isset($parameters[0]) && $parameters[0] == 'odhlasit'){
      $userManager->logout();
      $this->redirect('prihlaseni');
    }

    $user = $userManager->getUser();
    $this->data['user_name'] = $user['user_name'];
    $this->data['user_permissions'] = $user['user_permissions'];
    $this->data['user_id'] = $user['user_id'];

    $this->view = 'administration';
  }
}
