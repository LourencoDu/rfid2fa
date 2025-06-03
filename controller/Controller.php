<?php

namespace RFID2FA\Controller;

use RFID2FA\Helper\JsonResponse;

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

  final protected static function isProtectedApi() {
    Controller::isProtected( true);
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
