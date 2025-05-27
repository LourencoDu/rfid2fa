<?php

namespace RFID2FA\Model;

use RFID2FA\DAO\UsuarioDAO;

final class Usuario extends Model
{
  public $id, $nome, $email, $senha;

  public static function getById(int $id): ?Usuario
  {
    return (new UsuarioDAO())->selectById($id);
  }

  public function getByEmail(string $email): ?Usuario
  {
    return (new UsuarioDAO())->selectByEmail($email);
  }

  public static function getAllRowsByTipo(string $tipo): array
  {
    return (new UsuarioDAO())->selectByTipo($tipo);
  }

  public function getAllRows(): array
  {
    return (new UsuarioDAO())->select();
  }

  public function save(): Usuario
  {
    return (new UsuarioDAO())->save($this);
  }

  public static function delete(int $id): bool
  {
    return (new UsuarioDAO())->delete($id);
  }
}
