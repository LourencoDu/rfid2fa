<?php

namespace RFID2FA\Model;

use RFID2FA\DAO\CartaoDAO;

final class Cartao
{
  public $id, $uid, $id_usuario;

  function getByUid(string $uid): ?Cartao
  {
    return (new CartaoDAO())->selectByUid($uid);
  }

  function verificarLeitura(): ?Cartao
  {
    return (new CartaoDAO())->verificarLeitura($this);
  }

  function verificarUltimaLeitura(): ?Leitura
  {
    return (new CartaoDAO())->verificarUltimaLeitura();
  }

  function save(): Cartao
  {
    return (new CartaoDAO())->save($this);
  }
}
