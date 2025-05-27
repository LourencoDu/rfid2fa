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
    $sql = "SELECT
    u.id u_id, u.nome u_nome, u.email u_email, u.senha u_senha
    FROM usuario u
    WHERE u.email = ?;";

    $stmt = parent::$conexao->prepare($sql);
    $stmt->bindValue(1, $model->email);
    $stmt->execute();

    $data = $stmt->fetchObject();
    
    if(is_object($data)) {
      if(password_verify($model->senha, $data->u_senha)) {
        $login = new Login();
        $login->id = $data->u_id;
        $login->nome = $data->u_nome;
        $login->email = $data->u_email;
        $login->senha = $data->u_senha;

        return $login;
      }
    }

    return null;
  }
}