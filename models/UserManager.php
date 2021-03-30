<?php
class UserManager
{
  public function getHash($password){
    return password_hash($password, PASSWORD_DEFAULT);
  }

  public function register($username, $password, $passwordAgain, $year){
    if($year != date('Y'))
      throw new UserException('Špatně vyplňený antispam', false);
    if($password != $passwordAgain)
      throw new UserException('Hesla nesouhlasí', false);

    $user = array(
      'user_name' => $username,
      'user_password' => $this->getHash($password)
    );
    try{
      DB::insert('users', $user);
    } catch (PDOException $e){
      throw new UserException('Vyberte si prosím jiné jméno. Uživatel s tímto jménem je již zaregistrován');
    }
  }
  public function updateUser($userinput = array()){
    $id = $this->getUser()['user_id'];
      try{
        Db::update('users', $userinput, 'WHERE user_id = ?', array($id));
        $this->updateUserSession();
      }
      catch(PDOException $e){
        throw new UserException('Nebylo úspěšně změněno. ' . $e->getMessage());
      }
  }
  public function updatePassword($userinput = array()){
    $user = $this->getUser();
    if(password_verify($userinput['old_password'], $user['user_password'])) {
      if($userinput['new_password'] == $userinput['new_password_2']){
        $array['user_password'] = $this->getHash($userinput['new_password']);
        try{
          Db::update('users', $array, 'WHERE user_id = ?', array($user['user_id']));
          $this->updateUserSession();
        }
        catch(PDOException $e){
          throw new UserException('Heslo nebylo úspěšně změněno. ' . $e->getMessage());
        }
      }
      else{
        throw new UserException("Hesla se neshodují.");
      }
    }
    else{
      throw new UserException("Zkontrolujte si prosím staré heslo." . $user['user_password'] . " != " . $this->getHash($userinput['old_password']));
    }

  }

  public function login($username, $password){
    $user = Db::querySingleRow('
      SELECT user_id, user_name, user_permissions, user_password
      FROM users
      WHERE user_name = ?
    ', array($username));
    if(!$user || !password_verify($password, $user['user_password']))
      throw new UserException('Neplatné uživatelské jméno nebo heslo', false);
    $_SESSION['user'] = $user;
  }

  public function logout(){
    unset($_SESSION['user']);
  }

  public function getUser(){
    if(isset($_SESSION['user']))
      return $_SESSION['user'];
    return null;
  }
  private function updateUserSession(){
    $id = $this->getUser()['user_id'];
    $user = Db::querySingleRow('
      SELECT user_name, user_permissions, user_password
      FROM users
      WHERE user_id = ?
    ', array($id));
    $_SESSION['user'] = array_merge($_SESSION['user'], $user);
  }


  public function getUsername($id){
    $username = Db::querySingleColumn('
      SELECT user_name
      FROM users
      WHERE user_id = ?
    ', array($id));

    return $username;
  }
}
