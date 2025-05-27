<?php

namespace RFID2FA\DAO;

use RFID2FA\Model\Cartao;

final class CartaoDAO extends DAO
{
  public function __construct()
  {
    parent::__construct();
  }

    public function verificarLeitura(Cartao $model): ?Cartao
  {
    $sql = "SELECT * FROM cartao WHERE id_usuario = ?";
    $stmt = parent::$conexao->prepare($sql);
    $stmt->bindValue(1, $model->id_usuario);
    $stmt->execute();

    $cartao = $stmt->fetchObject();

    if (is_object($cartao)) {
      $sql = "SELECT l.id, l.data, l.uid_cartao FROM leitura l WHERE uid_cartao = ? AND `data` >= NOW() - INTERVAL 10 SECOND;";

      $stmt = parent::$conexao->prepare($sql);
      $stmt->bindValue(1, $cartao->uid);
      $stmt->execute();

      $data = $stmt->fetchObject(DAO::FETCH_ASSOC);

      if (is_array($data)) {
        $model = $cartao;

        return $model;
      } else {
        return null;
      }
    }

    return null;
  }

  public function verificarLeitura2(Cartao $model): ?Cartao
  {
    $sql = "SELECT l.id, l.data, l.uid_cartao FROM leitura l WHERE uid_cartao = ? AND `data` >= NOW() - INTERVAL 10 SECOND;";

    $stmt = parent::$conexao->prepare($sql);
    $stmt->bindValue(1, $model->uid);
    $stmt->execute();

    $leitura = $stmt->fetchObject();

    if (is_object($leitura)) {
      $sql_cartao = "SELECT c.id c_id, c.uid c_uid, c.id_usuario c_id_usuario WHERE c.uid = ?";

      $stmt = parent::$conexao->prepare($sql_cartao);
      $stmt->bindValue(1, $model->uid);
      $stmt->execute();

      $data = $stmt->fetchObject(DAO::FETCH_ASSOC);

      if (is_array($data)) {
        $model->id = $data["c_id"];
        $model->uid = $data["c_uid"];
        $model->id_usuario = $data["c_id_usuario"];

        return $model;
      } else {
        return null;
      }
    }

    return null;
  }
}
