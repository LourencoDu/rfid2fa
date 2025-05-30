<?php

namespace RFID2FA\Model;

use RFID2FA\DAO\LeituraDAO;

final class Leitura {
  public $id, $uid_cartao, $data, $lido;

  function getLast(): ?Leitura
  {
    return (new LeituraDAO())->getLastInInterval(5);
  }

  function save(): Leitura
  {
    return (new LeituraDAO())->save($this);
  }
}