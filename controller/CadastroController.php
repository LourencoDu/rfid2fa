<?php

namespace RFID2FA\Controller;

use RFID2FA\Model\Usuario;
use RFID2FA\Model\Prestador;

final class CadastroController extends Controller
{
  public function index(): void
  {
    $this->view = "Cadastro/index.php";
    $this->css = "Cadastro/style.css";
    $this->js = "Cadastro/script.js";
    $this->titulo = "Cadastro";
    if(parent::isPost()) {
      $this->cadastrar();
    }
    $this->render();
  }

  private function cadastrar(): void
  {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    if(!$nome || !$email || !$senha) {
      $this->data['erro'] = "Preencha todos os campos obrigatórios (*).";
      $this->data['form'] = [
        "nome" => $nome,
        "email" => $email,
        "senha" => $senha,
      ];
      return;
    }

    try {
      $model = new Usuario();

      $model->nome = $nome;
      $model->email = $email;
      $model->senha = $senha;

      $model->save();

      Header("Location: /".BASE_DIR_NAME."");
    } catch (\Throwable $th) {
      $this->data = array_merge($this->data ?? [], [
        "erro" => "Falha ao adicionar registro. Erro: ".$th->getMessage(),
        "exception" => $th->getMessage()
      ]);
    }
  }
}