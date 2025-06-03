<?php

namespace RFID2FA\Controller;

final class MeuPerfilController extends Controller
{
  public function index(): void
  {
    parent::isProtected();

    $this->view = "MeuPerfil/index.php";
    $this->js = "MeuPerfil/script.js";
    $this->titulo = "Meu Perfil";
    $this->render();
  }
}
