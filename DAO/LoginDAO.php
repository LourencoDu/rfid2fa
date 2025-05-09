<?php

namespace RFID2FA\DAO;

use RFID2FA\Model\Login;

final class LoginDAO extends DAO {
  public function __construct()
  {
    parent::__construct();
  }

  public function autenticar(Login $model) : ?Login
  {
    $sql = "SELECT id, nome, sobrenome, email, senha, tipo FROM usuario WHERE email=?;";

    $stmt = parent::$conexao->prepare($sql);
    $stmt->bindValue(1, $model->email);
    $stmt->execute();

    $login = $stmt->fetchObject("RFID2FA\Model\Login");

    
    if(is_object($login)) {
      if(password_verify($model->senha, $login->senha)) {
        return $login;
      }
    }

    return null;
  }
}