<?php

namespace RFID2FA\Model;

use RFID2FA\DAO\UsuarioDAO;

final class Usuario extends Model
{
  public $id, $nome, $email, $senha;
  public $nome_completo, $icone;

    public function getByEmail(string $email): ?Usuario
  {
    return (new UsuarioDAO())->selectByEmail($email);
  }

  public function getAllRows(): array
  {
    return (new UsuarioDAO())->select();
  }

  public function save(): Usuario
  {
    return (new UsuarioDAO())->save($this);
  }

  function logar() : ?Usuario {
    return (new UsuarioDAO())->autenticar($this);
  }
}
