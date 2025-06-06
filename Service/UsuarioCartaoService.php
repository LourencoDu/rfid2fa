<?php

namespace RFID2FA\Service;

use RFID2FA\Model\Usuario;
use RFID2FA\Model\Leitura;
use RFID2FA\DAO\DAO;
use RFID2FA\Model\Cartao;

class UsuarioCartaoService
{
  public static function cadastrar(Usuario $dadosUsuario, Leitura $dadosLeitura): Usuario
  {
    $pdo = DAO::getConexao();

    try {
      $pdo->beginTransaction();

      $usuario = new Usuario();
      $usuario->nome = $dadosUsuario->nome;
      $usuario->email = $dadosUsuario->email;
      $usuario->senha = $dadosUsuario->senha;
      $usuario->save();

      $cartao = new Cartao();
      $cartao->id_usuario = $usuario->id;
      $cartao->uid = $dadosLeitura->uid_cartao;
      $cartao->save();

      $pdo->commit();

      return $usuario;
    } catch (\Throwable $e) {
      $pdo->rollBack();
      throw $e;
    }
  }
}
