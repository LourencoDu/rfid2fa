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
    $this->isNotSignedOnly();

    $this->view = "Login/index.php";
    $this->js = "Login/script.js";
    $this->titulo = "Login";

    $this->render();
  }

  public function cadastro(): void
  {
    $this->isNotSignedOnly();

    $this->view = "Cadastro/index.php";
    $this->js = "Cadastro/script.js";
    $this->titulo = "Cadastro";

    $this->render();
  }

  public function bemVindo()
  {
    $this->isNotSignedOnly();

    $this->titulo = "Bem-vindo";
    $this->view = "Cadastro/bem-vindo/index.php";

    $this->render();
  }

  public function cadastrarAPI(): void
  {
    $this->isNotSignedOnly(true);

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
            $leitura->acao = "Cadastro do cartão";
            $leitura->save();
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
    $this->isNotSignedOnly();

    $this->view = "Login/index.php";
    $this->js = "Login/script.js";
    $this->titulo = "Login";

    $this->render();
  }

  public function logarAPI(): void
  {
    $this->isNotSignedOnly(true);

    $model = new Usuario();
    $model->email = $_POST['email'] ?? '';
    $model->senha = $_POST['senha'] ?? '';

    $usuario = $model->autenticar($model);

    if ($usuario != null) {
      $leitura = new Leitura();
      $leitura = $leitura->getLast();
      if($leitura != null) {
        $leitura_cartao = (new Cartao())->getByUid($leitura->uid_cartao);
        $cartao_pertence_usuario = false;

        if($leitura_cartao != null) {
          $cartao_pertence_usuario = $leitura_cartao->id_usuario == $usuario->id;
        }

        if($cartao_pertence_usuario) {
          $_SESSION["usuario"] = $usuario;
          $_SESSION['usuario']->nome_completo = $usuario->nome;
          $_SESSION['usuario']->icone = "fa-user";
          
          $response = JsonResponse::sucesso("Usuário logado com sucesso!");

          $leitura->acao = "Acesso ao sistema";
          $leitura->save();
        } else {
          $response = JsonResponse::erro("Cartão lido não pertence ao usuário.", ["cartao-invalido"]);
        }
      } else {
        $response = JsonResponse::erro("Aguardando leitura do cartão.", ["aguardando-leitura-cartao"]);
      }
    } else {
      $response = JsonResponse::erro("E-mail ou senha inválidos.", ["erro" => "email-ou-senha-invalidos"]);
    }

    $response->enviar();
  }

    public function alterarSenhaAPI(): void
  {
    parent::isProtectedApiByCartao("Alteração de senha");

    $senhaAtual = $_POST["senha-atual"] ?? "";
    $senhaNova = $_POST["senha-nova"] ?? "";

    $response = null;

    if (isset($senhaAtual) && isset($senhaNova)) {
      if (password_verify($senhaAtual, $_SESSION["usuario"]->senha)) {
        $resultado = Usuario::alterarSenha($_SESSION["usuario"]->id, $senhaNova);
        if ($resultado) {
          $response = JsonResponse::sucesso("Senha alterada com sucesso");
        } else {
          $response = JsonResponse::erro("Falha ao alterar senha. Tente novamente mais tarde.");
        }
      } else {
        $response = JsonResponse::erro("Senha atual incorreta.", [], 401);
      }
    } else {
      $response = JsonResponse::erro("Preencha todos os campos.");
    }

    $response->enviar();
  }

  public static function logout(): void
  {
    session_destroy();
    header("Location: login");
  }
}
