<?php
class Db
{
  private static $connection;
  private static $PDOSettings = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    PDO::ATTR_EMULATE_PREPARES => false,
  );

  public static function connect($host, $user, $password, $database){
    if(!isset(self::$connection)){
      self::$connection = @new PDO(
          "mysql:host=$host; dbname=$database",
          $user, $password, self::$PDOSettings
        );
    }
  }

  public static function querySingleRow($query, $parameters = array()){
    $return = self::$connection->prepare($query);
    $return->execute($parameters);
    return $return->fetch();
  }

  public static function queryAllRows($query, $parameters = array()){
    $return = self::$connection->prepare($query);
    $return->execute($parameters);
    return $return->fetchAll();
  }

  public static function querySingleColumn($query, $parameters = array()){
    $return = self::querySingleRow($query, $parameters);
    return $return[0];
  }

  public static function query($query, $parameters = array()){
    $return = self::$connection->prepare($query);
    $return->execute($parameters);
    return $return->rowcount();
  }

  public static function insert($table, $parameters = array()){
      return self::query("INSERT INTO `$table` (`".
          implode('`, `', array_keys($parameters)).
          "`) VALUES (".str_repeat('?,', sizeOf($parameters)-1)."?)",
              array_values($parameters));
  }
  public static function update($table, $values = array(), $condition, $parameters = array()){
    return self::query("UPDATE `$table` SET `".
          implode('` = ?, `', array_keys($values)).
          "` = ? " . $condition,
          array_merge(array_values($values), $parameters));
  }
  public static function getLastId(){
      return self::$connection->lastInsertId();
  }
}
