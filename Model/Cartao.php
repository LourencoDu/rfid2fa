<?php

namespace RFID2FA\Model;

use RFID2FA\DAO\CartaoDAO;

final class Cartao {
  public $id, $uid, $id_usuario;

  function verificarLeitura() : ?Cartao {
    return (new CartaoDAO())->verificarLeitura($this);
  }
}