<?php

namespace RFID2FA\Model;

use RFID2FA\DAO\LeituraDAO;

final class Leitura {
  public $id, $uid_cartao, $data;
  public $acao;

  function getAllByIdUsuario(int $id_usuario): array
  {
    return (new LeituraDAO())->getAllByIdUsuario($id_usuario);
  }

  function getLast(): ?Leitura
  {
    return (new LeituraDAO())->getLastInInterval(2);
  }

  function save(): Leitura
  {
    return (new LeituraDAO())->save($this);
  }
}