<?php

namespace RFID2FA\Controller;

use RFID2FA\Helper\JsonResponse;
use RFID2FA\Model\Cartao;
use RFID2FA\Model\Leitura;
use RFID2FA\Model\Usuario;
use RFID2FA\Service\UsuarioCartaoService;

final class UsuarioController extends Controller
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

  public function cadastro(): void
  {
    $this->view = "Cadastro/index.php";
    $this->js = "Cadastro/script.js";
    $this->titulo = "Cadastro";

    $this->render();
  }

  public function bemVindo()
  {
    $this->titulo = "Bem-vindo";
    $this->view = "Cadastro/bem-vindo/index.php";

    $this->render();
  }

  public function cadastrarAPI(): void
  {
    $model_usuario = new Usuario();
    $model_usuario->nome = $_POST['nome'] ?? '';
    $model_usuario->email = $_POST['email'] ?? '';
    $model_usuario->senha = $_POST['senha'] ?? '';

    $usuario_nao_existe = $model_usuario->getByEmail($_POST['email'] ?? "") == null;

    try {
      if ($usuario_nao_existe) {
        $leitura = (new Leitura())->getLast();

        if ($leitura != null) {
          $cartao_nao_existe = (new Cartao())->getByUid($leitura->uid_cartao) == null;

          if ($cartao_nao_existe) {
            $service = new UsuarioCartaoService();

            $usuario_cadastrado = $service->cadastrar($model_usuario, $leitura);

            $response = JsonResponse::sucesso("Usuário cadastrado com sucesso!", [$usuario_cadastrado]);
          } else {
            $response = JsonResponse::erro("O Cartão lido já está em uso. Utilize outro cartão.", ["cartao-em-uso"]);
          }
        } else {
          $response = JsonResponse::erro("Aguardando leitura do cartão.", ["aguardando-leitura-cartao"]);
        }
      } else {
        $response = JsonResponse::erro("E-mail já em uso.", ["email-em-uso"]);
      }
    } catch (\Throwable $th) {
      $response = JsonResponse::erro("Falha ao cadastro usuário. Verifique o LOG", [$th->getMessage()]);
    }

    $response->enviar();
  }

  public function login(): void
  {
    $this->view = "Login/index.php";
    $this->css = "Login/style.css";
    $this->titulo = "Login";

    $this->render();
  }

  public function logar(): void
  {
    $model = new Usuario();
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

  public function logarAPI(): void
  {
    $model = new Usuario();
    $model->email = $_POST['email'] ?? '';
    $model->senha = $_POST['senha'] ?? '';
    $usuario = $model->logar($model);

    if ($usuario != null) {
      $cartao = new Cartao();
      $cartao->id_usuario = $usuario->id;

      $leitura_cartao = $cartao->verificarLeitura($cartao);
      if ($leitura_cartao != null) {
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

  public static function logout(): void
  {
    session_destroy();
    header("Location: login");
  }
}
