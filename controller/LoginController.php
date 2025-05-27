<?php

namespace RFID2FA\Controller;

use RFID2FA\Helper\JsonResponse;
use RFID2FA\Model\Cartao;
use RFID2FA\Model\Login;

final class LoginController extends Controller
{
  public function index(): void
  {
    $this->view = "Login/index.php";
    $this->css = "Login/style.css";
    $this->titulo = "Login";

    if (parent::isPost()) {
      $this->logar();
    }

    $this->render();
  }

  public function logarAPI(): void {
    $model = new Login();
    $model->email = $_POST['email'] ?? '';
    $model->senha = $_POST['senha'] ?? '';
    $usuario = $model->logar($model);

    if ($usuario != null) {
      $cartao = new Cartao();
      $cartao->id_usuario = $usuario->id;

      $leitura_cartao = $cartao->verificarLeitura($cartao);
      if($leitura_cartao != null) {
        $_SESSION['usuario'] = $usuario;  
        $_SESSION['usuario']->nome_completo = $usuario->nome;

        $_SESSION['usuario']->icone = "fa-user";

        $response = JsonResponse::sucesso("Usuário autenticado com sucesso!");
      } else {
        $response = JsonResponse::erro("Aguardando leitura do cartão.", ["erro" => "aguardando-leitura-cartao"]);
      }
    } else {
      $response = JsonResponse::erro("E-mail ou senha inválidos.", ["erro" => "email-ou-senha-invalidos"]);
    }

    $response->enviar();
  }

  public function logar(): void
  {
    $model = new Login();
    $model->email = $_POST['email'] ?? '';
    $model->senha = $_POST['senha'] ?? '';
    $logado = $model->logar($model);

    if ($logado != null) {
      $_SESSION['usuario'] = $logado;  
      $_SESSION['usuario']->nome_completo = $logado->nome;

      $_SESSION['usuario']->icone = "fa-user";
      
      header("Location: home");
    } else {
      $this->data['erro'] = "E-mail ou senha inválidos.";
      $this->data['form'] = ["email" => $model->email, "senha" => $model->senha];
    }
  }

  public static function logout() : void {
      session_destroy();
      header("Location: login");
  }
}
