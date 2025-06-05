<?php

namespace RFID2FA\DAO;

use RFID2FA\Model\Cartao;
use RFID2FA\Model\Leitura;

final class CartaoDAO extends DAO
{
  public function __construct()
  {
    parent::__construct();
  }

  private function parseRow($data, $prefix = ""): Cartao
  {
    $model = new Cartao();

    $model->id = $data[$prefix . "id"] ?? null;
    $model->uid = $data[$prefix . "uid"] ?? null;
    $model->id_usuario = $data[$prefix . "id_usuario"] ?? null;

    return $model;
  }

  public function selectByUid(string $uid): ?Cartao
  {
    $sql = "SELECT * FROM cartao WHERE uid = ?;";

    $stmt = parent::$conexao->prepare($sql);
    $stmt->bindValue(1, $uid);
    $stmt->execute();

    $data = $stmt->fetch(DAO::FETCH_ASSOC);

    if (is_array($data)) {
      return $this->parseRow($data);
    }

    return null;
  }

  public function selectByIdUsuario(string $id_usuario): ?Cartao
  {
    $sql = "SELECT * FROM cartao WHERE id_usuario = ?;";

    $stmt = parent::$conexao->prepare($sql);
    $stmt->bindValue(1, $id_usuario);
    $stmt->execute();

    $data = $stmt->fetch(DAO::FETCH_ASSOC);

    if (is_array($data)) {
      return $this->parseRow($data);
    }

    return null;
  }

  public function verificarLeitura(Cartao $model): ?Cartao
  {
    $sql = "SELECT * FROM cartao WHERE id_usuario = ?";
    $stmt = parent::$conexao->prepare($sql);
    $stmt->bindValue(1, $model->id_usuario);
    $stmt->execute();

    $cartao = $stmt->fetch(DAO::FETCH_ASSOC);

    if (is_array($cartao)) {
      $sql = "SELECT l.id, l.data, l.uid_cartao FROM leitura l WHERE uid_cartao = ? AND `data` >= NOW() - INTERVAL 10 SECOND;";

      $stmt = parent::$conexao->prepare($sql);
      $stmt->bindValue(1, $cartao["uid"]);
      $stmt->execute();

      $data = $stmt->fetch(DAO::FETCH_ASSOC);

      if (is_array($data)) {
        $model = $cartao;

        return $this->parseRow($model);
      } else {
        return null;
      }
    }

    return null;
  }

  public function verificarUltimaLeitura(): ?Leitura
  {
    $sql = "SELECT l.id, l.data, l.uid_cartao FROM leitura l WHERE `data` >= NOW() - INTERVAL 5 SECOND;";

    $stmt = parent::$conexao->prepare($sql);
    $stmt->execute();

    $data = $stmt->fetch(DAO::FETCH_ASSOC);

    if (is_array($data)) {
      return (new LeituraDAO())->parseRow($data);
    } else {
      return null;
    }
  }

  public function save(Cartao $model): Cartao
  {
    return ($model->id == null) ? $this->insert($model) : $this->update($model);
  }

  private function insert(Cartao $model): Cartao
  {
    $sql = "INSERT INTO cartao (uid, id_usuario) VALUES (?, ?);";

    $stmt = parent::$conexao->prepare($sql);
    $stmt->bindValue(1, $model->uid);
    $stmt->bindValue(2, $model->id_usuario);
    $stmt->execute();

    $model->id = parent::$conexao->lastInsertId();

    return $model;
  }

  private function update(Cartao $model): Cartao
  {
    $sql = "UPDATE cartao SET uid=? WHERE id=?;";

    $stmt = parent::$conexao->prepare($sql);
    $stmt->bindValue(1, $model->uid);
    $stmt->bindValue(2, $model->id);
    $stmt->execute();

    return $model;
  }
}
