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
    $model->acao = $data[$prefix . "acao"] ?? null;

    return $model;
  }

  public function getAllByIdUsuario(int $id_usuario): array
  {
    $sql = "SELECT
    l.id, l.data, l.uid_cartao, l.acao
    FROM leitura l
    JOIN cartao c
    ON c.uid = l.uid_cartao
    JOIN usuario u
    ON u.id = c.id_usuario
    WHERE u.id = ?
    ORDER BY l.id DESC;";

    $stmt = parent::$conexao->prepare($sql);
    $stmt->bindValue(1, $id_usuario);
    $stmt->execute();

    $data = $stmt->fetchAll(DAO::FETCH_ASSOC);
    $lista = array();

    foreach ($data as $row) {
      $lista[] = $this->parseRow($row);
    }

    return $lista;
  }

  public function getLastInInterval(int $interval = 2): ?Leitura
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
    return ($model->id == null) ? $this->insert($model) : $this->update($model);
  }

  private function insert(Leitura $model): Leitura
  {
    $sql = "INSERT INTO leitura (uid_cartao, acao, data) VALUES (?, ?, ?);";

    date_default_timezone_set('America/Sao_Paulo');
    $dataHoraAtual = date('Y-m-d H:i:s');

    $stmt = parent::$conexao->prepare($sql);
    $stmt->bindValue(1, $model->uid_cartao);
    $stmt->bindValue(2, $model->acao);
    $stmt->bindValue(3, $dataHoraAtual);
    $stmt->execute();

    $model->id = parent::$conexao->lastInsertId();
    $model->data = $dataHoraAtual;

    return $model;
  }

  public function update(Leitura $model): Leitura
  {
    $sql = "UPDATE leitura SET acao=? WHERE id=?;";

    $stmt = parent::$conexao->prepare($sql);
    $stmt->bindValue(1, $model->acao);
    $stmt->bindValue(2, $model->id);
    $stmt->execute();

    return $model;
  }
}
