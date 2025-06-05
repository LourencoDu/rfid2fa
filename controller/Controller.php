<?php

namespace RFID2FA\Controller;

use RFID2FA\Helper\JsonResponse;
use RFID2FA\Model\Leitura;

class CaminhoItem
{
  public string $texto, $rota;

  public function __construct(string $texto, string $rota)
  {
    $this->texto = $texto;
    $this->rota = $rota;
  }
}

abstract class Controller
{
  protected $view, $css, $js, $titulo, $data;

  /**
   * @var CaminhoItem[]
   */
  protected ?array $caminho = array();

  public function render()
  {
    $config = [
      "view" => $this->view,
      "css" => $this->css,
      "js" => $this->js,
      "titulo" => $this->titulo,
      "caminho" => $this->caminho,
      "data" => $this->data,
    ];
    extract($config);
    require_once VIEWS . '/Layout/index.php';
  }

  final protected static function isNotSignedOnly(?bool $json = false)
  {
    if (isset($_SESSION["usuario"])) {
      if ($json == true) {
        $response = JsonResponse::erro("Somente acessivel estando deslogado.", [], 401);
        $response->enviar();
      } else {
        Header("Location: /" . BASE_DIR_NAME . "/");
      }
      exit;
    }
  }

  final protected static function isProtected(?bool $json = false)
  {
    if (!isset($_SESSION["usuario"])) {
      if ($json == true) {
        $response = JsonResponse::erro("Para acessar esse recurso é necessário estar logado.", [], 401);
        $response->enviar();
      } else {
        header("Location: login");
      }
      exit;
    }
  }

  final protected static function isProtectedApi()
  {
    Controller::isProtected(true);
  }

  final protected static function isProtectedApiByCartao($acao = null)
  {
    Controller::isProtectedApi();

    $leitura = new Leitura();
    $leitura = $leitura->getLast();
    if ($leitura != null) {
      $uid_cartao_usuario = $_SESSION["usuario"]->cartao->uid;
      $cartao_pertence_usuario = $uid_cartao_usuario == $leitura->uid_cartao;
      if (!$cartao_pertence_usuario) {
        $response = JsonResponse::erro("Cartão lido não pertence ao usuário.", ["cartao-invalido"]);
        $response->enviar();
        exit();
      } else {
        $leitura->acao = $acao;
        $leitura->save();
      }
    } else {
      $response = JsonResponse::erro("Aguardando leitura do cartão.", ["aguardando-leitura-cartao"]);
      $response->enviar();
      exit();
    }
  }


  final protected static function isPost(): bool
  {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
  }

  final protected static function isGet(): bool
  {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
  }

  final protected static function redirect(string $route)
  {
    Header("Location: /" . BASE_DIR_NAME . "/" . $route);
  }
}
