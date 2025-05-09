<?php

namespace RFID2FA\Model;

use RFID2FA\DAO\LoginDAO;

final class Login {
  public $id, $nome, $email, $senha;

  function logar() : ?Login {
    return (new LoginDAO())->autenticar($this);
  }
}