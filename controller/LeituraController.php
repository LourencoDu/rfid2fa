<?php

namespace RFID2FA\Controller;

use RFID2FA\Helper\JsonResponse;
use RFID2FA\Model\Leitura;

final class LeituraController extends Controller
{
  public function index(): void
  {
    parent::isProtected();

    $this->view = "Leitura/index.php";
    $this->titulo = "Leituras";

    $usuario = $_SESSION["usuario"];
    $id_usuario = $usuario->id;

    $leituras = (new Leitura())->getAllByIdUsuario((int) $id_usuario);
    $this->data["leituras"] = $leituras;

    $this->render();
  }

  public function cadastrar(): void
  {
    $post = json_decode(file_get_contents('php://input'), true);
    $model = new Leitura();
    $model->uid_cartao = $post['uid_cartao'] ?? '';

    if ($model->uid_cartao != "") {
      $model->save();
      $response = JsonResponse::sucesso("Leitura cadastrada com sucesso.", [$model]);
    } else {
      $response = JsonResponse::erro("'uid_cartao' não informado.", []);
    }

    $response->enviar();
  }
}
