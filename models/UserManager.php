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
      throw new UserException('Vyberte si prosím jiné jméno. Uživatel s tímto jménem je již zaregistrován', false);
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
}
