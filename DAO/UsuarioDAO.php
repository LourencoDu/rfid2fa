<?php

namespace RFID2FA\DAO;

use RFID2FA\Model\Usuario;

final class UsuarioDAO extends DAO
{
  public function __construct()
  {
    parent::__construct();
  }

  public static function parseRow($data, $prefix = ""): Usuario
  {
    $model = new Usuario();

    $model->id = $data[$prefix . "id"] ?? null;
    $model->nome = $data[$prefix . "nome"] ?? null;
    $model->email = $data[$prefix . "email"] ?? null;
    $model->senha = $data[$prefix . "senha"] ?? null;

    return $model;
  }

  public function save(Usuario $model): Usuario
  {
    return ($model->id == null) ? $this->insert($model) : $this->update($model);
  }

  private function insert(Usuario $model): Usuario
  {
    $sql = "INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?);";

    $stmt = parent::$conexao->prepare($sql);
    $stmt->bindValue(1, $model->nome);
    $stmt->bindValue(2, $model->email);
    $stmt->bindValue(3, password_hash($model->senha, PASSWORD_DEFAULT));
    $stmt->execute();

    $model->id = parent::$conexao->lastInsertId();

    return $model;
  }

  private function update(Usuario $model): Usuario
  {
    $sql = "UPDATE usuario SET nome=? WHERE id=?;";

    $stmt = parent::$conexao->prepare($sql);
    $stmt->bindValue(1, $model->nome);
    $stmt->bindValue(2, $model->id);
    $stmt->execute();

    return $model;
  }

  public function select(): array
  {
    $sql = "SELECT * FROM usuario;";

    $stmt = parent::$conexao->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(DAO::FETCH_CLASS, "RFID2FA\Model\Usuario");
  }

  public function selectByEmail(string $email): ?Usuario
  {
    $sql = "SELECT
    u.id u_id, u.nome u_nome, u.email u_email, u.senha u_senha
    FROM usuario u
    WHERE u.email = ?;";

    $stmt = parent::$conexao->prepare($sql);
    $stmt->bindValue(1, $email);
    $stmt->execute();

    $data = $stmt->fetch(DAO::FETCH_ASSOC);

    if (is_array($data)) {
      return $this->parseRow($data);
    }

    return null;
  }

  public function autenticar(Usuario $model): ?Usuario
  {
    $sql = "SELECT
    u.id u_id, u.nome u_nome, u.email u_email, u.senha u_senha
    FROM usuario u
    WHERE u.email = ?;";

    $stmt = parent::$conexao->prepare($sql);
    $stmt->bindValue(1, $model->email);
    $stmt->execute();

    $data = $stmt->fetchObject();

    if (is_object($data)) {
      if (password_verify($model->senha, $data->u_senha)) {
        $usuario = new Usuario();
        $usuario->id = $data->u_id;
        $usuario->nome = $data->u_nome;
        $usuario->email = $data->u_email;
        $usuario->senha = $data->u_senha;

        return $usuario;
      }
    }

    return null;
  }
}
