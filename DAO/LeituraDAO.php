<?php

namespace RFID2FA\DAO;

use RFID2FA\Model\Leitura;

final class LeituraDAO extends DAO
{
  public function __construct()
  {
    parent::__construct();
  }

  public function parseRow($data, $prefix = ""): Leitura
  {
    $model = new Leitura();

    $model->id = $data[$prefix . "id"] ?? null;
    $model->uid_cartao = $data[$prefix . "uid_cartao"] ?? null;
    $model->data = $data[$prefix . "data"] ?? null;

    return $model;
  }

  public function getLastInInterval(int $interval = 5): ?Leitura
  {
    $sql = "SELECT l.id, l.data, l.uid_cartao FROM leitura l WHERE `data` >= NOW() - INTERVAL ? SECOND;";

    $stmt = parent::$conexao->prepare($sql);
    $stmt->bindValue(1, $interval);
    $stmt->execute();

    $data = $stmt->fetch(DAO::FETCH_ASSOC);

    if (is_array($data)) {
      return $this->parseRow($data);
    } else {
      return null;
    }
  }

  public function save(Leitura $model): Leitura
  {
    return ($model->id == null) ? $this->insert($model) : null;
  }

  private function insert(Leitura $model): Leitura
  {
    $sql = "INSERT INTO leitura (uid_cartao, data) VALUES (?, ?);";

    date_default_timezone_set('America/Sao_Paulo');
    $dataHoraAtual = date('Y-m-d H:i:s');

    $stmt = parent::$conexao->prepare($sql);
    $stmt->bindValue(1, $model->uid_cartao);
    $stmt->bindValue(2, $dataHoraAtual);
    $stmt->execute();

    $model->id = parent::$conexao->lastInsertId();
    $model->data = $dataHoraAtual;

    return $model;
  }
}
