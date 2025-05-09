<?php

namespace RFID2FA\Model;

use RFID2FA\DAO\LoginDAO;

final class Login {
  public $id, $nome, $sobrenome, $email, $senha, $tipo;
  public $nome_completo, $icone;

  function logar() : ?Login {
    return (new LoginDAO())->autenticar($this);
  }
}