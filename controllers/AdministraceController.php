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

    if($_POST){
      if(isset($_POST['save_user'])){
        try{
          $keys = array('user_name', 'user_permissions');
          $input = array_intersect_key($_POST, array_flip($keys));
          $userManager->updateUser($input);
          $this->addMessage('Změny byly úspěšně uloženy.', true);
          $this->redirect('administrace');
        }
        catch(UserException $e){
          $this->addMessage($e->getMessage(), false);
        }
      }
      else if(isset($_POST['save_password'])){
        try{
          $keys = array('old_password', 'new_password', 'new_password_2');
          $input = array_intersect_key($_POST, array_flip($keys));
          $userManager->updatePassword($input);
          $this->addMessage('Heslo bylo úspěšně změněno.', true);
          $this->redirect('administrace');
        }
        catch(UserException $e){
          $this->addMessage($e->getMessage(), false);
        }
      }
    }

    $this->view = 'administration';
  }
}
